<?php
define('BASE_URL', 'http://localhost/Parte_UM');
define('TEMPLATES_PATH', __DIR__.'/../templates');

//require_once __DIR__.'/../includes/functions.php';
//require_once __DIR__.'/../includes/db.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$cleanPath = str_replace('/public', '', $path);

switch ("$method $cleanPath") {
    case 'GET /':
        include TEMPLATES_PATH.'/home.php';
        break;
        
    case 'GET /form':
        include TEMPLATES_PATH.'/form_task.php';
        break;
        
    case 'POST /form':
        require __DIR__.'/../includes/process_form.php';
        break;
        
    default:
        include TEMPLATES_PATH.'/home.php';
        break;
}
?>