<?php

namespace App\Controller;

use App\Utils\AppUtility;
use Cake\Event\Event;

class AdminController extends AppController
{
    public function index()
    {

    }

    public function beforeFilter(Event $event)
    {
        if (!$this->Session->check('store')) {
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }

        if (!$this->Session->check('user')) {
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }
    }
}

?>