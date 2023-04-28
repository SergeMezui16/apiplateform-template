<?php

namespace App\Validator\Attribute;

use App\Validator\Validator\RoleCodeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class RoleCode extends Constraint
{
    public $message = 'role_code_error_message';

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return RoleCodeValidator::class;
    }
}
