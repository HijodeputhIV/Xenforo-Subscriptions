<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use XF\Validator\Url as UrlValidator;
use XF\Db\Exception;

use HijodeputhIV\Subscriptions\Service\UserFinder;
use HijodeputhIV\Subscriptions\Service\WebhookChecker;
use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\ValueObject\SubscriptionId;
use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;
use HijodeputhIV\Subscriptions\Exception\InvalidUuidException;
use HijodeputhIV\Subscriptions\Exception\InvalidUserIdException;
use HijodeputhIV\Subscriptions\Exception\InvalidTokenException;
use HijodeputhIV\Subscriptions\Exception\InvalidWebhookException;
use HijodeputhIV\Subscriptions\Exception\UserNotFoundException;
use HijodeputhIV\Subscriptions\Exception\WebhookNotImplementedException;
use HijodeputhIV\Subscriptions\Exception\SubscriptionSaveException;

final class CreateSubscription
{
    public function __construct(
        private readonly UrlValidator $urlValidator,
        private readonly UserFinder $userFinder,
        private readonly WebhookChecker $webhookChecker,
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
    ) {}

    /**
     * @throws InvalidUuidException|InvalidUserIdException|InvalidWebhookException|InvalidTokenException
     * @throws UserNotFoundException
     * @throws WebhookNotImplementedException
     * @throws SubscriptionSaveException
     */
    public function execute(int $user_id, string $webhook, string $token) : void
    {
        $subscription = new Subscription(
            id: SubscriptionId::generate(),
            userId: new UserId($user_id),
            webhook: new Webhook($webhook, $this->urlValidator),
            token: new Token($token),
        );

        $this->userFinder->find($subscription->userId);
        $this->webhookChecker->check($subscription->webhook, $subscription->token);

        try {
            $this->subscriptionRepository->save($subscription);
        }
        catch (Exception $xenforoDatabaseException) {
            throw new SubscriptionSaveException($xenforoDatabaseException->getMessage());
        }

    }

}
