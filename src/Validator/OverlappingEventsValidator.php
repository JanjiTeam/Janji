<?php

namespace App\Validator;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OverlappingEventsValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $value Event */
        /* @var $constraint \App\Validator\OverlappingEvents */

        if (null === $value || '' === $value) {
            return;
        }

        $foundEvents = $this->entityManager->getRepository(Event::class)->findOverlappingEvents($value->getCalendar()->getId(), $value->getStart(), $value->getEnd());

        if (!empty($foundEvents)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('id')
                ->addViolation();
        }
    }
}
