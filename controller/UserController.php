<?php
require_once './model/User.php';
require_once './config/database.php';

class UserController {
    public function index() {
        $model = new User(Database::connect());
        $data = $model->getAll();
        include 'view/user/welcome.php';
    }

    public function create() {
        include 'view/user/create.php';
    }

    public function store() {
        $model = new User(Database::connect());
        $model->insert($_POST['nama']);
        header("Location: index.php");
    }

    public function edit($id) {
        $model = new User(Database::connect());
        $data = $model->getById($id);
        include './view/user/edit.php';
    }

    public function update($id) {
        $model = new User(Database::connect());
        $model->update($id, $_POST['nama']);
        header("Location: index.php");
    }

    public function delete($id) {
        $model = new User(Database::connect());
        $model->delete($id);
        header("Location: index.php");
    }
}
?>
