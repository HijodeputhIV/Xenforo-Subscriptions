<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Service\Conversation;

use XF;
use XF\Entity\ConversationMessage;
use XF\Entity\User;

use HijodeputhIV\Subscriptions\Action\NotifyConversationMessage;

final class Notifier extends XFCP_Notifier
{

    /**
     * @return array<int, User>
     */
    protected function _sendNotifications(
        $actionType,
        array $notifyUsers,
        ?ConversationMessage $message = null,
        ?User $sender = null,
    ) : array
    {
        $usersNotified = parent::_sendNotifications($actionType, $notifyUsers, $message, $sender);

        if ($message) {
            /** @var NotifyConversationMessage $notifyConversation */
            $notifyConversation = XF::app()->get(NotifyConversationMessage::class);
            $notifyConversation->notify($message, $usersNotified);
        }

        return $usersNotified;
    }

}
