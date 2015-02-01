<?php

namespace kaek14\Login;

/**
 * @backupGlobals disabled
 */
class LoginSessionControllerTest extends \PHPUnit_Framework_TestCase {

    public function testLoggedInAction() {
        $login = new \kaek14\Login\LoginSessionController();
        $di    = new \Anax\DI\CDIFactoryDefault();
        $login->setDI($di);

        $di->setShared('session', function () {
            $session = new \Anax\Session\CSession();
            $session->configure(ANAX_APP_PATH . 'config/session.php');
            $session->name();
            return $session;
        });

        $res = $login->isLoggedInAction();
        $this->assertFalse($res, "User is not logged in");
    }

    public function testLoginAction()
    {
        $login = new \kaek14\Login\LoginSessionController();
        $di    = new \Anax\DI\CDIFactoryDefault();
        $login->setDI($di);

        $di->setShared('session', function () {
            $session = new \Anax\Session\CSession();
            $session->configure(ANAX_APP_PATH . 'config/session.php');
            $session->name();
            return $session;
        });

        $res = $login->loginAction();
        $this->assertFalse($res, "doLogin is in post");

        $_POST['doLogin'] = true;
        $_POST['acronym'] = 'admin1';
        $_POST['password'] = 'adminpw1';
        $res = $login->loginAction();
        $this->assertFalse($res, "User acronym is wrong");

        $_POST['acronym'] = 'admin';
        $res = $login->loginAction();
        $this->assertFalse($res, "User password is wrong");

        $_POST['password'] = 'adminpw';

        $login->loginAction();
        $res = $login->isLoggedInAction();
        $this->assertTrue($res, "User is not logged in");
    }

    public function testViewAction() {
        $login = new \kaek14\Login\LoginSessionController();
        $di    = new \Anax\DI\CDIFactoryDefault();
        $login->setDI($di);

        $di->setShared('session', function () {
            $session = new \Anax\Session\CSession();
            $session->configure(ANAX_APP_PATH . 'config/session.php');
            $session->name();
            return $session;
        });

        $res = $login->isLoggedInAction();
        $this->assertTrue($res, "User is not logged in");

        $res = $login->viewAction();
        $this->assertEquals("admin", $res['acronym'], "The acronym does not match.");
        $this->assertEquals("adminpw", $res['password'], "The password does not match.");
        $this->assertEquals("Adminstratör", $res['name'], "The name does not match.");
        $this->assertEquals("admin@login.se", $res['email'], "The email does not match.");
        $this->assertEquals("2014-12-12 13:43:24", $res['created'], "The created date does not match.");
        $this->assertNull($res['updated'], "The updated date is not null");
        $this->assertNull($res['deleted'], "The deleted date is not null");
        $this->assertNull($res['active'], "The active date is not null");
    }

    public function testLogoutAction() {
        $login = new \kaek14\Login\LoginSessionController();
        $di    = new \Anax\DI\CDIFactoryDefault();
        $login->setDI($di);

        $di->setShared('session', function () {
            $session = new \Anax\Session\CSession();
            $session->configure(ANAX_APP_PATH . 'config/session.php');
            $session->name();
            return $session;
        });

        $res = $login->isLoggedInAction();
        $this->assertTrue($res, "User is not logged in");

        $res = $login->logoutAction();
        $this->assertFalse($res, "doLogout is true");

        $_POST['doLogout'] = true;
        $login->logoutAction();
        $res = $login->isLoggedInAction();
        $this->assertFalse($res, "User is logged in");
    }
}