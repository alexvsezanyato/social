<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class User {
    public function __construct(
        private Database $database,
        private SessionInterface $session,
        private Request $request,
    ) {}

    public function get(?string $field = null): mixed {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT * FROM "user" WHERE "id"=:id
            SQL
        );
        $id = $this->id();
        $statement->bindParam('id', $id);
        $result = $statement->execute();

        if (!$result) {
            return [];
        }

        $user = $statement->fetch();
        return $field === null ? $user : $user[$field];
    }

    public function in(): bool {
        $server = $this->request->server;
        $cookies = $this->request->cookies;

        $id = $this->session->get('id');
        $hash = $this->session->get('hash');

        $userAgent = $server->get('HTTP_USER_AGENT', 'agent');

        if ($id !== null && $hash === hash('md5', $userAgent)) { 
            return true; 
        }

        if (!$cookies->has('pid')) {
            return false;
        }

        [$id, $hash] = explode('-', $cookies->get('pid'));
        $user = $this->get();
        $random = $user['random'];

        if ($id !== null && $hash === hash('sha256', $id . $random)) {
            return true;
        }

        return false;
    }

    public function id(): ?int {
        $cookies = $this->request->cookies;

        $id = $this->session->get('id');

        if ($id !== null) {
            return $id;
        }

        if (!$cookies->has('pid')) {
            return null;
        }

        [$id] = explode('-', $cookies->get('pid'));

        if (!is_numeric($id)) {
            throw new \Exception('Invalid user ID in auth cookie');
        }

        return (int)$id;
    }
}
