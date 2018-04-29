<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [
    'Item' => [ // Controller
        ['index', '/', 'GET'], // action, url, method
        ['add', '/item/add', ['GET', 'POST']],
        ['edit', '/item/edit/{id:\d+}', ['GET', 'POST']],
        ['show', '/item/{id:\d+}', ['GET', 'POST']],
        ['delete', '/item/delete/{id:\d+}', 'GET'],
        ['search', '/item/search', 'GET'],

    ],

    'App' => [
        ['form', '/contact', ['GET', 'POST']],
        ['show', '/contact/show', ['GET', 'POST']],
        ['update', '/contact/update/{id:\d+}', ['GET', 'POST']],
        ['delete', '/contact/delete/{id:\d+}', 'GET'],
    ],

    'User' =>[
        ['signup', '/signup', ['GET', 'POST']],
        ['signin', '/signin', ['GET', 'POST']],
    ],
];
