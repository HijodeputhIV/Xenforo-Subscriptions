<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Entity;

use ReflectionException;

use XF;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;

final class Post extends XFCP_Post
{

    /**
     * @throws ReflectionException
     */
    public function _postSave() : void
    {
        /** @var MysqlSubscriptionRepository $subscriptionsRepository */
        $subscriptionsRepository = XF::app()->get(MysqlSubscriptionRepository::class);

        /** @var WebhookNotifier $webhookNotifier */
        $webhookNotifier = XF::app()->get(WebhookNotifier::class);

        try {
            $uniqueWebhooks = array_map(
                function (Subscription $subscription) : Webhook {
                    return $subscription->webhook;
                },
                $subscriptionsRepository->groupByWebhook(),
            );
            $postData = XFPostData::fromXFEntity($this);
            $webhookNotifier->notifyPost($uniqueWebhooks, $postData);
        }
        catch (ReflectionException $e) {
            XF::logError($e->getMessage());
        }

        parent::_postSave();
    }

}
