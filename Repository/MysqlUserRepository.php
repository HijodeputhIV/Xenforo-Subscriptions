<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Repository;

use XF\Mvc\Entity\Manager;

use HijodeputhIV\Subscriptions\ValueObject\UserId;
use XF\Entity\User;

final class MysqlUserRepository
{
    public function __construct(
        private readonly Manager $entityManager,
    ) {}

    public function get(UserId $userId) : ?User
    {
        return $this->entityManager->findOne(
            shortName: 'XF:User',
            where: ['user_id' => $userId->getValue()]
        );
    }

}
