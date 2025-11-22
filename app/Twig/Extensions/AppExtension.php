<?php

namespace App\Twig\Extensions;

use App\Services\UserService;
use App\Services\Vite\ViteInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private UserService $userService,
        private ViteInterface $vite,
    ) {
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('authenticated', [$this->userService, 'isAuthenticated']),
            new \Twig\TwigFunction('vite', [$this->vite, 'asset']),
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
