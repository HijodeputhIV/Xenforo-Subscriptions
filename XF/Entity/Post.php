<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\XF\Entity;

use ReflectionException;

use XF;

use HijodeputhIV\Subscriptions\Action\NotifyPost;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;

final class Post extends XFCP_Post
{

    /**
     * @throws ReflectionException
     */
    public function _postSave() : void
    {
        parent::_postSave();

        /** @var NotifyPost $notifyPost */
        $notifyPost = XF::app()->get(NotifyPost::class);
        $notifyPost->notify($this);
    }

}
