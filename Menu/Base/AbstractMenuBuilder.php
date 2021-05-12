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

namespace Paustian\MelodyMixerModule\Menu\Base;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use Paustian\MelodyMixerModule\Entity\GameScoreEntity;
use Paustian\MelodyMixerModule\Entity\ScoreEntity;
use Paustian\MelodyMixerModule\Entity\LevelEntity;
use Paustian\MelodyMixerModule\Entity\GraphicsAndSoundEntity;
use Paustian\MelodyMixerModule\Entity\MusicScoreEntity;
use Paustian\MelodyMixerModule\MelodyMixerEvents;
use Paustian\MelodyMixerModule\Event\ItemActionsMenuPostConfigurationEvent;
use Paustian\MelodyMixerModule\Event\ItemActionsMenuPreConfigurationEvent;
use Paustian\MelodyMixerModule\Event\ViewActionsMenuPostConfigurationEvent;
use Paustian\MelodyMixerModule\Event\ViewActionsMenuPreConfigurationEvent;
use Paustian\MelodyMixerModule\Helper\ModelHelper;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Menu builder base class.
 */
class AbstractMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;
    
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    
    /**
     * @var RequestStack
     */
    protected $requestStack;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var ModelHelper
     */
    protected $modelHelper;
    
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        VariableApiInterface $variableApi,
        ModelHelper $modelHelper
    ) {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestStack = $requestStack;
        $this->permissionHelper = $permissionHelper;
        $this->currentUserApi = $currentUserApi;
        $this->variableApi = $variableApi;
        $this->modelHelper = $modelHelper;
    }
    
    /**
     * Builds the item actions menu.
     */
    public function createItemActionsMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('itemActions');
        if (!isset($options['entity'], $options['area'], $options['context'])) {
            return $menu;
        }
    
        $entity = $options['entity'];
        $routeArea = $options['area'];
        $context = $options['context'];
        $menu->setChildrenAttribute('class', 'nav item-actions');
    
        $this->eventDispatcher->dispatch(
            new ItemActionsMenuPreConfigurationEvent($this->factory, $menu, $options)
        );
    
        $currentUserId = $this->currentUserApi->isLoggedIn()
            ? $this->currentUserApi->get('uid')
            : UsersConstant::USER_ID_ANONYMOUS
        ;
        if ($entity instanceof GameScoreEntity) {
            $routePrefix = 'paustianmelodymixermodule_gamescore_';
            $isOwner = 0 < $currentUserId
                && null !== $entity->getCreatedBy()
                && $currentUserId === $entity->getCreatedBy()->getUid()
            ;
            
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild('Edit', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Edit this game score'
                    )
                    ->setAttribute('icon', 'fas fa-edit')
                    ->setExtra('translation_domain', 'gameScore')
                ;
                $menu->addChild('Reuse', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        'Reuse for new game score'
                    )
                    ->setAttribute('icon', 'fas fa-copy')
                    ->setExtra('translation_domain', 'gameScore')
                ;
            }
            if ($this->permissionHelper->mayDelete($entity)) {
                $menu->addChild('Delete', [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Delete this game score'
                    )
                    ->setAttribute('icon', 'fas fa-trash-o')
                    ->setExtra('translation_domain', 'gameScore')
                ;
            }
        }
        if ($entity instanceof ScoreEntity) {
            $routePrefix = 'paustianmelodymixermodule_score_';
            $isOwner = 0 < $currentUserId
                && null !== $entity->getCreatedBy()
                && $currentUserId === $entity->getCreatedBy()->getUid()
            ;
            
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild('Edit', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Edit this score'
                    )
                    ->setAttribute('icon', 'fas fa-edit')
                    ->setExtra('translation_domain', 'score')
                ;
                $menu->addChild('Reuse', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        'Reuse for new score'
                    )
                    ->setAttribute('icon', 'fas fa-copy')
                    ->setExtra('translation_domain', 'score')
                ;
            }
            if ($this->permissionHelper->mayDelete($entity)) {
                $menu->addChild('Delete', [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Delete this score'
                    )
                    ->setAttribute('icon', 'fas fa-trash-o')
                    ->setExtra('translation_domain', 'score')
                ;
            }
        }
        if ($entity instanceof LevelEntity) {
            $routePrefix = 'paustianmelodymixermodule_level_';
            $isOwner = 0 < $currentUserId
                && null !== $entity->getCreatedBy()
                && $currentUserId === $entity->getCreatedBy()->getUid()
            ;
            
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild('Edit', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Edit this level'
                    )
                    ->setAttribute('icon', 'fas fa-edit')
                    ->setExtra('translation_domain', 'level')
                ;
                $menu->addChild('Reuse', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        'Reuse for new level'
                    )
                    ->setAttribute('icon', 'fas fa-copy')
                    ->setExtra('translation_domain', 'level')
                ;
            }
            if ($this->permissionHelper->mayDelete($entity)) {
                $menu->addChild('Delete', [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Delete this level'
                    )
                    ->setAttribute('icon', 'fas fa-trash-o')
                    ->setExtra('translation_domain', 'level')
                ;
            }
            
            // more actions for adding new related items
            
            if ($isOwner || $this->permissionHelper->hasComponentPermission('graphicsAndSound', ACCESS_EDIT)) {
                $menu->addChild('Create graphics and sound', [
                    'route' => 'paustianmelodymixermodule_graphicsandsound_' . $routeArea . 'edit',
                    'routeParameters' => ['level' => $entity->getKey()]
                ])
                    ->setAttribute('icon', 'fas fa-plus')
                    ->setExtra('translation_domain', 'level')
                ;
            }
            
            if ($isOwner || $this->permissionHelper->hasComponentPermission('musicScore', ACCESS_EDIT)) {
                $menu->addChild('Create music scores', [
                    'route' => 'paustianmelodymixermodule_musicscore_' . $routeArea . 'edit',
                    'routeParameters' => ['level' => $entity->getKey()]
                ])
                    ->setAttribute('icon', 'fas fa-plus')
                    ->setExtra('translation_domain', 'level')
                ;
            }
        }
        if ($entity instanceof GraphicsAndSoundEntity) {
            $routePrefix = 'paustianmelodymixermodule_graphicsandsound_';
            $isOwner = 0 < $currentUserId
                && null !== $entity->getCreatedBy()
                && $currentUserId === $entity->getCreatedBy()->getUid()
            ;
            
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild('Edit', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Edit this graphics and sound'
                    )
                    ->setAttribute('icon', 'fas fa-edit')
                    ->setExtra('translation_domain', 'graphicsAndSound')
                ;
                $menu->addChild('Reuse', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        'Reuse for new graphics and sound'
                    )
                    ->setAttribute('icon', 'fas fa-copy')
                    ->setExtra('translation_domain', 'graphicsAndSound')
                ;
            }
            if ($this->permissionHelper->mayDelete($entity)) {
                $menu->addChild('Delete', [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Delete this graphics and sound'
                    )
                    ->setAttribute('icon', 'fas fa-trash-o')
                    ->setExtra('translation_domain', 'graphicsAndSound')
                ;
            }
        }
        if ($entity instanceof MusicScoreEntity) {
            $routePrefix = 'paustianmelodymixermodule_musicscore_';
            $isOwner = 0 < $currentUserId
                && null !== $entity->getCreatedBy()
                && $currentUserId === $entity->getCreatedBy()->getUid()
            ;
            
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild('Edit', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Edit this music score'
                    )
                    ->setAttribute('icon', 'fas fa-edit')
                    ->setExtra('translation_domain', 'musicScore')
                ;
                $menu->addChild('Reuse', [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        'Reuse for new music score'
                    )
                    ->setAttribute('icon', 'fas fa-copy')
                    ->setExtra('translation_domain', 'musicScore')
                ;
            }
            if ($this->permissionHelper->mayDelete($entity)) {
                $menu->addChild('Delete', [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        'Delete this music score'
                    )
                    ->setAttribute('icon', 'fas fa-trash-o')
                    ->setExtra('translation_domain', 'musicScore')
                ;
            }
        }
    
        $this->eventDispatcher->dispatch(
            new ItemActionsMenuPostConfigurationEvent($this->factory, $menu, $options)
        );
    
        return $menu;
    }
    
    /**
     * Builds the view actions menu.
     */
    public function createViewActionsMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('viewActions');
        if (!isset($options['objectType'], $options['area'])) {
            return $menu;
        }
    
        $objectType = $options['objectType'];
        $routeArea = $options['area'];
        $menu->setChildrenAttribute('class', 'nav view-actions');
    
        $this->eventDispatcher->dispatch(
            new ViewActionsMenuPreConfigurationEvent($this->factory, $menu, $options)
        );
    
        $query = $this->requestStack->getMasterRequest()->query;
        $currentTemplate = $query->getAlnum('tpl', '');
        if ('gameScore' === $objectType) {
            $routePrefix = 'paustianmelodymixermodule_gamescore_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild('Create game score', [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fas fa-plus')
                            ->setExtra('translation_domain', 'gameScore')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild('Back to paginated view', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild('Show all entries', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild('Show also entries from other users', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild('Show only own entries', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-user')
                        ;
                    }
                }
            }
        }
        if ('score' === $objectType) {
            $routePrefix = 'paustianmelodymixermodule_score_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild('Create score', [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fas fa-plus')
                            ->setExtra('translation_domain', 'score')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild('Back to paginated view', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild('Show all entries', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild('Show also entries from other users', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild('Show only own entries', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-user')
                        ;
                    }
                }
            }
        }
        if ('level' === $objectType) {
            $routePrefix = 'paustianmelodymixermodule_level_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild('Create level', [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fas fa-plus')
                            ->setExtra('translation_domain', 'level')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild('Back to paginated view', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild('Show all entries', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild('Show also entries from other users', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild('Show only own entries', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-user')
                        ;
                    }
                }
            }
        }
        if ('graphicsAndSound' === $objectType) {
            $routePrefix = 'paustianmelodymixermodule_graphicsandsound_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild('Create graphics and sound', [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fas fa-plus')
                            ->setExtra('translation_domain', 'graphicsAndSound')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild('Back to paginated view', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild('Show all entries', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild('Show also entries from other users', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild('Show only own entries', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-user')
                        ;
                    }
                }
            }
        }
        if ('musicScore' === $objectType) {
            $routePrefix = 'paustianmelodymixermodule_musicscore_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild('Create music score', [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fas fa-plus')
                            ->setExtra('translation_domain', 'musicScore')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild('Back to paginated view', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild('Show all entries', [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fas fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild('Show also entries from other users', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild('Show only own entries', [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fas fa-user')
                        ;
                    }
                }
            }
        }
    
        $this->eventDispatcher->dispatch(
            new ViewActionsMenuPostConfigurationEvent($this->factory, $menu, $options)
        );
    
        return $menu;
    }
}
