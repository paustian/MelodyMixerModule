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

namespace Paustian\MelodyMixerModule\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Helper\ControllerHelper;
use Paustian\MelodyMixerModule\Helper\EntityDisplayHelper;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Retrieve item list for finder selections, for example used in Scribite editor plug-ins.
     */
    public function getItemListFinderAction(
        Request $request,
        ControllerHelper $controllerHelper,
        PermissionHelper $permissionHelper,
        EntityFactory $entityFactory,
        EntityDisplayHelper $entityDisplayHelper
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianMelodyMixerModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->query->getAlnum('ot', 'gameScore');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListFinder'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs), true)) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $entityFactory->getRepository($objectType);
        $descriptionFieldName = $entityDisplayHelper->getDescriptionFieldName($objectType);
        
        $sort = $request->query->getAlnum('sort');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields(), true)) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = strtolower($request->query->getAlpha('sortdir'));
        if ('asc' !== $sdir && 'desc' !== $sdir) {
            $sdir = 'asc';
        }
        
        $where = ''; // filters are processed inside the repository class
        $searchTerm = $request->query->get('q');
        $sortParam = $sort . ' ' . $sdir;
        
        $entities = [];
        if ('' !== $searchTerm) {
            list ($entities, $totalAmount) = $repository->selectSearch($searchTerm, [], $sortParam, 1, 50, false);
        } else {
            $entities = $repository->selectWhere($where, $sortParam);
        }
        
        $slimItems = [];
        foreach ($entities as $item) {
            if (!$permissionHelper->mayRead($item)) {
                continue;
            }
            $itemId = $item->getKey();
            $slimItems[] = $this->prepareSlimItem(
                $controllerHelper,
                $repository,
                $entityDisplayHelper,
                $item,
                $itemId,
                $descriptionFieldName
            );
        }
        
        // return response
        return $this->json($slimItems);
    }
    
    /**
     * Builds and returns a slim data array from a given entity.
     */
    protected function prepareSlimItem(
        ControllerHelper $controllerHelper,
        EntityRepository $repository,
        EntityDisplayHelper $entityDisplayHelper,
        $item,
        string $itemId,
        string $descriptionField
    ): array {
        $objectType = $item->get_objectType();
        $previewParameters = [
            $objectType => $item
        ];
        $contextArgs = ['controller' => $objectType, 'action' => 'display'];
        $previewParameters = $controllerHelper->addTemplateParameters(
            $objectType,
            $previewParameters,
            'controllerAction',
            $contextArgs
        );
    
        $previewInfo = $this->renderView(
            '@PaustianMelodyMixerModule/External/' . ucfirst($objectType) . '/info.html.twig',
            $previewParameters
        );
        $previewInfo = base64_encode($previewInfo);
    
        $title = $entityDisplayHelper->getFormattedTitle($item);
        $description = $descriptionField !== '' ? $item[$descriptionField] : '';
    
        return [
            'id' => $itemId,
            'title' => str_replace('&amp;', '&', $title),
            'description' => $description,
            'previewInfo' => $previewInfo
        ];
    }
    
    /**
     * Checks whether a field value is a duplicate or not.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function checkForDuplicateAction(
        Request $request,
        ControllerHelper $controllerHelper,
        EntityFactory $entityFactory
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianMelodyMixerModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->query->getAlnum('ot', 'gameScore');
        $contextArgs = ['controller' => 'ajax', 'action' => 'checkForDuplicate'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs), true)) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $fieldName = $request->query->getAlnum('fn');
        $value = $request->query->get('v');
        
        if (empty($fieldName) || empty($value)) {
            return $this->json($this->trans('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // check if the given field is existing and unique
        $uniqueFields = [];
        switch ($objectType) {
            case 'gameScore':
                $uniqueFields = ['playerUid'];
                break;
        }
        if (!count($uniqueFields) || !in_array($fieldName, $uniqueFields, true)) {
            return $this->json($this->trans('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $exclude = $request->query->getInt('ex');
        
        $result = false;
        switch ($objectType) {
            case 'gameScore':
                $repository = $entityFactory->getRepository($objectType);
                switch ($fieldName) {
                    case 'playerUid':
                        $result = !$repository->detectUniqueState('playerUid', $value, $exclude);
                        break;
                }
                break;
        }
        
        // return response
        return $this->json(['isDuplicate' => $result]);
    }
}
