<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\EventType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class EventTypeType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('duration', IntegerType::class, [
                'help' => 'duration_in_minutes',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.owner', 'u')
                        ->andWhere('u.id = :uid')
                        ->setParameter('uid', $this->security->getUser());
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventType::class,
        ]);
    }
}
