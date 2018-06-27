<?php

use App\Utils\AppUtility;

$session = $this->request->getSession();

if($session->read('role') >= 2){
    $adminLink = "<li><a href='../stores/admin'>店舗管理</a>";

    $this->assign('adminLink', $adminLink);
} else {
    $this->assign('adminLink', "");
}
if($session->read('role') == 1){
    $this->assign('showToggleBtn', '');
} else {
    $this->assign('showToggleBtn', 'style="display:none;"');
}
if($todayFlag){
    $this->assign('todayLink', '');
} else {
    $this->assign('todayLink', '<li><a href="./?today">今日のシートへ</a></li>');
}

// 値の読み込み、またはデフォルトの 0 を取得
$autoReload = $this->request->getCookie('autoReload', 0);
$this->assign('autoReload', $autoReload == 0 ? 'checked' : '');

$this->assign('title', $storeParentName . " " . $stores->name);
$this->assign('script', $this->Html->script('form.js'));

$this->assign('user', $users[$session->read('user')]->name);
$this->assign('currentDay', $currentDayText);
$this->assign('sheetDate', date("Y年m月d日", strtotime($currentDay)));



if(!AppUtility::checkFuture($nextDay)){
    $this->assign('nextDayLink', '<li><a href="?'.$nextDay.'">次の日＞</a></li>');
}

$this->assign('previousDay', $previousDay);

if($this->request->getQuery('forceFlag')){
    $todayFlag = true;
}


?>
<table id="table" class="" style="margin: 0 auto;">
        <thead>
        <tr>
            <th scope="col" colspan="2" style="">ライン</th>

            <th scope="col" style="min-width:150px;"><span style='display: inline-block;'>未処理</span><span style='display: inline-block;'>タスク</span></th>
            <th scope="col" style="min-width:150px;"><span style='display: inline-block;'>前日からの</span><span style='display: inline-block;'>引き継ぎ</span></th>
            <th scope="col" style="min-width:150px;"><span style='display: inline-block;'><?= $todayFlag ? '今日' : 'この日' ?></span><span style='display: inline-block;'>やったこと</span></th>
            <th scope="col" style="min-width:150px;"><span style='display: inline-block;'><?= $todayFlag ? '今日' : 'この日' ?>完了した</span><span style='display: inline-block;'>タスク</span></th>
            <th scope="col" style="min-width:150px;"><span style='display: inline-block;'>翌日への</span><span style='display: inline-block;'>引き継ぎ</span></th>
        </tr>
        </thead>

        <tbody>


        <?php foreach ($departments as $dep): ?>

        <tr id="table-<?php echo $dep->id; ?>" class="dep-table">
            <?php if(!empty($dep->sub)): ?><?php //str_replace("ー", "｜", $dep->name) ?>
                <td rowspan="<?= (count($dep->sub)+1) ?>"><?= $dep->name ?></td>

                <?php else: ?>
                <?php //str_replace("ー", "｜", $dep->name) ?>
                <td><?= $dep->name ?></td>


            <?php endif; ?>
        </tr>

        <?php
        $rowCount = 0;
        $dataId = 0;
        ?>
            <?php if(isset($dep->sub)): ?>
                <?php foreach ($dep->sub as $sub): ?>

                    <?php
                    $rowCount++;
                    $setColor = ($rowCount % 2) ? "" : "";
                    ?>

                    <tr id="table-<?php echo $sub->id; ?>" class="dep-table"><?php //str_replace("ー", "｜", $sub->name) ?>
                        <td style=""><span><?= $sub->name ?></span></td>



                        <td class="small">

                            <?php foreach($tasks as $task){

                                if($task->department == $sub->id){
                                    $dataId = uniqid();

                                    $worker = explode(',', $task->worker);

                                    $a = in_array($session->read('user'), $worker) || $task->author == $session->read('user') || $users[$session->read('user')]->role >= 2 ? '<a class="modaal" href="#modaal-task-edit" style="color:blue" onclick="showTaskEdit(\''.$dataId.'\')">edit</a>' : "";

                                    $updateDate = AppUtility::getDateText($task->update_date);
                                    $updateDateTime = AppUtility::getTimeText($task->update_date_time);
                                    $createdDate = AppUtility::getDateText($task->created_date);
                                    $createdDateTime = AppUtility::getTimeText($task->created_date_time);
                                    $dueDate = AppUtility::getDateText($task->due_date);
                                    $dueDateForm = date("Y年m月d日", strtotime($task->due_date));
                                    $dueDateOver = AppUtility::checkPast(date("Y-m-d", strtotime($task->due_date)));

                                    if($dueDate != "" && $dueDateOver){
                                        $s = " priority-5 ";
                                    } else {
                                        $s = "";
                                    }

                                    $d = 0;
                                    $dt = 0;
                                    if($updateDate == null){
                                        $d = $task->created_date;
                                        $dt = $task->created_date_time;
                                    } else {
                                        $d = $task->update_date;
                                        $dt = $task->update_date_time;
                                    }

                                    $caption = in_array($session->read('user'), $worker) ? '★' : '';
                                    $caption .= $task->caption;

                                    //echo "<task dataid='{$dataId}' updated='{$task->updated_time}' caption='{$task->caption}' detail='{$task->detail}' id='{$task->id}' situation='{$task->situation}' progress='{$task->progress}' created='{$task->created}' department='{$task->department}' modified='{$task->modified}' author='{$task->author}' completed='{$task->completed}' completed-date='{$task->completed_date}' priority='{$task->priority}' user-id='{$task->user_id}' store-id='{$task->store_id}' />\n";
                                    echo "<task dataid='{$dataId}' due-date-over='{$dueDateOver}' due-date-f='{$dueDateForm}' due-date='{$dueDate}' update-user='{$task->update_user}' worker='{$task->worker}' update-date='{$updateDate}' update-date-time='{$updateDateTime}' caption='{$task->caption}' detail='{$task->detail}' id='{$task->id}' situation='{$task->situation}' progress='{$task->progress}' created-date='{$createdDate}' department='{$task->department}' created-date-time='{$createdDateTime}' author='{$task->author}' completed='{$task->completed}' priority='{$task->priority}' user-id='{$task->user_id}' store-id='{$task->store_id}' />\n";
                                    echo '<li href="#modaal-task-view" class="modaal clickable '.$s.' priority-'.$task->priority.'" style="text-align:center;" onclick="showTaskView(\''.$dataId.'\')"><span>' . $caption . '</span></li><span class="edit">' . $d . ' ' . $dt . ' (' . $users[$task->user_id]->name . ') '.$a.'</span><div class="clearfix"></div>';
                                }
                            }

                            if($todayFlag){
                                echo '<span style="float: right"><a class="modaal" href="#modaal-task-edit" onclick="showTaskEdit(-1, '.$sub->id.')">追加</a></span>';
                            }

                            ?>


                        </td>


                        <td class="small">

                            <?php foreach($msgs as $msg) {

                                if ($msg->department == $sub->id) {
                                    $dataId = uniqid();

                                    $date = AppUtility::getDateText($msg->date);
                                    $dateTime = AppUtility::getTimeText($msg->date_time);

                                    //echo "<msg dataid='{$dataId}' id='{$msg->id}' updated='{$msg->updated_date}' department='{$msg->department}' detail='{$msg->detail}' user-id='{$msg->user_id}' date='{$msg->date}' store-id='{$msg->store_id}' />\n";
                                    echo "<msg dataid='{$dataId}' date-time='{$dateTime}' id='{$msg->id}' department='{$msg->department}' detail='{$msg->detail}' user-id='{$msg->user_id}' date='{$date}' store-id='{$msg->store_id}' />\n";
                                    echo '<li href="#modaal-msg-view" class="modaal clickable" onclick="showMsgView(\''.$dataId.'\')"><div style="width:200px;white-space:normal;" >' . $msg->detail . '</div></li><span class="edit">' . $msg->date . ' ' . $msg->date_time . ' (' . $users[$msg->user_id]->name . ') </span><div class="clearfix"></div>';
                                }

                            }

                            ?>

                        </td>


                        <td class="small">

                            <?php foreach($dtasks as $dtask) {

                                if ($dtask->department == $sub->id) {
                                    $dataId = uniqid();

                                    if($todayFlag) {
                                        $a = $dtask->user_id == $session->read('user') || $users[$session->read('user')]->role >= 2 ? '<a class="modaal" href="#modaal-dailytask-edit" style="color:blue" onclick="showDailyTaskEdit(\'' . $dataId . '\')">edit</a>' : "";
                                    } else {
                                        $a = '';
                                    }
                                    $date = AppUtility::getDateText($dtask->date);
                                    $dateTime = AppUtility::getTimeText($dtask->date_time);


                                    //echo "<dtask dataid='{$dataId}' id='{$dtask->id}' updated='{$dtask->updated_date}' department='{$dtask->department}' date='{$dtask->date}' caption='{$dtask->caption}' detail='{$dtask->detail}' work-time-h='{$dtask->work_time_h}' work-time-m='{$dtask->work_time_m}' user-id='{$dtask->user_id}' store-id='{$dtask->store_id}' />\n";
                                    echo "<dtask dataid='{$dataId}' id='{$dtask->id}' department='{$dtask->department}' date='{$date}' date-time='{$dateTime}' caption='{$dtask->caption}' detail='{$dtask->detail}' work-time-h='{$dtask->work_time_h}' work-time-m='{$dtask->work_time_m}' user-id='{$dtask->user_id}' store-id='{$dtask->store_id}' />\n";
                                    echo '<li href="#modaal-dailytask-view" class="modaal clickable" style=" text-align:center;" onclick="showDailyTaskView(\''.$dataId.'\')"><span>' . $dtask->caption . '</span></li><span class="edit">' . $dtask->date . ' ' . $dtask->date_time . ' (' . $users[$dtask->user_id]->name . ') '.$a.'</span><div class="clearfix"></div>';
                                }
                            }

                            if($todayFlag){
                                echo '<span style="float: right"><a class="modaal" href="#modaal-dailytask-edit" onclick="showDailyTaskEdit(-1 ,'.$sub->id.')">追加</a></span>';
                            }

                            ?>


                        </td>


                        <td class="small">

                            <?php foreach($tasksTodayCompleted as $taskTodayCompleted) {

                                if($taskTodayCompleted->department == $sub->id) {
                                    $dataId = uniqid();

                                    if($todayFlag) {
                                        $a = $taskTodayCompleted->author == $session->read('user') || $users[$session->read('user')]->role >= 2 ? '<a class="modaal" href="#modaal-dailytask-edit" style="color:blue" onclick="showDailyTaskEdit(\'' . $dataId . '\')">edit</a>' : "";
                                    } else {
                                        $a = '';
                                    }
                                    $updateDate = AppUtility::getDateText($taskTodayCompleted->update_date);
                                    $updateDateTime = AppUtility::getTimeText($taskTodayCompleted->update_date_time);
                                    $createdDate = AppUtility::getDateText($taskTodayCompleted->created_date);
                                    $createdDateTime = AppUtility::getTimeText($taskTodayCompleted->created_date_time);

                                    //echo "<tctask dataid='{$dataId}' updated='{$taskTodayCompleted->updated_date}' author='{$taskTodayCompleted->author}' id='{$taskTodayCompleted->id}' department='{$taskTodayCompleted->department}' caption='{$taskTodayCompleted->caption}' situation='{$taskTodayCompleted->situation}' detail='{$taskTodayCompleted->detail}' user-id='{$taskTodayCompleted->user_id}' store-id='{$taskTodayCompleted->store_id}' progress='{$taskTodayCompleted->progress}' created='{$taskTodayCompleted->created}' modified='{$taskTodayCompleted->modified}' completed='{$taskTodayCompleted->completed}' completed-date='{$taskTodayCompleted->completed_date}' priority='{$taskTodayCompleted->priority}' completed-user='{$taskTodayCompleted->completed_user}' />\n";
                                    echo "<tctask dataid='{$dataId}' worker='{$taskTodayCompleted->worker}' update-user='{$taskTodayCompleted->update_user}' update-date='{$updateDate}' update-date-time='{$updateDateTime}'  author='{$taskTodayCompleted->author}' id='{$taskTodayCompleted->id}' department='{$taskTodayCompleted->department}' caption='{$taskTodayCompleted->caption}' situation='{$taskTodayCompleted->situation}' detail='{$taskTodayCompleted->detail}' user-id='{$taskTodayCompleted->user_id}' store-id='{$taskTodayCompleted->store_id}' progress='{$taskTodayCompleted->progress}' created-date='{$createdDate}' created-date-time='{$createdDateTime}' completed='{$taskTodayCompleted->completed}' priority='{$taskTodayCompleted->priority}' completed-user='{$taskTodayCompleted->completed_user}' />\n";
                                    echo '<li href="#modaal-task-view" class="modaal clickable" style=" text-align:center;" onclick="showTaskView(\''.$dataId.'\', 1)"><span>' . $taskTodayCompleted->caption . '</span></li><span class="edit">' . $taskTodayCompleted->update_date . ' ' . $taskTodayCompleted->update_date_time . ' (' . $users[$taskTodayCompleted->completed_user]->name . ') </span><div class="clearfix"></div>';
                                }
                            }

                            ?>

                        </td>

                        <td class="small">

                            <?php foreach($msgsToday as $msgToday) {

                                if($msgToday->department == $sub->id) {
                                    $dataId = uniqid();

                                    if($todayFlag) {
                                        $a = $msgToday->user_id == $session->read('user') || $users[$session->read('user')]->role >= 2 ? '<a href="#modaal-msg-edit" class="modaal clickable" onclick="showMsgEdit(\'' . $dataId . '\', ' . $msgToday->department . ')" style="color:blue">edit</a>' : "";
                                    } else {
                                        $a = '';
                                    }
                                    $date = AppUtility::getDateText($msgToday->date);
                                    $dateTime = AppUtility::getTimeText($msgToday->date_time);

                                    echo "<msgToday dataid='{$dataId}' date-time='{$dateTime}' id='{$msgToday->id}' department='{$msgToday->department}'  detail='{$msgToday->detail}' user-id='{$msgToday->user_id}' date='{$date}' store-id='{$msgToday->store_id}'/>\n";
                                    echo '<li href="#modaal-msg-view" class="modaal clickable" onclick="showMsgView(\''.$dataId.'\')"><div style="width:200px;white-space:normal;" >' . $msgToday->detail . '</div></li><span class="edit">' . $msgToday->date . ' ' . $msgToday->date_time . ' (' . $users[$msgToday->user_id]->name . ') '.$a.'</span><div class="clearfix"></div>';
                                }
                            }

                            if($todayFlag){
                                echo '<span style="float: right"><a class="modaal" href="#modaal-msg-edit" onclick="showMsgEdit(-1 ,'.$sub->id.')">追加</a></span>';
                            }

                            ?>


                        </td>


                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endforeach; ?>



        </tbody>
    </table>

</div>




