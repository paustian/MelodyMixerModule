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

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Paustian\MelodyMixerModule\AppSettings;
use Paustian\MelodyMixerModule\Controller\Base\AbstractConfigController;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Config controller implementation class.
 *
 * @Route("/config")
 */
class ConfigController extends AbstractConfigController
{
    /**
     * @Route("/config",
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     */
    public function config(
        Request $request,
        PermissionHelper $permissionHelper,
        AppSettings $appSettings,
        LoggerInterface $logger,
        CurrentUserApiInterface $currentUserApi
    ): Response {
        return parent::configAction($request, $permissionHelper, $appSettings, $logger, $currentUserApi);
    }

    // feel free to add your own config controller methods here
}
