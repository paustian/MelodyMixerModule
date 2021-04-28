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

namespace Paustian\MelodyMixerModule\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Paustian\MelodyMixerModule\Controller\Base\AbstractAjaxController;
use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Helper\ControllerHelper;
use Paustian\MelodyMixerModule\Helper\EntityDisplayHelper;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Ajax controller implementation class.
 *
 * @Route("/ajax")
 */
class AjaxController extends AbstractAjaxController
{
    
    /**
     *
     * @Route("/getItemListFinder", methods = {"GET"}, options={"expose"=true})
     */
    public function getItemListFinderAction(
        Request $request,
        ControllerHelper $controllerHelper,
        PermissionHelper $permissionHelper,
        EntityFactory $entityFactory,
        EntityDisplayHelper $entityDisplayHelper
    ): JsonResponse {
        return parent::getItemListFinderAction(
            $request,
            $controllerHelper,
            $permissionHelper,
            $entityFactory,
            $entityDisplayHelper
        );
    }
    
    /**
     * @Route("/checkForDuplicate", methods = {"GET"}, options={"expose"=true})
     */
    public function checkForDuplicateAction(
        Request $request,
        ControllerHelper $controllerHelper,
        EntityFactory $entityFactory
    ): JsonResponse {
        return parent::checkForDuplicateAction(
            $request,
            $controllerHelper,
            $entityFactory
        );
    }

    // feel free to add your own ajax controller methods here
}
