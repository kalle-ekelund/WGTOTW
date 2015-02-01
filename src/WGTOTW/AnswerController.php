<?php

namespace Anax\WGTOTW;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class AnswerController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->answers = new \Anax\WGTOTW\Answer();
        $this->answers->setDI($this->di);
    }

    /**
     * View all questions.
     *
     * @return void
     */
    public function listAction($id = null, $user = null)
    {
        if($user){
            $answers = $this->answers->findUser($id);
            $this->questions = new \Anax\WGTOTW\Question();
            $this->questions->setDI($this->di);
            foreach($answers as $answer) {
                $answer->title = $this->questions->find($answer->question_id)->title;
            }

            $totalOfAnswers = $this->answers->countByUser($id);
        }
        else
        {
            $answers = $this->answers->findQuestion($id);

            $totalOfAnswers = $this->answers->count($id);
        }


        foreach($answers as $answer) {
            $this->UsersController->initialize(); //DAFUQ?????
            $answer->user = $this->UsersController->getUser($answer->user_id);
        }

        $this->theme->setTitle($user . ' Answers');
        $this->di->views->add('answers/list-all',  [
            'answers' => $answers,
            'totalOfAnswers' => $totalOfAnswers
        ]);
    }

    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($answer = null, $userId = null)
    {
        if(!$answer) die("Missing answer");

        $this->answers->create($answer);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$userId, 50]
        ]);
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

    public function voteAction($vote = null, $questionId = null, $answerId = null){
        if(!$vote) $this->response->redirect($this->url->create('question/view/' . $questionId));

        $answer = $this->answers->find($answerId);
        $newRating = $answer->rating + $vote;
        $this->answers->update(['rating' => $newRating]);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$answer->user_id, $newRating]
        ]);

        $this->response->redirect($this->url->create('question/view/' . $questionId));
    }

    public function acceptAction($questionId = null, $answerId = null){
        $answer = $this->answers->find($answerId);
        $this->answers->update(['accepted' => true]);
        $this->questions = new \Anax\WGTOTW\Question();
        $this->questions->setDI($this->di);
        $this->questions->find($questionId);
        $this->questions->update(['accepted' => true]);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$answer->user_id, 100]
        ]);


        $this->response->redirect($this->url->create('question/view/' . $questionId));
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

    public function createAction($questionId = null){
        $di = $this->di;
        $loginController = $di->get("LoginController");

        if($loginController->isLoggedInAction())
        {
            $form = new \Mos\HTMLForm\CForm([], [
                    'answer' => [
                        'type'  => 'textarea',
                        'validation'  => ['not_empty']
                    ],
                    'submit' => [
                        'type'      => 'submit',
                        'value' => 'Send',
                        'callback'  => function($form) {
                                $form->saveInSession = true;
                                return true;
                            }
                    ],
                    'reset' => [
                        'type' => 'reset',
                        'value' => 'Reset'
                    ]
                ]
            );

            // Check the status of the form
            $status = $form->check();

            if ($status === true) {
                $now = date('Y-m-d H:i:s');

                $answer = [
                    'answer' => $form->value('answer'),
                    'created' => $now,
                    'question_id' => $questionId,
                    'user_id' => $loginController->getId()
                ];

                $this->addAction($answer, $loginController->getId());

                $this->response->redirect($this->di->request->getCurrentUrl());

            } else if ($status === false) {
                $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
                header("Location: " . $di->request->getCurrentUrl());
            }

            return [
                'title' => "Write an answer",
                'form' => $form->getHTML()
            ];
        } else {
            return [
                'title' => "You have to be logged in to answer",
                'form' => ""
            ];
        }
    }

    public function editAction($id = null, $questionId = null) {

        $answer = $this->answers->find($id);

        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'answer' => [
                    'type'  => 'textarea',
                    'value' => $answer->answer,
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
                    'value' => 'Reset'
                ]
            ]
        );

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $now = date('Y-m-d H:i:s');

            $this->answers->update([
                'answer' => $form->value('answer'),
                'updated' => $now,
            ]);

            $url = $this->url->create('question/view/' . $questionId);
            $this->response->redirect($url);

        } else if ($status === false) {
            $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }

        $this->theme->setTitle('Edit Answer');
        $this->theme->addStylesheet('css/form.css');
        $this->views->addString("<article class='article1'><h1>Edit Answer</h1>" . $form->getHTML(['novalidate' => true]) . "</article>", 'main');
    }
}
