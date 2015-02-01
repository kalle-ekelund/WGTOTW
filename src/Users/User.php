<?php
namespace Anax\Users;

/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{

    public function findById($id = null) {
        $all = $this->query()
            ->where('id = "'. $id . '"')
            ->execute();

        return $all;
    }

    public function findRanked($limit = '-1') {
        $all = $this->query()
            ->orderBy('rating DESC')
            ->limit($limit)
            ->execute();

        return $all;
    }

}