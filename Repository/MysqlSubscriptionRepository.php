<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Repository;

use ReflectionClass;
use ReflectionException;

use XF\Mvc\Entity\Manager;
use XF\Db\Exception;

use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;

final class MysqlSubscriptionRepository
{
    public function __construct(
        private readonly Manager $entityManager,
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

    /**
     * @throws ReflectionException
     */
    private function hydrateInstance(array $arguments) : Subscription
    {
        return new Subscription(
            userId: $this->hydrateProperty(UserId::class, ['user_id' => $arguments['user_id']]),
            webhook: $this->hydrateProperty(Webhook::class, ['url' => $arguments['webhook']]),
            token: $this->hydrateProperty(Token::class, ['token' => $arguments['token']]),
            id: $arguments['id'],
        );
    }

    /**
     * @throws ReflectionException
     */
    public function get(UserId $userId) : ?Subscription
    {
        $row = $this->entityManager->getDb()->fetchRow(
            query: 'SELECT * FROM `xf_subscriptions` WHERE user_id = ?',
            params: ['user_id' => $userId->getValue()]
        );

        if (!$row) {
            return null;
        }

        return $this->hydrateInstance($row);
    }

    /**
     * @throws ReflectionException
     */
    public function all() : array
    {
        $rows = $this->entityManager->getDb()->fetchAll('SELECT * FROM `xf_subscriptions`');

        return array_map(
            function (array $row) : Subscription {
                return $this->hydrateInstance($row);
            },
            $rows,
        );
    }

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
