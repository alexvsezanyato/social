<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\UserRepository;

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

    #[ORM\Column]
    public ?string $random = null;

    #[ORM\Column]
    public ?string $public = null;
}
