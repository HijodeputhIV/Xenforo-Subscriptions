<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Repository;

use XF\Mvc\Entity\Manager;
use XF\Db\Exception;

use HijodeputhIV\Subscriptions\Entity\Subscription;

final class MysqlSubscriptionRepository
{
    public function __construct(
        private readonly Manager $entityManager,
    ) {}

    /**
     * @throws Exception
     */
    public function save(Subscription $subscription) : void
    {
        $this->entityManager->getDb()->insert(
            table: 'xf_subscriptions',
            rawValues: [
                'user_id' => $subscription->userId->getValue(),
                'webhook' => $subscription->webhook->getValue(),
                'token' => $subscription->token->getValue(),
            ],
            replaceInto: true,
            onDupe: true,
        );
    }

}
