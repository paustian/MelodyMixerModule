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

namespace Paustian\MelodyMixerModule\Entity;

use Paustian\MelodyMixerModule\Entity\Base\AbstractScoreEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for score entities.
 * @ORM\Entity(repositoryClass="Paustian\MelodyMixerModule\Entity\Repository\ScoreRepository")
 * @ORM\Table(name="paustian_melody_score",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class ScoreEntity extends BaseEntity
{
    // feel free to add your own methods here
}