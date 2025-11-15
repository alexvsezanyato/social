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
        type: Types::STRING,
        length: 2000,
    )]
    public string $text;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(
        name: 'post_id',
        referencedColumnName: 'id',
        nullable: false,
    )]
    public Post $post;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(
        name: 'author_id',
        referencedColumnName: 'id',
        nullable: false,
    )]
    public User $author;
}
