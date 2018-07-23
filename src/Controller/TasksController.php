<?php

namespace App\Controller;

use App\Utils\AppUtility;
use Cake\Event\Event;

class TasksController extends AppController {

    public function index(){
        $this->redirect(['action' => 'view']);
        return;
    }

    public function view()
    {
        $session = $this->request->getSession();

        $currentDay = explode('&', $_SERVER['QUERY_STRING'])[0];

        if($session->check('admin')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }
        if(!$session->check('store')){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }

        if(!$session->check('user')){
            $this->redirect(['controller' => 'users', 'action' => 'login']);
            return;
        }

        $storeId = $session->read('store');


        if($storeId == 1){
            $this->redirect(['controller' => 'stores', 'action' => 'login']);
            return;
        }


        if($currentDay == null || !AppUtility::checkDate($currentDay)){
            //$currentDay = date("Y年m月d日");
            $currentDay = date("Y-m-d");
        }

        if(AppUtility::checkFuture($currentDay)){
            $this->redirect(['controller' => 'tasks', 'action' => 'view']);
            return;
        }


        $nextDate = AppUtility::getNextDate($currentDay);
        $previousDate = AppUtility::getPreviousDate($currentDay);
        $pastFlag = AppUtility::checkPast($currentDay);
        $todayFlag = AppUtility::checkToday($currentDay);

        $this->set('currentDay', $currentDay);
        $this->set('currentDayText', AppUtility::getDateText($currentDay));
        $this->set('nextDay', $nextDate);
        $this->set('previousDay', $previousDate);

        $this->set('pastFlag', $pastFlag);
        $this->set('todayFlag', $todayFlag);

        $stores = $this->Stores->find('all')->where(['id' => $storeId])->toArray()[0];
        $this->set('stores', $stores);
        $this->set('storeParentName', $this->Stores->find('all')->where(['id' => '1'])->toArray()[0]['name']);


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


        // 前日からの引き継ぎ//date('Y-m-d', strtotime(str_replace('/', '-', $currentDay) . " -1 day"))
        $msgs = $this->Messages->find('all')->where(['date' => $previousDate, 'store_id' => $storeId]);
        $this->set('msgs', $msgs);
        // 翌日への引き継ぎ（当日の分）
        $msgsToday = $this->Messages->find('all')->where(['date' => str_replace('/', '-', $currentDay), 'store_id' => $storeId]);
        $this->set('msgsToday', $msgsToday);


        //
        $dtasks = $this->Dailytasks->find('all')->where(['date' => str_replace('/', '-', $currentDay), 'store_id' => $storeId]);
        $this->set('dtasks', $dtasks);


        $tasks = $this->Tasks->find('all')->where(['completed' => false, 'store_id' => $storeId]);
        $this->set('tasks', $tasks);

        $tasksTodayCompleted = $this->Tasks->find('all')->where(['completed' => true, 'update_date' => $currentDay, 'store_id' => $storeId]);
        $this->set('tasksTodayCompleted', $tasksTodayCompleted);

        //$usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role' => '3']]]);
        $usersTmp = $this->Users->find('all')->where(['deleted' => '0', 'OR' => [[ 'store' => $storeId], ['role <=' => '3']]]);
        //$usersTmp = $this->Users->find('all')->where(['OR' => ['store' => $storeId], ['role' => '3']]);
        $users = array();
        foreach ($usersTmp as $userTmp) {
            $users[$userTmp->id] = $userTmp;
            //$users[$userTmp->id]['id'] = $usersTmp->id;
        }
        $this->set('users', $users);
    }


    public function edit(){
        $this->autoRender = false;

        $session = $this->request->getSession();

        if($this->request->is('post')){
            $receive = $this->request->getData();

            if($receive['action'] == 'task-add'){
                //debug($receive);

                $newTask = $this->Tasks->newEntity();

                $workers = '';
                for($i = 0; $i < count($receive['task-worker']); $i++){
                    if($workers != '') $workers .= ',';
                    $workers .= $receive['task-worker'][$i];
                }

                $newTask->caption = nl2br($receive['task-caption']);
                $newTask->detail = nl2br($receive['task-detail']);
                $newTask->department = $receive['task-department'];
                $newTask->created_date = date('Y-m-d');
                $newTask->created_date_time = date('H:i');
                $due = str_replace('年', '-', $receive['task-due-date']);
                $due = str_replace('月', '-', $due);
                $due = str_replace('日', '', $due);
                $newTask->due_date = $due == '' ? NULL : $due;
                $newTask->situation = $receive['task-situation'];
                $newTask->author = $session->read('user');
                $newTask->priority = $receive['task-priority'];
                $newTask->worker = $workers;
                $newTask->user_id = $session->read('user');
                $newTask->store_id = $session->read('store');


                //データを保存する
                if ($this->Tasks->save($newTask)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'task-delete'){
                $task = $this->Tasks->get($receive['task-id']);

                $this->Tasks->delete($task);

                $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                return;
            } else if($receive['action'] == 'task-edit'){
                $task = $this->Tasks->get($receive['task-id']);

                $workers = '';
                for($i = 0; $i < count($receive['task-worker']); $i++){
                    if($workers != '') $workers .= ',';
                    $workers .= $receive['task-worker'][$i];
                }

                $task->caption = $receive['task-caption'];
                $task->detail = nl2br($receive['task-detail']);
                $task->situation = nl2br($receive['task-situation']);
                $task->department = $receive['task-department'];
                $task->update_date = date('Y-m-d');
                $task->update_date_time = date('H:i');
                $task->update_user = $session->read('user');
                $due = str_replace('年', '-', $receive['task-due-date']);
                $due = str_replace('月', '-', $due);
                $due = str_replace('日', '', $due);
                $task->due_date = $due == '' ? NULL : $due;
                $task->priority = $receive['task-priority'];
                $task->worker = $workers;


                //データを保存する
                if ($this->Tasks->save($task)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'task-success'){
                $task = $this->Tasks->get($receive['task-id']);

                $workers = '';
                for($i = 0; $i < count($receive['task-worker']); $i++){
                    if($workers != '') $workers .= ',';
                    $workers .= $receive['task-worker'][$i];
                }

                $task->caption = $receive['task-caption'];
                $task->detail = nl2br($receive['task-detail']);
                $task->situation = nl2br($receive['task-situation']);
                $task->department = $receive['task-department'];
                $task->update_date = date('Y-m-d');
                $task->update_date_time = date('H:i');
                $task->update_user = $session->read('user');
                $due = str_replace('年', '-', $receive['task-due-date']);
                $due = str_replace('月', '-', $due);
                $due = str_replace('日', '', $due);
                $task->due_date = $due == '' ? NULL : $due;
                $task->priority = $receive['task-priority'];
                $task->worker = $workers;


                $task->completed = 1;
                $task->completed_user = $session->read('user');
                $task->update_date = date('Y-m-d');
                $task->update_date_time = date('H:i');
                $task->update_user = $session->read('user');


                //データを保存する
                if ($this->Tasks->save($task)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'msg-add'){
                $newMsg = $this->Messages->newEntity();

                $newMsg->detail = nl2br($receive['msg-detail']);
                $newMsg->department = $receive['msg-department'];
                $newMsg->date = date('Y-m-d');
                $newMsg->date_time = date('H:i');
                $newMsg->user_id = $session->read('user');
                $newMsg->store_id = $session->read('store');


                //データを保存する
                if ($this->Messages->save($newMsg)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'msg-edit'){
                $msg = $this->Messages->get($receive['msg-id']);

                $msg->detail = nl2br($receive['msg-detail']);
                $msg->department = $receive['msg-department'];
                $msg->date = date('Y-m-d');
                $msg->date_time = date('H:i');


                //データを保存する
                if ($this->Messages->save($msg)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'msg-delete'){
                $msg = $this->Messages->get($receive['msg-id']);

                $this->Messages->delete($msg);

                $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
            } else if($receive['action'] == 'dailytask-add'){
                $newTask = $this->Dailytasks->newEntity();

                $newTask->caption = $receive['dailytask-caption'];
                $newTask->detail = nl2br($receive['dailytask-detail']);
                $newTask->department = $receive['dailytask-department'];
                $newTask->work_time_h = $receive['worktime-h'];
                $newTask->work_time_m = $receive['worktime-m'];
                $newTask->date = date('Y-m-d');
                $newTask->date_time = date('H:i');
                $newTask->user_id = $session->read('user');
                $newTask->store_id = $session->read('store');


                //データを保存する
                if ($this->Dailytasks->save($newTask)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'dailytask-edit'){
                $task = $this->Dailytasks->get($receive['dailytask-id']);

                $task->caption = $receive['dailytask-caption'];
                $task->detail = nl2br($receive['dailytask-detail']);
                $task->department = $receive['dailytask-department'];
                $task->work_time_h = $receive['worktime-h'];
                $task->work_time_m = $receive['worktime-m'];
                $task->date = date('Y-m-d');
                $task->date_time = date('H:i');


                //データを保存する
                if ($this->Dailytasks->save($task)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            } else if($receive['action'] == 'dailytask-delete'){
                $task = $this->Dailytasks->get($receive['dailytask-id']);

                if ($this->Dailytasks->delete($task)) {
                    $this->redirect(['controller' => 'Tasks', 'action' => 'View']);
                    return;
                }
            }


        }
    }

    public function beforeFilter(Event $event) {

    }

}

?>