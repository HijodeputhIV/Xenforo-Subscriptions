<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use JsonSerializable;

use XF\Entity\UserAlert;

class XFUserAlertData implements JsonSerializable
{
    public readonly string $content_type;

    public function __construct(
        public readonly int $content_id,
        public readonly int $user_id,
    )
    {
        $this->content_type = 'post';
    }

    public static function fromXFEntity(UserAlert $userAlert) : XFUserAlertData
    {
        return new XFUserAlertData(
            content_id: $userAlert->content_id,
            user_id: $userAlert->user_id,
        );
    }

    public function jsonSerialize() : array
    {
        return (array)$this;
    }

}
