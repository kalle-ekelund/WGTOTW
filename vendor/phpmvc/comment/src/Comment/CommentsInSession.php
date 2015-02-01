<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment)
    {
        $comments = $this->session->get($comment['key'], []);

        // If we have no comments stored add id zero otherwise
        // take the last comments id + 1
        if(!empty($comments))
        {
            $lastComment = end($comments);
            $comment['id'] = $lastComment['id'] + 1;
            reset($comments);
        } else {
            $comment['id'] = 0;
        }

        $comments[] = $comment;
        $this->session->set($comment['key'], $comments);
    }



    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll($key)
    {
        return $this->session->get($key, []);
    }



    /**
     * Delete all comments.
     *
     * @return void
     */
    public function deleteAll($key)
    {
        $this->session->set($key, []);
    }

    /**
     * Delete a comment.
     *
     * @return void
     */
    public function delete($key, $id)
    {
        $comments = $this->session->get($key, []);

        foreach ($comments as $index => $comment) {
            if ($comment['id'] == $id) {
                unset($comments[$index]);
                break;
            }
        }
        $this->session->set($key, $comments);

    }

    /**
     * Find a comment.
     *
     * @return array with comment
     */

    public function find($key, $id) {
        $comments = $this->session->get($key, []);

        foreach ($comments as $index => $comment) {
            if ($comment['id'] == $id) {
                return $comment;
            }
        }

        return array();
    }

    /**
     * Save a comment that have been edited
     *
     * @param array $comment with details of the comment
     * @param string $key the page we want comments from
     * @param int $id the id of the comment
     *
     *
     * @return void
     */

    public function save($comment, $key, $id) {
        $comments = $this->session->get($key, []);

        foreach ($comments as $index => $com) {
            if ($com['id'] == $id) {
                $comments[$index] = array_replace($comments[$index], $comment);
                break;
            }
        }

        $this->session->set($key, $comments);
    }
}
