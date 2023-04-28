<?php

namespace App\Validator\Attribute;

use App\Validator\Validator\PhoneNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PhoneNumber extends Constraint
{
    public $message = 'phone_number_error_message';

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return PhoneNumberValidator::class;
    }
}
