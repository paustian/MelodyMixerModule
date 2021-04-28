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

namespace Paustian\MelodyMixerModule\Helper\Base;

use Symfony\Component\Form\FormInterface;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\Bundle\CoreBundle\UrlInterface;
use Zikula\Bundle\HookBundle\Dispatcher\HookDispatcherInterface;
use Zikula\Bundle\HookBundle\FormAwareHook\FormAwareHook;
use Zikula\Bundle\HookBundle\FormAwareHook\FormAwareResponse;
use Zikula\Bundle\HookBundle\Hook\Hook;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\Hook\ValidationHook;
use Zikula\Bundle\HookBundle\Hook\ValidationProviders;

/**
 * Helper base class for hook related methods.
 */
abstract class AbstractHookHelper
{
    /**
     * @var HookDispatcherInterface
     */
    protected $hookDispatcher;
    
    public function __construct(HookDispatcherInterface $hookDispatcher)
    {
        $this->hookDispatcher = $hookDispatcher;
    }
    
    /**
     * Calls validation hooks.
     *
     * @return string[] List of error messages returned by validators
     */
    public function callValidationHooks(EntityAccess $entity, string $hookType): array
    {
        $hookAreaPrefix = $entity->getHookAreaPrefix();
    
        $hook = new ValidationHook(new ValidationProviders());
        $validators = $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $hook)->getValidators();
    
        return $validators->getErrors();
    }
    
    /**
     * Calls process hooks.
     */
    public function callProcessHooks(EntityAccess $entity, string $hookType, UrlInterface $routeUrl = null): void
    {
        $hookAreaPrefix = $entity->getHookAreaPrefix();
    
        $hook = new ProcessHook($entity->getKey(), $routeUrl);
        $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $hook);
    }
    
    /**
     * Calls form aware display hooks.
     */
    public function callFormDisplayHooks(FormInterface $form, EntityAccess $entity, string $hookType): FormAwareHook
    {
        $hookAreaPrefix = $entity->getHookAreaPrefix();
        $hookAreaPrefix = str_replace('.ui_hooks.', '.form_aware_hook.', $hookAreaPrefix);
    
        $hook = new FormAwareHook($form);
        $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $hook);
    
        return $hook;
    }
    
    /**
     * Calls form aware processing hooks.
     */
    public function callFormProcessHooks(
        FormInterface $form,
        EntityAccess $entity,
        string $hookType,
        UrlInterface $routeUrl = null
    ): void {
        $formResponse = new FormAwareResponse($form, $entity, $routeUrl);
        $hookAreaPrefix = $entity->getHookAreaPrefix();
        $hookAreaPrefix = str_replace('.ui_hooks.', '.form_aware_hook.', $hookAreaPrefix);
    
        $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $formResponse);
    }
    
    /**
     * Dispatch hooks.
     */
    public function dispatchHooks(string $eventName, Hook $hook)
    {
        return $this->hookDispatcher->dispatch($eventName, $hook);
    }
}
