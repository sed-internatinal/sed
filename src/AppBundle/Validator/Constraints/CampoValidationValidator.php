<?php
/**
 * Created by PhpStorm.
 * User: FERNEY
 * Date: 06/06/2017
 * Time: 13:46
 */
namespace AppBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CampoValidationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}