<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;

use App\Services\User;
use App\Services\Database;

class ProfileController
{
    public function index(User $user)
    {
        return new Response(view('home', [
            'user' => $user,
        ]));
    }

    public function settings(User $user)
    {
        return new Response(view('settings', [
            'user' => $user,
        ]));
    }
    public function apply(User $user, Database $database)
    {
        if (!$user->in()) {
            return new Response(1);
        }

        $public = $_POST['public'];

        if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
            return new Response(2);
        }

        $user = $user->get();
        $id = $user['id'];

        $statement = $database->connection->prepare(
            <<<SQL
            UPDATE "user" SET "public"=:public WHERE "id"=:id
            SQL
        );

        $statement->bindParam('public', $public);
        $statement->bindParam('id', $id);
        return new Response($statement->execute() ? 0 : 1);
    }
}
