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

namespace Paustian\MelodyMixerModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\ThemeModule\Bridge\Event\TwigPostRenderEvent;
use Zikula\ThemeModule\Bridge\Event\TwigPreRenderEvent;

/**
 * Event handler base class for theme-related events.
 */
abstract class AbstractThemeListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            TwigPreRenderEvent::class  => ['preRender', 5],
            TwigPostRenderEvent::class => ['postRender', 5]
        ];
    }
    
    /**
     * Listener for the `TwigPreRenderEvent`.
     *
     * Occurs immediately before twig theme engine renders a template.
     */
    public function preRender(TwigPreRenderEvent $event): void
    {
    }
    
    /**
     * Listener for the `TwigPostRenderEvent`.
     *
     * Occurs immediately after twig theme engine renders a template.
     */
    public function postRender(TwigPostRenderEvent $event): void
    {
    }
}
