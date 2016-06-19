<?php

// this is an example of legacy system, which has own auth mechanism and supported
// by AppBundle\Security\LegacyAuthenticator
session_start();

$_SESSION['userId'] = 123;
$_SESSION['username'] = 'fix';
$_SESSION['roles'] = [
    'old_role_admin',
    'old_role_user',
    'old_role_super_admin',
];

var_dump($_SESSION);