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
class AbstractGraphicsAndSoundPreUpdateEvent
{
    /**
     * @var GraphicsAndSoundEntity Reference to treated entity instance.
     */
    protected $graphicsAndSound;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    public function __construct(GraphicsAndSoundEntity $graphicsAndSound, array $entityChangeSet = [])
    {
        $this->graphicsAndSound = $graphicsAndSound;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * @return GraphicsAndSoundEntity
     */
    public function getGraphicsAndSound(): GraphicsAndSoundEntity
    {
        return $this->graphicsAndSound;
    }

    /**
     * @return array Entity change set
     */
    public function getEntityChangeSet(): array
    {
        return $this->entityChangeSet;
    }

    /**
     * @param array $changeSet Entity change set
     */
    public function setEntityChangeSet(array $changeSet = []): void
    {
        $this->entityChangeSet = $changeSet;
    }
}
