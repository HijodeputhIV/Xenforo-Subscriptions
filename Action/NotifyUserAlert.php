<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use XF\Entity\UserAlert;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\ValueObject\XFUserAlertData;

class NotifyUserAlert
{
    private static string $notifiableContentType = 'post';

    private static array $notifiableUserAlerts = [
        'quote',
        'mention',
    ];

    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
        private readonly WebhookNotifier $webhookNotifier,
    ) {}

    private function isNotifiable(UserAlert $userAlert) : bool
    {
        return $userAlert->content_type === self::$notifiableContentType
            && in_array($userAlert->action, self::$notifiableUserAlerts);
    }

    public function notify(UserAlert $userAlert) : void
    {
        if (!$this->isNotifiable($userAlert)) {
            return;
        }

        $this->webhookNotifier->notifyUserAlert(
            subscriptions: $this->subscriptionRepository->getByUser($userAlert->Receiver),
            userAlertData: XFUserAlertData::fromXFEntity($userAlert),
        );
    }

}
