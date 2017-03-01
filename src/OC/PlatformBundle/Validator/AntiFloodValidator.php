<?php
/**
 * Created by PhpStorm.
 * User: Shaheer
 * Date: 3/1/2017
 * Time: 2:33 PM
 */

namespace OC\PlatformBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class AntiFloodValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        if (strlen($value) < 3){
            $this->context->addViolation($constraint->message);
        }
    }

}