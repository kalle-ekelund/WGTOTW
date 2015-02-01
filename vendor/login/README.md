kaek14/login
=========
[![Build Status](https://travis-ci.org/kalle-ekelund/kaek14.svg)](https://travis-ci.org/kalle-ekelund/kaek14)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kalle-ekelund/kaek14/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kalle-ekelund/kaek14/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/kalle-ekelund/kaek14/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kalle-ekelund/kaek14/?branch=master)

PHP class for login handling in Anax-MVC

By Kalle Ekelund, kalle.ekelund.90@gmail.com


License
------------------

This software is free software and carries a MIT license.

Install
-----------------------------------

Before you install you will need to have downloaded ["Anax-MVC"](https://github.com/mosbth/Anax-MVC)  and ["CDatabase"](https://github.com/mosbth/cdatabase).

In order to use this module you will need to copy the files under src/ into Anax/src/Login (if no Login folder exists, create one)

In webroot you'll find a test file login-test.php that you can use to try the module out. Copy that into Anax/webroot.
Then you need to copy config_mysql.php into Anax/app/config and change the settings in the files to your database.
Last you'll need to copy the .tpl in views into Anax/app/view/login.

If you don't have a database already created, there is a setup-database.php that will do that for you. Just copy the file
into Anax/webroot and uncomment the require_once in login-test.php


History
-----------------------------------
v1.1 Added testing