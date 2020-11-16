<?php

namespace App\Validator;

use App\Entity\Event;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UnassignedEventValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $value Event */
        /* @var $constraint \App\Validator\UnassignedEvent */

        if (null === $value || '' === $value) {
            return;
        }

        if (null !== $value->getUser()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getId())
                ->addViolation();
        }
    }
}
