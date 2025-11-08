<?php

namespace App\Twig\Extensions;

use App\Services\App;
use App\Services\User;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('authenticated', function() {
                return App::$instance->container->make(User::class)->in();
            }),
            new \Twig\TwigFunction('user', function(?string $field = '') {
                $user = App::$instance->container->make(User::class)->get();
                return $field === null ? $user : $user[$field];
            }),
        ];
    }
}