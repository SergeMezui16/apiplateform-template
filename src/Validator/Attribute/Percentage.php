<?php

namespace App\Validator\Attribute;

use App\Validator\Validator\PercentageValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Percentage extends Constraint
{

    public $message = 'percentage_error_message';

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return PercentageValidator::class;
    }
}
