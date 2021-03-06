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

namespace Paustian\MelodyMixerModule\HookSubscriber\Base;

use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\HookBundle\Category\FormAwareCategory;
use Zikula\Bundle\HookBundle\HookSubscriberInterface;

/**
 * Base class for form aware hook subscriber.
 */
abstract class AbstractGraphicsAndSoundFormAwareHookSubscriber implements HookSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getOwner(): string
    {
        return 'PaustianMelodyMixerModule';
    }
    
    public function getCategory(): string
    {
        return FormAwareCategory::NAME;
    }
    
    public function getTitle(): string
    {
        return $this->translator->trans('Graphics and sound form aware subscriber', [], 'hooks');
    }
    
    public function getAreaName(): string
    {
        return 'subscriber.paustianmelodymixermodule.form_aware_hook.graphicsandsound';
    }

    public function getEvents(): array
    {
        return [
            // Display hook for create/edit forms.
            FormAwareCategory::TYPE_EDIT => 'paustianmelodymixermodule.form_aware_hook.graphicsandsound.edit',
            // Process the results of the edit form after the main form is processed.
            FormAwareCategory::TYPE_PROCESS_EDIT => 'paustianmelodymixermodule.form_aware_hook.graphicsandsound.process_edit',
            // Display hook for delete forms.
            FormAwareCategory::TYPE_DELETE => 'paustianmelodymixermodule.form_aware_hook.graphicsandsound.delete',
            // Process the results of the delete form after the main form is processed.
            FormAwareCategory::TYPE_PROCESS_DELETE => 'paustianmelodymixermodule.form_aware_hook.graphicsandsound.process_delete'
        ];
    }
}
