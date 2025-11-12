<?php

namespace App\Twig\Extensions;

use App\Entities\User;
use App\Services\UserService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('authenticated', fn(): bool => $this->userService->isAuthenticated()),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'globals' => [
                'user' => $this->userService->getCurrentUser(),
            ],
        ];
    }
}
