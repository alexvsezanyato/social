<?php

namespace App\Http\Controllers\Api;

use App\Services\Users;
use App\Services\User;
use App\Services\Database;

class ProfileController
{
    public function index(Users $users)
    {
        return view('home', [
            'users' => $users,
        ]);
    }

    public function settings(Users $users)
    {
        return view('settings', [
            'users' => $users,
        ]);
    }
    public function apply(User $user, Database $database)
    {
        if (!$user->in()) {
            echo '1';
            exit;
        }

        $public = $_POST['public'];

        if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
            echo '2';
            exit;
        }

        $user = $user->get();
        $id = $user['id'];

        $statement = $database->connection->prepare(
            <<<SQL
            UPDATE "user" SET "public"=? WHERE "id"=?
            SQL
        );

        $result = $statement->execute([$public, $id]);

        if (!$result) {
            echo '1';
            exit;
        }
        else {
            echo '0';
            exit;
        }
    }
}
