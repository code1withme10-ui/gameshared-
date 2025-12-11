<?php
/**
 * routes.php
 * Maps pages to views and optional controllers
 */

$routes = [
    'home' => ['view' => 'home'],
    'about' => ['view' => 'about'],
    'gallery' => ['view' => 'gallery'],
    'code_of_conduct' => ['view' => 'code_of_conduct'],
    'help' => ['view' => 'help'],
    'admission' => ['view' => 'admission', 'controller' => 'ParentController'],
    'login' => ['view' => 'login', 'controller' => null],
    'logout' => ['view' => 'logout'],
    'parent_dashboard' => ['view' => 'parent_dashboard', 'controller' => 'ParentController'],
    'progress_report' => ['view' => 'progress_report', 'controller' => 'ParentController'],
    'headmaster_dashboard' => ['view' => 'headmaster_dashboard', 'controller' => 'HeadmasterController']
];


