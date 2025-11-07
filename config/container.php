<?php

use App\Services\Auth;
use App\Services\Database;
use App\Services\User;
use App\Services\Users;
use App\Services\Documents;
use App\Services\Pictures;
use App\Services\Posts;

return [
    Database::class  => fn()  => new Database(connection: connect()),
    User::class      => DI\autowire(User::class),
    Users::class     => DI\autowire(Users::class),
    Documents::class => DI\autowire(Documents::class),
    Pictures::class  => DI\autowire(Pictures::class),
    Posts::class     => DI\autowire(Posts::class),
    Auth::class      => DI\autowire(Auth::class),
];