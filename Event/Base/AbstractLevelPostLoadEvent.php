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

use Paustian\MelodyMixerModule\Entity\LevelEntity;

/**
 * Event base class for filtering level processing.
 */
class AbstractLevelPostLoadEvent
{
    /**
     * @var LevelEntity Reference to treated entity instance.
     */
    protected $level;

    public function __construct(LevelEntity $level)
    {
        $this->level = $level;
    }

    /**
     * @return LevelEntity
     */
    public function getLevel(): LevelEntity
    {
        return $this->level;
    }
}
