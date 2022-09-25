<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Repository;

use ReflectionClass;
use ReflectionException;

use XF\Mvc\Entity\Manager;
use XF\Entity\User;
use XF\Error;

use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\ValueObject\SubscriptionId;
use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;

final class MysqlSubscriptionRepository
{
    public function __construct(
        private readonly Manager $entityManager,
        private readonly Error $error,
    ) {}

    /**
     * @throws ReflectionException
     */
    private function hydrateProperty(string $className, array $arguments) : object
    {
        $instance = (new ReflectionClass($className))->newInstanceWithoutConstructor();
        $reflectionClass = new ReflectionClass($instance);

        foreach ($arguments as $argument => $value) {
            $property = $reflectionClass->getProperty($argument);
            $property->setAccessible(true);
            $property->setValue($instance, $value);
        }

        return $instance;
    }

    private function hydrateInstance(array $arguments) : ?Subscription
    {
        try {
            return new Subscription(
                id: $this->hydrateProperty(SubscriptionId::class, ['uuid' => $arguments['id']]),
                userId: $this->hydrateProperty(UserId::class, ['user_id' => $arguments['user_id']]),
                webhook: $this->hydrateProperty(Webhook::class, ['url' => $arguments['webhook']]),
                token: $this->hydrateProperty(Token::class, ['hulsash' => $arguments['token']]),
            );
        }
        catch (ReflectionException $e) {
            $this->error->logError($e->getMessage());
            return null;
        }
    }

    private function createInstance(false|array $row) : ?Subscription
    {
        if (!$row) {
            return null;
        }

        return $this->hydrateInstance($row);
    }

    /**
     * @return Subscription[]
     */
    private function createInstances(array $rows) : array
    {
        return array_filter(
            array_map(
                function (array $row) : ?Subscription {
                    return $this->hydrateInstance($row);
                },
                $rows,
            )
        );
    }

    public function get(SubscriptionId $id) : ?Subscription
    {
        $row = $this->entityManager->getDb()->fetchRow(
            query: 'SELECT * FROM `xf_subscriptions` WHERE id = ?',
            params: ['user_id' => $id->getValue()]
        );

        return $this->createInstance($row);
    }

    /**
     * @return Subscription[]
     */
    public function getByUser(User $user) : array
    {
        $rows = $this->entityManager->getDb()->fetchAll(
            query: 'SELECT * FROM `xf_subscriptions` WHERE user_id = ?',
            params: ['user_id' => $user->user_id]
        );

        return $this->createInstances($rows);
    }

    /**
     * @param User[] $users
     *
     * @return Subscription[]
     */
    public function getByUsers(array $users) : array
    {
        $userIds = array_map(
            function (User $user) : int {
                return $user->user_id;
            },
            $users
        );

        $userIdsPadding = implode(
            separator: ',',
            array: array_fill(0, count($userIds), '?')
        );

        $rows = $this->entityManager->getDb()->fetchAll(
            query: 'SELECT * FROM `xf_subscriptions` WHERE user_id IN ('.$userIdsPadding.')',
            params: $userIds
        );

        return $this->createInstances($rows);
    }

    /**
     * @return Subscription[]
     */
    public function all() : array
    {
        $rows = $this->entityManager->getDb()->fetchAll('SELECT * FROM `xf_subscriptions`');
        return $this->createInstances($rows);
    }

    /**
     * @return Subscription[]
     */
    public function groupByWebhook() : array
    {
        $rows = $this->entityManager->getDb()->fetchAll('SELECT * FROM `xf_subscriptions` GROUP BY `webhook`');
        return $this->createInstances($rows);
    }

    public function save(Subscription $subscription) : void
    {
        $this->entityManager->getDb()->insert(
            table: 'xf_subscriptions',
            rawValues: [
                'id' => $subscription->id->getValue(),
                'user_id' => $subscription->userId->getValue(),
                'webhook' => $subscription->webhook->getValue(),
                'token' => $subscription->token->getValue(),
            ],
            replaceInto: true,
            onDupe: true,
        );
    }

}
