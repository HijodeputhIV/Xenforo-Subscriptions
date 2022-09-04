<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Entity;

use HijodeputhIV\Subscriptions\ValueObject\UserId;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;

final class Subscription
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Webhook $webhook,
        public readonly Token $token
    ) {}


}
