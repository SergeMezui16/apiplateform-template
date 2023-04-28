<?php

namespace App\Validator\Validator;

use App\Validator\Attribute\RoleCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * RoleCodeValidator
 * 
 * This Validator check if the value start with ROLE_
 */
class RoleCodeValidator extends ConstraintValidator
{
    public function __construct(private TranslatorInterface $translator)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) return;
        if (!$constraint instanceof RoleCode) throw new UnexpectedTypeException($constraint, RoleCode::class);
        if (\str_starts_with($value, 'ROLE_')) return;

        $this->context->buildViolation($this->translator->trans($constraint->message))
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
