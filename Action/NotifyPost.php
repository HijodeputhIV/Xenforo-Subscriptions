<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use ReflectionException;

use XF\Error;
use XF\Entity\Post;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;

final class NotifyPost
{
    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
        private readonly WebhookNotifier $webhookNotifier,
        private readonly Error $error,
    ) {}

    public function notify(Post $post) : void
    {
        try {
            $this->webhookNotifier->notifyPost(
                subscriptions: $this->subscriptionRepository->groupByWebhook(),
                postData: XFPostData::fromXFEntity($post),
            );
        }
        catch (ReflectionException $e) {
            $this->error->logError($e->getMessage());
        }
    }

}
