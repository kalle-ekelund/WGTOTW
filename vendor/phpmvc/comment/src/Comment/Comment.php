<?php
namespace Phpmvc\Comment;

/**
 * Model for Comments.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel
{

    public function findByKey($key = null) {
        $all = $this->query()
            ->where('page_key = "'. $key . '"')
            ->execute();

        return $all;
    }

    public function findByQuestion($questionId = null) {
        $all = $this->query()
            ->where('question_id = "'. $questionId . '"')
            ->execute();

        return $all;
    }

    public function findByAnswer($answerId = null) {
        $all = $this->query()
            ->where('answer_id = "'. $answerId . '"')
            ->execute();

        return $all;
    }

    public function countByUser($userId = null) {
        $rows = $this->query('COUNT(user_id) as count')
            ->where('user_id = "' . $userId . '"')
            ->execute();

        return $rows[0]->count;
    }

    public function getTopThreeByUser($userId = null) {

        $all = $this->query()
            ->where('user_id = "'. $userId . '"')
            ->orderBy("created DESC")
            ->limit("3")
            ->execute();

        return $all;
    }
}