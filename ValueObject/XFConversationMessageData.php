<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use JsonSerializable;

use XF\Entity\ConversationMessage;

final class XFConversationMessageData implements JsonSerializable
{
    public function __construct(
        public readonly int $message_id,
        public readonly int $conversation_id,
        public readonly int $sender_id,
        public readonly string $sender_name,
        public readonly int $message_date,
        public readonly string $message,
    ) {}

    public static function fromXFEntity(ConversationMessage $conversationMessage) : XFConversationMessageData
    {
        return new XFConversationMessageData(
            message_id: $conversationMessage->message_id,
            conversation_id: $conversationMessage->conversation_id,
            sender_id: $conversationMessage->user_id,
            sender_name: $conversationMessage->username,
            message_date: $conversationMessage->message_date,
            message: $conversationMessage->message,
        );
    }

    public function jsonSerialize() : array
    {
        return (array)$this;
    }

}
