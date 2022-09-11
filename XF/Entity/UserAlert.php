<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Entity;

use ReflectionException;

use XF;

use HijodeputhIV\Subscriptions\Action\NotifyUserAlert;
use HijodeputhIV\Subscriptions\ValueObject\XFUserAlertData;


final class UserAlert extends XFCP_UserAlert
{

    /**
     * @throws ReflectionException
     */
    public function _postSave() : void
    {
        parent::_postSave();

        /** @var NotifyUserAlert $notifyUserAlert */
        $notifyUserAlert = XF::app()->get(NotifyUserAlert::class);
        $notifyUserAlert->notify($this);
    }
}
