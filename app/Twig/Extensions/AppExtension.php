<?php

namespace App\Twig\Extensions;

use App\Services\User;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private User $user,
    ) {}

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('authenticated', fn(): bool => $this->user->in()),
            new \Twig\TwigFunction('user', fn(?string $field = null): mixed => $this->user->get($field)),
        ];
    }
}