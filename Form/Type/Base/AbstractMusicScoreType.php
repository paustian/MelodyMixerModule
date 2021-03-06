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

namespace Paustian\MelodyMixerModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Annotation\Translate;
use Paustian\MelodyMixerModule\Entity\Factory\EntityFactory;
use Paustian\MelodyMixerModule\Entity\MusicScoreEntity;
use Paustian\MelodyMixerModule\Helper\CollectionFilterHelper;
use Paustian\MelodyMixerModule\Helper\EntityDisplayHelper;
use Paustian\MelodyMixerModule\Helper\ListEntriesHelper;
use Paustian\MelodyMixerModule\Traits\ModerationFormFieldsTrait;

/**
 * Music score editing form type base class.
 */
abstract class AbstractMusicScoreType extends AbstractType
{
    use ModerationFormFieldsTrait;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;

    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    public function __construct(
        EntityFactory $entityFactory,
        CollectionFilterHelper $collectionFilterHelper,
        EntityDisplayHelper $entityDisplayHelper,
        ListEntriesHelper $listHelper
    ) {
        $this->entityFactory = $entityFactory;
        $this->collectionFilterHelper = $collectionFilterHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->listHelper = $listHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEntityFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds basic entity fields.
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('levelId', IntegerType::class, [
            'label' => 'Level id:',
            'empty_data' => 0,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the level id of the music score. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('exNum', IntegerType::class, [
            'label' => 'Ex num:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The example number for this level'
            ],
            'help' => 'The example number for this level',
            'empty_data' => 0,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the ex num of the music score. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('gsGraphic', TextType::class, [
            'label' => 'Gs graphic:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The name of the graphic to load and score'
            ],
            'help' => 'The name of the graphic to load and score',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the gs graphic of the music score.'
            ],
            'required' => true,
        ]);
        
        $builder->add('gsMidi', TextType::class, [
            'label' => 'Gs midi:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The name of the midi file to play'
            ],
            'help' => 'The name of the midi file to play',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the gs midi of the music score.'
            ],
            'required' => true,
        ]);
        
        $builder->add('scoreIt', IntegerType::class, [
            'label' => 'Score it:',
            'empty_data' => 0,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the score it of the music score. Only digits are allowed.'
            ],
            'required' => false,
        ]);
        
        $builder->add('musicOrder', IntegerType::class, [
            'label' => 'Music order:',
            'help' => 'Note: this value must not be greater than %maxValue%.',
            'help_translation_parameters' => ['%maxValue%' => 4],
            'empty_data' => 0,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'max' => 4,
                'title' => 'Enter the music order of the music score. Only digits are allowed.'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds submit buttons.
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = []): void
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], SubmitType::class, [
                /** @Ignore */
                'label' => $action['title'],
                'icon' => 'delete' === $action['id'] ? 'fa-trash-alt' : '',
                'attr' => [
                    'class' => $action['buttonClass']
                ]
            ]);
            if ('create' === $options['mode'] && 'submit' === $action['id'] && !$options['inline_usage']) {
                // add additional button to submit item and return to create form
                $builder->add('submitrepeat', SubmitType::class, [
                    'label' => 'Submit and repeat',
                    'icon' => 'fa-repeat',
                    'attr' => [
                        'class' => $action['buttonClass']
                    ]
                ]);
            }
        }
        $builder->add('reset', ResetType::class, [
            'label' => 'Reset',
            'icon' => 'fa-sync',
            'attr' => [
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => 'Cancel',
            'validate' => false,
            'icon' => 'fa-times'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paustianmelodymixermodule_musicscore';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => MusicScoreEntity::class,
                'translation_domain' => 'musicScore',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createMusicScore();
                },
                'error_mapping' => [
                ],
                'mode' => 'create',
                'actions' => [],
                'has_moderate_permission' => false,
                'allow_moderation_specific_creator' => false,
                'allow_moderation_specific_creation_date' => false,
                'filter_by_ownership' => true,
                'inline_usage' => false
            ])
            ->setRequired(['mode', 'actions'])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedTypes('actions', 'array')
            ->setAllowedTypes('has_moderate_permission', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creator', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creation_date', 'bool')
            ->setAllowedTypes('filter_by_ownership', 'bool')
            ->setAllowedTypes('inline_usage', 'bool')
            ->setAllowedValues('mode', ['create', 'edit'])
        ;
    }
}
