<?php

/**
 * MelodyMixer.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytextbook.com
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\MelodyMixerModule\Form\Handler\Score\Base;

use Paustian\MelodyMixerModule\Form\Handler\Common\EditHandler;
use Paustian\MelodyMixerModule\Form\Type\ScoreType;
use Exception;
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Paustian\MelodyMixerModule\Entity\ScoreEntity;

/**
 * This handler class handles the page events of editing forms.
 * It aims on the score object type.
 */
abstract class AbstractEditHandler extends EditHandler
{
    public function processForm(array $templateParameters = [])
    {
        $this->objectType = 'score';
        $this->objectTypeCapital = 'Score';
        $this->objectTypeLower = 'score';
        
        $this->hasPageLockSupport = true;
    
        $result = parent::processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
    
        if ('create' === $this->templateParameters['mode'] && !$this->modelHelper->canBeCreated($this->objectType)) {
            $request = $this->requestStack->getCurrentRequest();
            if ($request->hasSession() && ($session = $request->getSession())) {
                $session->getFlashBag()->add(
                    'error',
                    $this->trans(
                        'Sorry, but you can not create the score yet as other items are required which must be created before!',
                        [],
                        'score'
                    )
                );
            }
            $logArgs = [
                'app' => 'PaustianMelodyMixerModule',
                'user' => $this->currentUserApi->get('uname'),
                'entity' => $this->objectType
            ];
            $this->logger->notice(
                '{app}: User {user} tried to create a new {entity}, but failed'
                    . ' as other items are required which must be created before.',
                $logArgs
            );
    
            return new RedirectResponse($this->getRedirectUrl(['commandName' => '']), 302);
        }
    
        // assign data to template (for additions like standard fields)
        $this->templateParameters[$this->objectTypeLower] = $this->entityRef;
    
        return $result;
    }
    
    protected function createForm(): ?FormInterface
    {
        return $this->formFactory->create(ScoreType::class, $this->entityRef, $this->getFormOptions());
    }
    
    protected function getFormOptions(): array
    {
        $options = [
            'mode' => $this->templateParameters['mode'],
            'actions' => $this->templateParameters['actions'],
            'has_moderate_permission' => $this->permissionHelper->hasEntityPermission($this->entityRef, ACCESS_ADMIN),
            'allow_moderation_specific_creator' => (bool)$this->variableApi->get(
                'PaustianMelodyMixerModule',
                'allowModerationSpecificCreatorFor' . $this->objectTypeCapital,
                false
            ),
            'allow_moderation_specific_creation_date' => (bool)$this->variableApi->get(
                'PaustianMelodyMixerModule',
                'allowModerationSpecificCreationDateFor' . $this->objectTypeCapital,
                false
            ),
        ];
    
        return $options;
    }

    protected function getRedirectCodes(): array
    {
        $codes = parent::getRedirectCodes();
    
        // user list of scores
        $codes[] = 'userView';
        // admin list of scores
        $codes[] = 'adminView';
        // user list of own scores
        $codes[] = 'userOwnView';
        // admin list of own scores
        $codes[] = 'adminOwnView';
    
    
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl(array $args = []): string
    {
        $objectIsPersisted = 'delete' !== $args['commandName']
            && !('create' === $this->templateParameters['mode'] && 'cancel' === $args['commandName']
        );
        if (null !== $this->returnTo && $objectIsPersisted) {
            // return to referer
            return $this->returnTo;
        }
    
        $routeArea = array_key_exists('routeArea', $this->templateParameters)
            ? $this->templateParameters['routeArea']
            : ''
        ;
        $routePrefix = 'paustianmelodymixermodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // redirect to the list of scores
        $url = $this->router->generate($routePrefix . 'view');
    
        return $url;
    }

    public function handleCommand(array $args = [])
    {
        $result = parent::handleCommand($args);
        if (false === $result) {
            return $result;
        }
    
        // build $args for BC (e.g. used by redirect handling)
        foreach ($this->templateParameters['actions'] as $action) {
            if ($this->form->get($action['id'])->isClicked()) {
                $args['commandName'] = $action['id'];
            }
        }
        if (
            'create' === $this->templateParameters['mode']
            && $this->form->has('submitrepeat')
            && $this->form->get('submitrepeat')->isClicked()
        ) {
            $args['commandName'] = 'submit';
            $this->repeatCreateAction = true;
        }
    
        return new RedirectResponse($this->getRedirectUrl($args), 302);
    }
    
    protected function getDefaultMessage(array $args = [], bool $success = false): string
    {
        if (false === $success) {
            return parent::getDefaultMessage($args, $success);
        }
    
        switch ($args['commandName']) {
            case 'submit':
                if ('create' === $this->templateParameters['mode']) {
                    $message = $this->trans('Done! Score created.', [], 'score');
                } else {
                    $message = $this->trans('Done! Score updated.', [], 'score');
                }
                break;
            case 'delete':
                $message = $this->trans('Done! Score deleted.', [], 'score');
                break;
            default:
                $message = $this->trans('Done! Score updated.', [], 'score');
                break;
        }
    
        return $message;
    }

    /**
     * @throws RuntimeException Thrown if concurrent editing is recognised or another error occurs
     */
    public function applyAction(array $args = []): bool
    {
        // get treated entity reference from persisted member var
        /** @var ScoreEntity $entity */
        $entity = $this->entityRef;
    
        $action = $args['commandName'];
    
        $success = false;
        try {
            // execute the workflow action
            $success = $this->workflowHelper->executeAction($entity, $action);
        } catch (Exception $exception) {
            $request = $this->requestStack->getCurrentRequest();
            if ($request->hasSession() && ($session = $request->getSession())) {
                $session->getFlashBag()->add(
                    'error',
                    $this->trans(
                        'Sorry, but an error occured during the %action% action. Please apply the changes again!',
                        ['%action%' => $action]
                    ) . ' ' . $exception->getMessage()
                );
            }
            $logArgs = [
                'app' => 'PaustianMelodyMixerModule',
                'user' => $this->currentUserApi->get('uname'),
                'entity' => 'score',
                'id' => $entity->getKey(),
                'errorMessage' => $exception->getMessage()
            ];
            $this->logger->error(
                '{app}: User {user} tried to edit the {entity} with id {id},'
                    . ' but failed. Error details: {errorMessage}.',
                $logArgs
            );
        }
    
        $this->addDefaultMessage($args, $success);
    
        if ($success && 'create' === $this->templateParameters['mode']) {
            // store new identifier
            $this->idValue = $entity->getKey();
        }
    
        return $success;
    }

    /**
     * Get URL to redirect to.
     */
    protected function getRedirectUrl(array $args = []): string
    {
        if ($this->repeatCreateAction) {
            return $this->repeatReturnUrl;
        }
    
        $request = $this->requestStack->getCurrentRequest();
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->has('paustianmelodymixermodule' . $this->objectTypeCapital . 'Referer')) {
                $this->returnTo = $session->get('paustianmelodymixermodule' . $this->objectTypeCapital . 'Referer');
                $session->remove('paustianmelodymixermodule' . $this->objectTypeCapital . 'Referer');
            }
        }
    
        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes(), true)) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args);
        }
    
        $routeArea = 0 === strpos($this->returnTo, 'admin') ? 'admin' : '';
        $routePrefix = 'paustianmelodymixermodule_' . $this->objectTypeLower . '_' . $routeArea;
    
        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'userView':
            case 'adminView':
                return $this->router->generate($routePrefix . 'view');
            case 'userOwnView':
            case 'adminOwnView':
                return $this->router->generate($routePrefix . 'view', [ 'own' => 1 ]);
            default:
                return $this->getDefaultReturnUrl($args);
        }
    }
}
