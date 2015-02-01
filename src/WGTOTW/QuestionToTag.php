<?php
namespace Anax\WGTOTW;

/**
 * Model for Comments.
 *
 */
class QuestionToTag extends \Anax\MVC\CDatabaseModel
{
    public function findQuestionByTag($tagId = null) {
        $all = $this->query()
            ->where('tag_id = "'. $tagId . '"')
            ->execute();

        return $all;
    }

    public function countTag($tagId) {
        $rows = $this->query('COUNT(tag_id) as count')
            ->where('tag_id = "' . $tagId . '"')
            ->execute();

        return $rows[0]->count;
    }

    public function findPopularTags($limit = "-1") {
        $all = $this->query("tag_id, COUNT(tag_id) as count")
            ->orderBy('tag_id ASC')
            ->groupBy("tag_id")
            ->limit($limit)
            ->execute();

        return $all;
    }
}