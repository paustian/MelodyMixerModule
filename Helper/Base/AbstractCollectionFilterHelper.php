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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use Paustian\MelodyMixerModule\Helper\PermissionHelper;

/**
 * Entity collection filter helper base class.
 */
abstract class AbstractCollectionFilterHelper
{
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
     * @var bool Fallback value to determine whether only own entries should be selected or not
     */
    protected $showOnlyOwnEntries = false;
    
    public function __construct(
        RequestStack $requestStack,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        VariableApiInterface $variableApi
    ) {
        $this->requestStack = $requestStack;
        $this->permissionHelper = $permissionHelper;
        $this->currentUserApi = $currentUserApi;
        $this->showOnlyOwnEntries = (bool)$variableApi->get('PaustianMelodyMixerModule', 'showOnlyOwnEntries');
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    public function getViewQuickNavParameters(string $objectType = '', string $context = '', array $args = []): array
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'], true)) {
            $context = 'controllerAction';
        }
    
        if ('gameScore' === $objectType) {
            return $this->getViewQuickNavParametersForGameScore($context, $args);
        }
        if ('score' === $objectType) {
            return $this->getViewQuickNavParametersForScore($context, $args);
        }
        if ('level' === $objectType) {
            return $this->getViewQuickNavParametersForLevel($context, $args);
        }
        if ('graphicsAndSound' === $objectType) {
            return $this->getViewQuickNavParametersForGraphicsAndSound($context, $args);
        }
        if ('musicScore' === $objectType) {
            return $this->getViewQuickNavParametersForMusicScore($context, $args);
        }
    
        return [];
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    public function addCommonViewFilters(string $objectType, QueryBuilder $qb): QueryBuilder
    {
        if ('gameScore' === $objectType) {
            return $this->addCommonViewFiltersForGameScore($qb);
        }
        if ('score' === $objectType) {
            return $this->addCommonViewFiltersForScore($qb);
        }
        if ('level' === $objectType) {
            return $this->addCommonViewFiltersForLevel($qb);
        }
        if ('graphicsAndSound' === $objectType) {
            return $this->addCommonViewFiltersForGraphicsAndSound($qb);
        }
        if ('musicScore' === $objectType) {
            return $this->addCommonViewFiltersForMusicScore($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    public function applyDefaultFilters(string $objectType, QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        if ('gameScore' === $objectType) {
            return $this->applyDefaultFiltersForGameScore($qb, $parameters);
        }
        if ('score' === $objectType) {
            return $this->applyDefaultFiltersForScore($qb, $parameters);
        }
        if ('level' === $objectType) {
            return $this->applyDefaultFiltersForLevel($qb, $parameters);
        }
        if ('graphicsAndSound' === $objectType) {
            return $this->applyDefaultFiltersForGraphicsAndSound($qb, $parameters);
        }
        if ('musicScore' === $objectType) {
            return $this->applyDefaultFiltersForMusicScore($qb, $parameters);
        }
    
        return $qb;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForGameScore(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $parameters['scores'] = $request->query->get('scores', 0);
        if (is_object($parameters['scores'])) {
            $parameters['scores'] = $parameters['scores']->getId();
        }
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['playerUid'] = $request->query->getInt('playerUid', 0);
        $parameters['q'] = $request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForScore(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['q'] = $request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForLevel(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $parameters['graphicsAndSound'] = $request->query->get('graphicsAndSound', 0);
        if (is_object($parameters['graphicsAndSound'])) {
            $parameters['graphicsAndSound'] = $parameters['graphicsAndSound']->getId();
        }
        $parameters['musicScores'] = $request->query->get('musicScores', 0);
        if (is_object($parameters['musicScores'])) {
            $parameters['musicScores'] = $parameters['musicScores']->getId();
        }
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['q'] = $request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForGraphicsAndSound(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['q'] = $request->query->get('q', '');
        $parameters['graphicAtBottom'] = $request->query->get('graphicAtBottom', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForMusicScore(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['q'] = $request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForGameScore(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForGameScore();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('gameScore', $qb, $v);
                }
                continue;
            }
            if (in_array($k, ['scores']) && !empty($v)) {
                // multi-valued target of outgoing relation (one2many or many2many)
                $qb->andWhere(
                    $qb->expr()->isMemberOf(':' . $k, 'tbl.' . $k)
                )
                    ->setParameter($k, $v)
                ;
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                $v = (string)$v;
                if ('workflowState' === $k && 0 === strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, substr($v, 1));
                } elseif (0 === strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . substr($v, 1) . '%');
                } else {
                    if (in_array($k, ['playerUid'], true)) {
                        $qb->leftJoin('tbl.' . $k, 'tbl' . ucfirst($k))
                           ->andWhere('tbl' . ucfirst($k) . '.uid = :' . $k)
                           ->setParameter($k, $v);
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                    }
                }
            }
        }
    
        return $this->applyDefaultFiltersForGameScore($qb, $parameters);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForScore(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForScore();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('score', $qb, $v);
                }
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                $v = (string)$v;
                if ('workflowState' === $k && 0 === strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, substr($v, 1));
                } elseif (0 === strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . substr($v, 1) . '%');
                } else {
                    $qb->andWhere('tbl.' . $k . ' = :' . $k)
                       ->setParameter($k, $v);
                }
            }
        }
    
        return $this->applyDefaultFiltersForScore($qb, $parameters);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForLevel(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForLevel();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('level', $qb, $v);
                }
                continue;
            }
            if (in_array($k, ['graphicsAndSound', 'musicScores']) && !empty($v)) {
                // multi-valued target of outgoing relation (one2many or many2many)
                $qb->andWhere(
                    $qb->expr()->isMemberOf(':' . $k, 'tbl.' . $k)
                )
                    ->setParameter($k, $v)
                ;
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                $v = (string)$v;
                if ('workflowState' === $k && 0 === strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, substr($v, 1));
                } elseif (0 === strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . substr($v, 1) . '%');
                } else {
                    $qb->andWhere('tbl.' . $k . ' = :' . $k)
                       ->setParameter($k, $v);
                }
            }
        }
    
        return $this->applyDefaultFiltersForLevel($qb, $parameters);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForGraphicsAndSound(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForGraphicsAndSound();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('graphicsAndSound', $qb, $v);
                }
                continue;
            }
            if (in_array($k, ['graphicAtBottom'], true)) {
                // boolean filter
                if ('no' === $v) {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ('yes' === $v || '1' === $v) {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                $v = (string)$v;
                if ('workflowState' === $k && 0 === strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, substr($v, 1));
                } elseif (0 === strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . substr($v, 1) . '%');
                } else {
                    $qb->andWhere('tbl.' . $k . ' = :' . $k)
                       ->setParameter($k, $v);
                }
            }
        }
    
        return $this->applyDefaultFiltersForGraphicsAndSound($qb, $parameters);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForMusicScore(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForMusicScore();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('musicScore', $qb, $v);
                }
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                $v = (string)$v;
                if ('workflowState' === $k && 0 === strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, substr($v, 1));
                } elseif (0 === strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . substr($v, 1) . '%');
                } else {
                    $qb->andWhere('tbl.' . $k . ' = :' . $k)
                       ->setParameter($k, $v);
                }
            }
        }
    
        return $this->applyDefaultFiltersForMusicScore($qb, $parameters);
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForGameScore(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== strpos($routeName, 'paustianmelodymixermodule_gamescore_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved game scores only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForScore(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== strpos($routeName, 'paustianmelodymixermodule_score_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved scores only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForLevel(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== strpos($routeName, 'paustianmelodymixermodule_level_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved levels only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForGraphicsAndSound(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== strpos($routeName, 'paustianmelodymixermodule_graphicsandsound_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved graphics and sound only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForMusicScore(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== strpos($routeName, 'paustianmelodymixermodule_musicscore_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved music scores only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds a where clause for search query.
     */
    public function addSearchFilter(string $objectType, QueryBuilder $qb, string $fragment = ''): QueryBuilder
    {
        if ('' === $fragment) {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
        if ('gameScore' === $objectType) {
            $filters[] = 'tbl.playerEmail = :searchPlayerEmail';
            $parameters['searchPlayerEmail'] = $fragment;
            $filters[] = 'tbl.firstName LIKE :searchFirstName';
            $parameters['searchFirstName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.lastName LIKE :searchLastName';
            $parameters['searchLastName'] = '%' . $fragment . '%';
        }
        if ('score' === $objectType) {
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.levelId = :searchLevelId';
                $parameters['searchLevelId'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.playerUid = :searchPlayerUid';
                $parameters['searchPlayerUid'] = $fragment;
            }
            $filters[] = 'tbl.levelName LIKE :searchLevelName';
            $parameters['searchLevelName'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreOne = :searchScoreOne';
                $parameters['searchScoreOne'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreTwo = :searchScoreTwo';
                $parameters['searchScoreTwo'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreThree = :searchScoreThree';
                $parameters['searchScoreThree'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreFour = :searchScoreFour';
                $parameters['searchScoreFour'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreFive = :searchScoreFive';
                $parameters['searchScoreFive'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreSix = :searchScoreSix';
                $parameters['searchScoreSix'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreSeven = :searchScoreSeven';
                $parameters['searchScoreSeven'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreEight = :searchScoreEight';
                $parameters['searchScoreEight'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreNine = :searchScoreNine';
                $parameters['searchScoreNine'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreTen = :searchScoreTen';
                $parameters['searchScoreTen'] = $fragment;
            }
        }
        if ('level' === $objectType) {
            $filters[] = 'tbl.levelName LIKE :searchLevelName';
            $parameters['searchLevelName'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.displayGraphicLevelId = :searchDisplayGraphicLevelId';
                $parameters['searchDisplayGraphicLevelId'] = $fragment;
            }
        }
        if ('graphicsAndSound' === $objectType) {
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.levelid = :searchLevelid';
                $parameters['searchLevelid'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.exNum = :searchExNum';
                $parameters['searchExNum'] = $fragment;
            }
            $filters[] = 'tbl.gsName LIKE :searchGsName';
            $parameters['searchGsName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.gsPath LIKE :searchGsPath';
            $parameters['searchGsPath'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.xPos = :searchXPos';
                $parameters['searchXPos'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.yPos = :searchYPos';
                $parameters['searchYPos'] = $fragment;
            }
            $filters[] = 'tbl.gsLabel LIKE :searchGsLabel';
            $parameters['searchGsLabel'] = '%' . $fragment . '%';
            $filters[] = 'tbl.descText LIKE :searchDescText';
            $parameters['searchDescText'] = '%' . $fragment . '%';
            $filters[] = 'tbl.gsUrl LIKE :searchGsUrl';
            $parameters['searchGsUrl'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.xDes = :searchXDes';
                $parameters['searchXDes'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.yDes = :searchYDes';
                $parameters['searchYDes'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.boxWidth = :searchBoxWidth';
                $parameters['searchBoxWidth'] = $fragment;
            }
        }
        if ('musicScore' === $objectType) {
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.levelId = :searchLevelId';
                $parameters['searchLevelId'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.exNum = :searchExNum';
                $parameters['searchExNum'] = $fragment;
            }
            $filters[] = 'tbl.gsGraphic LIKE :searchGsGraphic';
            $parameters['searchGsGraphic'] = '%' . $fragment . '%';
            $filters[] = 'tbl.gsMidi LIKE :searchGsMidi';
            $parameters['searchGsMidi'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.scoreIt = :searchScoreIt';
                $parameters['searchScoreIt'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.musicOrder = :searchMusicOrder';
                $parameters['searchMusicOrder'] = $fragment;
            }
        }
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')');
    
        foreach ($parameters as $parameterName => $parameterValue) {
            $qb->setParameter($parameterName, $parameterValue);
        }
    
        return $qb;
    }
    
    /**
     * Adds a filter for the createdBy field.
     */
    public function addCreatorFilter(QueryBuilder $qb, int $userId = null): QueryBuilder
    {
        if (null === $userId) {
            $userId = $this->currentUserApi->isLoggedIn()
                ? (int)$this->currentUserApi->get('uid')
                : UsersConstant::USER_ID_ANONYMOUS
            ;
        }
    
        $qb->andWhere('tbl.createdBy = :userId')
           ->setParameter('userId', $userId);
    
        return $qb;
    }
}
