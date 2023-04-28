<?php

namespace App\Validator\Validator;

use App\Validator\Attribute\PhoneNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * PhoneNumberValidator
 * 
 * This Validator check if the value is a valid phone number
 */
class PhoneNumberValidator extends ConstraintValidator
{
    public function __construct(private TranslatorInterface $translator)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) return;
        if (!$constraint instanceof PhoneNumber) throw new UnexpectedTypeException($constraint, PhoneNumber::class);

        if (preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/', $value)) return;

        $this->context->buildViolation($this->translator->trans($constraint->message))
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
