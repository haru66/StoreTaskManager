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
        マネージャーリスト - StoreTaskManager
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

        $userList = array();

        echo 'var userlist = {};';

        foreach($users as $user){
            echo 'userlist["'.$user->id.'"] = { name: "'.$user->name.'", role:'.$user->role.', require_password: '.$user->require_password.', department:['.$user->department.']};';
        }

        ?>


        var nowSelectId = 0;
        var nowStoreName = '';

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

                if(id != 0) {
                    $('#user-edit-div').show('fast');
                } else {
                    $('#user-edit-div').hide('fast');
                }

                nowSelectId = id;

            });

            $('#name-edit-submit').click(function () {
                if ($('#edit-name').val() == '') {
                    $('#error-name-empty').show();
                    return false;
                }

                $('#name-edit-id').val(nowSelectId);

                if(confirm(userlist[nowSelectId]['name'] + 'さんのマネージャー名を ' + $('#edit-name').val() + 'に変更してもよろしいですか？')) {
                    var data = {
                        action : 'edit-name',
                        id : nowSelectId,
                        name: $('#edit-name').val()
                    };

                    $.ajax({
                        url: '../stores/user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("マネージャー名を変更しました。");
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

        });

        function deleteUser(){

            if(confirm(userlist[nowSelectId]['name'] + ' さんを削除してもよろしいですか？')){
                if(confirm('この操作は取り消しできません。\n本当に削除してもよろしいですか？')){
                    var data = {
                        action : 'delete-user',
                        id : nowSelectId,
                    };

                    $.ajax({
                        url: '../stores/user',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("マネージャーを削除しました。");
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

    </script>
</head>
<body style="background-color:#B2EBF6">

<div id="modaal-name-edit-form" class="modaal modaal-name-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group">

        <h3 style="text-align: center">マネージャー名を変更</h3>



        <div class="form-group">

            <label for="edit-name" style="text-align: left;">マネージャー名：</label><span id="error-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <input type="hidden" name="action" value="edit-name">
                <input type="text" id="edit-name" name="name" value="">
                <input type="hidden" name="id" id="name-edit-id" value="">
            <div class="form-group" style="text-align: center">
                <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                <button class="form-control btn btn-primary" id="name-edit-submit" style="margin-left:15px;width:120px;" type="submit">変更</button>
            </div>


        </div>



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




<div style="margin: 10px auto;">



    <div id="container" class="shadow" style="padding:30px;width: 360px; background-color: white; margin: 150px auto; text-align: center">


        <p><h3 style="text-align: center;">マネージャーリスト</h3></p>

        <div>

            <div class="form-group">

                <?php

                echo '<select style="text-align: center;" class="form-control" id="user-name">';
                echo "<option value='0' selected='selected'>マネージャーを選択</option>";


                foreach ($users as $user) {
                    if ($user->id == 0 || $user->deleted == 1) continue;

                    echo "<option value='{$user->id}'>{$user->name}</option>";

                    $c++;
                }

                echo '</select>';

                ?>



            </div>


            <div id="user-edit-div" style="display: none">

                <p><button class="form-control btn btn-info modaal" href="#modaal-name-edit-form" onclick="" id="user-name-edit">マネージャー名変更</button></p>

                <p><button class="form-control btn btn-primary modaal" href="#modaal-pw-reset-form" id="user-password-edit">パスワードリセット</button></p>

                <p><button class="form-control btn btn-danger" id="user-delete" onclick="deleteUser()">マネージャー削除</button></p>

            </div>



            <div class="form-group" style="padding-top:30px;">
                <button class="form-control btn btn-info" style="" onclick="history.back()">戻る</button>
            </div>

        </div>

    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>