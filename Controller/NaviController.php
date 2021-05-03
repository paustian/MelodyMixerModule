<?php

declare(strict_types=1);

namespace Paustian\MelodyMixerModule\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Zikula\ThemeModule\Engine\AssetFilter;
use Zikula\Bundle\CoreBundle\Response\PlainResponse;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Navigation controller for navigation pages like the main level and the various training pages.
 *
 * @Route("/navi")
 */

class NaviController extends AbstractController
{
    /**
     * @Route("/main",
     *        methods = {"GET", "POST"}
     * )
     */
    public function mainAction(
        Request $request,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        AssetFilter $assetFilter
    ): Response {
        if(!$permissionHelper->hasPermission( ACCESS_COMMENT)){
            return $this->render('@PaustianMelodyMixerModule/Navi/registerFirst.html.twig');
        }

        $output = $this->renderView("@PaustianMelodyMixerModule/Navi/mainLevel.html.twig");
        $output = $assetFilter->filter($output);
        return new PlainResponse($output);
    }
}