<?php

// this is an example of legacy system, which has own auth mechanism and supported
// by AppBundle\Security\LegacyAuthenticator
session_start();

$_SESSION['userId'] = 123;
$_SESSION['username'] = 'fix';
$_SESSION['roles'] = array(1,2,3);

var_dump($_SESSION);