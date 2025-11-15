<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\PictureRepository;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ORM\Table('`picture`')]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $pid = null;

    #[ORM\Column]
    public ?string $source = null;

    #[ORM\Column]
    public ?string $mime = null;

    #[ORM\Column]
    public ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(name: 'pid', referencedColumnName: 'id')]
    public Post $post;
}
