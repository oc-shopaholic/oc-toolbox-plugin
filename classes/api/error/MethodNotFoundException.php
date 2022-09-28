<?php namespace Lovata\Toolbox\Classes\Api\Error;

use GraphQL\Error\ClientAware;
use GraphQL\Error\Error;

/**
 * Class MethodNotFoundException
 * @package Lovata\Toolbox\Classes\Api\Error
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class MethodNotFoundException extends Error implements ClientAware
{
    const CATEGORY_BUSINESS_LOGIC = 'businessLogic';

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return self::CATEGORY_BUSINESS_LOGIC;
    }
}
