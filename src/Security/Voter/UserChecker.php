<?php

namespace App\Security;

use DateTime;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserChecker implements UserCheckerInterface {

     /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */

    /**
     * @param User $user 
    */
    public function checkPreAuth(UserInterface $user) {
        if ($user->getBannedUntil() === null) {
            return;
        }

        $now = new DateTime();

        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedHttpException('The user is currently banned');
        }

    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    /**
     * @param User $user 
    */
    public function checkPostAuth(UserInterface $user) {

    } 
}