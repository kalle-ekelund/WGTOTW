<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Phpmvc\Comment\Comment();
        $this->comments->setDI($this->di);
    }

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($key = null)
    {

        if($key) {
            $all = $this->comments->findByKey($key);
        } else {
            $all = $this->comments->findAll();
        }


        $this->views->add('comment/comments', [
            'comments' => $all,
        ]);
    }



    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction()
    {
        $isPosted = $this->request->getPost('doCreate');
        if(!$isPosted) die("Request error");

        $isAnswer = $this->request->getPost('answerId');
        $now = date('Y-m-d H:i:s');
        if(!$isAnswer){
            $comment = ['comment' => $this->request->getPost('comment'),
                        'created' => $now,
                        'user_id' => $this->request->getPost('userId'),
                        'question_id' => $this->request->getPost('questionId')];
        } else {
            $comment = ['comment' => $this->request->getPost('comment'),
                        'created' => $now,
                        'user_id' => $this->request->getPost('userId'),
                        'answer_id' => $this->request->getPost('answerId')];
        }

        $this->comments->create($comment);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$this->request->getPost('userId'), 25]
        ]);

        $this->response->redirect($this->url->create('question/view/' . $this->request->getPost('questionId')));
    }

    public function voteAction($vote = null, $questionId = null, $commentId = null){
        if(!$vote) $this->response->redirect($this->url->create('question/view/' . $questionId));

        $comment = $this->comments->find($commentId);
        $newRating = $comment->rating + $vote;
        $this->comments->update(['rating' => $newRating]);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$comment->user_id, $newRating]
        ]);

        $this->response->redirect($this->url->create('question/view/' . $questionId));
    }



    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->deleteAll($this->request->getPost('key'));

        $this->response->redirect($this->request->getPost('redirect'));
    }

    /**
     * Remove a comment
     *
     * @return void
     */
    public function removeAction($id = null, $key = null){
        if (!isset($id)) {
            die("Missing id");
        }

        $res = $this->comments->delete($id);

        $url = $this->url->create('' . $key);
        $this->response->redirect($url);
    }

    public function addFormAction($key = null){
        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'content' => [
                    'type'  => 'textarea',
                    'validation'  => ['not_empty']
                ],
                'name' => [
                    'type'  => 'text',
                    'label' => 'Namn',
                    'validation'  => ['not_empty']
                ],
                'submit' => [
                    'type'      => 'submit',
                    'value' => 'Skicka',
                    'callback'  => function($form) {
                            $form->saveInSession = true;
                            return true;
                        }
                ],
                'reset' => [
                    'type' => 'reset',
                    'value' => 'Återställ'
                ]
            ]
        );

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $now = date('Y-m-d H:i:s');

            $comment = [
                'content' => $form->value('content'),
                'name' => $form->value('name'),
                'created' => $now,
                'page_key' => $key
            ];

            $this->addAction($comment);

            $this->response->redirect($di->request->getCurrentUrl());

        } else if ($status === false) {
            $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }

        $this->di->views->add('comment/form', [
            'title' => "Try out a form using CForm",
            'form' => $form->getHTML()
        ]);
    }

    public function editAction($id = null, $questionId = null) {

        $c = $this->comments->find($id);

        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'comment' => [
                    'type'  => 'textarea',
                    'value' => $c->comment,
                    'validation'  => ['not_empty']
                ],
                'submit' => [
                    'type'      => 'submit',
                    'value' => 'Update',
                    'callback'  => function($form) {
                            $form->saveInSession = true;
                            return true;
                        }
                ],
                'reset' => [
                    'type' => 'reset',
                    'value' => 'Återställ'
                ]
            ]
        );

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $now = date('Y-m-d H:i:s');

            $this->comments->update([
                'comment' => $form->value('comment'),
                'updated' => $now,
            ]);

            $this->response->redirect($this->url->create('question/view/' . $questionId));

        } else if ($status === false) {
            $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }

        $this->theme->addStylesheet('css/form.css');
        $this->theme->setTitle("Edit comment");
        $this->di->views->add('comment/edit', [
            'title' => "Edit comment",
            'form' => $form->getHTML()
        ]);
    }
}
