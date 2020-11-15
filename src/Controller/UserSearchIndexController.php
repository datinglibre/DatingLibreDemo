<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\FilterForm;
use App\Form\FilterFormType;
use DatingLibre\AppBundle\Entity\Filter;
use DatingLibre\AppBundle\Entity\User;
use DatingLibre\AppBundle\Repository\FilterRepository;
use DatingLibre\AppBundle\Repository\UserRepository;
use DatingLibre\AppBundle\Service\ProfileService;
use DatingLibre\AppBundle\Service\RequirementService;
use DatingLibre\AppBundle\Service\SuspensionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSearchIndexController extends AbstractController
{
    private const PREVIOUS = 'previous';
    private const NEXT = 'next';
    private const LIMIT = 10;
    private ProfileService $profileService;
    private UserRepository $userRepository;
    private FilterRepository $filterRepository;
    private SuspensionService $suspensionService;
    private RequirementService $requirementService;

    public function __construct(
        ProfileService $profileService,
        UserRepository $userRepository,
        FilterRepository $filterRepository,
        SuspensionService $suspensionService,
        RequirementService $requirementService
    ) {
        $this->profileService = $profileService;
        $this->userRepository = $userRepository;
        $this->filterRepository = $filterRepository;
        $this->suspensionService = $suspensionService;
        $this->requirementService = $requirementService;
    }

    public function index(UserInterface $user, Request $request)
    {
        if (null === $this->profileService->find($user->getId())) {
            $this->addFlash('warning', 'profile.incomplete');
            return new RedirectResponse($this->generateUrl('user_profile_edit'));
        }

        if (null !== $this->suspensionService->findOpenByUserId($user->getId())) {
            return new RedirectResponse($this->generateUrl('user_profile_index'));
        }

        $user = $this->userRepository->find($this->getUser()->getId());
        $profile = $this->profileService->find($user->getId());
        $filter = $this->filterRepository->find($user->getId()) ?? $this->createDefaultFilter($user);


        $filterForm = new FilterForm();
        $filterForm->setDistance($filter->getDistance());
        $filterForm->setMaxAge($filter->getMaxAge());
        $filterForm->setMinAge($filter->getMinAge());
        $filterForm->setRegion($filter->getRegion());
        $filterForm->setRelationships($this->requirementService->getMultipleByUserAndCategory($user->getId(), 'relationship'));
        $filterForm->setSexes($this->requirementService->getMultipleByUserAndCategory($user->getId(), 'sex'));

        $filterFormType = $this->createForm(
            FilterFormType::class,
            $filterForm,
            [
                'regions' => $profile->getCity()->getRegion()->getCountry()->getRegions(),
            ]
        );

        $filterFormType->handleRequest($request);

        if ($filterFormType->isSubmitted() && $filterFormType->isValid()) {
            $filter->setUser($user);
            $filter->setRegion($filterForm->getRegion());
            $filter->setMinAge($filterForm->getMinAge());
            $filter->setMaxAge($filterForm->getMaxAge());
            $filter->setDistance($filterForm->getDistance());
            $this->filterRepository->save($filter);

            $this->requirementService->createRequirementsInCategory(
                $user,
                'sex',
                $filterForm->getSexes()
            );

            $this->requirementService->createRequirementsInCategory(
                $user,
                'relationship',
                $filterForm->getRelationships()
            );

            return new RedirectResponse($this->generateUrl('user_search_index'));
        }

        $previous = (int) $request->query->get(self::PREVIOUS, 0);
        $next = (int) $request->query->get(self::NEXT, 0);

        $profiles = $this->profileService->findByLocation(
            $user->getId(),
            $filter->getDistance(),
            empty($filter->getRegion()) ? null : $filter->getRegion()->getId(),
            $filter->getMinAge(),
            $filter->getMaxAge(),
            $previous,
            $next,
            self::LIMIT
        );

        return $this->render('@DatingLibreApp/user/search/index.html.twig', [
            'next' => $this->getNext($profiles, $previous),
            'previous' => $this->getPrevious($profiles, $next),
            'page' => 'search',
            'profiles' => $profiles,
            'filterForm' => $filterFormType->createView()
        ]);
    }

    public function createDefaultFilter(User $user): Filter
    {
        $filter = new Filter();
        $filter->setUser($user);
        return $this->filterRepository->save($filter);
    }

    private function getPrevious(array &$profiles, ?int $next): array
    {
        if ($next !== 0) {
            return [self::PREVIOUS => $next - 1];
        }

        if (count($profiles) === self::LIMIT + 1) {
            $previous = array_shift($profiles);
            return [self::PREVIOUS => $previous->getSortId()];
        }

        return [];
    }

    private function getNext(array &$profiles, ?int $previous): array
    {
        if ($previous !== 0) {
            return [self::NEXT => $previous + 1];
        }

        if (count($profiles) === self::LIMIT + 1) {
            $next = array_pop($profiles);
            return [self::NEXT => $next->getSortId()];
        }

        return [];
    }
}
