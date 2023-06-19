<?php

namespace handler\Listener;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

session_start();
class Listener extends injectable
{
    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {
        $acl = new Memory();
        /*
         * Add the roles
         */

        $acl->addRole('user');
        $acl->addRole('guest');
        /*
         * Add the Components
         */
        $acl->addComponent('index', ['index', 'signup', 'login', 'doLogin']);
        $acl->addComponent('chat', ['index', 'add', 'logout']);

        $acl->allow('user', 'index', '*');
        $acl->allow('user', 'chat', '*');
        $acl->allow('guest', '*', '*');
        $acl->deny('guest', 'chat', 'add');
        
        $role = "guest";
        if (isset($_SESSION['id'])) {
            $role = "user";
        }
        $controller = "index";
        $action = "index";

        if (!empty($dis->getControllerName())) {
            $controller = $dis->getControllerName();
        }
        if (!empty($dis->getActionName())) {
            $action = $dis->getActionName();
        }
        if (true === $acl->isAllowed($role, $controller, $action)) {
            if (file_exists(APP_PATH . "/controllers/$controller/")) {
                $this->response->redirect($controller / $action);
            } else {
                echo 'Access Granted :)';
            }
        } else {
            echo 'Access denied :(';
            die;
        }
    }
}
