<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class' => 'App_hooks',
    'function' => 'is_offline',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params'	=> ''
);
$hook['post_controller_constructor'][] = array(
    'class' => 'App_hooks',
    'function' => 'redirect_ssl',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params'	=> ''
);
$hook['display_override'][] = array(
    'class' => 'App_hooks',
    'function' => 'is_compress',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks'
);
$hook['post_controller'][] = array(
    'class'    => 'App_hooks',
    'function' => 'log_queries',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks'
);
$hook['post_controller_constructor'][] = array(
    'class' => 'App_hooks',
    'function' => 'log_server',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks'
);
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
