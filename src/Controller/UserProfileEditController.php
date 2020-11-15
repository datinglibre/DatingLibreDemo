<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ProfileForm;
use App\Form\ProfileFormType;
use DatingLibre\AppBundle\Entity\Profile;
use DatingLibre\AppBundle\Repository\ProfileRepository;
use DatingLibre\AppBundle\Repository\UserRepository;
use DatingLibre\AppBundle\Service\ProfileService;
use DatingLibre\AppBundle\Service\SuspensionService;
use DatingLibre\AppBundle\Service\UserAttributeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserProfileEditController extends AbstractController
{
    private ProfileRepository $profileRepository;
    private UserRepository $userRepository;
    private UserAttributeService $userAttributeService;
    private ProfileService $profileService;
    private SuspensionService $suspensionService;

    public function __construct(
        ProfileRepository $profileRepository,
        ProfileService $profileService,
        UserRepository $userRepository,
        UserAttributeService $userAttributeService,
        SuspensionService $suspensionService
    ) {
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->userAttributeService = $userAttributeService;
        $this->profileService = $profileService;
        $this->suspensionService = $suspensionService;
    }

    public function edit(Request $request)
    {
        $userId = $this->getUser();
        $user = $this->userRepository->find($userId);
        $profile = $this->profileRepository->find($userId) ?? (new Profile())->setUser($user);
        $profileProjection = $this->profileService->findProjection($user->getId());
        $permanentSuspension = $this->suspensionService->findOpenPermanentSuspension($user->getId());

        if ($permanentSuspension !== null) {
            return new RedirectResponse($this->generateUrl('user_profile_index'));
        }

        $profileForm = new ProfileForm();
        $city = $profile->getCity();

        if ($city != null) {
            $profileForm->setCountry($city->getCountry());
            $profileForm->setRegion($city->getRegion());
            $profileForm->setCity($city);
        }

        $profileForm->setAbout($profile->getAbout());
        $profileForm->setUsername($profile->getUsername());
        $profileForm->setDob($profile->getDob());
        $profileForm->setSex($this->userAttributeService->getOneByCategoryName($user, 'sex'));
        $profileForm->setRelationship($this->userAttributeService->getOneByCategoryName($user, 'relationship'));

        $profileFormType = $this->createForm(ProfileFormType::class, $profileForm);
        $profileFormType->handleRequest($request);

        if ($profileFormType->isSubmitted() && $profileFormType->isValid()) {
            $this->userAttributeService->createUserAttributes(
                $user,
                [$profileForm->getSex(), $profileForm->getRelationship()]
            );

            $profile->setCity($profileForm->getCity());
            $profile->setUsername($profileForm->getUsername());
            $profile->setAbout($profileForm->getAbout());
            $profile->setDob($profileForm->getDob());
            $this->profileRepository->save($profile);
            return new RedirectResponse($this->generateUrl('user_profile_index'));
        }

        return $this->render('@DatingLibreApp/user/profile/edit.html.twig', [
            'profileForm' => $profileFormType->createView(),
            'profile' => $profileProjection,
        ]);
    }
}
