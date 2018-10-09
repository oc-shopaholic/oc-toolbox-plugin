<?php namespace Lovata\Toolbox\Classes\Helper\Users;

/**
 * Class RainLabUserHelper
 * @package Lovata\Toolbox\Classes\Helper\Users
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class RainLabUserHelper extends AbstractUserHelper
{
    /**
     * Find user by email
     * @param string $sEmail
     *
     * @return \RainLab\User\Models\User|null
     */
    public function findUserByEmail($sEmail)
    {
        if (empty($sEmail)) {
            return null;
        }

        return \RainLab\User\Models\User::findByEmail($sEmail);
    }

    /**
     * Get user model class name
     * @return string
     */
    public function getUserModel()
    {
        return \RainLab\User\Models\User::class;
    }

    /**
     * Get user controller class name
     * @return string
     */
    public function getUserController()
    {
        return \RainLab\User\Controllers\Users::class;
    }

    /**
     * Get auth facade class name
     * @return string
     */
    public function getAuthFacade()
    {
        return \RainLab\User\Facades\Auth::class;
    }
}
