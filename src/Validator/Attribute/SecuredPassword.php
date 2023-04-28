<?php

namespace App\Validator\Attribute;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SecuredPassword extends Constraint
{

    public $message = 'secured_password_error_message';

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return SecuredPasswordValidator::class;
    }
}
