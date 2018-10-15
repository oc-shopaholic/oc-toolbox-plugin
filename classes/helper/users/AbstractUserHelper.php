<?php namespace Lovata\Toolbox\Classes\Helper\Users;

/**
 * Class AbstractUserHelper
 * @package Lovata\Toolbox\Classes\Helper\Users
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractUserHelper
{
    /**
     * Get user model class name
     * @return string
     */
    abstract public function getUserModel();

    /**
     * Get user controller class name
     * @return string
     */
    abstract public function getUserController();

    /**
     * Get auth facade class name
     * @return string
     */
    abstract public function getAuthFacade();

    /**
     * Find User object by email
     * @param string $sEmail
     * @return \Lovata\Buddies\Models\User|\RainLab\User\Models\User|null
     */
    abstract public function findUserByEmail($sEmail);
}
