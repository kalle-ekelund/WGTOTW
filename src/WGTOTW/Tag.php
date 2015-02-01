<?php
namespace Anax\WGTOTW;

/**
 * Model for Comments.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel
{
    public function findById($id = null) {
        $all = $this->query()
            ->where('id = "'. $id . '"')
            ->execute();

        return $all;
    }

    public function findByTag($tag = null) {
        $all = $this->query()
            ->where('tag = "'. $tag . '"')
            ->execute();

        return $all;
    }

    public function countQuestion($questionId = null) {
        $rows = $this->query('COUNT(question_id) as count')
            ->where('question_id = "' . $questionId . '"')
            ->execute();

        return $rows[0]->count;
    }
}