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

use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreBundle\Translation\TranslatorTrait;

/**
 * Helper base class for list field entries related methods.
 */
abstract class AbstractListEntriesHelper
{
    use TranslatorTrait;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }
    
    /**
     * Return the name or names for a given list item.
     */
    public function resolve(
        string $value,
        string $objectType = '',
        string $fieldName = '',
        string $delimiter = ', '
    ): string {
        if ((empty($value) && '0' !== $value) || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        $isMulti = $this->hasMultipleSelection($objectType, $fieldName);
        $values = $isMulti ? $this->extractMultiList($value) : [];
    
        $options = $this->getEntries($objectType, $fieldName);
        $result = '';
    
        if (true === $isMulti) {
            foreach ($options as $option) {
                if (!in_array($option['value'], $values, true)) {
                    continue;
                }
                if (!empty($result)) {
                    $result .= $delimiter;
                }
                $result .= $option['text'];
            }
        } else {
            foreach ($options as $option) {
                if ($option['value'] !== $value) {
                    continue;
                }
                $result = $option['text'];
                break;
            }
        }
    
        return $result;
    }
    
    
    /**
     * Extract concatenated multi selection.
     */
    public function extractMultiList(string $value): array
    {
        $listValues = explode('###', $value);
        $amountOfValues = count($listValues);
        if ($amountOfValues > 1 && '' === $listValues[$amountOfValues - 1]) {
            unset($listValues[$amountOfValues - 1]);
        }
        if ('' === $listValues[0]) {
            // use array_shift instead of unset for proper key reindexing
            // keys must start with 0, otherwise the dropdownlist form plugin gets confused
            array_shift($listValues);
        }
    
        return $listValues;
    }
    
    
    /**
     * Determine whether a certain dropdown field has a multi selection or not.
     */
    public function hasMultipleSelection(string $objectType, string $fieldName): bool
    {
        if (empty($objectType) || empty($fieldName)) {
            return false;
        }
    
        $result = false;
        switch ($objectType) {
            case 'gameScore':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'score':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'level':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'graphicsAndSound':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'musicScore':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                }
                break;
            case 'appSettings':
                switch ($fieldName) {
                    case 'enabledFinderTypes':
                        $result = true;
                        break;
                }
                break;
        }
    
        return $result;
    }
    
    
    /**
     * Get entries for a certain dropdown field.
     */
    public function getEntries(string $objectType, string $fieldName): array
    {
        if (empty($objectType) || empty($fieldName)) {
            return [];
        }
    
        $entries = [];
        switch ($objectType) {
            case 'gameScore':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForGameScore();
                        break;
                }
                break;
            case 'score':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForScore();
                        break;
                }
                break;
            case 'level':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForLevel();
                        break;
                }
                break;
            case 'graphicsAndSound':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForGraphicsAndSound();
                        break;
                }
                break;
            case 'musicScore':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForMusicScore();
                        break;
                }
                break;
            case 'appSettings':
                switch ($fieldName) {
                    case 'enabledFinderTypes':
                        $entries = $this->getEnabledFinderTypesEntriesForAppSettings();
                        break;
                }
                break;
        }
    
        return $entries;
    }
    
    
    /**
     * Get 'workflow state' list entries.
     */
    public function getWorkflowStateEntriesForGameScore(): array
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->trans('Approved'),
            'title'   => $this->trans('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->trans('Trashed'),
            'title'   => $this->trans('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->trans('All except approved'),
            'title'   => $this->trans('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->trans('All except trashed'),
            'title'   => $this->trans('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'workflow state' list entries.
     */
    public function getWorkflowStateEntriesForScore(): array
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->trans('Approved'),
            'title'   => $this->trans('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->trans('Trashed'),
            'title'   => $this->trans('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->trans('All except approved'),
            'title'   => $this->trans('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->trans('All except trashed'),
            'title'   => $this->trans('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'workflow state' list entries.
     */
    public function getWorkflowStateEntriesForLevel(): array
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->trans('Approved'),
            'title'   => $this->trans('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->trans('Trashed'),
            'title'   => $this->trans('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->trans('All except approved'),
            'title'   => $this->trans('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->trans('All except trashed'),
            'title'   => $this->trans('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'workflow state' list entries.
     */
    public function getWorkflowStateEntriesForGraphicsAndSound(): array
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->trans('Approved'),
            'title'   => $this->trans('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->trans('Trashed'),
            'title'   => $this->trans('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->trans('All except approved'),
            'title'   => $this->trans('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->trans('All except trashed'),
            'title'   => $this->trans('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'workflow state' list entries.
     */
    public function getWorkflowStateEntriesForMusicScore(): array
    {
        $states = [];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->trans('Approved'),
            'title'   => $this->trans('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'trashed',
            'text'    => $this->trans('Trashed'),
            'title'   => $this->trans('Content has been marked as deleted, but is still persisted in the database.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->trans('All except approved'),
            'title'   => $this->trans('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!trashed',
            'text'    => $this->trans('All except trashed'),
            'title'   => $this->trans('Shows all items except these which are trashed'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'enabled finder types' list entries.
     */
    public function getEnabledFinderTypesEntriesForAppSettings(): array
    {
        $states = [];
    
        return $states;
    }
}
