<?php
/**
 * Created by PhpStorm.
 * User: Shaheer
 * Date: 3/1/2017
 * Time: 2:29 PM
 */

namespace OC\PlatformBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class AntiFlood
 * @package OC\PlatformBundle\Validator
 * @Annotation
 */
class AntiFlood extends Constraint
{
    public $message ="Attendez 15 secondes";

}