<?php

namespace App\Controller;

use App\Utils\AppUtility;
use Cake\Event\Event;

ini_set('display_errors', 0);
class StoresController extends AppController
{
    public $area;

    public function index()
    {

    }

    public function login(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        $this->set('area', $this->area);

        if($this->request->is('get')) {
            $this->set('stores', $this->Stores->find('all')->where(['id >=' => '2']));
            $this->set('storeParentName', $this->Stores->find('all')->where(['id' => '1'])->toArray()[0]['name']);
            $this->set('storeId', 1);
            $this->set('errmsg', '');
        } else if($this->request->is('post')){
            $receive = $this->request->getData();

            $storeId = $receive['store-id'];
            $password = sha1($receive['password']);

            $store = $this->Stores->find('all')->where(['id' => $storeId])->toArray()[0];

            // 書き
            $this->response = $this->response->withCookie("store", $storeId);

            if($store['password'] == $password){
                if($session->check('admin')) $session->delete('admin');

                $session->write('store', $storeId);
                $session->write('user', null);
                $session->write('role', 99);

                if($storeId == 1){
                    $session->write('admin', 1);
                    $session->write('user', 0);
                    $this->redirect(['controller' => 'stores', 'action' => 'management']);
                    return;
                }

                $this->redirect(['controller' => 'tasks', 'action' => 'view']);
                return;
            } else {
                $this->set('stores', $this->Stores->find('all'));
                $this->set('storeParentName', $this->Stores->find('all')->where(['id' => '1'])->toArray()[0]['name']);
                $this->set('storeId', $storeId);
                $this->set('errmsg', 'パスワードが正しくありません。');
            }
        }
    }

    public function logout(){
        $this->viewBuilder()->setLayout(false);

        $this->request->getSession()->destroy();

        $this->redirect(array('action' => 'login'));
    }

    public function admin(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();


        if($this->request->is('post')) {
            $this->viewBuilder()->setLayout(false);
            $this->autoRender = false;

            if (!$session->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                return;
            }
        }


        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('user')){
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }
        if($session->read('role') < 2){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            return;
        }


        $storeId = $session->read('store');
        $store = $this->Stores->find('all')->where(['id' => $storeId])->toArray()[0];

        $this->set('storeName', $store->name);
        $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
    }

    public function edit(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();


        /*if(!$session->check('admin')){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            return;
        }*/


        if($this->request->is('post')) {
            $this->autoRender = false;

            if (!$this->request->getSession()->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }

        $this->set('msg', '');

        $this->set('stores', $this->Stores->find('all')->where(['id >=' => '2']));
        $this->set('area', $this->area);


        if($this->request->is('post')) {
            $this->autoRender = false;
            $receive = $this->request->getData();

            if($receive['action'] == 'store-name-change'){
                $store = $this->Stores->get($receive['id']);

                $store->name = str_replace('　', ' ', $receive['name']);;

                if($this->Stores->save($store)){
                    $this->set('msg', "店舗名を変更しました");
                    $this->autoRender = true;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                }
            }
            else if($receive['action'] == 'store-password-change'){
                $store = $this->Stores->get($receive['id']);

                $store->password = sha1($receive['password']);

                if($this->Stores->save($store)){
                    $this->set('msg', "パスワードを変更しました");
                    $this->autoRender = true;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                }
            }
            else if($receive['action'] == 'delete-store'){
                $store = $this->Stores->get($receive['id']);

                $name = $store->name;

                if($this->Stores->delete($store)){
                    $users = $this->Users->find('all')->where(['store' => $receive['id']]);
                    $deps = $this->Departments->find('all')->where(['store_id' => $receive['id']]);

                    foreach($users as $user){
                        $this->Users->delete($this->Users->get($user->id));
                    }
                    foreach($deps as $dep){
                        $this->Departments->delete($this->Departments->get($dep->id));
                    }

                    $this->set('msg', $name."を削除しました");
                    $this->autoRender = true;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                }
            }
            else if($receive['action'] == 'add'){
                $newDep = $this->Departments->newEntity();


                $index = null;
                $parent = null;
                $is_sub = null;

                if($receive['parent'] != 0){
                    $res = $this->Departments->find('all')
                        ->where(['is_sub' => 1])
                        ->where(['parent' => $receive['parent']])
                        //->where(['deleted' => 0])
                        ->where(['store_id' => $session->read('store')])
                        ->order(['dep_index' => 'DESC'])
                        ->limit(1)->toArray();

                    $id = (intval($res[0]['dep_index'])+1);

                    $index = $id;
                    $parent = $receive['parent'];
                    $is_sub = 1;
                } else {
                    $res = $this->Departments->find('all')
                        ->where(['is_sub' => 0])
                        ->where(['parent' => 0])
                        //->where(['deleted' => 0])
                        ->where(['store_id' => $session->read('store')])
                        ->order(['dep_index' => 'DESC'])
                        ->limit(1)->toArray();

                    $id = (intval($res[0]['dep_index'])+1);

                    $index = $id;
                    $parent = 0;
                    $is_sub = 0;
                }


                $newDep->name = htmlspecialchars($receive['name']);
                $newDep->dep_index = $index;
                $newDep->is_sub = $is_sub;
                $newDep->parent = $parent;
                $newDep->store_id = $session->read('store');


                //データを保存する
                if ($this->Departments->save($newDep)) {
                    $res = array();
                    $res[0] = $newDep;

                    if($is_sub == 0){

                        $newSubDep = $this->Departments->newEntity();
                        $newSubDep->name = "全般";
                        $newSubDep->dep_index = 1;
                        $newSubDep->is_sub = 1;
                        $newSubDep->parent = $newDep->id;
                        $newSubDep->store_id = $session->read('store');

                        $this->Departments->save($newSubDep);

                        $res[1] = $newSubDep;
                    }

                    echo json_encode($res);
                    //echo $newDep->name;
                    return;
                }

            } else if($receive['action'] == 'edit-name'){
                $dep = $this->Departments->get($receive['id']);

                $dep->name = $receive['name'];

                if($this->Departments->save($dep)){
                    echo $dep->name;
                } else {
                    echo 'false';
                }

                return;
            } else if($receive['action'] == 'change-index'){
                $depReplace = $this->Departments->get($receive['replace']);
                $depTarget = $this->Departments->get($receive['target']);

                $replace = $depTarget->dep_index;
                $target = $depReplace->dep_index;

                $depReplace->dep_index = $replace;
                $depTarget->dep_index = $target;

                if($this->Departments->save($depReplace) && $this->Departments->save($depTarget)) {
                    echo 'true';
                } else {
                    echo 'false';
                }

                return;
            } else if($receive['action'] == 'delete'){
                $dep = $this->Departments->get($receive['id']);

                $session = $this->request->getSession();

                $storeId = $session->read('store');

                $usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'store' => $storeId]);
                $users = array();
                foreach ($usersTmp as $userTmp) {
                    $users[$userTmp->id] = $userTmp;
                }


                $del = array();

                $j = 0;
                if(!$dep->is_sub){
                    $subDeps = $this->Departments->find('all')->where(['is_sub' => '1', 'parent' => $receive['id'], 'store_id' => $storeId]);

                    foreach ($subDeps as $subDep) {
                        $a = $this->Departments->get($subDep->id);
                        $del[$j] = $subDep->id;
                        $this->Departments->delete($a);
                        $j++;
                    }
                }

                $flag = false;

                foreach($users as $user){
                    $deplist = explode(',', $user->department);

                    $k = 0;
                    foreach($deplist as $d){

                        if (in_array($d, $del)) {
                            unset($deplist[$k]);
                            echo 'hey';
                            debug($d);
                            $flag = true;
                        } else if ($d == $receive['id']) {
                            unset($deplist[$k]);
                            $flag = true;
                        }

                        $k++;
                    }

                    if($flag){
                        $_user = $this->Users->get($user->id);
                        $_user->department = implode(",", $deplist);
                        $this->Users->save($_user);
                    }
                }




                if($this->Departments->delete($dep)){
                    echo 'true';
                } else {
                    echo 'false';
                }

                return;
            }

            if($receive['action'] == 'company-name-change'){
                $store = $this->Stores->get(1);

                $store->name = str_replace('　', ' ', $receive['name']);;

                if($this->Stores->save($store)){
                    $this->autoRender = false;
                    echo '{"res":"ok"}';
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                }
            }
        }
    }

    public function user(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if($this->request->is('post')) {
            $this->autoRender = false;

            /*if (!$session->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }*/
        }


        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('user')){
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }
        if($session->read('role') < 2){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            exit;
        }

        $storeId = $session->read('store');

        $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
        $this->set('storeName', $this->Stores->find('all')->where(['id' => $session->read('store')])->toArray()[0]['name']);




        // サブ部門がある部門リスト
        $subDepsList = array_unique(
            array_column(
                $this->Departments->find('all')->where(['is_sub' => true, 'store_id' => $storeId])->toArray(),
                'parent'
            )
        );

        $deps = $this->Departments->find('all')->where(['is_sub' => false, 'store_id' => $storeId]);
        $departments = array();

        foreach($deps as $dep) {

            $departments[$dep->dep_index] = $dep;

            // サブ部門があるか？
            if(in_array($dep->id, $subDepsList)){
                // この部門の全サブ部門取得
                $subDeps = $this->Departments->find('all')->where(
                    ['parent' => $dep->id, 'is_sub' => true, 'store_id' => $storeId]
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



        if($this->request->is('post')) {
            $receive = $this->request->getData();

            if($receive['action'] == 'edit-role'){
                $user = $this->Users->get($receive['edit-id']);

                $user->role = $receive['role'] == 2 ? 1 : 2;

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit;
                }
            } else if($receive['action'] == 'add'){
                $user = $this->Users->newEntity();

                $user->name = str_replace('　', ' ', $receive['name']);
                $user->role = $receive['role'];
                $user->require_password = $receive['require_password'];
                $user->department = implode(',', $receive['department']);
                $user->password = sha1($receive['password']);
                $user->deleted = 0;
                $user->store = $session->read('store');

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit;
                }
            } else if($receive['action'] == 'delete-user') {
                $user = $this->Users->get($receive['id']);

                $user->deleted = 1;
                $user->name .= "(退職済)";

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit;
                }
            } else if($receive['action'] == 'edit-name') {
                $user = $this->Users->get($receive['id']);

                $user->name = str_replace('　', ' ', $receive['name']);

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit;
                } else {
                    echo '{"res":"error", "message":""}';
                    exit;
                }
            } else if($receive['action'] == 'edit-password') {
                $user = $this->Users->get($receive['id']);

                $user->password = sha1($receive['password']);

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit();
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit();
                }
            } else if($receive['action'] == 'reset-password') {
                $user = $this->Users->get($receive['id']);

                $user->password = sha1('password');

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit();
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit();
                }
            } else if($receive['action'] == 'edit-department') {
                $user = $this->Users->get($receive['id']);

                $user->department = implode(',', $receive['department']);

                if($this->Users->save($user)){
                    echo '{"res":"ok"}';
                    exit();
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    exit();
                }
            }


            //$this->redirect(['action' => 'user']);
            return;
        }


        $usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role' => '3']]]);
        $users = array();
        foreach ($usersTmp as $userTmp) {
            $users[$userTmp->id] = $userTmp;
        }
        $this->set('users', $users);
        $this->set('userId', $session->read('user'));


        // サブ部門がある部門リスト
        $subDepsList = array_unique(
            array_column(
                $this->Departments->find('all')->where(['is_sub' => true, 'store_id' => $storeId])->toArray(),
                'parent'
            )
        );

        $deps = $this->Departments->find('all')->where(['is_sub' => false, 'store_id' => $storeId]);
        $departments = array();

        foreach($deps as $dep) {

            $departments[$dep->dep_index] = $dep;

            // サブ部門があるか？
            if(in_array($dep->id, $subDepsList)){
                // この部門の全サブ部門取得
                $subDeps = $this->Departments->find('all')->where(
                    ['parent' => $dep->id, 'is_sub' => true, 'store_id' => $storeId]
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


        if($this->request->getQuery('mode') == 'depAjax') {
            $this->autoRender = false;
            $print = '';

            $uDep = explode(',', $users[$this->request->getQuery('id')]->department);

            foreach ($departments as $dep) {

                $print .= "<div class='form-control'>";
                $print .= '<button class="btn btn-sm btn-primary form-control" onclick="$(\'#main-f-'.$dep->id.'\').toggle(\'fast\'); return false;">' . $dep->name . '</button>';

                if (!$dep->is_sub) {

                    $print .= "<div id='main-f-{$dep->id}' class='sub-dep-f form-control' style='margin-top: 5px;display: none;'>";

                    foreach ($dep->sub as $sub) {
                        $s = '';
                        if (in_array($sub->id, $uDep)) $s = "checked='checked'";
                        $print .= "<input type='checkbox' {$s} name='departments[]' value='{$sub->id}' id='checkbox-f-{$sub->id}' />";
                        $print .= "<label style='' for='checkbox-f-{$sub->id}' class='checkbox'>{$sub->name}</label>";
                    }

                    $print .= '</div>';

                }

                $print .= '</div>';
            }

            //echo '{"res:""ok", "html":"'.$print.'"}';
            echo $print;
            exit;
        }
    }

    public function password(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();


        if($this->request->is('post')) {
            $this->autoRender = false;

            if (!$this->request->getSession()->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }


        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('user')){
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }
        if($session->read('role') < 2){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            exit;
        }
        $this->set('errmsg', '');

        if($this->request->is('post')) {
            $receive = $this->request->getData();

            $store = $this->Stores->get($this->request->getSession()->read('store'));

            $old = $store->password;
            $new = sha1($receive['password']);

            if($old == sha1($receive['oldpassword'])) {
                $store->password = $new;

                if ($this->Stores->save($store)) {
                    //$this->redirect(['controller' => 'stores', 'action' => 'admin']);
                    echo '{"res":"ok"}';
                    return;
                } else {
                    echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                    return;
                }
            } else {
                echo '{"res":"error", "message":"現在のパスワードが間違っています。"}';
                return;
                //$this->set('errmsg', '現在のパスワードが一致しません。');
            }

            return;

        }
    }

    public function department(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('user')){
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }
        if($session->read('role') < 2){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。a"}';
            exit;
        }

        $storeId = $session->read('store');

        // 部門の処理

        // サブ部門がある部門リスト
        $subDepsList = array_unique(
            array_column(
                $this->Departments->find('all')->where(['is_sub' => true, 'store_id' => $storeId])->toArray(),
                'parent'
            )
        );

        $deps = $this->Departments->find('all')->where(['is_sub' => false, 'store_id' => $storeId]);
        $departments = array();





        foreach($deps as $dep) {

            $departments[$dep->dep_index] = $dep;



            //あとはメイン部門をサブ部門と同じ処理でソートするだけ！




            // サブ部門があるか？
            if(in_array($dep->id, $subDepsList)){
                // この部門の全サブ部門取得
                $subDeps = $this->Departments->find('all')->where(
                    ['parent' => $dep->id, 'is_sub' => true, 'store_id' => $storeId]
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

    public function management(){
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();


        if(!$session->check('admin')){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            exit;
        }

        $this->set('storeParentName', $this->Stores->find('all')->where(['id' => 1])->toArray()[0]['name']);
    }

    public function add()
    {
        $this->viewBuilder()->setLayout(false);

        $session = $this->request->getSession();

        $this->set('area', $this->area);

        if($this->request->is('post')) {
            $this->autoRender = false;

            if (!$this->request->getSession()->check('user')) {
                echo '{"res":"error","message":"ログインセッションが切れています。\nお手数ですが再度ログインし直してください。"}';
                exit;
            }
        }


        if(!$session->check('admin')){
            $this->autoRender = false;

            echo '{"res":"error","message":"このページにアクセスするための権限がありません。"}';
            exit;
        }

        $this->set('done', false);

        if ($this->request->is('post')) {

            $receive = $this->request->getData();

            $new = $this->Stores->newEntity();

            $new->name = $receive['name'];
            $new->password = sha1($receive['password']);
            $new->area = $receive['area'];

            if ($this->Stores->save($new)) {
                $d1 = $this->Departments->newEntity();
                $d1->name = "グッズ";
                $d1->dep_index = 1;
                $d1->is_sub = 0;
                $d1->parent = 0;
                $d1->store_id = $new->id;
                $this->Departments->save($d1);
                $d2 = $this->Departments->newEntity();
                $d2->name = "全般";
                $d2->dep_index = 1;
                $d2->is_sub = 1;
                $d2->parent = $d1->id;
                $d2->store_id = $new->id;
                $this->Departments->save($d2);

                $this->autoRender = true;
                $this->set('done', true);
            } else {
                echo '{"res":"error", "message":"データベースにデータを保存できませんでした。"}';
                exit;
            }
        }
    }

    public function beforeFilter(Event $event) {
        if ($this->params['action'] == 'edit') {
            $this->loadComponent('Security');
            //CSRFチェックOFF
            $this->Security->setConfig('unlockedActions', ['edit']);
        }

        $this->area = array();
        $this->area[0] = NULL;
        $this->area[1] = "北海道・東北";
        $this->area[2] = "関東・甲信越";
        $this->area[3] = "東海・北陸";
        $this->area[4] = "関西";
        $this->area[5] = "九州・四国";
        $this->area[6] = "通販・海外";

        /*if($this->params['action'] != 'login'){
            if(!$this->request->getSession()->check('store')){
                $this->redirect(['controller' => 'stores', 'action' => 'login']);
                return;
            }
            if(!$this->request->getSession()->check('user')){
                $this->redirect(['controller' => 'users', 'action' => 'login']);
                return;
            }
        }*/
    }
}