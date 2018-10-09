<?php namespace Lovata\Toolbox\Classes\Helper\Users;

/**
 * Class BuddiesUserHelper
 * @package Lovata\Toolbox\Classes\Helper\Users
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class BuddiesUserHelper extends AbstractUserHelper
{
    /**
     * Find user by email
     * @param string $sEmail
     *
     * @return \Lovata\Buddies\Models\User|null
     */
    public function findUserByEmail($sEmail)
    {
        if (empty($sEmail)) {
            return null;
        }

        return \Lovata\Buddies\Models\User::getByEmail($sEmail)->first();
    }

    /**
     * Get user model class name
     * @return string
     */
    public function getUserModel()
    {
        return \Lovata\Buddies\Models\User::class;
    }

    /**
     * Get user controller class name
     * @return string
     */
    public function getUserController()
    {
        return \Lovata\Buddies\Controllers\Users::class;
    }

    /**
     * Get auth facade class name
     * @return string
     */
    public function getAuthFacade()
    {
        return \Lovata\Buddies\Facades\AuthHelper::class;
    }
}
