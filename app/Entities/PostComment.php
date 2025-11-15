<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\PostCommentRepository;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: PostCommentRepository::class)]
#[ORM\Table('`post_comment`')]
class PostComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(
        name: 'author_id',
        type: Types::INTEGER,
        nullable: false,
    )]
    public int $authorId;

    #[ORM\Column(
        name: 'post_id',
        type: Types::INTEGER,
        nullable: false,
    )]
    public int $postId;

    #[ORM\Column(
        type: Types::STRING,
        length: 2000,
    )]
    public string $text;
}
