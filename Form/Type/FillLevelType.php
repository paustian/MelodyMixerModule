<?php
declare(strict_types=1);

namespace Paustian\MelodyMixerModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FillLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('levelname', TextType::class, [
                'label' => 'Name of Level: ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
            ])
            ->add('levelnumber', NumberType::class, [
                'label' => 'Number of Level',
                'mapped' => false,
                'required' => true
            ])
            ->add('numexamples', NumberType::class, [
                'label' => 'The number of Examples',
                'mapped' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, ['label' => 'Create Levels']);
    }
}