<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Entity;

use XF;

use HijodeputhIV\Subscriptions\Action\NotifyUserAlert;

final class UserAlert extends XFCP_UserAlert
{

    protected function _postSave() : void
    {
        parent::_postSave();

        /** @var NotifyUserAlert $notifyUserAlert */
        $notifyUserAlert = XF::app()->get(NotifyUserAlert::class);
        $notifyUserAlert->notify($this);
    }

}
