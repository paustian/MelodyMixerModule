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

namespace Paustian\MelodyMixerModule\Block\Base;

use Exception;
use Twig\Loader\LoaderInterface;
use Zikula\BlocksModule\AbstractBlockHandler;
use Paustian\MelodyMixerModule\Block\Form\Type\ItemListBlockType;
use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Helper\ControllerHelper;
use Paustian\MelodyMixerModule\Helper\ModelHelper;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Generic item list block base class.
 */
abstract class AbstractItemListBlock extends AbstractBlockHandler
{
    /**
     * @var LoaderInterface
     */
    protected $twigLoader;
    
    /**
     * @var ControllerHelper
     */
    protected $controllerHelper;
    
    /**
     * @var ModelHelper
     */
    protected $modelHelper;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    
    public function getType(): string
    {
        return $this->trans('Melody mixer list');
    }
    
    public function display(array $properties = []): string
    {
        // only show block content if the user has the required permissions
        if (!$this->hasPermission('PaustianMelodyMixerModule:ItemListBlock:', $properties['title'] . '::', ACCESS_OVERVIEW)) {
            return '';
        }
        
        // set default values for all params which are not properly set
        $defaults = $this->getDefaults();
        $properties = array_merge($defaults, $properties);
        
        $contextArgs = ['name' => 'list'];
        $allowedObjectTypes = $this->controllerHelper->getObjectTypes('block', $contextArgs);
        if (
            !isset($properties['objectType'])
            || !in_array($properties['objectType'], $allowedObjectTypes, true)
        ) {
            $properties['objectType'] = $this->controllerHelper->getDefaultObjectType('block', $contextArgs);
        }
        
        $objectType = $properties['objectType'];
        
        $repository = $this->entityFactory->getRepository($objectType);
        
        // create query
        $orderBy = $this->modelHelper->resolveSortParameter($objectType, $properties['sorting']);
        $qb = $repository->getListQueryBuilder($properties['filter'], $orderBy);
        
        // get objects from database
        $currentPage = 1;
        $resultsPerPage = $properties['amount'];
        $paginator = $repository->retrieveCollectionResult($qb, true, $currentPage, $resultsPerPage);
        $entities = $paginator->getResults();
        
        // filter by permissions
        $entities = $this->permissionHelper->filterCollection($objectType, $entities, ACCESS_READ);
        
        // set a block title
        if (empty($properties['title'])) {
            $properties['title'] = $this->trans('Melody mixer list');
        }
        
        $template = $this->getDisplayTemplate($properties);
        
        $templateParameters = [
            'vars' => $properties,
            'objectType' => $objectType,
            'items' => $entities
        ];
        
        $templateParameters = $this->controllerHelper->addTemplateParameters(
            $properties['objectType'],
            $templateParameters,
            'block'
        );
        
        return $this->renderView($template, $templateParameters);
    }
    
    /**
     * Returns the template used for output.
     */
    protected function getDisplayTemplate(array $properties = []): string
    {
        $templateFile = $properties['template'];
        if (
            'custom' === $templateFile
            && null !== $properties['customTemplate']
            && '' !== $properties['customTemplate']
        ) {
            $templateFile = $properties['customTemplate'];
        }
    
        $templateForObjectType = str_replace('itemlist_', 'itemlist_' . $properties['objectType'] . '_', $templateFile);
    
        $templateOptions = [
            'Block/' . $templateForObjectType,
            'Block/' . $templateFile,
            'Block/itemlist.html.twig'
        ];
    
        $template = '';
        foreach ($templateOptions as $templatePath) {
            if ($this->twigLoader->exists('@PaustianMelodyMixerModule/' . $templatePath)) {
                $template = '@PaustianMelodyMixerModule/' . $templatePath;
                break;
            }
        }
    
        return $template;
    }
    
    public function getFormClassName(): string
    {
        return ItemListBlockType::class;
    }
    
    public function getFormOptions(): array
    {
        $objectType = 'gameScore';
    
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request && $request->attributes->has('blockEntity')) {
            $blockEntity = $request->attributes->get('blockEntity');
            if (is_object($blockEntity) && method_exists($blockEntity, 'getProperties')) {
                $blockProperties = $blockEntity->getProperties();
                if (isset($blockProperties['objectType'])) {
                    $objectType = $blockProperties['objectType'];
                } else {
                    // set default options for new block creation
                    $blockEntity->setProperties($this->getDefaults());
                }
            }
        }
    
        return [
            'object_type' => $objectType
        ];
    }
    
    public function getFormTemplate(): string
    {
        return '@PaustianMelodyMixerModule/Block/itemlist_modify.html.twig';
    }
    
    /**
     * Returns default settings for this block.
     */
    protected function getDefaults(): array
    {
        return [
            'objectType' => 'gameScore',
            'sorting' => 'default',
            'amount' => 5,
            'template' => 'itemlist_display.html.twig',
            'customTemplate' => null,
            'filter' => ''
        ];
    }
    
    /**
     * @required
     */
    public function setTwigLoader(LoaderInterface $twigLoader): void
    {
        $this->twigLoader = $twigLoader;
    }
    
    /**
     * @required
     */
    public function setControllerHelper(ControllerHelper $controllerHelper): void
    {
        $this->controllerHelper = $controllerHelper;
    }
    
    /**
     * @required
     */
    public function setModelHelper(ModelHelper $modelHelper): void
    {
        $this->modelHelper = $modelHelper;
    }
    
    /**
     * @required
     */
    public function setPermissionHelper(PermissionHelper $permissionHelper): void
    {
        $this->permissionHelper = $permissionHelper;
    }
    
    /**
     * @required
     */
    public function setEntityFactory(EntityFactory $entityFactory): void
    {
        $this->entityFactory = $entityFactory;
    }
}