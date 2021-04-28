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

namespace Paustian\MelodyMixerModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Paustian\MelodyMixerModule\Traits\StandardFieldsTrait;
use Paustian\MelodyMixerModule\Validator\Constraints as MelodyMixerAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for graphics and sound entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractGraphicsAndSoundEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'graphicsAndSound';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var int $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     *
     * @ORM\Column(length=20)
     * @Assert\NotBlank
     * @MelodyMixerAssert\ListEntry(entityName="graphicsAndSound", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var int $levelid
     */
    protected $levelid = 0;
    
    /**
     * The name of the graphic
     *
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     * @var string $gsName
     */
    protected $gsName = '';
    
    /**
     * The path to the graphic/sound
     *
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     * @var string $gsPath
     */
    protected $gsPath = '';
    
    /**
     * The x position of the graphic on the stage
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=100000000000)
     * @var int $xPos
     */
    protected $xPos = 0;
    
    /**
     * The y position of the graphic on the stage
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=100000000000)
     * @var int $yPos
     */
    protected $yPos = 0;
    
    /**
     * Any text the graphic might want to say
     *
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotNull
     * @Assert\Length(min="0", max="2000", allowEmptyString="true")
     * @var string $descText
     */
    protected $descText = '';
    
    /**
     * The position of the descriptive text box
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=100000000000)
     * @var int $xDes
     */
    protected $xDes = 0;
    
    /**
     * The y position of the descriptive text
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=100000000000)
     * @var int $yDes
     */
    protected $yDes = 0;
    
    /**
     * The width of the descriptive text box
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=100000000000)
     * @var int $boxWidth
     */
    protected $boxWidth = 0;
    
    /**
     * Shoud the graphic be placed at the bottom of the stage. 
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $graphicAtBottom
     */
    protected $graphicAtBottom = false;
    
    
    
    /**
     * GraphicsAndSoundEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
    }
    
    public function get_objectType(): string
    {
        return $this->_objectType;
    }
    
    public function set_objectType(string $_objectType): void
    {
        if ($this->_objectType !== $_objectType) {
            $this->_objectType = $_objectType ?? '';
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id = null): void
    {
        if ((int)$this->id !== $id) {
            $this->id = $id;
        }
    }
    
    public function getWorkflowState(): string
    {
        return $this->workflowState;
    }
    
    public function setWorkflowState(string $workflowState): void
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = $workflowState ?? '';
        }
    }
    
    public function getLevelid(): int
    {
        return $this->levelid;
    }
    
    public function setLevelid(int $levelid): void
    {
        if ((int)$this->levelid !== $levelid) {
            $this->levelid = $levelid;
        }
    }
    
    public function getGsName(): string
    {
        return $this->gsName;
    }
    
    public function setGsName(string $gsName): void
    {
        if ($this->gsName !== $gsName) {
            $this->gsName = $gsName ?? '';
        }
    }
    
    public function getGsPath(): string
    {
        return $this->gsPath;
    }
    
    public function setGsPath(string $gsPath): void
    {
        if ($this->gsPath !== $gsPath) {
            $this->gsPath = $gsPath ?? '';
        }
    }
    
    public function getXPos(): int
    {
        return $this->xPos;
    }
    
    public function setXPos(int $xPos): void
    {
        if ((int)$this->xPos !== $xPos) {
            $this->xPos = $xPos;
        }
    }
    
    public function getYPos(): int
    {
        return $this->yPos;
    }
    
    public function setYPos(int $yPos): void
    {
        if ((int)$this->yPos !== $yPos) {
            $this->yPos = $yPos;
        }
    }
    
    public function getDescText(): string
    {
        return $this->descText;
    }
    
    public function setDescText(string $descText): void
    {
        if ($this->descText !== $descText) {
            $this->descText = $descText ?? '';
        }
    }
    
    public function getXDes(): int
    {
        return $this->xDes;
    }
    
    public function setXDes(int $xDes): void
    {
        if ((int)$this->xDes !== $xDes) {
            $this->xDes = $xDes;
        }
    }
    
    public function getYDes(): int
    {
        return $this->yDes;
    }
    
    public function setYDes(int $yDes): void
    {
        if ((int)$this->yDes !== $yDes) {
            $this->yDes = $yDes;
        }
    }
    
    public function getBoxWidth(): int
    {
        return $this->boxWidth;
    }
    
    public function setBoxWidth(int $boxWidth): void
    {
        if ((int)$this->boxWidth !== $boxWidth) {
            $this->boxWidth = $boxWidth;
        }
    }
    
    public function getGraphicAtBottom(): bool
    {
        return $this->graphicAtBottom;
    }
    
    public function setGraphicAtBottom(bool $graphicAtBottom): void
    {
        if ((bool)$this->graphicAtBottom !== $graphicAtBottom) {
            $this->graphicAtBottom = $graphicAtBottom;
        }
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     */
    public function createUrlArgs(): array
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     */
    public function getKey(): ?int
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     */
    public function supportsHookSubscribers(): bool
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     */
    public function getHookAreaPrefix(): string
    {
        return 'paustianmelodymixermodule.ui_hooks.graphicsandsound';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     */
    public function getRelatedObjectsToPersist(array &$objects = []): array
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString(): string
    {
        return 'Graphics and sound ' . $this->getKey() . ': ' . $this->getGsName();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    }
}
