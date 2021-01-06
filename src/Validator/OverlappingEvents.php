<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OverlappingEvents extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The event is overlapping with another on this calendar';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
