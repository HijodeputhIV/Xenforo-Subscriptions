<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Entity;

use XF;

use HijodeputhIV\Subscriptions\Action\NotifyPost;

final class Post extends XFCP_Post
{

    public function _postSave() : void
    {
        parent::_postSave();

        /** @var NotifyPost $notifyPost */
        $notifyPost = XF::app()->get(NotifyPost::class);
        $notifyPost->notify($this);
    }

}
