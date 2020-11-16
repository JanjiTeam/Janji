<?php

namespace App\DataTransformer;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EventTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Event $event
     *
     * @return string
     */
    public function transform($event)
    {
        if (null === $event) {
            return '';
        }

        return $event->getId();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        $event = $this->entityManager
            ->getRepository(Event::class)
            ->find($value)
        ;

        if (null === $event) {
            throw new TransformationFailedException(sprintf('An event with number "%s" does not exist!', $value));
        }

        return $event;
    }
}
