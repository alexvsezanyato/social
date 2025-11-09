<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\DocumentRepository;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table('`document`')]
class Document
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
}
