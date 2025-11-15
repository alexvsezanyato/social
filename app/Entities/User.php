<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\UserRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table('`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?string $login = null;

    #[ORM\Column]
    public ?int $age = null;

    #[ORM\Column]
    public ?string $hash = null;

    #[ORM\Column]
    public ?string $salt = null;

    #[ORM\Column(
        options: [
            'default' => '',
        ],
    )]
    public ?string $random = null;

    #[ORM\Column(
        options: [
            'default' => '',
        ],
    )]
    public string $public = '';

    #[ORM\OneToMany(
        targetEntity: Post::class,
        mappedBy: 'author',
    )]
    public Collection $posts;
}
