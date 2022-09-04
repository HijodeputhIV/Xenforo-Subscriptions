<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use JsonSerializable;

use XF\Entity\Post;

class XFPostData implements JsonSerializable
{
    public function __construct(
        public readonly int $post_id,
        public readonly int $thread_id,
        public readonly int $author_id,
        public readonly string $author_name,
        public readonly int $create_date,
        public readonly int $update_date,
        public readonly string $message,
    )
    {}

    public static function fromXFEntity(Post $post) : XFPostData
    {
        return new XFPostData(
            post_id: $post->post_id,
            thread_id: $post->thread_id,
            author_id: $post->user_id,
            author_name: $post->username,
            create_date: $post->post_date,
            update_date: $post->last_edit_date,
            message: $post->message,
        );
    }

    public function jsonSerialize() : array
    {
        return (array)$this;
    }

}
