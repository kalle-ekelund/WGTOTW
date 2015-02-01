<?php
namespace Anax\WGTOTW;

/**
 * Model for Comments.
 *
 */
class Question extends \Anax\MVC\CDatabaseModel
{

    public function findById($id = null) {
        $all = $this->query()
            ->where('id = "'. $id . '"')
            ->orderBy("created DESC")
            ->execute();

        return $all;
    }

    public function findByUser($userId = null) {
        $all = $this->query()
            ->where('user_id = "'. $userId . '"')
            ->execute();

        return $all;
    }

    public function getTopThreeByUser($userId = null) {

        $all = $this->query()
            ->where('user_id = "'. $userId . '"')
            ->orderBy("created DESC")
            ->limit("3")
            ->execute();

        return $all;
    }

    public function countByUser($userId = null) {
        $rows = $this->query('COUNT(user_id) as count')
            ->where('user_id = "' . $userId . '"')
            ->execute();

        return $rows[0]->count;
    }

    public function findRecent($limit = "-1") {
        $all = $this->query()
            ->orderBy("created DESC")
            ->limit($limit)
            ->execute();

        return $all;
    }
}