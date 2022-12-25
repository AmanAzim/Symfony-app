<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\ProfileImageType;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SettingsProfileController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] 
    public function profile(Request $request, UserRepository $userRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser(); // method of abstract controller
        $userProfile = $user->getUserProfile() ?? new UserProfile();

        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $user->setUserProfile($userProfile);
            $userRepo->save($user, true);

            $this->addFlash('success', 'User profile settings are saved');

            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings_profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/settings/profile-image', name: 'app_settings_profile_image')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] 
    public function profileImage(Request $request, SluggerInterface $slugger, UserRepository $userRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser(); 

        $form = $this->createForm(ProfileImageType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $profileImageFile = $form->get('profileImage')->getData(); // this getData will return an instance of the File class

            if ($profileImageFile) {
                $originalFileName = pathinfo($profileImageFile->getClientOriginalName(), PATHINFO_FILENAME); // pathinfo() returns information about uploaded file and its path
                $safeFileName = $slugger->slug($originalFileName); // add hyfens '-' in places of other seperator in file name string
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $profileImageFile->guessExtension();

                try {
                    // php initially uploads file in a temporary directory so we need to move it to a permanent directory
                    $profileImageFile->move(
                        $this->getParameter('profiles_directory'),
                        $newFileName
                    );

                } catch (FileException $err) {}

                $profile = $user->getUserProfile() ?? new UserProfile();
                $profile->setImage($newFileName);
                $user->setUserProfile($profile);
                $userRepo->save($user, true);

                $this->addFlash('success', 'User profile image is updated');

                return $this->redirectToRoute('app_settings_profile_image');
            }
        }
        
        return $this->render('settings_profile/profile_image.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
