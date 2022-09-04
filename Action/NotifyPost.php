<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use ReflectionException;

use XF\Error;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;
use HijodeputhIV\Subscriptions\Entity\Subscription;

final class NotifyPost
{
    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
        private readonly WebhookNotifier $webhookNotifier,
        private readonly Error $error,
    ) {}

    public function notify(XFPostData $postData) : void
    {
        try {
            $uniqueWebhooks = array_map(
                function (Subscription $subscription) : Webhook {
                    return $subscription->webhook;
                },
                $this->subscriptionRepository->groupByWebhook(),
            );

            $this->webhookNotifier->notifyPost($uniqueWebhooks, $postData);
        }
        catch (ReflectionException $e) {
            $this->error->logError($e->getMessage());
        }
    }

}
