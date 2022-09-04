<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use XF\Db\Exception;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;
use HijodeputhIV\Subscriptions\Exception\InvalidUserIdException;
use HijodeputhIV\Subscriptions\Exception\InvalidTokenException;
use HijodeputhIV\Subscriptions\Exception\InvalidWebhookException;
use HijodeputhIV\Subscriptions\Exception\SubscriptionSaveException;

final class CreateSubscription
{
    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
    ) {}

    /**
     * @throws InvalidUserIdException|InvalidWebhookException|InvalidTokenException
     * @throws SubscriptionSaveException
     */
    public function execute(int $user_id, string $webhook, string $token) : void
    {
        $subscription = new Subscription(
            userId: new UserId($user_id),
            webhook: new Webhook($webhook),
            token: new Token($token),
        );

        try {
            $this->subscriptionRepository->save($subscription);
        }
        catch (Exception $xenforoDatabaseException) {
            throw new SubscriptionSaveException($xenforoDatabaseException->getMessage());
        }

    }

}
