<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Action;

use ReflectionException;

use XF\Error;
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
        private readonly Error $error,
    ) {}

    /**
     * @param User[] $usersNotified
     */
    public function notify(ConversationMessage $conversationMessage, array $usersNotified) : void
    {
        try {
            $this->webhookNotifier->notifyConversationMessage(
                subscriptions: $this->subscriptionRepository->getByUsers($usersNotified),
                conversationMessageData: XFConversationMessageData::fromXFEntity($conversationMessage),
            );
        }
        catch (ReflectionException $e) {
            $this->error->logError($e->getMessage());
        }
    }

}
