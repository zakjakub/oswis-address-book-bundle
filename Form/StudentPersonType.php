<?php

namespace Zakjakub\OswisAddressBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zakjakub\OswisAddressBookBundle\Entity\Person;

class StudentPersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'fullName',
                TextType::class,
                array(
                    'label' => 'Celé jméno',
                    'attr'  => [
                        'autocomplete' => 'section-student name',
                    ],
                )
            )
            ->add(
                'contactDetails',
                CollectionType::class,
                array(
                    'label'         => false,
                    'entry_type'    => ContactDetailType::class,
                    'entry_options' => array('label' => false),
                )
            )
            ->add(
                'studies',
                CollectionType::class,
                array(
                    'label'         => 'Fakulta',
                    'help'          => 'Vyberte příslušnou fakultu.',
                    'entry_type'    => SchoolPositionType::class,
                    'entry_options' => array('label' => false),
                )
            );
    }

    final public function configureOptions(OptionsResolver $resolver): void
    {
        try {
            $resolver->setDefaults(
                array(
                    'data_class' => Person::class,
                )
            );
        } catch (AccessException $e) {
        }
    }

    final public function getName(): string
    {
        return 'address_book_student_person';
    }
}