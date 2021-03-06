<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SlugType extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The slug is already using.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
