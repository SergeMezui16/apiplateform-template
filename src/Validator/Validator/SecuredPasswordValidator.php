<?php

namespace App\Validator\Validator;

use App\Validator\Attribute\SecuredPassword;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * SecuredPasswordValidator
 * 
 * This Validator check if the value is a secured password
 */
class SecuredPasswordValidator extends ConstraintValidator
{
    public function __construct(private TranslatorInterface $translator)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) return;
        if (!$constraint instanceof SecuredPassword) throw new UnexpectedTypeException($constraint, SecuredPassword::class);

        if (preg_match('/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/', $value)) return;

        $this->context->buildViolation($this->translator->trans($constraint->message))
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
