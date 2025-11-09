<?php

namespace App\Twig\Extensions;

use App\Services\UserService;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('authenticated', fn(): bool => $this->userService->isAuthenticated()),
            new \Twig\TwigFunction('user', fn(?string $field = null): mixed => $this->userService->getCurrentUser()->{$field}),
        ];
    }
}