<?php

use Phalcon\Mvc\Controller;

class UsersController extends Controller
{
    public function indexAction()
    {
        $output = $this->mongo->menu->find();
        $data = [];
        $cuisine = [];
        foreach ($output as $value) {
            $data[] = $value;
            $cuisine[] = $value['cuisine'];
        }
        $this->view->data = json_encode($data);
        $this->view->cuisine = json_encode($cuisine);
    }

    public function orderAction()
    {
        $id = $_GET['id'];
        $uid = $_SESSION['uid'];
        $uname = $_SESSION['name'];
        $arr = [
            'itemID' => $id,
            'uid' => $uid,
            'uname' => $uname,
            'status' => 'placed',

        ];
        $output = $this->mongo->orders->insertOne($arr);
        $success = $output->getInsertedCount();
        if ($success > 0) {
            $this->response->redirect("/users/review?id=$id");
        } else {
            echo "<h3>There was some error</h3>";
            die;
        }
    }

    public function reviewAction()
    {
        $uid = $_SESSION['id'];
        $id = $_GET['id'];
        echo $id;
        die;
    }

    public function cuisineAction()
    {
        $cuisine = $_GET['cuisine'];
        $output = $this->mongo->menu->find(['cuisine' => $cuisine]);
        $data = [];
        foreach ($output as $value) {
            $data[] = $value;
        }
        $this->view->data = json_encode($data);
    }
}
