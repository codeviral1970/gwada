<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstName', TextType::class, [
        'label' => 'Votre prénom',
        'constraints' => [
          new Assert\Length([
            'min' => 2,
            'minMessage' => "Minimum 2 caractères",
            'max' => 50,
            'maxMessage' => "Maximum 50 caractères",
          ]),
          new Assert\NotBlank([
            'message' => 'Merci de renseigner votre prénom'
          ])
        ]
      ])
      ->add('lastName', TextType::class, [
        'label' => 'Votre nom',
        'constraints' => [
          new Assert\Length([
            'min' => 2,
            'minMessage' => "Minimum 2 caractères",
            'max' => 50,
            'maxMessage' => "Maximum 50 caractères",
          ]),
          new Assert\NotBlank([
            'message' => 'Merci de renseigner votre nom'
          ])
        ]
      ])
      ->add('email', EmailType::class, [
        'label' => 'Votre email',
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Merci de renseigner votre email'
          ]),
          new Assert\Email([
            'message' => 'Email invalid'
          ])
        ]
      ])
      ->add('subject', TextType::class, [
        'label' => 'Sujet',
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Merci de renseigner le sujet'
          ])
        ]
      ])
      ->add('message', TextareaType::class, [
        'label' => 'Message',
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'Merci de faire une description'
          ])
        ]
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Envoyer'
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Contact::class,
      'attr' => [
        'novalidate' => true
      ]
    ]);
  }
}
