<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\MicroPost;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MicroPostVoter extends Voter
{
    public function __construct(public Security $security)
    {
        
    }

    protected function supports(string $attribute, mixed $subject): bool //$attribute is the action wanted to be perform by the $subject / entity
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [MicroPost::EDIT, MicroPost::VIEW])
            && $subject instanceof MicroPost;
    }

    /** @var MicroPost $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
    
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true; // admin has access of everyting no need to check 
        }

        $isAuthenticated = $user instanceof UserInterface;
        $isUserTheAuthor = $subject->getAuthor()->getId() === $user->getId();
        $isEditor = $this->security->isGranted('ROLE_EDITOR');

        $isCurrentUserIsFollowingAuthor = $subject->getAuthor()->getFollowedBy()->contains($user);

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case MicroPost::EDIT:
                return $isAuthenticated && ($isUserTheAuthor || $isEditor);
            case MicroPost::VIEW:
                if (!$subject->isExtraPrivacy()) {
                    return true;
                }

                return $isAuthenticated && ($isUserTheAuthor || $isCurrentUserIsFollowingAuthor);
        }

        return false;
    }
}
