<?php
/**
 * Created by PhpStorm.
 * User: Kalle Ekelund
 * Date: 2014-12-23
 * Time: 14:47
 */

namespace kaek14\Login;

/**
 * A controller to login a user onto your website
 */

class LoginController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        var_dump("Init");
        $this->users = new \kaek14\Login\User();
        $this->users->setDI($this->di);
    }

    public function loginAction() {

        $isPosted = $this->request->getPost('doLogin');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        var_dump("Got past doLogin");
        $res = $this->users->query()
                    ->where("acronym = '". $this->request->getPost('acronym')."'")
                    ->execute();

        if(!$res) {
            die("No user with that username is stored in the database");
            //$this->response->redirect($this->request->getPost('redirect'));
        }

        $verified = password_verify($this->request->getPost('password'), $res[0]->password);

        if(!$verified) {
            die("The password doesn't match");
            //$this->response->redirect($this->request->getPost('redirect'));
        }

        if($this->session->has('login')){
            $this->session->set('login', []);
        }

        $user = $this->session->get('login', []);
        $user[] = $res[0];
        $this->session->set('login', $user);

        $this->response->redirect($this->request->getPost('redirect'));

    }

    public function logoutAction() {
        $isPosted = $this->request->getPost('doLogout');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $this->session->set('login', []);
        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function isLoggedInAction() {
        $user = $this->session->get('login', []);

        if(empty($user)) {
            return false;
        } else {
            return true;
        }
    }

    public function viewAction(){
        $user = $this->session->get('login', []);

        $this->theme->setTitle("View profile");
        $this->views->add('login/view', [
            'user' => $user[0],
        ]);
    }

}