<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use XF\Entity\ConversationMessage;
use XF\Entity\User;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\ValueObject\XFConversationMessageData;

final class NotifyConversationMessage
{
    public function __construct(
        private readonly MysqlSubscriptionRepository $subscriptionRepository,
        private readonly WebhookNotifier $webhookNotifier,
    ) {}

    /**
     * @param User[] $usersNotified
     */
    public function notify(ConversationMessage $conversationMessage, array $usersNotified) : void
    {
        $this->webhookNotifier->notifyConversationMessage(
            subscriptions: $this->subscriptionRepository->getByUsers($usersNotified),
            conversationMessageData: XFConversationMessageData::fromXFEntity($conversationMessage),
        );
    }

}
