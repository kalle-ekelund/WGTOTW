<?php
/**
 * Config-file for navigation bar.
 *
 */
$login = $this->di->LoginController->isLoggedInAction() ?
    [
        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Home route of current frontcontroller'
        ],

        // This is a menu item
        'questions'  => [
            'text'  => 'Questions',
            'url'   => 'question/list',
            'title' => 'Search through the latest questions asked',
        ],

        // This is a menu item
        'tags' => [
            'text'  => 'Tags',
            'url'   => 'tags',
            'title' => 'Find questions through tags'
        ],
        'users' => [
            'text'  => 'Users',
            'url'   => 'users/list',
            'title' => 'List of users active on the site'
        ],
        'about' => [
            'text'  => 'About',
            'url'   => 'about',
            'title' => "About this site and it's creator"
        ],
        'profile'  => [
            'text'  => 'Profile',
            'url'   => 'users/id/' . $this->di->LoginController->getId(),
            'title' => 'View your profile',
        ],
        'logout'  => [
            'text'  => 'Logout',
            'url'   => 'login/logoutSimple',
            'title' => 'Logout from your account',
        ]
    ] :
    [
        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Home route of current frontcontroller'
        ],

        // This is a menu item
        'questions'  => [
            'text'  => 'Questions',
            'url'   => 'question/list',
            'title' => 'Search through the latest questions asked',
        ],

        // This is a menu item
        'tags' => [
            'text'  => 'Tags',
            'url'   => 'tags',
            'title' => 'Find questions through tags'
        ],
        'users' => [
            'text'  => 'Users',
            'url'   => 'users/list',
            'title' => 'List of users active on the site'
        ],
        'about' => [
            'text'  => 'About',
            'url'   => 'about',
            'title' => "About this site and it's creator"
        ],
        'login'  => [
            'text'  => 'Login',
            'url'   => 'login/addForm',
            'title' => 'Login to your account to ask or answer questions',
        ],
        'sign_up'  => [
            'text'  => 'Sign up',
            'url'   => 'users/add',
            'title' => 'Sign up to our website',
        ]
    ];

return [
    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => $login,

    // Callback tracing the current selected menu item base on scriptname
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
];