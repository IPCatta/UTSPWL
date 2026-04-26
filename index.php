<?php
require_once './controller/UserController.php';

$act = $_GET['act'] ?? $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

$controller = new UserController();

switch ($act) {
    case 'create': $controller->create(); break;
    case 'store': $controller->store(); break;
    case 'edit': $controller->edit($id); break;
    case 'update': $controller->update($id); break;
    case 'delete': $controller->delete($id); break;
    default: $controller->index();
}
?>