<?php

namespace App\Services;

class Posts {
    static function exists($id) {
        $pdo = connect();
        $statement = $pdo->prepare('select 1 from posts where authorid=? limit 1');
        $statement->execute([$id]);
        if (!$statement) return false;
        if ($statement->fetch()) return true;
        else return false;
    }

    static function fetch($id) {
        $pdo = connect();
        
        if (self::$posts === null) {
            $statement = $pdo->prepare('
                select *, 
                cast(createdat as date) as date, 
                cast(createdat as time) as time 
                from posts 
                where authorid=? 
                order by id desc
            ');

            // limit 10
            $statement->execute([$id]);
            if (!$statement) return null;

            if ($row = $statement->fetch()) {
                self::$posts = $statement;
                return $row;
            }
        } 
        else {
            $row = self::$posts->fetch();

            if (!$row) {
                self::$posts = null;
                return null;
            }

            return $row;
        }
    }

    public static $posts = null;
}
