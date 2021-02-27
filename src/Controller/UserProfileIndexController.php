<?php

declare(strict_types=1);

namespace App\Controller;

use DatingLibre\AppBundle\Repository\ProfileRepository;
use DatingLibre\AppBundle\Repository\RequirementRepository;
use DatingLibre\AppBundle\Repository\UserAttributeRepository;
use DatingLibre\AppBundle\Service\SuspensionService;
use DatingLibre\AppBundle\Service\UserInterestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class UserProfileIndexController extends AbstractController
{
    private ProfileRepository $profileRepository;
    private UserAttributeRepository $userAttributeRepository;
    private RequirementRepository $requirementRepository;
    private SuspensionService $suspensionService;
    private UserInterestService $userInterestService;

    public function __construct(
        ProfileRepository $profileRepository,
        UserAttributeRepository $userAttributeRepository,
        RequirementRepository $requirementRepository,
        SuspensionService $suspensionService,
        UserInterestService $userInterestService
    ) {
        $this->profileRepository = $profileRepository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->requirementRepository = $requirementRepository;
        $this->suspensionService = $suspensionService;
        $this->userInterestService = $userInterestService;
    }

    public function index()
    {
        $profile = $this->profileRepository->findProjection($this->getUser()->getId());
        $suspension = $this->suspensionService->findOpenByUserId($this->getUser()->getId());

        if (null == $profile) {
            $this->addFlash('warning', 'profile.incomplete');
            return new RedirectResponse($this->generateUrl('user_profile_edit'));
        }

        return $this->render('@DatingLibreApp/user/profile/index.html.twig', [
            'sex' => $this->userAttributeRepository->getOneByUserAndCategory($this->getUser()->getId(), 'sex'),
            'relationship' => $this->userAttributeRepository->getOneByUserAndCategory($this->getUser()->getId(), 'relationship'),
            'sexes' => $this->requirementRepository->getMultipleByUserAndCategory($this->getUser()->getId(), 'sex'),
            'interests' => $this->userInterestService->findInterestsByUserId(Uuid::fromString($profile->getId())),
            'profile' => $profile,
            'suspension' => $suspension,
        ]);
    }
}