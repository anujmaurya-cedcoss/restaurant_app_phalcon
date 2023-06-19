<?php

use Phalcon\Mvc\Controller;

session_start();
class ManagerController extends Controller
{
    public function indexAction()
    {
        // show all the available items in this shop

    }
    public function addItemAction()
    {
        if ($_POST['name'] != '' && $_POST['price'] > 0 && $_POST['cuisine'] != '' && $_POST['restID'] != '') {
            $_POST['itemID'] = uniqid();
            $output = $this->mongo->menu->insertOne($_POST);
            $success = $output->getInsertedCount();
            if ($success > 0) {
                $this->response->redirect('/manager/index');
            } else {
                echo "<h3>There was some error</h3>";
                die;
            }
        } else {
            echo "<h3>Please fill all the values correctly</h3>";
            die;
        }
    }

    public function menuAction()
    {
        $output = $this->mongo->menu->find();
        $data = [];
        foreach ($output as $value) {
            $data[] = $value;
        }
        $this->view->data = json_encode($data);
    }

    public function allOrdersAction()
    {
        $output = $this->mongo->orders->find();
        $data = [];
        foreach ($output as $value) {
            $data[] = $value;
        }
        $this->view->data = json_encode($data);
    }
    public function deleteAction()
    {
        $id = $_GET['id'];
        $output = $this->mongo->menu->deleteOne(['itemID' => (string)$id]);
        $success = $output->getDeletedCount();
        if ($success > 0) {
            $this->response->redirect('/manager/menu');
        } else {
            echo "<h3>There was some error</h3>";
            die;
        }
    }
}
