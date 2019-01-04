<?php

namespace App\Repository;


use App\Entity\User;
use App\Exception\AlreadyExistException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(User $user): User
    {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new AlreadyExistException('username o email already exist');
        }
        return $user;
    }

    public function update(int $userId, User $user): ?User
    {
        $currentUser = $this->findById($userId);
        if (null === $currentUser) {
            return null;
        }

        $currentUser->merge($user);

        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();
        return $currentUser;
    }

    public function delete(User $user): ?User
    {
        if (null === $user || null === $user->getId()) {
            return null;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $user;
    }

    public function deleteById(int $userId): ?User
    {
        $user = $this->findById($userId);
        if (null === $user) {
            return null;
        }

        return $this->delete($user);
    }

    public function findById(int $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

}