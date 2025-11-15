<?php

namespace App\Entities;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repositories\PostRepository;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table('`post`')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?string $text = null;

    #[ORM\Column(
        name: 'author_id',
    )]
    public ?int $authorId = null;

    #[ORM\Column(
        name: 'created_at',
        type: Types::DATETIME_MUTABLE,
        nullable: false,
        options: [
            'default' => 'CURRENT_TIMESTAMP',
        ],
    )]
    public \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
