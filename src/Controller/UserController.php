<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path=UserController::API_USERS_PATH, name="api_users_")
 */
class UserController extends AbstractController
{
    // Ruta API User
    public const API_USERS_PATH = '/api/v1/users';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ResultRepository
     */
    private $resultRepository;

    public function __construct(UserRepository $userRepository, ResultRepository $resultRepository)
    {
        $this->userRepository = $userRepository;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @Route("", name="getc", methods={"GET"})
     * @return JsonResponse
     */
    public function readUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return JsonResponseBuilder::success200Ok(['users' => $users])->build();
    }

    /**
     * @Route("/{id}", name="get", methods={"GET"})
     * @param User|null $user
     * @return JsonResponse
     */
    public function readUser(?User $user): JsonResponse
    {
        if ($user === null) {
            self::throwUserNotFoundException();
        }

        return JsonResponseBuilder::success200Ok(['user' => $user])->build();
    }


    /**
     * @Route("", name="post", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        $userDto = UserDto::fromRequest($request);
        $userDto->validate();

        $user = $userDto->toEntity(new User());
        $user = $this->userRepository->create($user);

        return JsonResponseBuilder::success201Created(['user' => $user])->build();
    }

    /**
     * @Route("/{id}", name="put", methods={"PUT"})
     * @param Request $request
     * @param User|null $user
     * @return JsonResponse
     */
    public function updateUser(Request $request, ?User $user): JsonResponse
    {
        if ($user === null) {
            self::throwUserNotFoundException();
        }

        $userDto = UserDto::fromRequest($request);
        $userDto->validate();

        $userDto->toEntity($user);
        $user = $this->userRepository->update($user->getId(), $user);

        return JsonResponseBuilder::success200Ok(['user' => $user])->build();
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param User|null $user
     * @return JsonResponse
     */
    public function deleteUser(?User $user): JsonResponse
    {
        if ($user === null) {
            self::throwUserNotFoundException();
        }

        $this->userRepository->delete($user);

        return JsonResponseBuilder::success204Deleted()->build();
    }

    /**
     * @Route("/{id}/results", name="getc_results", methods={ "GET" })
     * @param User|null $user
     * @return JsonResponse
     */
    public function readResults(?User $user): JsonResponse
    {
        if ($user === null) {
            self::throwUserNotFoundException();
        }

        $results = $this->resultRepository->findByUserId($user->getId());

        return JsonResponseBuilder::success200Ok(['results' => $results])->build();
    }

    public static function throwUserNotFoundException(): void
    {
        throw new NotFoundException('User not found');
    }

}
