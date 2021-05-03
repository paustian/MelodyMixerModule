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

namespace Paustian\MelodyMixerModule\Form\Type\QuickNavigation\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Translation\Extractor\Annotation\Ignore;
use Paustian\MelodyMixerModule\Helper\ListEntriesHelper;

/**
 * Graphics and sound quick navigation form type base class.
 */
abstract class AbstractGraphicsAndSoundQuickNavType extends AbstractType
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
        $builder
            ->setMethod('GET')
            ->add('all', HiddenType::class)
            ->add('own', HiddenType::class)
            ->add('tpl', HiddenType::class)
        ;

        $this->addListFields($builder, $options);
        $this->addSearchField($builder, $options);
        $this->addSortingFields($builder, $options);
        $this->addAmountField($builder, $options);
        $this->addBooleanFields($builder, $options);
        $builder->add('updateview', SubmitType::class, [
            'label' => 'OK',
            'attr' => [
                'class' => 'btn-secondary btn-sm'
            ]
        ]);
    }

    /**
     * Adds list fields.
     */
    public function addListFields(FormBuilderInterface $builder, array $options = []): void
    {
        $listEntries = $this->listHelper->getEntries('graphicsAndSound', 'workflowState');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('workflowState', ChoiceType::class, [
            'label' => 'State',
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'required' => false,
            'placeholder' => 'All',
            'choices' => $choices,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds a search field.
     */
    public function addSearchField(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('q', SearchType::class, [
            'label' => 'Search',
            'attr' => [
                'maxlength' => 255,
                'class' => 'form-control-sm'
            ],
            'required' => false
        ]);
    }


    /**
     * Adds sorting fields.
     */
    public function addSortingFields(FormBuilderInterface $builder, array $options = []): void
    {
        $builder
            ->add('sort', ChoiceType::class, [
                'label' => 'Sort by',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'choices' => [
                    'Levelid' => 'levelid',
                    'Ex num' => 'exNum',
                    'Gs name' => 'gsName',
                    'Gs path' => 'gsPath',
                    'X pos' => 'xPos',
                    'Y pos' => 'yPos',
                    'Gs label' => 'gsLabel',
                    'Desc text' => 'descText',
                    'Gs url' => 'gsUrl',
                    'X des' => 'xDes',
                    'Y des' => 'yDes',
                    'Box width' => 'boxWidth',
                    'Graphic at bottom' => 'graphicAtBottom',
                    'Creation date' => 'createdDate',
                    'Creator' => 'createdBy',
                    'Update date' => 'updatedDate',
                    'Updater' => 'updatedBy'
                ],
                'required' => true,
                'expanded' => false
            ])
            ->add('sortdir', ChoiceType::class, [
                'label' => 'Sort direction',
                'empty_data' => 'asc',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'choices' => [
                    'Ascending' => 'asc',
                    'Descending' => 'desc'
                ],
                'required' => true,
                'expanded' => false
            ])
        ;
    }

    /**
     * Adds a page size field.
     */
    public function addAmountField(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('num', ChoiceType::class, [
            'label' => 'Page size',
            'empty_data' => 20,
            'attr' => [
                'class' => 'form-control-sm text-right'
            ],
            /** @Ignore */
            'choices' => [
                5 => 5,
                10 => 10,
                15 => 15,
                20 => 20,
                30 => 30,
                50 => 50,
                100 => 100
            ],
            'required' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds boolean fields.
     */
    public function addBooleanFields(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('graphicAtBottom', ChoiceType::class, [
            'label' => 'Graphic at bottom',
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'required' => false,
            'placeholder' => 'All',
            'choices' => [
                'No' => 'no',
                'Yes' => 'yes'
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paustianmelodymixermodule_graphicsandsoundquicknav';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'translation_domain' => 'graphicsAndSound'
        ]);
    }
}
