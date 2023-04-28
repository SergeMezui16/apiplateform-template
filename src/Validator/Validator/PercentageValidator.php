<?php

namespace App\Validator\Validator;

use App\Validator\Attribute\Percentage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * PercentageValidator
 * 
 * This Validator check if the value is a number 
 * and if it beatween 0 and 100.
 */
class PercentageValidator extends ConstraintValidator
{
    public function __construct(private TranslatorInterface $translator)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) return;
        if (!$constraint instanceof Percentage) throw new UnexpectedTypeException($constraint, Percentage::class);

        if ($value <= 100 && $value >= 0) return;

        $this->context->buildViolation($this->translator->trans($constraint->message))
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
