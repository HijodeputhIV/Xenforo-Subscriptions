<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Service;

use XF\Entity\User;

use HijodeputhIV\Subscriptions\Repository\MysqlUserRepository;
use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\Exception\UserNotFoundException;

class UserFinder
{
    public function __construct(
        private readonly MysqlUserRepository $userRepository,
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function find(UserId $userId) : User
    {
        return $this->userRepository->get($userId) ?? throw new UserNotFoundException($userId);
    }

}
