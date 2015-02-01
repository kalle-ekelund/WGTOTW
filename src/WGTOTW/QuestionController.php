<?php

namespace Anax\WGTOTW;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->questions = new \Anax\WGTOTW\Question();
        $this->questions->setDI($this->di);
        $this->answers = new \Anax\WGTOTW\Answer();
        $this->answers->setDI($this->di);
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        $this->questionToTag = new \Anax\WGTOTW\QuestionToTag();
        $this->questionToTag->setDI($this->di);
        $this->tags = new \Anax\WGTOTW\Tag();
        $this->tags->setDI($this->di);
        $this->comments = new \Phpmvc\Comment\Comment();
        $this->comments->setDI($this->di);
    }

    /**
     * View all questions.
     *
     * @return void
     */
    public function listAction($id = null, $user = null, $sort = "created DESC")
    {
        if($user){
            $questions = $this->questions->findByUser($id);
        } else{
            if($id) {
                $questionsByTag = $this->questionToTag->findQuestionByTag($id);
                $questions = array();

                foreach($questionsByTag as $qbt) {
                    $questions[] = $this->questions->findById($qbt->question_id)[0];
                }
            } else {
                $questions = $this->questions->findAll($sort);
            }
        }

        foreach($questions as $question) {
            $this->getTagsForQuestion($question);
            $question->answers = $this->answers->count($question->id);
            $question->user = $this->users->findById($question->user_id);
        }

        $this->theme->addStylesheet('css/questions.css');
        $this->theme->setTitle("List all questions");
        $this->views->add('questions/list-all', [
            'questions' => $questions
        ]);
    }

    public function viewAction($id = null, $sort = null)
    {
        if($id == null){
            $this->response->redirect($this->di->request->getCurrentUrl());
        }

        $question = $this->questions->find($id);
        $this->getTagsForQuestion($question);
        $comments = $this->comments->findByQuestion($id);
        foreach($comments as $comment) {
            $comment->user = $this->users->findById($comment->user_id)[0];
        }
        $this->UsersController->initialize();
        $user = $this->UsersController->getUser($question->user_id);

        if($sort){
            $sort = ($sort == 'created') ? 'created ASC, rating' : 'rating DESC, created';
            $answers = $this->answers->findQuestion($question->id, $sort);
        } else {
            $answers = $this->answers->findQuestion($question->id);
        }

        $numberOfAnswers = $this->answers->count($question->id);
        foreach($answers as $answer) {
            $this->UsersController->initialize();
            $answer->user = $this->UsersController->getUser($answer->user_id);
            $answer->comments = $this->comments->findByAnswer($answer->id);
            foreach($answer->comments as $comment) {
                $comment->user = $this->users->findById($comment->user_id)[0];
                //var_dump($comment->user);
            }
        }

        $this->AnswerController->initialize();
        $answerForm = $this->AnswerController->createAction($question->id);

        $this->theme->addStylesheet('css/form.css');
        $this->theme->addStylesheet('css/questions.css');
        $this->theme->addJavaScript('js/commentScript.js');
        $this->theme->setTitle($question->title);

        $this->di->views->add('questions/view', [
            'question' => $question,
            'user' => $user,
            'comments' => $comments,
            'numberOfAnswers' => $numberOfAnswers,
            'answers' => $answers,
            'answerForm' => $answerForm
        ]);
    }


    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($question = null, $tagString = null, $userId = null)
    {
        if(!$question) die("Missing comment");

        $this->questions->create($question);
        if(!empty($tagString))
        {
            $tagString = strtolower($tagString);
            $tags = preg_split('~,\s*~', $tagString);

            foreach($tags as $tag) {
                $res = $this->tags->findByTag($tag);
                if($res){
                    $this->questionToTag->create(['question_id' => $this->questions->id, 'tag_id' => $res[0]->id]);
                } else {
                    $this->tags->create(['tag' => $tag]);
                    $this->questionToTag->create(['question_id' => $this->questions->id, 'tag_id' => $this->tags->id]);
                }
            }
        }

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$userId, 25]
        ]);

        $this->response->redirect($this->url->create('question/view/' . $this->questions->id));
    }

    public function voteAction($vote = null, $questionId = null){
        if(!$vote) $this->response->redirect($this->url->create('question/view/' . $questionId));

        $question = $this->questions->find($questionId);
        $newRating = $question->rating + $vote;
        $this->questions->update(['rating' => $newRating]);

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'addRating',
            'params'     => [$question->user_id, $newRating]
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

    public function createAction(){
        $di = $this->di;
        $loginController = $di->get("LoginController");

        if($loginController->isLoggedInAction())
        {
            $form = new \Mos\HTMLForm\CForm([], [
                    'title' => [
                        'type'  => 'text',
                        'label' => 'Title',
                        'validation'  => ['not_empty']
                    ],
                    'tags' => [
                        'type'  => 'text',
                        'label' => 'Tags (Seperate tags with comma e.g. ps2,ps3)'
                    ],
                    'question' => [
                        'type'  => 'textarea',
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

                $question = [
                    'title' => $form->value('title'),
                    'question' => $form->value('question'),
                    'created' => $now,
                    'user_id' => $loginController->getId()
                ];

                $this->addAction($question, $form->value('tags'), $loginController->getId());

                $this->response->redirect($di->request->getCurrentUrl());

            } else if ($status === false) {
                $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
                header("Location: " . $di->request->getCurrentUrl());
            }

            $this->theme->addStylesheet('css/form.css');
            $this->theme->setTitle("Create a question");
            $this->di->views->add('questions/create', [
                'form' => $form->getHTML()
            ]);
        } else {
            $this->theme->setTitle("Not logged in");
            $content = $this->di->fileContent->get('notLoggedIn.md');
            $content = $this->di->textFilter->doFilter($content, 'shortcode, markdown');
            $this->di->views->add('me/page', [
                'content' => $content
            ]);
        }

    }

    public function editAction($id = null) {
        $question = $this->questions->find($id);

        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'title' => [
                    'type'  => 'text',
                    'label' => 'Title',
                    'value' => $question->title,
                    'validation'  => ['not_empty']
                ],
                'question' => [
                    'type'  => 'textarea',
                    'value' => $question->question,
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

            $this->questions->update([
                'question' => $form->value('question'),
                'title' => $form->value('title'),
                'updated' => $now,
            ]);

            $url = $this->url->create('question/view/' . $question->id);
            $this->response->redirect($url);

        } else if ($status === false) {
            $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }
        $this->theme->setTitle('Edit Question');
        $this->theme->addStylesheet('css/form.css');
        $this->views->addString("<article class='article1'><h1>Edit Question</h1>" . $form->getHTML(['novalidate' => true]) . "</article>", 'main');
    }

    private function getTagsForQuestion($question) {

        $this->di->db->select()
            ->from('questiontotag')
            ->where('question_id = "' . $question->id .'"');
        $this->di->db->execute($this->db->getSQL(), []);
        $this->di->db->setFetchModeClass(__CLASS__);

        $tags = $this->di->db->fetchAll();

        $tagArray = array();

        if(is_array($tags)){
            foreach($tags as $tag) {
                $this->di->db->select()
                    ->from('tag')
                    ->where('id = "' . $tag->tag_id .'"');
                $this->di->db->execute($this->db->getSQL(), []);
                $this->di->db->setFetchModeClass(__CLASS__);

                $tagName = $this->di->db->fetchOne();
                $tagArray[] = $tagName;

            }
        }

        $question->tags = $tagArray;
    }
}
