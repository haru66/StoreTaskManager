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

<style type="text/css">
    /* BOX DESIGN https://saruwakakun.com/html-css/reference/box */

    li{
        list-style-type: none;
    }

    td li {
        list-style-type: none;
        border-bottom: solid 1px;

    }

    td li.clickable{
        cursor: pointer;
    }
    td li.clickable:hover{
        color:royalblue;
    }

    span.edit{
        float:right;
        font-size: 10px
    }

    .priority-5 {background: red; color:white; }
    .priority-4 {background: #FDC4C4; }
    /*.priority-3 {background: #ffd;}
    .priority-2 {background: #f6f6f6;}
    .priority-1 {background: #dff;}*/

    thead > tr > th {
        text-align: center;
    }



    /* http://js.crap.jp/css3-table/ */

    table {

        *border-collapse: collapse;
        /*border-collapse: separate;*/
        border-spacing: 0;
        font-size:14px;
    }
    #table th {
        color: #fff;
        padding: 8px 15px;
        background: #258;
        background:-moz-linear-gradient(rgba(34,85,136,0.7), rgba(34,85,136,0.9) 50%);
        background:-webkit-gradient(linear, 100% 0%, 100% 50%, from(rgba(34,85,136,0.7)), to(rgba(34,85,136,0.9)));
        font-weight: bold;
        border-left:1px solid #258;
        border-top:1px solid #258;
        border-bottom:1px solid #258;
        line-height: 120%;
        text-align: center;
        text-shadow:0 -1px 0 rgba(34,85,136,0.9);
        box-shadow: 0px 1px 1px rgba(255,255,255,0.3) inset;
    }
    #table th:first-child {
        border-radius: 5px 0 0 0;
    }
    #table th:last-child {
        border-radius:0 5px 0 0;
        border-right:1px solid #258;
        box-shadow: 2px 2px 1px rgba(0,0,0,0.1), 0px 1px 1px rgba(255,255,255,0.3) inset;
    }
    #table td {
        padding: 8px 15px;
        border-bottom: 1px solid #84b2e0;
        border-left: 1px solid #84b2e0;

        box-shadow: 2px 2px 1px rgba(0,0,0,0.1);
        /*text-align: center;
        width:1px;white-space:nowrap;*/
    }
    .department-name{
        -webkit-writing-mode: vertical-rl;
        -ms-writing-mode: tb-rl;
        -webkit-text-orientation: upright;
        text-orientation: upright;
        writing-mode: tb-rl; /* IE用 */
        writing-mode: vertical-rl; /* Chrome、Firefox用 */
        margin: 0 auto;
        white-space: nowrap;
        width: 1em; /* firefox対策 */
        line-height: 1em; /* firefox対策 */
    }

    @media only screen and (max-width: 1000px) {
        #table {
            display: block;
            width: 100%;
            margin: 0 -10px;
        }
        #table thead{
            display: block;
            float: left;
            overflow-x:scroll;
        }
        #table tbody{
            display: block;
            width: auto;
            overflow-x: auto;
            white-space: nowrap;
        }
        #table th{
            display: block;
            width:auto;
        }
        #table tbody tr{
            display: inline-block;
            margin: 0 -3px;
        }
        #table td{
            display: block;
        }
    }

    #table tr td:last-child {
        border-right: 1px solid #84b2e0;

    }
    #table tr {
        background: #fff;
    }
    #table tr:nth-child(2n+1) {
        /*background: #f1f6fc;*/
    }
    #table tr:last-child td {
        box-shadow: 2px 2px 1px rgba(0,0,0,0.1);
    }
    #table tr:last-child td:first-child {
        border-radius: 0 0 0 5px;
    }
    #table tr:last-child td:last-child {
        border-radius: 0 0 5px 0;
    }
    #table tr:hover {
        /*background: #bbd4ee;*/
        background: #e3eaef;
        /*cursor:pointer;*/
    }


</style>

<script type="text/javascript">

    <?php

        $deplist = array();

        echo 'var deplist = {};';
        //alert(deplist['2']['name']);
        foreach ($departments as $dep){

            $deplist[$dep->id]['name'] = $dep->name;
            $deplist[$dep->id]['parent'] = 0;
            $deplist[$dep->id]['is_sub'] = $dep->is_sub;

            echo 'deplist["'.$dep->id.'"] = { name: "'.$dep->name.'", parent: 0, is_sub: false };';

            if(!$dep->is_sub){

                foreach($dep->sub as $sub){

                        $deplist[$sub->id]['name'] = $sub->name;
                        $deplist[$sub->id]['is_sub'] = $sub->is_sub;
                        $deplist[$sub->id]['parent'] = $sub->parent;

                        echo 'deplist["'.$sub->id.'"] = { id: '.$sub->id.', name: "'.$sub->name.'", parent: '.$sub->parent.', is_sub: true };';
                }

            }
        }



        $deplist = array();
        $i = 0;

        echo 'var deplist2 = {};';
        foreach ($departments as $dep){

            $deplist[$dep->id]['name'] = $dep->name;
            $deplist[$dep->id]['parent'] = 0;
            $deplist[$dep->id]['is_sub'] = $dep->is_sub;

            echo 'deplist2["'.$i.'"] = { name: "'.$dep->name.'", id: '.$dep->id.', parent: 0, is_sub: false };';

            if(!$dep->is_sub){

                foreach($dep->sub as $sub){
                        $i++;
                        $deplist[$sub->id]['name'] = $sub->name;
                        $deplist[$sub->id]['is_sub'] = $sub->is_sub;
                        $deplist[$sub->id]['parent'] = $sub->parent;

                        echo 'deplist2["'.$i.'"] = { id: '.$sub->id.', name: "'.$sub->name.'", parent: '.$sub->parent.', is_sub: true };';


                }

            } else {
                $i++;
            }


        }



        $userList = array();

        echo 'var userlist = {};';
        echo 'var useridlist = {};';

        $d = null;
        foreach($users as $user){
            echo 'userlist["'.$user->id.'"] = { name: "'.$user->name.'", role:'.$user->role.', department:['.$user->department.']};';
            if($d != '') $d .= ", ";
            $d .= "{$user->id}";
        }

        echo "useridlist = [ {$d} ];";


        echo 'var userId = ' . $session->read('user') . ';';

    ?>




    var modalOpen = false;

    $(function(){

        setInterval(function(){
            if(!modalOpen) location.reload();
        },1000*60); // reload after 1 min (1sec x 60)

        $("#sheet-date").datepicker({
            maxViewMode: 2,
            todayBtn: "linked",
            format: "yyyy年mm月dd日",
            language: "ja",
            daysOfWeekHighlighted: "0,1,2,3,4,5,6",
            autoclose: true,
            todayHighlight: true,
            weekStart: 1,
            endDate: Date()
        });



        $('.modaal').modaal({
            animation_speed: 50,
            width: 490,
            is_locked: true,
            background: "gray",
            loading_content: 'Loading content, please wait.'
        });

        $('input').change(function(elem){

            var check_count = $('#task-edit-worker-div :checked').length;

            if(userlist[userId]['role'] == 1) {
                if (check_count == 0) {
                    alert('作業者がいなくなります！');
                    $(elem.target).prop('checked', 'checked');
                    return false;
                }
            }
        });

        $('#sheet-date').change(function(elem){
            location.href = "?" + $(elem.target).val().replace('年', '-').replace('月', '-').replace('日', '');
        });

        $('#task-edit-submit').click(function(){

        });

        $('#memo-submit-button').click(function(){
            //$("#mode-change-id").val();
                var memo = $('#memo-text').val();

                //alert('変更しました！');
                var data = {
                    action: 'memo-update',
                    memo: memo
                };

                $.ajax({
                    url: '../users/my',
                    type: 'post',
                    data: data,
                }).done(function (data, status, jqXHR) {
                    // 成功時の処理
                    var res = JSON.parse(data);
                    if (res.res != 'error') {
                        $('.modaal').modaal('close');
                    } else {
                        alert("エラーが発生しました。再ログインしてください。");
                    }
                }).fail(function (jqXHR, status, error) {
                    // 失敗時の処理
                    alert('Error : ' + error);
                });


                return true;
        });
    });

    function countStr(str){
        len = 0;
        str = escape(str);
        for (i=0;i<str.length;i++,len++) {
            if (str.charAt(i) == "%") {
                if (str.charAt(++i) == "u") {
                    i += 3;
                    len++;
                }
                i++;
            }
        }
        return len;
    }

    function initTaskDatepicker(){
        $("#task-edit-due-date").datepicker({
            maxViewMode: 2,
            clearBtn: true,
            format: "yyyy年mm月dd日",
            language: "ja",
            daysOfWeekHighlighted: "0,1,2,3,4,5,6",
            autoclose: true,
            todayHighlight: true,
            weekStart: 1,
            startDate: Date()
        });
    }

    function br2nl(str) {
        return str.replace(/(<br>|<br \/>)/gi, '\n');
    };
    function brdel(str) {
        return str.replace(/(<br>|<br \/>)/gi, '');
    };

    function refreshMemo(){
        modalOpen = true;

        $.ajax({
            url: '../users/my?req=memo',
            type: 'get'
        }).done(function (data, status, jqXHR) {
            $('#memo-text').val(brdel(data));
            // 成功時の処理
            /*var res = JSON.parse(data);
            if (res.res != 'error') {
                $('#memo-text').text(br2nl(res.memo));
            } else {
                alert("エラーが発生しました。再ログインしてください。");
            }*/
        }).fail(function (jqXHR, status, error) {
            // 失敗時の処理
            alert('Error : ' + error);
        });


        return true;
    }

    function closeModal(){
        modalOpen = false;
        $('.modaal').modaal('close');
    }

    function showTaskEdit(id = -1, dep =-1){
        modalOpen = true;

        setFocus('#task-edit-caption');

        $('#worker-select-div').hide();
        $('#task-edit-worker-checkbox' + userId).attr('onclick', 'return false;');

        $('#action-task').val('');

        $('#task-edit-due-date-text').text('');
        $('#task-edit-due-date-info').hide();

        if(id == -1) {
            initTaskDatepicker();

            $('#task-edit-worker-checkbox' + userId).prop('checked', 'checked');
            if(userlist[userId]['role'] == 1) {
                $('#task-edit-worker-checkbox' + userId).attr('onclick', 'return false;');
            } else {
                $('#task-edit-worker-checkbox' + userId).removeAttr('onclick');
            }

            if(userlist[userId]['role'] != 3) {
                for(var i = 0; i < Object.keys(userlist).length; i++){
                    //alert(useridlist[i]);
                    if(userlist[useridlist[i]]['role'] == 3) {
                        $('#task-edit-worker-checkbox' + useridlist[i] + "-label").addClass('hide').hide();
                    }
                }
            } else if(userlist[userId]['role'] == 3) {
                for(var i = 0; i < Object.keys(userlist).length; i++){
                    $('#task-edit-worker-checkbox' + useridlist[i] + "-label").addClass('show').show();
                }
            }

            for(var i = 0; i < Object.keys(userlist).length; i++){
                $('#task-edit-worker-checkbox' +  useridlist[i]).removeAttr('checked');
            }
            $('#task-edit-worker-checkbox' + userId).prop('checked', 'checked');
            $('#action-id').val(0);

            $('[id="task-edit-title"]').text("タスク追加");
            $('[id="task-edit-submit"]').text("追  加");

            $('[id="task-edit-situation"]').text('');

            $('#task-edit-due-date-div').show();
            $('#task-edit-due-date').val('');
            $('#task-edit-due-date-text').text('');

            $('[id="task-edit-caption"]').val('');
            $('[id="task-edit-detail"]').val('');

            $('input[name=task-priority]').val(['3']);

            $('#task-edit-department-' + dep).attr('selected', 'selected');

            $('#task-edit-delete').hide();
            $('#task-edit-situation-div').hide();

            $('#task-edit-success').hide();

            $('#task-edit-worker-button').show();
            $('#worker-select-div').hide();
            $('#task-edit-worker-div').show();
            $('#task-edit-worker-list').hide();
            $('#task-edit-worker-info').hide();


            $('#action-task').val('task-add');
        } else {
            $('[id="task-edit-title"]').text("タスク編集");
            $('[id="task-edit-submit"]').text("更  新");

            $('#task-edit-delete').show();

            var task = $('[dataid="' + id + '"]');

            var worker = task.attr('worker').split(',');

            $('#action-id').val(task.attr('id'));

            if(task.attr('due-date') != '') {
                $('#task-edit-due-date').prop('value', task.attr('due-date-f')).change();
            } else {
                $('#task-edit-due-date').val('').change();
            }

            initTaskDatepicker();


            $('[id="task-edit-caption"]').val(task.attr('caption'));
            $('[id="task-edit-detail"]').text(br2nl(task.attr('detail')));

            $('input[name=task-priority]').val([task.attr('priority')]);

            $('#task-edit-department-' + task.attr('department')).attr('selected', 'selected');


            if(userlist[userId]['role'] != 3) {
                for(var i = 0; i < Object.keys(userlist).length; i++){
                    if(userlist[useridlist[i]]['role'] == 3) {
                        $('#task-edit-worker-checkbox' + useridlist[i] + "-label").addClass('hide').hide();
                    }
                }
            } else if(userlist[userId]['role'] == 3) {
                for(var i = 0; i < Object.keys(userlist).length; i++){
                    $('#task-edit-worker-checkbox' + useridlist[i] + "-label").addClass('show').show();
                }
            }


            $('#task-edit-delete').show();

            $('[id="task-edit-situation"]').text(brdel(task.attr('situation')));

            $('#task-edit-situation-div').show();

            $('#task-edit-success').show();

            $('#action-task').val("task-edit");

            if(userId == task.attr('author') || userlist[userId]['role'] >= 2){
                $('#task-edit-worker-info').hide();
                $('#task-edit-due-date-info').hide();

                $('#task-edit-due-date-div').show();


                /*if(userlist[userId]['role'] >= 2){
                    if(userId != task.attr('author')){
                        $('#task-edit-worker-checkbox' + userId).removeAttr('onclick');
                    } else {
                        $('#task-edit-worker-checkbox' + userId).attr('onclick', 'alert("タスク作成者は、管理者であっても作業者から外すことはできません。"); return false;');
                    }
                }
                else if(userId == task.attr('author')){
                    $('#task-edit-worker-checkbox' + userId).attr('onclick', 'alert("タスク作成者は作業者から外すことはできません。"); return false;');
                }*/
                if(userId == task.attr('author') && userlist[userId]['role'] == 1){
                    $('#task-edit-worker-checkbox' + userId).attr('onclick', 'alert("タスク作成者は作業者から外すことはできません。"); $("#task-edit-worker-checkbox" + userId).prop("checked", "checked")');
                } else {
                    $('#task-edit-worker-checkbox' + userId).attr('onclick', '');
                }

                for(var i = 0; i < Object.keys(userlist).length; i++) {
                    $('#task-edit-worker-button').show();
                    $('#worker-select-div').hide();
                    $('#task-edit-worker-div').show();
                    $('#task-edit-worker-list').hide();
                }
            } else {
                $('#task-edit-worker-info').show();
                $('#task-edit-delete').hide();

                $('#task-edit-due-date-info').show();
                $('#task-edit-due-date-div').hide();

                $('#task-edit-due-date-text').text(task.attr('due-date-f'));
                if(task.attr('due-date-over')){
                    $('#task-edit-due-date-text').css('color', 'red').css('font-weight', 'bold');
                } else {
                    $('#task-edit-due-date-text').css('color', 'black').css('font-weight', '');
                }

                var workerList = '';
                for(var i = 0; i < worker.length; i++){
                    console.log(worker[i]);
                    workerList =  workerList + "<span style='margin-right: 16px; display: inline-block;'>" + userlist[worker[i]]['name'] + "</span>";
                }

                for(var i = 0; i < Object.keys(userlist).length; i++){
                    $('#task-edit-worker-button').hide();
                    $('#worker-select-div').hide();
                    $('#task-edit-worker-div').hide();
                    $('#task-edit-worker-list').html(workerList).show();
                }
            }

            for(var i = 0; i < useridlist.length; i++){
                //console.log("UID"+useridlist[i]);
                $('#task-edit-worker-checkbox' + useridlist[i]).prop('checked' , false);
            }
            for(var i = 0; i < worker.length; i++){
                //console.log("WID"+worker[i]);
                $('#task-edit-worker-checkbox' + worker[i]).prop('checked', 'checked');
            }
        }
    }

    function showTaskView(id = -1, done = 0){
        modalOpen = true;

        $('#task-edit-task-detail-div').hide();

        var priorityText = ['最低', '低', '普通', '高', '最高'];

        var task = $('[dataid="' + id + '"]');

        var text = '';

        var worker = task.attr('worker').split(',');
        var workerList = '';
        for(var i = 0; i < worker.length; i++){
            workerList =  workerList + "<span style='margin-right: 16px; display: inline-block;'>" + userlist[worker[i]]['name'] + "</span>";
        }

        $('[id="task-view-work-user"]').html(workerList);


        if(done != 0){
            text = '完了したタスク名：';
            $('[id="task-view-work-complete-user"]').text(userlist[task.attr('completed-user')]['name']);
            $('#task-view-work-user-div').show();
            $('#task-view-work-complete-user-div').show();
            $('#task-view-situation-div').hide();

            $('#task-view-due-date-label').hide();
            $('#task-view-due-date').text('').hide();

            if(task.attr('update-date') != '') {
                $('#task-view-updated-label').text('完了日時：').show();
                $('#task-view-updated').text(task.attr('update-date') + " " + task.attr('update-date-time')).show();
                $('#task-view-update-user-label').text('更新者：').hide();
                //$('#task-view-update-user').text(userlist[task.attr('update-user')]['name']).hide();
                $('#task-view-update-user').text(userlist[task.attr('update-user')]['name']).hide();
            } else {
                $('#task-view-updated-label').hide();
                $('#task-view-updated').text('').hide();
                $('#task-view-update-user-label').hide();
                $('#task-view-update-user').text('').hide();
            }
        }
        else {
            text = 'タスク名：';
            $('#task-view-work-user-div').show();
            $('#task-view-work-complete-user-div').hide();

            if(task.attr('due-date') != ''){
                $('#task-view-due-date-label').show();
                $('#task-view-due-date').text(task.attr('due-date')).show();
                if(task.attr('due-date-over')){
                    $('#task-view-due-date').css('color', 'red').css('font-weight', 'bold');
                } else {
                    $('#task-view-due-date').css('color', 'black').css('font-weight', '');
                }
            } else {
                $('#task-view-due-date-label').show();
                $('#task-view-due-date').text('設定なし').css('color', 'black').css('font-weight', '').show();
            }

            $('[id="task-view-work-user"]').html(workerList);

            if(task.attr('update-date') != '') {
                $('#task-view-updated-label').text('更新日時：').show();
                $('#task-view-updated').text(task.attr('update-date') + " " + task.attr('update-date-time')).show();
                $('#task-view-update-user-label').text('更新者：').show();
                $('#task-view-update-user').text(userlist[task.attr('update-user')]['name']).show();
            } else {
                $('#task-view-updated-label').hide();
                $('#task-view-updated').text('').hide();
                $('#task-view-update-user-label').hide();
                $('#task-view-update-user').text('').hide();
            }
        }
        if(task.attr('situation') == ''){
            $('#task-view-situation-div').hide();
        } else {
            $('#task-view-situation-div').show();
            $('#task-view-situation').html(task.attr('situation'));
        }

        $('[id="task-view-author"]').text(userlist[task.attr('author')]['name']);
        $('#task-view-caption-label').text(text);
        var dName = deplist[task.attr('department')]['name'];
        var dParentName = deplist[deplist[task.attr('department')]['parent']]['name'];
        $('[id="task-view-department"]').text(dParentName + " - " + dName);
        $('[id="task-view-caption"]').text(task.attr('caption'));

        if(task.attr('detail') == ''){
            $('[id="task-view-detail"]').html("なし");
        } else {
            $('[id="task-view-detail"]').html(task.attr('detail'));
        }

        $('#task-view-created').text(task.attr('created-date') + " " + task.attr('created-date-time'));

        $('[id=task-view-priority]').text(priorityText[task.attr('priority')-1]);
    }

    function showMsgEdit(id = -1, dep = 0){
        modalOpen = true;

        setFocus('#msg-detail');

        if(id == -1) {
            $('[id="msg-edit-title"]').text("翌日への引き継ぎ追加");
            $('[id="msg-edit-submit"]').text("追  加");

            $('[id="msg-detail"]').val('');

            $('#msg-edit-department-' + dep).attr('selected', 'selected');

            $('#msg-edit-delete').hide();

            $('#msg-action').val("msg-add");
            $('#msg-id').val(0);
        } else {
            $('[id="msg-edit-title"]').text("翌日への引き継ぎ編集");
            $('[id="msg-edit-submit"]').text("更  新");

            var task = $('[dataid="' + id + '"]');

            $('[id="msg-detail"]').val(brdel(task.attr('detail')));

            $('#msg-edit-department-' + task.attr('department')).attr('selected', 'selected');

            $('#msg-edit-delete').show();

            $('#msg-action').val("msg-edit");
            $('#msg-id').val(task.attr('id'));
        }
    }

    function showDailyTaskEdit(id = -1, dep = 0){
        modalOpen = true;

        setFocus('#dailytask-edit-caption');

        if(id == -1) {
            $('[id="dailytask-edit-title"]').text("今日やったこと追加");
            $('[id="dailytask-edit-submit"]').text("追  加");

            $('[id="dailytask-edit-caption"]').val('');
            $('[id="dailytask-edit-detail"]').val('');

            $('#dailytask-edit-delete').hide();

            $("#worktime-h0").prop('selected', 'selected');
            $("#worktime-m30").prop('selected', 'selected');

            $('#dailytask-edit-department-' + dep).attr('selected', 'selected');

            $('#dailytask-action').val('dailytask-add');
            $('#dailytask-id').val(0);
        } else {
            $('[id="dailytask-edit-title"]').text("今日やったこと編集");
            $('[id="dailytask-edit-submit"]').text("更  新");

            $('#dailytask-edit-delete').show();

            var task = $('[dataid="' + id + '"]');

            $('#dailytask-edit-department-' + task.attr('department')).attr('selected', 'selected');

            $('[id="dailytask-edit-caption"]').val(task.attr('caption'));
            $("#worktime-h" + task.attr('work-time-h')).prop('selected', 'selected');
            $("#worktime-m" + task.attr('work-time-m')).prop('selected', 'selected');
            $('[id="dailytask-edit-detail"]').val(brdel(task.attr('detail')));

            $('#dailytask-action').val('dailytask-edit');
            $('#dailytask-id').val(task.attr('id'));
        }
    }

    function showDailyTaskView(id){
        modalOpen = true;

        var task = $('[dataid="' + id + '"]');

        $('#dailytask-view-caption').text(task.attr('caption'));
        $('#dailytask-view-author').text(userlist[task.attr('user-id')]['name']);

        $('#dailytask-view-date').text(task.attr('date') + " " + task.attr('date-time'));

        if(task.attr('detail') == ''){
            $('[id="dailytask-view-detail"]').html("なし");
        } else {
            $('[id="dailytask-view-detail"]').html(task.attr('detail'));
        }
        $('#dailytask-view-worktime-h').text(task.attr('work-time-h'));
        $('#dailytask-view-worktime-m').text(task.attr('work-time-m'));
        var dName = deplist[task.attr('department')]['name'];
        var dParentName = deplist[deplist[task.attr('department')]['parent']]['name'];
        $('#dailytask-view-department').text(dParentName + " - " + dName);

        if(task.attr('work-time-h') == 0){
            $('.worktime-h').hide();
        } else {
            $('.worktime-h').show();
        }
    }

    function showMsgView(id){
        modalOpen = true;

        var task = $('[dataid="' + id + '"]');

        $('#msg-view-author').text(userlist[task.attr('user-id')]['name']);

        $('#msg-view-date').text(task.attr('date') + " " + task.attr('date-time'));

        var dName = deplist[task.attr('department')]['name'];
        var dParentName = deplist[deplist[task.attr('department')]['parent']]['name'];
        $('#msg-view-department').text(dParentName + " - " + dName);
        $('#msg-view-detail').html(task.attr('detail'));
    }


    function toggleView(){
        var state = $('#toggle-view-btn').text() == '担当部門のみ表示' ? true : false;

        if(state){
            $(".dep-table").hide();

            for(var i = 0; i < Object.keys(deplist2).length; i++){
                if($.inArray(deplist2[i]['id'], userlist[userId]['department']) >= 0){
                    $('#table-' + deplist2[i]['id']).show();
                    $('#table-' + deplist2[i]['parent']).show();
                }
            }

            $('#toggle-view-btn').text('全部門を表示');
        } else {
            $(".dep-table").show();

            $('#toggle-view-btn').text('担当部門のみ表示');
        }
    }

    function setFocus(sel){
        setTimeout(function(){
            //webkit,geckoにはfocus()にsetTimeoutが必要
            $(sel).focus();//入力欄にフォーカス
        },200);
    }

</script>


<div id="memo" style="display: none">
    <label for="memo-text" style="color: black; font-size: 20px;">メモ：</label>
    <textarea id="memo-text" rows="13" style="font-size:18px;"></textarea>

    <div class="" style="text-align: center;padding-top:25px;">

    <button type="button" class="btn btn-info" style="width:100px; " onclick="closeModal()">閉じる</button>
        <button type="button" id="memo-submit-button" class="btn btn-primary" style="" onclick="">更新して閉じる</button>
    </div>
</div>

<div id="modaal-task-edit" class="modaal-task-edit" style="display:none; margin: 10px auto;">

    <h3 style="text-align: center" id="task-edit-title"></h3>
    <p id="task-edit-department"></p>

    <form id="task-edit-form" action="Edit" method="post">
        <input type="hidden" name="action" id="action-task" value="">
        <input type="hidden" name="task-id" id="action-id" value="">

        <div class="form-group">

        <label for="task-edit-caption">タスク名:</label> <span id="task-edit-large-caption" style="display: none; color:red; font-size: 13px;">文字数がオーバーしています</span><span id="task-edit-empty-caption" style="display: none; color:red; font-size: 13px;">入力は必須です</span>
        <input type="text" class="form-control" name="task-caption" id="task-edit-caption">
            </div>

        <div class="form-group">
            <label for="task-edit-department">部門:</label>

            <select class="form-control" id="task-edit-department" name="task-department">

                <?php

                foreach ($departments as $dep){
                    if(!$dep->is_sub){
                        echo "<optgroup label='{$dep->name}'>";

                        foreach ($dep->sub as $sub){
                            echo "<option id='task-edit-department-{$sub->id}' value='{$sub->id}'>{$dep->name} - {$sub->name}</option>";
                        }

                        echo '</optgroup>';
                    }
                }

                ?>

            </select>

        </div>

        <div class="form-group">
            <label for="task-edit-worker-radio">作業者:</label><span id="task-edit-worker-info" style="display:none; font-size: 12px; padding-left: 10px;">※タスク作成者または社員のみ編集可</span>

            <div id="task-edit-worker-div">

            <section id="task-edit-worker-radio">

                <input type="checkbox" name="task-worker[]" value="<?php echo $session->read('user'); ?>"  class="checkbox" checked="checked" id="task-edit-worker-checkbox<?php echo $session->read('user'); ?>" />
                <label for="task-edit-worker-checkbox<?php echo $session->read('user'); ?>" class="checkbox" id="task-edit-worker-checkbox<?php echo $session->read('user'); ?>-label" ><?php echo $users[$session->read('user')]->name; ?></label>

                <button class="btn btn-sm btn-primary" id="task-edit-worker-button" onclick="$('#worker-select-div').toggle('fast'); return false;">他の作業者…</button>



                <div id="worker-select-div" style="display: none">

                    <?php

                    foreach($users as $user){
                        if($user->id == $session->read('user')) continue;

                        echo '<input type="checkbox" class="checkbox" name="task-worker[]" value="'.$user->id.'" id="task-edit-worker-checkbox'.$user->id.'" />';
                        echo '<label id="task-edit-worker-checkbox'.$user->id.'-label" for="task-edit-worker-checkbox'.$user->id.'" class="checkbox">'.$user->name.'</label>';
                    }

                    ?>

                </div>

            </section>

            </div>

            <p id="task-edit-worker-list"></p>

        </div>

        <div class="form-group">
            <label for="task-edit-priority-radio">優先度:</label>
            <section id="task-edit-priority-radio" style="margin-top: -5px;">

                <input type="radio" name="task-priority" value="1" id="radio05" />
                <label for="radio05" class="radio">最低</label>

                <input type="radio" name="task-priority" value="2" id="radio04" />
                <label for="radio04" class="radio" style="margin-left: -16px;">低</label>

                <input type="radio" name="task-priority" value="3" id="radio03" />
                <label for="radio03" class="radio" style="margin-left: -16px;">普通</label>

                <input type="radio" name="task-priority" value="4" id="radio02" />
                <label for="radio02" class="radio" style="margin-left: -16px;">高</label>

                <input type="radio" name="task-priority" value="5" checked id="radio01" />
                <label for="radio01" class="radio" style="margin-left: -16px;">最高</label>

            </section>
        </div>

        <div class="form-inline" style="margin-top: -20px;">
            <label for="task-edit-due-date">期日(省略可):</label><span id="task-edit-due-date-info" style="display:none; font-size: 12px; padding-left: 10px;">※タスク作成者または社員のみ編集可</span>

            <div id="task-edit-due-date-div" style="display: none;">
                <input id="task-edit-due-date" name="task-due-date" class="form-control" type="text" style="margin-top:17px;margin-left:10px;margin-right:15px;width: 127px;cursor: pointer">
            </div>

            <p id="task-edit-due-date-text" ></p>
        </div>



        <div class="form-group">
        <label for="tasktask-edit-detail">タスク詳細(省略可):</label>
        <textarea id="task-edit-detail" name="task-detail" class="form-control" rows="5"></textarea>
            </div>

        <div class="form-group" id="task-edit-situation-div">
            <label for="task-edit-situation">作業状況(省略可):</label>
            <textarea id="task-edit-situation" name="task-situation" class="form-control" rows="5"></textarea>
        </div>

        <div class="form-group" style="text-align: center;">
        <button type="button" class="btn btn-md" style="width:100px;" onclick="closeModal()">閉じる</button>

        <button type="submit" id="task-edit-submit" class="btn btn-md btn-primary" style="width:100px;"></button>
            <button type="submit" id="task-edit-success" class="btn btn-md btn-success" style="width:100px;" onclick="">完 了</button>
            <button type="submit" id="task-edit-delete" class="btn btn-md btn-danger" style="width:100px;">削除</button>
            </div>

    </form>

    <div class="clearfix"></div>

</div>

<div id="modaal-task-view" class="modaal-task-view modal-view" style="display:none; margin: 10px auto;">

    <div class="form-group">
        <label for="task-view-caption" id="task-view-caption-label"></label>
        <p id="task-view-caption" class="form-content" style="color: black"></p>
    </div>


    <div class="form-inline" style="">
        <div class="form-group" style="margin-top: -12px;">
            <label for="task-view-due-date" id="task-view-due-date-label">期日：</label>
            <p id="task-view-due-date" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:35px;color: black"></p>
        </div>

        <div class="form-group" style="margin-top: -12px;">
            <label for="task-view-priority">優先度：</label>
            <p id="task-view-priority" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:15px;color: black"></p>
        </div>

        <div class="form-group" style="margin-top: -12px; margin-left:18px;" id="task-view-work-complete-user-div">
            <label for="task-view-complete-work-user">作業完了者：</label>
            <p id="task-view-work-complete-user" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:15px;color: black"></p>
        </div>
    </div>


    <div class="form-group">
        <label for="task-view-detail">タスク詳細：</label>
        <p id="task-view-detail" class="form-control" style="color: black"></p>
    </div>

    <div class="form-group" id="task-view-situation-div">
        <label for="task-view-situation">作業状況：</label>
        <p id="task-view-situation" class="form-control" style="color: black"></p>
    </div>




    <label for="task-edit-task-detail-div" style="color:blue;" onclick="$('#task-edit-task-detail-div').toggle('fast')">詳細情報...</label>
    <div id="task-edit-task-detail-div" class="form-control" style="padding-top:1px;padding-bottom:1px;margin-bottom:15px;margin-top: -0px;display: none;">


        <div class="form-group" id="task-view-work-user-div" style="margin-top:11px;">
            <label for="task-view-work-user">作業者：</label>
            <p id="task-view-work-user" class="form-control" style="width:385px;margin-top:-2px;margin-left:10px;margin-right:15px;color: black"></p>
        </div>

        <div class="form-inline" style="margin-top:-18px;">
            <div class="form-group" id="task-view-author-div">
                <label for="task-view-author">作&nbsp;&nbsp;成&nbsp;&nbsp;者：</label>
                <p id="task-view-author" class="form-content" style="margin-top:17px;margin-left:11px;margin-right:15px;color: black"></p>
            </div>

            <div class="form-group" style="margin-left:18px;">
                <label for="task-view-update-user" id="task-view-update-user-label"></label>
                <p id="task-view-update-user" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:15px;color: black"></p>
            </div>


        </div>





        <div class="form-inline"  style="margin-top: -20px;">
            <div class="form-group">
                <label for="task-view-department">部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;門：</label>
                <p id="task-view-department" class="form-content" style="margin-top:17px;margin-left:9px;margin-right:15px;color: black"></p>
            </div>
        </div>

        <div class="form-inline"  style="margin-top: -20px;">
            <div class="form-group">
                <label for="task-view-created">作成日時：</label>
                <p id="task-view-created" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:15px;color: black"></p>
            </div>

            <div class="form-group" style="margin-top: -20px;">
                <label for="task-view-updated" id="task-view-updated-label"></label>
                <p id="task-view-updated" class="form-content" style="margin-top:17px;margin-left:10px;margin-right:15px;color: black"></p>
            </div>
        </div>

    </div>


    <div class="form-group" style="text-align: center;">
        <button type="button" class="btn btn-md btn-primary" style="width:100px;" onclick="closeModal()">閉じる</button>
    </div>


    <div class="clearfix"></div>

</div>

<div id="modaal-msg-edit" class="modaal-msg-edit" style="display:none; margin: 10px auto;">

    <h3 style="text-align: center" id="msg-edit-title"></h3>

    <div style="margin-left: 66px; margin-top: 15px;">

        <form action="Edit" method="post">
            <input type="hidden" name="action" id="msg-action" value="msg-edit" >
            <input type="hidden" name="msg-id" id="msg-id" value="" >

            <div class="form-group">
                <label for="msg-edit-department">部門:</label>

                <select class="form-control" id="msg-edit-department" name="msg-department" style="width: 300px;">

                    <?php

                    foreach ($departments as $dep){
                        if(!$dep->is_sub){
                            echo "<optgroup label='{$dep->name}'>";

                            foreach ($dep->sub as $sub){
                                echo "<option id='msg-edit-department-{$sub->id}' value='{$sub->id}'>{$dep->name} - {$sub->name}</option>";
                            }

                            echo '</optgroup>';
                        }
                    }

                    ?>

                </select>

            </div>

            <div class="form-group">
                <label for="msg-detail">内容:</label>  <span id="msg-edit-empty-detail" style="display: none; color:red; font-size: 13px;">入力は必須です</span>
                <textarea id="msg-detail" name="msg-detail" class="form-control" rows="10" style="width: 300px;"></textarea>
            </div>

            <div class="form-group" style="margin-left: -5px;">
                <button type="button" class="btn btn-md" style="width:100px;" onclick="closeModal()">閉じる</button>
                <button type="submit" id="msg-edit-submit" class="btn btn-md btn-primary" style="width:100px;"></button>
                <button type="submit" onclick="" id="msg-edit-delete" class="btn btn-md btn-danger" style="width:100px;">削除</button>
            </div>

        </form>

    </div>

    <div class="clearfix"></div>

</div>

<div id="modaal-msg-view" class="modaal-msg-view" style="display:none; margin: 10px auto;">

    <h3 style="text-align: center" id="msg-view-title"></h3>


        <div class="form-inline" style="margin-top: -20px;">
            <label for="msg-view-department">部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;門：</label>
            <p id="msg-view-department" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>

        </div>

        <div class="form-inline" style="margin-top: -20px;">
            <label for="msg-view-author">記&nbsp;&nbsp;入&nbsp;&nbsp;者：</label>
            <p id="msg-view-author" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>

        </div>

        <div class="form-inline" style="margin-top: -20px;">
            <label for="msg-view-date">記入日時：</label>
            <p id="msg-view-date" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>

        </div>

        <div class="form-group">
            <label for="msg-view-detail">内容：</label>
            <p id="msg-view-detail" class="form-control" style="color: black"></p>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="button" class="btn btn-md btn-primary" style="width:100px;" onclick="closeModal()">閉じる</button>
        </div>

    <div class="clearfix"></div>

</div>

<div id="modaal-dailytask-edit" class="modaal-dailytask-edit" style="display:none; margin: 10px auto;">

    <h3 style="text-align: center" id="dailytask-edit-title"></h3>

    <form action="Edit" method="post">
        <input type="hidden" name="action" id="dailytask-action" value="">
        <input type="hidden" name="dailytask-id" id="dailytask-id" value="">

        <div class="form-group">
            <label for="dailytask-edit-caption">概要:</label>  <span id="dailytask-edit-large-caption" style="display: none; color:red; font-size: 13px;">文字数がオーバーしています</span><span id="dailytask-edit-empty-caption" style="display: none; color:red; font-size: 13px;">入力は必須です</span>
            <input type="text" name="dailytask-caption" class="form-control" id="dailytask-edit-caption">
        </div>

        <div class="form-group">
            <label for="dailytask-edit-department">部門:</label>

            <select class="form-control" id="dailytask-edit-department" name="dailytask-department">

                <?php

                foreach ($departments as $dep){
                    if(!$dep->is_sub){
                        echo "<optgroup label='{$dep->name}'>";

                        foreach ($dep->sub as $sub){
                            echo "<option id='dailytask-edit-department-{$sub->id}' value='{$sub->id}'>{$dep->name} - {$sub->name}</option>";
                        }

                        echo '</optgroup>';
                    }
                }

                ?>

            </select>

        </div>



        <div class="form-group" style="">
            <label for="dailytask-edit-worktime">作業時間:</label>  <span id="msg-edit-empty-worktime" style="display: none; color:red; font-size: 13px;">作業時間は10分以上で選択してください</span>

            <div class="form-inline" style="padding-top: 16px;" id="dailytask-edit-worktime">
                <!--input type="text" class="form-inline form-control" id="dailytask-edit-worktime-h" style="padding-top: -16px; width:50px;"-->
                <select class="form-control form-inline" id="worktime-h" name="worktime-h" style="margin-top: -16px; width:60px;">
                    <option value="0" id="worktime-h0">0</option>
                    <option value="1" id="worktime-h1">1</option>
                    <option value="2" id="worktime-h2">2</option>
                    <option value="3" id="worktime-h3">3</option>
                    <option value="4" id="worktime-h4">4</option>
                    <option value="5" id="worktime-h5">5</option>
                    <option value="6" id="worktime-h6">6</option>
                    <option value="7" id="worktime-h7">7</option>
                    <option value="8" id="worktime-h8">8</option>
                </select>
                <label for="dailytask-edit-worktime-h" style="text-align:right; padding-right: 15px; padding-left: 10px; margin-top: -12px;">時間</label>

                <select class="form-control form-inline" id="worktime-m" name="worktime-m" style="margin-top: -16px; width:70px;">
                    <option value="0" id="worktime-m0">0</option>
                    <option value="10" id="worktime-m10">10</option>
                    <option value="20" id="worktime-m20">20</option>
                    <option value="30" id="worktime-m30">30</option>
                    <option value="40" id="worktime-m40">40</option>
                    <option value="50" id="worktime-m50">50</option>
                </select>
                <!--input type="text" class="form-inline form-control" id="dailytask-edit-worktime-m" style="padding-top: -16px; width:50px;"-->
                <label for="dailytask-edit-worktime-m" style="text-align:right; padding-right: 15px; padding-left: 10px; margin-top: -12px;">分</label>
            </div>
        </div>

        <div class="form-group">
            <label for="dailytask-edit-detail">詳細(省略可):</label>
            <textarea id="dailytask-edit-detail" name="dailytask-detail" class="form-control" rows="10"></textarea>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="button" class="btn btn-md" style="width:100px;" onclick="closeModal()">閉じる</button>
            <button type="submit" id="dailytask-edit-submit" class="btn btn-md btn-primary" style="width:100px;"></button>
            <button type="submit" onclick="" id="dailytask-edit-delete" class="btn btn-md btn-danger" style="width:100px;">削除</button>
        </div>

    </form>

    <div class="clearfix"></div>

</div>


<div id="modaal-dailytask-view" class="modaal-dailytask-view modal-view" style="display:none; margin: 10px auto;">

    <h3 id="dailytask-view-title"></h3>

    <form action="Edit" method="post">

        <div class="form-inline" style="margin-top:-17px;">
            <label for="dailytask-view-department">部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;門：</label>
            <p id="dailytask-view-department" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>
        </div>

        <div class="form-inline" style="margin-top:-17px;">
            <label for="dailytask-view-author">作&nbsp;&nbsp;業&nbsp;&nbsp;者：</label>
            <p id="dailytask-view-author" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>
        </div>

        <!--div class="form-group">
            <label for="dailytask-view-date">完了日時:</label>
            <p id="dailytask-view-date" class="form-control" style="color: black"></p>
        </div-->

        <div class="form-inline" style="margin-top:-17px;">
            <label for="dailytask-view-worktime">作業時間：</label>

            <div class="form-inline" id="dailytask-view-worktime" style="margin-top:13px; margin-left: 15px;">
                <p id="dailytask-view-worktime-h" class="worktime-h" style="margin-top: 3px; color: black"></p>
                <label for="dailytask-view-worktime-h" class="worktime-h" style="text-align:right; padding-right: 15px; padding-left: 10px; margin-top: -12px;">時間</label>

                <p id="dailytask-view-worktime-m" style="margin-top: 3px; color: black"></p>
                <label for="dailytask-view-worktime-m" style="text-align:right; padding-right: 15px; padding-left: 10px; margin-top: -12px;">分</label>
            </div>
        </div>

        <div class="form-inline" style="margin-top:-17px;">
            <label for="dailytask-view-caption">概&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;要：</label>
            <p id="dailytask-view-caption" class="form-content" style="margin-top:17px;margin-left:14px;color: black"></p>
        </div>

        <div class="form-group">
            <label for="dailytask-view-detail">詳細：</label>
            <p id="dailytask-view-detail" class="form-control" style="color: black"></p>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="button" class="btn btn-md btn-primary" style="width:100px;" onclick="closeModal();">閉じる</button>

        </div>

    </form>

    <div class="clearfix"></div>

</div>


<div style=" padding: 30px 0px 60px 0px;">


    <!--<table width="1400px" id="table" class="table table-bordered table-hover table-striped table-responsive">-->
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




