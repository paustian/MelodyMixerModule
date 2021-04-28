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

namespace Paustian\MelodyMixerModule\Event\Base;

use Paustian\MelodyMixerModule\Entity\GraphicsAndSoundEntity;

/**
 * Event base class for filtering graphics and sound processing.
 */
class AbstractGraphicsAndSoundPostUpdateEvent
{
    /**
     * @var GraphicsAndSoundEntity Reference to treated entity instance.
     */
    protected $graphicsAndSound;

    public function __construct(GraphicsAndSoundEntity $graphicsAndSound)
    {
        $this->graphicsAndSound = $graphicsAndSound;
    }

    /**
     * @return GraphicsAndSoundEntity
     */
    public function getGraphicsAndSound(): GraphicsAndSoundEntity
    {
        return $this->graphicsAndSound;
    }
}
