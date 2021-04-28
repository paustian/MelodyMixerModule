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

namespace Paustian\MelodyMixerModule\Controller\Base;

use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\Bundle\FormExtensionBundle\Form\Type\DeletionType;
use Zikula\Bundle\HookBundle\Category\FormAwareCategory;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Component\SortableColumns\Column;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Paustian\MelodyMixerModule\Entity\GameScoreEntity;
use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Form\Handler\GameScore\EditHandler;
use Paustian\MelodyMixerModule\Helper\ControllerHelper;
use Paustian\MelodyMixerModule\Helper\HookHelper;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;
use Paustian\MelodyMixerModule\Helper\ViewHelper;
use Paustian\MelodyMixerModule\Helper\WorkflowHelper;

/**
 * Game score controller base class.
 */
abstract class AbstractGameScoreController extends AbstractController
{
    
    /**
     * This is the default action handling the index area called without defining arguments.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    protected function indexInternal(
        Request $request,
        PermissionHelper $permissionHelper,
        bool $isAdmin = false
    ): Response {
        $objectType = 'gameScore';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_OVERVIEW;
        if (!$permissionHelper->hasComponentPermission($objectType, $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        return $this->redirectToRoute('paustianmelodymixermodule_gamescore_' . $templateParameters['routeArea'] . 'view');
    }
    
    
    /**
     * This action provides an item list overview.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws Exception
     */
    protected function viewInternal(
        Request $request,
        RouterInterface $router,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        string $sort,
        string $sortdir,
        int $page,
        int $num,
        bool $isAdmin = false
    ): Response {
        $objectType = 'gameScore';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$permissionHelper->hasComponentPermission($objectType, $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        $request->query->set('sort', $sort);
        $request->query->set('sortdir', $sortdir);
        $request->query->set('page', $page);
        
        $routeName = 'paustianmelodymixermodule_gamescore_' . ($isAdmin ? 'admin' : '') . 'view';
        $sortableColumns = new SortableColumns($router, $routeName, 'sort', 'sortdir');
        
        $sortableColumns->addColumns([
            new Column('playerEmail'),
            new Column('firstName'),
            new Column('lastName'),
            new Column('createdBy'),
            new Column('createdDate'),
            new Column('updatedBy'),
            new Column('updatedDate'),
        ]);
        
        $templateParameters = $controllerHelper->processViewActionParameters(
            $objectType,
            $sortableColumns,
            $templateParameters,
            true
        );
        
        // filter by permissions
        $templateParameters['items'] = $permissionHelper->filterCollection(
            $objectType,
            $templateParameters['items'],
            $permLevel
        );
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'view', $templateParameters);
    }
    
    
    /**
     * This action provides a handling of edit requests.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws RuntimeException Thrown if another critical error occurs (e.g. workflow actions not available)
     * @throws Exception
     */
    protected function editInternal(
        Request $request,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EditHandler $formHandler,
        bool $isAdmin = false
    ): Response {
        $objectType = 'gameScore';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_EDIT;
        if (!$permissionHelper->hasComponentPermission($objectType, $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        $templateParameters = $controllerHelper->processEditActionParameters($objectType, $templateParameters);
        
        // delegate form processing to the form handler
        $result = $formHandler->processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
        
        $templateParameters = $formHandler->getTemplateParameters();
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'edit', $templateParameters);
    }
    
    
    /**
     * This is a custom action.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    protected function setScoresInternal(
        Request $request,
        PermissionHelper $permissionHelper,
        bool $isAdmin = false
    ): Response {
        $objectType = 'gameScore';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_OVERVIEW;
        if (!$permissionHelper->hasComponentPermission($objectType, $permLevel)) {
            throw new AccessDeniedException();
        }
        
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        // return template
        return $this->render('@PaustianMelodyMixerModule/GameScore/setScores.html.twig', $templateParameters);
    }
    
    
    /**
     * This action provides a handling of simple delete requests.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown if game score to be deleted isn't found
     * @throws RuntimeException Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    protected function deleteInternal(
        Request $request,
        LoggerInterface $logger,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EntityFactory $entityFactory,
        CurrentUserApiInterface $currentUserApi,
        WorkflowHelper $workflowHelper,
        HookHelper $hookHelper,
        int $id,
        bool $isAdmin = false
    ): Response {
        if (null === $gameScore) {
            $gameScore = $entityFactory->getRepository('gameScore')->selectById($id);
        }
        if (null === $gameScore) {
            throw new NotFoundHttpException(
                $this->trans(
                    'No such game score found.',
                    [],
                    'gameScore'
                )
            );
        }
        
        $objectType = 'gameScore';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_DELETE;
        if (!$permissionHelper->hasEntityPermission($gameScore, $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $logArgs = ['app' => 'PaustianMelodyMixerModule', 'user' => $currentUserApi->get('uname'), 'entity' => 'game score', 'id' => $gameScore->getKey()];
        
        // determine available workflow actions
        $actions = $workflowHelper->getActionsForObject($gameScore);
        if (false === $actions || !is_array($actions)) {
            $this->addFlash('error', 'Error! Could not determine workflow actions.');
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but failed to determine available workflow actions.', $logArgs);
            throw new RuntimeException($this->trans('Error! Could not determine workflow actions.'));
        }
        
        // redirect to the list of game scores
        $redirectRoute = 'paustianmelodymixermodule_gamescore_' . ($isAdmin ? 'admin' : '') . 'view';
        
        // check whether deletion is allowed
        $deleteActionId = 'delete';
        $deleteAllowed = false;
        foreach ($actions as $actionId => $action) {
            if ($actionId != $deleteActionId) {
                continue;
            }
            $deleteAllowed = true;
            break;
        }
        if (!$deleteAllowed) {
            $this->addFlash(
                'error',
                $this->trans(
                    'Error! It is not allowed to delete this game score.',
                    [],
                    'gameScore'
                )
            );
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but this action was not allowed.', $logArgs);
        
            return $this->redirectToRoute($redirectRoute);
        }
        
        $form = $this->createForm(DeletionType::class, $gameScore);
        if ($gameScore->supportsHookSubscribers()) {
            // call form aware display hooks
            $formHook = $hookHelper->callFormDisplayHooks($form, $gameScore, FormAwareCategory::TYPE_DELETE);
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                if ($gameScore->supportsHookSubscribers()) {
                    // Let any ui hooks perform additional validation actions
                    $validationErrors = $hookHelper->callValidationHooks($gameScore, UiHooksCategory::TYPE_VALIDATE_DELETE);
                    if (0 < count($validationErrors)) {
                        foreach ($validationErrors as $message) {
                            $this->addFlash('error', $message);
                        }
                    } else {
                        // execute the workflow action
                        $success = $workflowHelper->executeAction($gameScore, $deleteActionId);
                        if ($success) {
                            $this->addFlash(
                                'status',
                                $this->trans(
                                    'Done! Game score deleted.',
                                    [],
                                    'gameScore'
                                )
                            );
                            $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', $logArgs);
                        }
                        
                        if ($gameScore->supportsHookSubscribers()) {
                            // Call form aware processing hooks
                            $hookHelper->callFormProcessHooks($form, $gameScore, FormAwareCategory::TYPE_PROCESS_DELETE);
                        
                            // Let any ui hooks know that we have deleted the game score
                            $hookHelper->callProcessHooks($gameScore, UiHooksCategory::TYPE_PROCESS_DELETE);
                        }
                        
                        return $this->redirectToRoute($redirectRoute);
                    }
                } else {
                    // execute the workflow action
                    $success = $workflowHelper->executeAction($gameScore, $deleteActionId);
                    if ($success) {
                        $this->addFlash(
                            'status',
                            $this->trans(
                                'Done! Game score deleted.',
                                [],
                                'gameScore'
                            )
                        );
                        $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', $logArgs);
                    }
                    
                    if ($gameScore->supportsHookSubscribers()) {
                        // Call form aware processing hooks
                        $hookHelper->callFormProcessHooks($form, $gameScore, FormAwareCategory::TYPE_PROCESS_DELETE);
                    
                        // Let any ui hooks know that we have deleted the game score
                        $hookHelper->callProcessHooks($gameScore, UiHooksCategory::TYPE_PROCESS_DELETE);
                    }
                    
                    return $this->redirectToRoute($redirectRoute);
                }
            } elseif ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', 'Operation cancelled.');
        
                return $this->redirectToRoute($redirectRoute);
            }
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            'deleteForm' => $form->createView(),
            $objectType => $gameScore
        ];
        if ($gameScore->supportsHookSubscribers()) {
            $templateParameters['formHookTemplates'] = $formHook->getTemplates();
        }
        
        $templateParameters = $controllerHelper->processDeleteActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'delete', $templateParameters);
    }
    
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    protected function handleSelectedEntriesActionInternal(
        Request $request,
        LoggerInterface $logger,
        EntityFactory $entityFactory,
        WorkflowHelper $workflowHelper,
        HookHelper $hookHelper,
        CurrentUserApiInterface $currentUserApi,
        bool $isAdmin = false
    ): RedirectResponse {
        $objectType = 'gameScore';
        
        // Get parameters
        $action = $request->request->get('action');
        $items = $request->request->get('items');
        if (!is_array($items) || !count($items)) {
            return $this->redirectToRoute('paustianmelodymixermodule_gamescore_' . ($isAdmin ? 'admin' : '') . 'index');
        }
        
        $action = strtolower($action);
        
        $repository = $entityFactory->getRepository($objectType);
        $userName = $currentUserApi->get('uname');
        
        // process each item
        foreach ($items as $itemId) {
            // check if item exists, and get record instance
            $entity = $repository->selectById($itemId, false);
            if (null === $entity) {
                continue;
            }
        
            // check if $action can be applied to this entity (may depend on it's current workflow state)
            $allowedActions = $workflowHelper->getActionsForObject($entity);
            $actionIds = array_keys($allowedActions);
            if (!in_array($action, $actionIds, true)) {
                // action not allowed, skip this object
                continue;
            }
        
            if ($entity->supportsHookSubscribers()) {
                // Let any ui hooks perform additional validation actions
                $hookType = 'delete' === $action
                    ? UiHooksCategory::TYPE_VALIDATE_DELETE
                    : UiHooksCategory::TYPE_VALIDATE_EDIT
                ;
                $validationErrors = $hookHelper->callValidationHooks($entity, $hookType);
                if (count($validationErrors) > 0) {
                    foreach ($validationErrors as $message) {
                        $this->addFlash('error', $message);
                    }
                    continue;
                }
            }
        
            $success = false;
            try {
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $action);
            } catch (Exception $exception) {
                $this->addFlash(
                    'error',
                    $this->trans(
                        'Sorry, but an error occured during the %action% action.',
                        ['%action%' => $action]
                    ) . '  ' . $exception->getMessage()
                );
                $logger->error(
                    '{app}: User {user} tried to execute the {action} workflow action for the {entity} with id {id},'
                        . ' but failed. Error details: {errorMessage}.',
                    [
                        'app' => 'PaustianMelodyMixerModule',
                        'user' => $userName,
                        'action' => $action,
                        'entity' => 'game score',
                        'id' => $itemId,
                        'errorMessage' => $exception->getMessage()
                    ]
                );
            }
        
            if (!$success) {
                continue;
            }
        
            if ('delete' === $action) {
                $this->addFlash(
                    'status',
                    $this->trans(
                        'Done! Game score deleted.',
                        [],
                        'gameScore'
                    )
                );
                $logger->notice(
                    '{app}: User {user} deleted the {entity} with id {id}.',
                    [
                        'app' => 'PaustianMelodyMixerModule',
                        'user' => $userName,
                        'entity' => 'game score',
                        'id' => $itemId
                    ]
                );
            } else {
                $this->addFlash(
                    'status',
                    $this->trans(
                        'Done! Game score updated.',
                        [],
                        'gameScore'
                    )
                );
                $logger->notice(
                    '{app}: User {user} executed the {action} workflow action for the {entity} with id {id}.',
                    [
                        'app' => 'PaustianMelodyMixerModule',
                        'user' => $userName,
                        'action' => $action,
                        'entity' => 'game score',
                        'id' => $itemId
                    ]
                );
            }
        
            if ($entity->supportsHookSubscribers()) {
                // Let any ui hooks know that we have updated or deleted an item
                $hookType = 'delete' === $action
                    ? UiHooksCategory::TYPE_PROCESS_DELETE
                    : UiHooksCategory::TYPE_PROCESS_EDIT
                ;
                $url = null;
                $hookHelper->callProcessHooks($entity, $hookType, $url);
            }
        }
        
        return $this->redirectToRoute('paustianmelodymixermodule_gamescore_' . ($isAdmin ? 'admin' : '') . 'index');
    }
    
}
