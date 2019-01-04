<?php

namespace App\Controller;

use App\Dto\ResultDto;
use App\Entity\Result;
use App\Exception\NotFoundException;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path=ResultController::API_RESULTS_PATH, name="api_results_")
 */
class ResultController extends AbstractController
{
    // Ruta API Result
    public const API_RESULTS_PATH = '/api/v1/results';

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
     * @Route("/{id}", name="get", methods={"GET"})
     * @param Result|null $result
     * @return JsonResponse
     */
    public function readResult(?Result $result): JsonResponse
    {
        if ($result === null) {
            self::throwResultNotFoundException();
        }

        return JsonResponseBuilder::success200Ok(['result' => $result])->build();
    }

    /**
     * @Route("", name="post", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createResult(Request $request): JsonResponse
    {
        $resultDto = ResultDto::fromRequest($request);
        $resultDto->validate();

        $user = $this->userRepository->findById($resultDto->getUserId());
        if ($user === null) {
            UserController::throwUserNotFoundException();
        }

        $result = new Result($resultDto->getResult(), $user, new \DateTime('now'));
        $result = $this->resultRepository->create($result);

        return JsonResponseBuilder::success201Created(['result' => $result])->build();
    }

    /**
     * @Route("/{id}", name="put", methods={"PUT"})
     * @param Request $request
     * @param Result|null $result
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateResult(Request $request, ?Result $result): JsonResponse
    {
        if ($result === null) {
            self::throwResultNotFoundException();
        }

        $resultDto = ResultDto::fromRequest($request);
        $resultDto->validate();

        $user = $this->userRepository->findById($resultDto->getUserId());
        if ($user === null) {
            UserController::throwUserNotFoundException();
        }

        $result = $this->resultRepository->update($result->getId(),
            new Result($resultDto->getResult(), $result->getUser(), new \DateTime('now')));

        return JsonResponseBuilder::success200Ok(['result' => $result])->build();
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Result|null $result
     * @return JsonResponse
     */
    public function deleteResult(?Result $result): JsonResponse
    {
        if ($result === null) {
            self::throwResultNotFoundException();
        }

        $this->resultRepository->delete($result);

        return JsonResponseBuilder::success204Deleted()->build();
    }

    public static function throwResultNotFoundException(): void
    {
        throw new NotFoundException('Result not found');
    }

}