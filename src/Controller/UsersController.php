<?php

namespace App\Controller;

use App\Utils\AppUtility;
use Cake\Event\Event;


class UsersController extends AppController
{
    public function index()
    {

    }

    public function login(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if($session->check('admin')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }

        $storeId = $session->read('store');
        $store = $this->Stores->find('all')->where(['id' => $storeId])->toArray()[0];

        $usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role <=' => '3']]]);
        $users = array();
        foreach ($usersTmp as $userTmp) {
            $users[$userTmp->id] = $userTmp;
        }
        $arr = array();
        foreach ($users as $v) $arr[] = $v['role'];

        // array_multisortで'index'の列を昇順に並び替える
        @array_multisort($arr, SORT_ASC, $users);
        $this->set('users', $users);

        if($this->request->is('get')) {
            //$this->set('users', $this->Users->find('all')->where(['store' => $storeId]));
            $this->set('pwRequired', 'none');
            $this->set('userId', 0);
            $this->set('storeName', $store->name);
            $this->set('users', $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role' => '3']]]));
            $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
            $this->set('errmsg', '');
        } else if($this->request->is('post')){
            $receive = $this->request->getData();

            $userId = $receive['user-id'];
            $password = sha1($receive['password']);

            $user = $this->Users->find('all')->where(['deleted' => '0', 'id' => $userId])->toArray()[0];


            if($storeId == 1 && $user->role == 3){
                if($user['password'] == $password){
                    $session->write('user', $userId);
                    $session->write('role', $user['role']);

                    $this->redirect(['controller' => 'Stores', 'action' => 'Management']);
                    return;
                } else {
                    $this->set('userId', $userId);
                    $this->set('storeName', "管理者ツール");
                    $this->set('users', $this->Users->find('all')->where(['deleted' => '0', 'role' => '3']));
                    $this->set('pwRequired', 'block');
                    $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
                    $this->set('errmsg', 'パスワードが正しくありません。');
                }
            } else if($user['role'] == 1 && $user['require_password'] == false){
                //echo 'LOGIN SUCCESS';

                $session->write('user', $userId);
                $session->write('role', $user['role']);

                $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                return;
            } else {
                if($user['password'] == $password){
                    $session->write('user', $userId);
                    $session->write('role', $user['role']);

                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                } else {
                    //$this->set('users', $this->Users->find('all')->where(['deleted' => '0', 'store' => $storeId]));
                    $this->set('userId', $userId);
                    $p = 'none';
                    $this->set('users', $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role' => '3']]]));
                    $data = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role' => '3']]])->toArray()[0];
                    if($data['role'] >= 2 || $data['require_password'] == 1) $p = 'block';
                    $this->set('pwRequired', $p);
                    $this->set('storeName', $store->name);
                    $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
                    $this->set('errmsg', 'パスワードが正しくありません。');
                }
            }
        }
    }

    public function logout(){
        $this->viewBuilder()->setLayout(false);

        $this->request->getSession()->write('user', null);
        $this->response = $this->response->withCookie('viewMode', '0');
        $this->response = $this->response->withCookie('autoReload', '0');

        $this->redirect(array('action' => 'login'));
    }

    public function my()
    {
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if (!$session->check('store')) {
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if (!$session->check('user')) {
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }


        if($this->request->is('post')) {
            $this->autoRender = false;

            if (!$session->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }



        $my = $this->Users->get($session->read('user'));

        $this->set('user', $my->name);
        $this->set('userId', $session->read('user'));
        $this->set('role', AppUtility::getRoleText($my->role));
        $this->set('usepw', $my->require_password == 1 ? "する" : "しない");

        if ($this->request->is('post')) {
            $receive = $this->request->getData();

            if ($receive['action'] == 'edit-password') {

                $password = sha1($receive['password']);

                $my->password = $password;

                if ($this->Users->save($my)) {
                    echo '{"res":"ok"}';
                    return;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    return;
                }
            } else if ($receive['action'] == 'mode-change') {
                $my->require_password = $my->require_password == 1 ? 0 : 1;

                if ($this->Users->save($my)) {
                    echo '{"res":"ok"}';
                    //$this->redirect(['action' => 'my']);
                    return;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    return;
                }
            } else if ($receive['action'] == 'memo-update') {
                $my->memo = nl2br($receive['memo']);

                if ($this->Users->save($my)) {
                    echo '{"res":"ok"}';
                    //$this->redirect(['action' => 'my']);
                    return;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    return;
                }
            }
        }

        if($this->request->is('get')){
            {
                $my = $this->Users->find('all')->where(['deleted' => '0', 'id' => $session->read('user')])->toArray()[0];
                $this->set('my', $my);



                // サブ部門がある部門リスト
                $subDepsList = array_unique(
                    array_column(
                        $this->Departments->find('all')->where(['is_sub' => true, 'store_id' => $session->read('store')])->toArray(),
                        'parent'
                    )
                );

                $deps = $this->Departments->find('all')->where(['is_sub' => false, 'store_id' => $session->read('store')]);
                $departments = array();

                foreach($deps as $dep) {

                    $departments[$dep->dep_index] = $dep;

                    // サブ部門があるか？
                    if(in_array($dep->id, $subDepsList)){
                        // この部門の全サブ部門取得
                        $subDeps = $this->Departments->find('all')->where(
                            ['parent' => $dep->id, 'is_sub' => true, 'store_id' => $session->read('store')]
                        )->toArray();

                        // array_multisortの下準備
                        $i = 0;
                        foreach ($subDeps as $key => $value) {
                            $index[$key] = $value['dep_index'];
                            $i++;
                        }

                        // array_multisortで'index'の列を昇順に並び替える
                        @array_multisort($index, SORT_ASC, $subDeps);

                        if($i == 2){
                            if($subDeps[0]['dep_index'] > $subDeps[1]['dep_index']){
                                $_1 = $subDeps[1];
                                $_2 = $subDeps[0];

                                $subDeps[0] = $_1;
                                $subDeps[1] = $_2;
                            }
                        }

                        $departments[$dep->dep_index]['sub'] = $subDeps;
                    }
                }

                $arr = array();
                foreach ($departments as $v) $arr[] = $v['dep_index'];

                // array_multisortで'index'の列を昇順に並び替える
                @array_multisort($arr, SORT_ASC, $departments);

                $this->set('departments', $departments);
            }

            if($this->request->getQuery('req') == 'memo'){
                $this->autoRender = false;

                $s = $this->Users->find('all')->where(['id' => $session->read('user')])->toArray()[0]['memo'];

                //echo '{"res":"ok", "memo":"'.$s.'"}';
                echo $s;
                return;
            }
        }
    }

    public function add()
    {
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if(!$session->check('admin')){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            return;
        }

        if ($this->request->is('post')) {
            $this->autoRender = false;

            if (!$session->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }

        $this->set('done', false);

        if ($this->request->is('post')) {
            $receive = $this->request->getData();

            $new = $this->Users->newEntity();

            $new->name = str_replace('　', ' ', $receive['name']);
            $new->password = sha1($receive['password']);
            $new->role = 3;
            $new->department = 0;
            $new->deleted = 0;
            $new->require_password = 1;
            $new->store = 1;

            if ($this->Users->save($new)) {
                //echo '{"res":"ok"}';
                $this->autoRender = true;
                $this->set('done', true);
                //$this->redirect(['controller' => 'stores', 'action' => 'management']);
                //return;
            } else {
                echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                return;
            }

        }

    }


    public function edit()
    {
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if (!$session->check('admin')) {
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            return;
        }

        if ($this->request->is('post')) {
            $this->autoRender = false;

            if (!$session->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }

        $this->set('msg','');


        $usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $session->read('user')], ['role' => '3']]]);
        $users = array();
        foreach ($usersTmp as $userTmp) {
            $users[$userTmp->id] = $userTmp;
        }
        $this->set('users', $users);
    }


    public function beforeFilter(Event $event) {
    /*if($this->request->is('post')) {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = false;

        if (!$this->request->getSession()->check('store')) {
            echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
            exit;
        }
    }*/
    }
}

?>