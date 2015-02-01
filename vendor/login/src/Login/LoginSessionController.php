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

class LoginSessionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    private $usersArr = array(
    [
        'acronym'   => 'admin',
        'password'  => 'adminpw',
        'name'      => 'Adminstratör',
        'email'     => 'admin@login.se',
        'created'   => '2014-12-12 13:43:24',
        'updated'   => null,
        'deleted'   => null,
        'active'    => null
    ]);

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {

    }

    public function loginAction() {
        $isPosted = isset($_POST['doLogin']) ? $_POST['doLogin'] : null;

        if (!$isPosted) {
            return false;
        }

        if(!in_array($_POST['acronym'], $this->usersArr[0])) {
            return false;
        }

        if(!in_array($_POST['password'], $this->usersArr[0])) {
            return false;
        }

        if($this->session->has('login')){
            $this->session->set('login', []);
        }

        $user = $this->session->get('login', []);
        $user[] = $this->usersArr[0];
        $this->session->set('login', $user);
    }

    public function logoutAction() {
        $isPosted = isset($_POST['doLogout']) ? $_POST['doLogout'] : null;

        if (!$isPosted) {
            return false;
        }

        $this->session->set('login', []);

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

        return $user[0];

        $this->theme->setTitle("View profile");
        $this->views->add('login/view', [
            'user' => $user[0],
        ]);
    }

}