<?php
namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }


    /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $all = $this->users->findAll();

        $this->theme->addStylesheet('css/user.css');
        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users",
        ]);
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);

        $this->questions = new \Anax\WGTOTW\Question();
        $this->questions->setDI($this->di);
        $questions = $this->questions->getTopThreeByUser($id);
        $totalOfQuestions = $this->questions->countByUser($id);

        $this->answers = new \Anax\WGTOTW\Answer();
        $this->answers->setDI($this->di);
        $answers = $this->answers->getTopThreeByUser($id);

        foreach($answers as $answer){
            $answer->title = $this->questions->find($answer->question_id)->title;
        }
        $totalOfAnswers = $this->answers->countByUser($id);
        $totalOfAcceptedAnswers = $this->answers->countAcceptedAnswers($id);

        $this->comments = new \Phpmvc\Comment\Comment();
        $this->comments->setDI($this->di);
        $totalOfComments = $this->comments->countByUser($id);

        $this->theme->addStylesheet('css/user.css');
        $this->theme->setTitle("View profile");
        $this->views->add('users/view', [
            'user' => $user,
            'questions' => $questions,
            'totalOfQuestions' => $totalOfQuestions,
            'answers' => $answers,
            'totalOfAnswers' => $totalOfAnswers,
            'totalOfComments' => $totalOfComments,
            'totalOfAcceptedAnswers' => $totalOfAcceptedAnswers
        ]);
    }

    public function getUser($id = null)
    {
        return $user = $this->users->find($id);
    }

    /**
     * Add new user.
     *
     * @return void
     */
    public function addAction()
    {
        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'name' => [
                    'type'  => 'text',
                    'label' => 'Name',
                    'validation'  => ['not_empty']
                ],
                'acronym' => [
                    'type'  => 'text',
                    'label' => 'Username',
                    'validation'  => ['not_empty']
                ],
                'email' => [
                    'type'  => 'email',
                    'label' => 'E-mail',
                    'validation'  => ['not_empty', 'email_adress']
                ],
                'password' => [
                    'type'  => 'password',
                    'label' => 'Password',
                    'validation'  => ['not_empty',
                        'custom_test' => [
                            'message' => 'Password needs to be more than 4 characters',
                            'test' => function($value) {
                                    if (strlen($value) < 4) return false;
                                    return true;
                                }
                        ]
                    ]
                ],
                'submit' => [
                    'type'      => 'submit',
                    'callback'  => function($form) {
                            $form->saveInSession = true;
                            return true;
                        }
                ]
            ]
        );

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $now = date('Y-m-d H:i:s');

            $this->users->create([
                'acronym' => $form->value('acronym'),
                'email' => $form->value('email'),
                'name' => $form->value('name'),
                'password' => password_hash($form->value('password'), PASSWORD_DEFAULT),
                'created' => $now,
                'active' => $now
            ]);


            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);

        } else if ($status === false) {
            //$form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }
        $this->theme->addStylesheet('css/form.css');
        $this->theme->setTitle('Sign up');
        $this->views->addString("<article class='article1'><h1>Sign up</h1>" . $form->getHTML(['novalidate' => true]) . "</article>", 'main');
    }

    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $res = $this->users->delete($id);

        $url = $this->url->create('users');
        $this->response->redirect($url);
    }

    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->deleted = $now;
        $user->save();

        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * Takes back a suer from the dead.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function restoreAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);

        $user->deleted = null;
        $user->save();

        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();

        $this->theme->setTitle("Aktiva användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Aktiva användare",
        ]);
    }

    /**
     * Activates an inactive user
     *
     * @return void
     */
    public function activateAction($id =null) {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->active = $now;
        $user->save();

        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * List all inactive and deleted users.
     *
     * @return void
     */
    public function inactiveAction()
    {
        $all = $this->users->query()
            ->where('active IS NULL')
            ->andWhere('deleted is NULL')
            ->execute();

        $this->theme->setTitle("Inaktiva användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Inaktiva användare",
        ]);
    }

    /**
     * Deactivates an active user
     *
     * @return void
     */
    public function deactivateAction($id =null) {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);

        $user->active = null;
        $user->save();

        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }

    /**
     * List all inactive and deleted users.
     *
     * @return void
     */
    public function trashcanAction()
    {
        $all = $this->users->query()
            ->where('deleted is NOT NULL')
            ->execute();

        $this->theme->setTitle("Kastade användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Användare som har kastats",
        ]);
    }

    /**
     * Edit user information
     *
     * @return void
     */
    public function editAction($id = null ) {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);

        $di = $this->di;

        $form = new \Mos\HTMLForm\CForm([], [
                'name' => [
                    'type'  => 'text',
                    'value' => $user->name,
                    'label' => 'Name',
                    'validation'  => ['not_empty']
                ],
                'email' => [
                    'type'  => 'email',
                    'value' => $user->email,
                    'label' => 'E-mail',
                    'validation'  => ['not_empty', 'email_adress']
                ],
                'password' => [
                    'type'  => 'password',
                    'value' => $user->password,
                    'label' => 'Password',
                    'validation'  => [
                        'custom_test' => [
                            'message' => 'Lösenordet måste vara minst 4 tecken långt och innehålla minst en siffra',
                            'test' => function($value) {
                                    if (strlen($value) < 4) return false;
                                    if (!preg_match("/\d/", $value)) return false;
                                    return true;
                                }
                        ]
                    ]
                ],
                'submit' => [
                    'type'      => 'submit',
                    'callback'  => function($form) {
                            $form->saveInSession = true;
                            return true;
                        }
                ]
            ]
        );

        // Check the status of the form
        $status = $form->check();

        if ($status === true) {
            $now = date('Y-m-d H:i:s');

            $this->users->update([
                'email' => $form->value('email'),
                'name' => $form->value('name'),
                'password' => password_hash($form->value('password'), PASSWORD_DEFAULT),
                'updated' => $now,
            ]);


            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);

        } else if ($status === false) {
            $form->AddOutput("<h2>Hoppsan!</h2><p>Ett fel uppstod. Kontrollera att du fyllt i formuläret på rätt sätt.</p>", 'gw');
            header("Location: " . $di->request->getCurrentUrl());
        }
        $this->theme->setTitle('Edit User');
        $this->theme->addStylesheet('css/form.css');
        $this->views->addString("<article class='article1'><h1>Edit User</h1>" . $form->getHTML(['novalidate' => true]) . "</article>", 'main');

    }

    public function addRatingAction($userId = null, $rating = 0) {
        $user = $this->users->find($userId);
        $newRating = $user->rating + $rating;
        $this->users->update(['rating' => $newRating]);
    }

}

