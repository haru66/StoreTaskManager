<?php
/**
 * Created by PhpStorm.
 * User: haruki
 * Date: 18/06/08
 * Time: 5:44
 */


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        店舗スタッフ管理 - StoreTaskManager
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('bootstrap.css') ?>
    <?= $this->Html->css('bootstrap-datepicker.css') ?>
    <?= $this->Html->css('font.css') ?>
    <?= $this->Html->css('modaal.css') ?>

    <?= $this->Html->script('jquery-2.2.4.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('bootstrap-datepicker.min.js') ?>
    <?= $this->Html->script('bootstrap-datepicker.ja.min.js') ?>
    <?= $this->Html->script('modaal.js') ?>


    <script>

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



        $userList = array();

        echo 'var userlist = {};';

        foreach($users as $user){
            echo 'userlist["'.$user->id.'"] = { name: "'.$user->name.'", role:'.$user->role.', require_password: '.$user->require_password.', department:['.$user->department.']};';
        }

        ?>

        var nowSelectId = 0;

        $(function(){

            $('.modaal').modaal({
                animation_speed: 50,
                width: 500,
                start_open: false,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });

            $("#user-name").change(function(){
                var id = $(this).val();

                if(id == 0){
                    $('#user-detail').hide('fast');
                } else {
                    $('#user-detail').show('fast');
                }

                //alert(deplist[userlist[id]['department']]['name']);

                nowSelectId = id;

                var role = ['アルバイト', '社員', 'マネージャー'];
                $('#dep-list-link').show();

                if(userlist[id]['role'] != 1) {
                    $('#user-dep').text("全部門");
                    $('#dep-list-link').hide();
                } else {
                    $('#user-dep').empty();
                    var dom = $("#dep-data-"+nowSelectId).attr('value');
                    $('#user-dep').append(dom);
                }
                if(userlist[id]['role'] == 3){
                    $('#role-edit').hide();
                    $('#user-edit-div').hide();
                } else {
                    $('#role-edit').show();
                    $('#user-edit-div').show();
                }
                $('#user-role').text(role[userlist[id]['role']-1]);
            });

            $('#submit-role-form').click(function() {

                if (confirm('権限を変更してもよろしいですか？')) {
                    var data = {
                        action: 'edit-role',
                        'edit-id': nowSelectId,
                        role: userlist[nowSelectId]['role']
                    };

                    $.ajax({
                        url: './user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        // 成功時の処理
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("権限を変更しました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                            $('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;

                }

                return false;
            });

            $('#name-edit-submit').click(function(){
                if($('#edit-name').val() == ''){
                    $('#error-name-empty').show();
                    return false;
                }

                if(confirm('変更してもよろしいですか？')) {
                    var data = {
                        action: 'edit-name',
                        name: $('#edit-name').val(),
                        id: $('#name-edit-id').val()
                    };

                    $.ajax({
                        url: './user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        // 成功時の処理
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("変更しました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                }
            });



            $('#reset-password-submit').click(function () {
                $('#reset-password-id').val(nowSelectId);

                if(confirm('パスワードをリセットします。よろしいですか？\n(リセット後のパスワードは、「password」です。)')) {
                    var data = {
                        action : 'reset-password',
                        id : nowSelectId,
                    };

                    $.ajax({
                        url: '../stores/user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("パスワードをリセットしました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                            $('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                } else {
                    return false;
                }
            });


            $('#dep-toggle-btn').click(function () {
                if($('#dep-toggle-btn').val() == 'すべて展開'){
                    $('.sub-dep').show('fast');
                    $('#dep-toggle-btn').val('すべて隠す');
                } else {
                    $('.sub-dep').hide('fast');
                    $('#dep-toggle-btn').val('すべて展開');
                }
            });

            $('#dep-f-toggle-btn').click(function () {
                if($('#dep-f-toggle-btn').val() == 'すべて展開'){
                    $('.sub-dep-f').show('fast');
                    $('#dep-f-toggle-btn').val('すべて隠す');
                } else {
                    $('.sub-dep-f').hide('fast');
                    $('#dep-f-toggle-btn').val('すべて展開');
                }
            });


            $('#add-user-form-submit').click(function () {
                var n = $('#add-user-name').val();
                var err = false;

                $('#error-user-name-empty').hide();
                $('#error-add-password-empty').hide();
                $('#error-add-password-length').hide();
                $('#error-add-password2-empty').hide();
                $('#error-add-password-invalid').hide();
                $('#error-dep-empty').hide();

                if(n == ''){
                    $('#error-user-name-empty').show();
                    err = true;
                }
                if($('#add-user-password').val() == ''){
                    $('#error-add-password-empty').show();
                    err = true;
                }
                else if($('#add-user-password').val().length < 4){
                    $('#error-add-password-length').show();
                    err = true;
                }
                if($('#add-user-password2').val() == ''){
                    $('#error-add-password2-empty').show();
                    err = true;
                }
                else if($('#add-user-password').val() != $('#add-user-password2').val()){
                    $('#error-add-password-invalid').show();
                    err = true;
                }
                var check_count = $('#department-list :checked').length;
                if(check_count == 0){
                    $('#error-dep-empty').show();
                    err = true;
                }
                if(err) return false;

                if(confirm('入力の内容でユーザーを追加します。よろしいですか？')) {
                    var checked = [];
                    $('[name="departments[]"]:checked').each(function(){
                        checked.push($(this).val());
                    });
                    var pw = $('#add-user-password').val();
                    var role = $('input[name="role"]:checked').val();
                    var data = {
                        action : 'add',
                        name : n,
                        role: role,
                        password: pw,
                        department: checked,
                        'require_password': 1
                    };

                    $.ajax({
                        url: './user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("ユーザー "+n+"を追加しました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                            $('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                } else {
                    return false;
                }
            });


            $('#department-edit-submit-f').click(function () {
                var check_count = $('#department-list-f :checked').length;
                if(check_count == 0){
                    $('#error-dep-empty-f').show();
                    return false;
                }

                if(confirm('担当部門を変更してもよろしいですか？')) {
                    var checked = [];
                    $('[name="departments[]"]:checked').each(function(){
                        checked.push($(this).val());
                    });
                    var data = {
                        action : 'edit-department',
                        id : nowSelectId,
                        department: checked
                    };

                    $.ajax({
                        url: './user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("担当部門を変更しました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                            $('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                } else {
                    return false;
                }
            });
        });

        function showRoleForm(){
            $('#role-user-name').text(userlist[nowSelectId]['name']);
            $('#role-from').text(userlist[nowSelectId]['role'] == 1 ? "アルバイト" : "社員");
            $('#role-to').text(userlist[nowSelectId]['role'] == 2 ? "アルバイト" : "社員権限");
            $('#role-now').val(userlist[nowSelectId]['role']);
            $('#role-edit-id').val(nowSelectId);
        }

        function deleteUser(){

            if(confirm(userlist[nowSelectId]['name'] + ' さんを削除してもよろしいですか？')){
                if(confirm('この操作は取り消しできません。\n本当に削除してもよろしいですか？')){
                    var data = {
                        action : 'delete-user',
                        id : nowSelectId,
                    };

                    $.ajax({
                        url: './user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("ユーザーを削除しました。");
                            $('.modaal').modaal('close');
                            location.reload();
                        } else {
                            alert(res.message);
                            $('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                }
            }

            return false;
        }


        function nameEdit(){
            $('#edit-name').val('').focus();
            setFocus('#edit-name');
            $('#error-name-empty').hide();
            $('#name-edit-id').val(nowSelectId);

        }

        function pwEdit(){
            //$('#edit-password').val('');
            $('#error-password-empty').hide();
            $('#password-edit-id').val(nowSelectId);

        }

        function setFocus(sel){
            setTimeout(function(){
                //webkit,geckoにはfocus()にsetTimeoutが必要
                $(sel).focus();//入力欄にフォーカス
            },200);
        }


        function getDep(){

            $.ajax({
                url: './user?mode=depAjax&id='+nowSelectId,
                type: 'get',
            }).done(function (data, status, jqXHR) {
                /*var res = JSON.parse(data);
                if (res.res != 'error') {
                    $('##department-list-f').append(res.html);
                } else {

                }*/
                $('#department-list-f').empty();
                var dom = $(data);
                $('#department-list-f').append(dom);
                $('#dep-f-toggle-btn').val('すべて展開');
            }).fail(function (jqXHR, status, error) {
                // 失敗時の処理
                alert('Error : ' + error);
            });


            return true;


        }
    </script>
</head>
<body style="background-color:#B2EBF6">
<?php
foreach($users as $user) {
    $uDep = explode(',', $user->department);

    echo '<department-data id="dep-data-'.$user->id.'" value="';

    foreach ($departments as $dep) {

        if (!$dep->is_sub) {

            $cnt = 0;
            foreach ($dep->sub as $sub) {
                if(in_array($sub->id, $uDep)) {
                    if($cnt == 0) {
                        echo "<div><span style='font-weight: bold; font-size: 18px;'>" . $dep->name . "</span>";
                        echo "<div style='padding-left: 15px;'>";
                    }
                    echo "<span style='padding-right: 10px;display: inline-block;'>{$sub->name}</span>";
                    $cnt++;
                }
            }

            if($cnt != 0)echo "</div>";

        }

        if($cnt != 0)echo "</div>";
    }

    echo '">';

}

?>



<div id="modaal-role-form" class="modaal modaal-role-form" style="display: none;margin: 10px auto;">

    <div class="form-group" style="text-align: center">

        <h3 style="text-align: center">ユーザー権限を変更</h3>
        <p><span id="role-user-name" style="font-weight: bold;"></span> さんを <span id="role-from" style="font-weight: bold;"></span> から <span id="role-to" style="font-weight: bold;"></span> に切り替える</p>

        <!--form action="user" method="post" id="role-form"-->

            <div class="form-group">

                <input type="hidden" id="role-now" name="role" value="">
                <input type="hidden" name="action" value="edit-role">
                <input type="hidden" name="edit-id" id="role-edit-id" value="">

                <div class="form-group" style="text-align: center">
                    <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                    <button class="form-control btn btn-primary" id="submit-role-form" onclick="" style="margin-left:15px;width:120px;" type="submit">実行</button>
                </div>

            </div>

        <!--/form-->

    </div>

</div>


<div id="modaal-user-add-form" class="modaal modaal-user-add-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <h3 style="text-align: center">ユーザー追加フォーム</h3>



            <div class="form-group">

                <input type="hidden" name="action" value="add">
                <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>

                <label for="add-user-name">ユーザー名：</label>
                <span id="error-user-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <input type="text" name="name" id="add-user-name">

                <label for="add-user-password">パスワード：</label>
                <span id="error-add-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <span id="error-add-password-length" style="display:none; color:red; font-size: 13px;">パスワードは4文字以上必要です</span>
                <input type="password" name="password" id="add-user-password">

                <label for="add-user-password2">パスワード(再入力)：</label>
                <span id="error-add-password2-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <span id="error-add-password-invalid" style="display:none; color:red; font-size: 13px;">パスワードが一致しません</span>
                <input type="password" id="add-user-password2">

                <div id="add-user-role-div" class="form-group">
                    <label for="add-user-role">権限：</label>
                    <input type="radio" name="role" value="1" checked id="radio01" />
                    <label for="radio01" class="radio">アルバイト</label>

                    <input type="radio" name="role" value="2" id="radio02" />
                    <label for="radio02" class="radio">社員</label>
                </div>

                <div class="form-control" style="margin-top: 20px;">

                    <div class="" style="padding-bottom: 10px;padding-top: 8px;">
                    <label for="department-list">部門担当：</label><span id="error-dep-empty" style="display:none; color:red; font-size: 13px;">最低1部門は選択してください</span>
                        <input type="button" class="btn btn-sm btn-info form-inline" style="width:130px;margin-right:10px;float: right" id="dep-toggle-btn" value="すべて展開">
                    </div>
                    <div id="department-list">

                    <?php

                    foreach($departments as $dep){

                        echo '<div class="form-group">';
                        echo '<button class="btn btn-sm btn-primary form-control" onclick="$(\'#main-'.$dep->id.'\').toggle(\'fast\'); return false;">'.$dep->name . '</button>';

                        if(!$dep->is_sub){

                            echo "<div id='main-{$dep->id}' class='sub-dep form-control' style='margin-top: 5px;display: none;'>";

                            foreach($dep->sub as $sub){
                                echo "<input type='checkbox' name='departments[]' value='{$sub->id}' id='checkbox-{$sub->id}' />";
                                echo "<label style='' for='checkbox-{$sub->id}' class='checkbox'>{$sub->name}</label>";
                            }

                            echo '</div>';

                        }

                        echo '</div>';
                    }

                    ?>

                    </div>

                </div>

                <div class="form-group" style="padding-top: 20px;text-align: center">
                    <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                    <button class="form-control btn btn-primary" id="add-user-form-submit" style="margin-left:15px;width:120px;" type="button">追加</button>
                </div>

            </div>



    </div>

</div>


<div id="modaal-department-form" class="modaal modaal-department-form" style="display: none;margin: 10px auto;">

    <div class="form-control">

        <div class="" style="padding-bottom: 10px;padding-top: 8px;">
            <label for="department-list-f">担当部門：</label><span id="error-dep-empty-f" style="display:none; color:red; font-size: 13px;">最低1部門は選択してください</span>
            <input type="button" class="btn btn-sm btn-info form-inline" style="width:130px;margin-right:10px;float: right" id="dep-f-toggle-btn" value="すべて展開">
        </div>
        <div id="department-list-f">



        </div>

        <div class="form-group" style="padding-top: 20px; text-align: center">
            <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">閉じる</button>
            <button class="form-control btn btn-primary" id="department-edit-submit-f" style="margin-left:15px;width:120px;" type="submit">変更</button>
        </div>

    </div>

</div>


<div id="modaal-name-edit-form" class="modaal modaal-name-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <h3 style="text-align: center">ユーザー名を変更</h3>



        <div class="form-group">

            <label for="edit-name" style="text-align: left;">ユーザー名：</label><span id="error-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
            <input type="text" id="edit-name" name="name" value="">
            <input type="hidden" name="id" id="name-edit-id" value="">
            <div class="form-group" style="text-align: center">
                <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                <button class="form-control btn btn-primary" id="name-edit-submit" style="margin-left:15px;width:120px;" type="submit">変更</button>
            </div>

        </div>



    </div>

</div>


<div id="modaal-pw-edit-form" class="modaal modaal-pw-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <h3 style="text-align: center">パスワード変更</h3>

        <form action="user" method="post" id="password-edit-form">

            <div class="form-group">

                <label for="edit-password" style="text-align: left;">パスワード：</label><span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <input type="text" id="edit-password" name="password" value="">

                <input type="hidden" name="action" value="edit-pw">
                <input type="hidden" name="id" id="password-edit-id" value="">

                <div class="form-group" style="text-align: center">
                    <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                    <button class="form-control btn btn-primary" onclick="pwEdit()" style="margin-left:15px;width:120px;" type="submit">変更</button>
                </div>

            </div>

        </form>

    </div>

</div>


<div id="modaal-pw-reset-form" class="modaal modaal-reset-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <p><h3 style="text-align: center">パスワードのリセット</h3></p>

        <div class="form-group">

            <p>
                <button class="form-control btn btn-danger" id="reset-password-submit" type="button">リセット実行</button>
            </p>

            <p>
                <button class="form-control btn btn-info" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
            </p>
        </div>


    </div>

</div>


<div id="modaal-dep-edit-form" class="modaal modaal-dep-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <h3 style="text-align: center">パスワード変更</h3>

        <form action="user" method="post" id="password-edit-form">

            <div class="form-group">

                <label for="edit-password" style="text-align: left;">パスワード：</label><span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <input type="text" id="edit-password" name="password" value="">

                <input type="hidden" name="action" value="edit-pw">
                <input type="hidden" name="id" id="password-edit-id" value="">

                <div class="form-group" style="text-align: center">
                    <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                    <button class="form-control btn btn-primary" onclick="pwEdit()" style="margin-left:15px;width:120px;" type="submit">変更</button>
                </div>

            </div>

        </form>

    </div>

</div>



<div style="margin: 10px auto;">



    <div id="container" class="shadow" style="padding:30px;width: 360px; background-color: white; margin: 150px auto; text-align: center">


        <p><h3 style="text-align: center;">店舗スタッフ管理</h3></p>
        <p><h5 style="text-align: center;"><span style='display: inline-block;'><?= $storeParentName ?></span> <span style='display: inline-block;'><?= $storeName ?></span> </h5></p>

        <div>

            <div class="form-group">

                <?php
                /*
                echo '<select style="text-align: center;" class="form-control" id="user-name">';
                echo "<option value='0'>ユーザーを選択</option>";

                foreach($users as $user){
                    if($user->id == 0) continue;
                    echo "<option value='{$user->id}'>{$user->name}</option>";
                }

                echo '</select>';
                */


                echo '<select style="text-align: center;" class="form-control" id="user-name">';
                echo "<option value='0' selected='selected'>ユーザーを選択</option>";

                $cnt1 = 0;
                $cnt2 = 0;
                $cnt3 = 0;
                foreach($users as $user){
                    if($user->id == 0) continue;

                    if($user->role == 1) $cnt1++;
                    if($user->role == 2) $cnt2++;
                    if($user->role == 3) $cnt3++;
                }

                $c = 0;
                $print = '';
                if($cnt1 != 0) {
                    $print .= "<optgroup label='アルバイト'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 1 || $user->deleted == 1) continue;

                        //$s = $user->id == $userId ? 'selected="selected"' : '';
                        $print .= "<option value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if($c != 0){
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }

                if($cnt2 != 0) {
                    $print .= "<optgroup label='社員'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 2 || $user->deleted == 1) continue;

                        //$s = $user->id == $userId ? 'selected="selected"' : '';
                        $print .= "<option value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if($c != 0){
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }

                /*
                if($cnt3 != 0) {
                    $print .= "<optgroup label='管理者'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 3 || $user->deleted == 1) continue;

                        //$s = $user->id == $userId ? 'selected="selected"' : '';
                        $print .= "<option value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if ($c != 0) {
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }*/

                //$show = $user->role >= 2 || $user->require_password == 1 ? 'block' : 'none';

                echo '</select>';

            ?>



            </div>

            <div class="form-group" id="user-detail" style="display: none;text-align: left">
                <label for="user-dep">担当部門：</label><a id="dep-list-link" onclick="getDep()" class="modaal" style="margin-left: 10px; font-size: 12px;" href="#modaal-department-form">担当部門を変更</a>

                    <div class="form-control" id="user-dep">



                    </div>






                <label for="user-role">ユーザー権限：</label><a id="role-edit" onclick="showRoleForm()" class="modaal" style="margin-left: 10px; font-size: 12px;" href="#modaal-role-form">権限を変更</a>
                <p class="form-control" id="user-role"></p>

                <div id="user-edit-div">

                    <p><button class="form-control btn btn-info modaal" href="#modaal-name-edit-form" onclick="nameEdit()" id="user-name-edit">ユーザー名変更</button></p>

                    <p><button class="form-control btn btn-primary modaal" href="#modaal-pw-reset-form" id="user-password-edit">パスワードリセット</button></p>

                    <p><button class="form-control btn btn-danger" id="user-delete" onclick="deleteUser()">ユーザー削除</button></p>

                </div>

            </div>


            <div class="form-group" style="padding-top:30px;">
                <button class="form-control btn btn-info" style=" width: 130px;" onclick="history.back()">戻る</button>
                <button class="form-control btn btn-primary modaal" href="#modaal-user-add-form" style="text-align: center; padding-left: 10px;width: 130px;" onclick="setFocus('#add-user-name');">ユーザー追加</button>
            </div>

        </div>

    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>


