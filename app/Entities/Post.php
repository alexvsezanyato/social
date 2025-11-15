<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repositories\PostRepository;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\ManyToOne(
        targetEntity: User::class,
        inversedBy: 'posts',
    )]
    #[ORM\JoinColumn(
        name: 'author_id',
        referencedColumnName: 'id',
    )]
    public User $author;

    #[ORM\OneToMany(
        targetEntity: Picture::class,
        mappedBy: 'post',
    )]
    public Collection $pictures;

    #[ORM\OneToMany(
        targetEntity: Document::class,
        mappedBy: 'post',
    )]
    public Collection $documents;

    #[ORM\OneToMany(
        targetEntity: PostComment::class,
        mappedBy: 'post',
    )]
    public Collection $comments;

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

        $this->pictures = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
}
