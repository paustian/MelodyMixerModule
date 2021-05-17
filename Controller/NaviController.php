<?php

declare(strict_types=1);

namespace Paustian\MelodyMixerModule\Controller;

use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Entity\GraphicsAndSoundEntity;
use Paustian\MelodyMixerModule\Entity\MusicScoreEntity;
use Paustian\MelodyMixerModule\Entity\Repository\ScoreRepository;
use Paustian\MelodyMixerModule\Form\Type\ImportType;
use Paustian\MelodyMixerModule\Helper\WorkflowHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
     * @Route("/main", options={"expose"=true, "i18n"=false}, methods = {"GET", "POST"})
     *
     */
    public function mainAction(
        Request $request,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        AssetFilter $assetFilter
    ): Response {
        if(!$permissionHelper->hasPermission( ACCESS_COMMENT)){
            return $this->render('@PaustianMelodyMixerModule/Navi/registerfirst.html.twig');
        }
        $route = $this->get('router')->generate('paustianmelodymixermodule_navi_level', ['name' => 'basics']);
        $output = $this->renderView("@PaustianMelodyMixerModule/Navi/mainLevel.html.twig");
        $output = $assetFilter->filter($output);
        return new PlainResponse($output);
    }

    /**
     * @Route("/level/{name}", options={"expose"=true, "i18n"=false}, methods = {"GET", "POST"})
     */
    public function levelAction(
        Request $request,
        string $name,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        EntityFactory $entityFactory,
        AssetFilter $assetFilter
    ): Response {
        if(!$permissionHelper->hasPermission( ACCESS_COMMENT)){
            return $this->render('@PaustianMelodyMixerModule/Navi/registerfirst.html.twig');
        }
        $levelList = [];
        $repo = $entityFactory->getRepository('Score');
        $uid = $currentUserApi->get('uid');
        $levelId = 0;
        //Note that that the integers are the ids of the levels stored in LevelEntity NOT the levelId or the exNum
        $pgTitle = ucwords($name);
        switch ($name){
            case 'training':
                //This needs to be a unique since there is only 1 level.
                $levelList = [1];
                $levelId = 1;
                break;
            case 'basics':
                $levelList = [2,3,4,5,6,7,8,9,10,11];
                $levelId = 2;
                break;
            case 'rhythm':
                $levelList = [12,13,14,15,16,17,18,19,20,21];
                $levelId = 3;
                break;
            case 'kss':
                $levelList = [22,23,24,25,26,27,28,29,30,31];
                $levelId = 4;
                $pgTitle = 'Key Signature and Scales';
                break;
            case 'intervals':
                $levelList = [32,33,34,35,36,37,38,39,40,41];
                $levelId = 5;
                break;
            case 'constructs':
                $levelList = [42,43,44,45,46,47,48,49,50,51];
                $levelId = 6;
                break;
            case 'triads':
                $levelList = [52,53,54,55,56,57,58,59,60,61];
                $levelId = 7;
                break;
            case '7thchords':
                $levelList = [62,63,64,65,66,67,68,69,70,71];
                $levelId = 8;
                $pgTitle = 'Seventh Chords';
                break;
            case 'punctuation':
                $levelList = [72,73,74,75,76,77,78,79,80,81];
                $levelId = 9;
                break;
            case 'commonchord':
                $levelList = [82,83,84,85,86,87,88,89,90,91];
                $levelId = 10;
                $pgTitle = 'Common Chords';
                break;
            case 'experiment':
                $levelList = [1,2,3,4,5,6,7,8,9,10];
                $pgTitle = 'Experiment Zone';
                break;
        }
        if($levelId == 0){
            $scores = [0,0,0,0,0,0,0,0,0,0];
        } else {
            $scores = $this->getScores($levelId, (int)$uid, $repo);
        }

        $output = $this->renderView("@PaustianMelodyMixerModule/Navi/level.html.twig",
            ['name' => $name, 'levelList' => $levelList, 'scores' => $scores, 'pgTitle' => $pgTitle]);
        $output = $assetFilter->filter($output);
        return new PlainResponse($output);
    }
    /**
     *
     * @Route("/import", methods = {"GET","POST"})
     * @Theme("admin")
     * import the data for creating the navigation pages. This is an admin page.
     */
    public function importAction(
        Request $request,
        PermissionHelper $permissionHelper,
        WorkflowHelper $workflowHelper) : Response
    {
        if(!$permissionHelper->hasPermission(ACCESS_ADMIN)){
            throw new AccessDeniedException();
        }
        $form = $this->createForm(ImportType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileToParse = $form['import']->getData();
            if($fileToParse != null) {
                $csvData = file($fileToParse->getPathname());
                $em = $this->getDoctrine()->getManager();
                foreach($csvData as $graphicData){
                    $gAndSArray = explode(',', $graphicData);
                    $gAndSEntity = new GraphicsAndSoundEntity();
                    $gAndSEntity->setLevelid((int)$gAndSArray[0]);
                    $gAndSEntity->setExNum((int)$gAndSArray[1]);
                    $gAndSEntity->setGsName($gAndSArray[2]);
                    $gAndSEntity->setGsPath($gAndSArray[3]);
                    $gAndSEntity->setXPos((int)$gAndSArray[4]);
                    $gAndSEntity->setYPos((int)$gAndSArray[5]);
                    $gAndSEntity->setGsLabel($gAndSArray[6]);
                    $gAndSEntity->setDescText($gAndSArray[7]);
                    $gAndSEntity->setGsUrl($gAndSArray[8]);
                    $gAndSEntity->setXDes((int)$gAndSArray[9]);
                    $gAndSEntity->setYDes((int)$gAndSArray[10]);
                    $gAndSEntity->setBoxWidth(0);
                    $gAndSEntity->setGraphicAtBottom(false);
                    $workflowHelper->executeAction($gAndSEntity, 'submit');
                    $itemName = $gAndSArray[2];
                    $itemExtension =pathinfo($gAndSArray[3], PATHINFO_EXTENSION);
                    if( (str_contains($itemName, 'bar')) && $itemExtension == 'mid'){
                        //strip the last character, which will be a s
                        $itemName =  substr($itemName, 0, -1);
                        //this is a string that we want to create a music score matrix for
                        $musicScore = new MusicScoreEntity();
                        $musicScore->setLevelId((int)$gAndSArray[0]);
                        $musicScore->setExNum((int)$gAndSArray[1]);
                        $musicScore->setGsGraphic($itemName . "g");
                        $musicScore->setGsMidi($itemName . "s");
                        if(str_contains($itemName, 'bad')){
                            $musicScore->setScoreIt(0);
                            $musicScore->setMusicOrder(0);
                        } else {
                            $musicScore->setScoreIt(1);
                            //The last charter of the string is the order that we want
                            $musicScore->setMusicOrder((int)$itemName[-1]);
                        }
                        $workflowHelper->executeAction($musicScore, 'submit');
                    }
                }
                $this->addFlash('status', $this->trans('Items Imported'));
            } else {
                $this->addFlash('error', $this->trans('The file that was picked was invalid or you forgot to pick a file.'));
            }
        }
        return $this->render('@PaustianMelodyMixerModule/Navi/import.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/viewscores", options={"expose"=true, "i18n"=false}, methods = {"GET", "POST"})
     *
     */
    public function viewscoresAction(
        Request $request,
        PermissionHelper $permissionHelper,
        EntityFactory $entityFactory
    ): Response {
        if(!$permissionHelper->hasPermission( ACCESS_EDIT)){
            return $this->render('@PaustianMelodyMixerModule/Navi/registerfirst.html.twig');
        }
        $rows = [];
        $exerciseArray = ['Basics' => "0 - 0",
            'Rhythm' => "0 - 0",
            'kss' => "0 - 0",
            'intervals' => "0 - 0",
            'constructs'  => "0 - 0",
            'triads'  => "0 - 0",
            '7thchords'  => "0 - 0",
            'punctuation'  => "0 - 0",
            'Common Chords'  => "0 - 0"];
        $repo = $entityFactory->getRepository('GameScore');
        $scoreRepo = $entityFactory->getRepository('Score');
        $users = $repo->findAll();
        foreach($users as $user){
            $item = [];
            $item['name'] = $user->getLastName() . ", " . $user->getFirstName();
            $item['email'] = $user->getPlayerEmail();
            $item['date'] = $user->getCreatedDate();
            $playerId = $user->getPlayerUid();
            $scores =  $scoreRepo->findBy(['playerUid' => $playerId]);
            $exercisesTried = 0;
            $scoreTotal = 0;
            $exResults = [];
            foreach($scores as $score){
                if($score->getLevelName() == "Training"){
                    continue;
                }
                if(($theScore = $score->getScoreOne()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreTwo()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreThree()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreFour() )!== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreFive() )!== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreSix()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreSeven()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }if(($theScore = $score->getScoreEight()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreNine())!== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                if(($theScore = $score->getScoreTen()) !== 0){
                    $exercisesTried++;
                    $scoreTotal += $theScore;
                }
                $exResults[$score->getLevelName()] = $exercisesTried . "/10 - " . round($scoreTotal/$exercisesTried, 0);
                $exercisesTried = 0;
                $scoreTotal = 0;
            }
            $item['scores'] = array_merge($exerciseArray, $exResults);
            $rows[] = $item;
        }
        return $this->render('@PaustianMelodyMixerModule/Navi/viewscores.html.twig', ['rows' => $rows]);
    }
}