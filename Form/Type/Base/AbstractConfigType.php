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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Annotation\Translate;
use Paustian\MelodyMixerModule\Form\Type\Field\MultiListType;
use Paustian\MelodyMixerModule\AppSettings;
use Paustian\MelodyMixerModule\Helper\ListEntriesHelper;

/**
 * Configuration form type base class.
 */
abstract class AbstractConfigType extends AbstractType
{

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    public function __construct(
        ListEntriesHelper $listHelper
    ) {
        $this->listHelper = $listHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addListViewsFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addIntegrationFields($builder, $options);

        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds fields for list views fields.
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('gameScoreEntriesPerPage', IntegerType::class, [
            'label' => 'Game score entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of game scores shown per page'
            ],
            'help' => 'The amount of game scores shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the game score entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('scoreEntriesPerPage', IntegerType::class, [
            'label' => 'Score entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of scores shown per page'
            ],
            'help' => 'The amount of scores shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the score entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('levelEntriesPerPage', IntegerType::class, [
            'label' => 'Level entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of levels shown per page'
            ],
            'help' => 'The amount of levels shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the level entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('graphicsAndSoundEntriesPerPage', IntegerType::class, [
            'label' => 'Graphics and sound entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of graphics and sound shown per page'
            ],
            'help' => 'The amount of graphics and sound shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the graphics and sound entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('musicScoreEntriesPerPage', IntegerType::class, [
            'label' => 'Music score entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of music scores shown per page'
            ],
            'help' => 'The amount of music scores shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the music score entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('showOnlyOwnEntries', CheckboxType::class, [
            'label' => 'Show only own entries:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether only own entries should be shown on view pages by default or not'
            ],
            'help' => 'Whether only own entries should be shown on view pages by default or not',
            'attr' => [
                'class' => '',
                'title' => 'The show only own entries option'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for moderation fields.
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('allowModerationSpecificCreatorForGameScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for game score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for game score option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForGameScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for game score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for game score option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreatorForScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for score option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for score option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreatorForLevel', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for level:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for level option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForLevel', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for level:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for level option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreatorForGraphicsAndSound', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for graphics and sound:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for graphics and sound option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForGraphicsAndSound', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for graphics and sound:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for graphics and sound option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreatorForMusicScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for music score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for music score option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForMusicScore', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for music score:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for music score option'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for integration fields.
     */
    public function addIntegrationFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $listEntries = $this->listHelper->getEntries('appSettings', 'enabledFinderTypes');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('enabledFinderTypes', MultiListType::class, [
            'label' => 'Enabled finder types:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'Which sections are supported in the Finder component (used by Scribite plug-ins).'
            ],
            'help' => 'Which sections are supported in the Finder component (used by Scribite plug-ins).',
            'empty_data' => [],
            'attr' => [
                'class' => '',
                'title' => 'Choose the enabled finder types.'
            ],
            'required' => false,
            'placeholder' => 'Choose an option',
            'choices' => /** @Ignore */$choices,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
    }

    /**
     * Adds submit buttons.
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('save', SubmitType::class, [
            'label' => 'Update configuration',
            'icon' => 'fa-check',
            'attr' => [
                'class' => 'btn-success'
            ]
        ]);
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
        return 'paustianmelodymixermodule_config';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // define class for underlying data
            'data_class' => AppSettings::class,
            'translation_domain' => 'config'
        ]);
    }
}
