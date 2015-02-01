<?php
namespace Anax\WGTOTW;

/**
 * Model for Comments.
 *
 */
class Answer extends \Anax\MVC\CDatabaseModel
{

    public function findQuestion($questionId = null, $sort = "rating DESC, created") {
        $all = $this->query()
            ->orderBy($sort)
            ->where('question_id = "'. $questionId . '"')
            ->execute();

        return $all;
    }

    public function findUser($userId = null) {
        $all = $this->query()
            ->where('user_id = "'. $userId . '"')
            ->execute();

        return $all;
    }

    public function count($questionId = null) {
        $rows = $this->query('COUNT(question_id) as count')
                ->where('question_id = "' . $questionId . '"')
                ->execute();

        return $rows[0]->count;
    }

    public function countAcceptedAnswers($userId = null) {
        $rows = $this->query('COUNT(accepted) as count')
            ->where('user_id = "' . $userId . '"')
            ->andWhere("accepted = 1")
            ->execute();

        return $rows[0]->count;
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
            ->groupBy("question_id")
            ->orderBy("created")
            ->limit("3")
            ->execute();

        return $all;
    }
}