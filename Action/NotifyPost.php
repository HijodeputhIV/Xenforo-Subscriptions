<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use XF\Entity\Post;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;

final class NotifyPost
{
    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
        private readonly WebhookNotifier $webhookNotifier,
    ) {}

    public function notify(Post $post) : void
    {
        $this->webhookNotifier->notifyPost(
            subscriptions: $this->subscriptionRepository->groupByWebhook(),
            postData: XFPostData::fromXFEntity($post),
        );
    }

}
