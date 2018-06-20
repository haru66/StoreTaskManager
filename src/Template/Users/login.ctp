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
        ユーザーログイン - StoreTaskManager
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


        $(function(){

            $('.modaal-login-form').modaal({
                animation_speed: 50,
                width: 300,
                start_open: true,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });

            $('#login-form').submit(function(){
                if($("#user-id option:selected").text() == 'ユーザーを選択'){
                    alert('ユーザーを選択してください。');
                    return false;
                }

                return true;
            });

            $("#user-id").change(function(){
                var id = $(this).val();

                if(userlist[id]['role'] >= 2 || userlist[id]['require_password'] == 1){
                    $('#user-password-div').show('fast');
                } else {
                    $('#user-password-div').hide('fast');
                }
            });

        });




    </script>
</head>
<body>

<div id="modaal-login-form" class="modaal-login-form" style="margin: 10px auto;">

    <p><h5 style="text-align: center;"><span style='display: inline-block;'><?= $storeParentName ?></span> <span style='display: inline-block;'><?= $storeName ?></span></h5></p>

    <p id="error-msg" style="display: <?= empty($errmsg) ? 'none;' : 'block;'; ?> color: red;"><?= $errmsg ?></p>

    <form id="login-form" action="Login" method="post">
        <div class="form-group">

            <!--label for="user-id">ユーザー:</label-->

            <?php

                echo '<select style="text-align: center;" class="form-control" id="user-id" name="user-id">';
                echo "<option value='0'>ユーザーを選択</option>";

                $cnt1 = 0;
                $cnt2 = 0;
                $cnt3 = 0;
                foreach ($users as $user) {
                    if ($user->id == 0) continue;

                    if ($user->role == 1) $cnt1++;
                    if ($user->role == 2) $cnt2++;
                    if ($user->role == 3) $cnt3++;
                }

                $c = 0;
                $print = '';
                if ($cnt1 != 0) {
                    $print .= "<optgroup label='アルバイト'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 1 || $user->deleted == 1) continue;

                        $s = $user->id == $userId ? 'selected' : '';
                        $print .= "<option {$s}='{$s}' value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if ($c != 0) {
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }

                if ($cnt2 != 0) {
                    $print .= "<optgroup label='社員'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 2 || $user->deleted == 1) continue;

                        $s = $user->id == $userId ? 'selected' : '';
                        $print .= "<option {$s}='{$s}' value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if ($c != 0) {
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }

                if ($cnt3 != 0) {
                    $print .= "<optgroup label='マネージャー'>";
                    foreach ($users as $user) {
                        if ($user->id == 0 || $user->role != 3 || $user->deleted == 1) continue;

                        $s = $user->id == $userId ? 'selected' : '';
                        $print .= "<option {$s}='{$s}' value='{$user->id}'>{$user->name}</option>";

                        $c++;
                    }
                    $print .= "</optgroup>";

                    if ($c != 0) {
                        echo $print;
                    }
                    $c = 0;
                    $print = '';
                }


                //$show = $user->role >= 2 || $user->require_password == 1 ? 'block' : 'none';

                echo '</select>';


            ?>

        </div>

        <div class="form-group" id="user-password-div" style="display: <?= $pwRequired; ?>;">

            <label for="user-password">パスワード:</label>
            <span id="error-password-empty" style="display:none; color:red; font-size: 13px;">パスワードが入力されていません</span>
            <input type="password" class="form-control" name="password" id="user-password">
        </div>


        <div class="form-group" style="text-align: center;">
            <a class="btn btn-md btn-info" href="../stores/login" style="width:100px;">店舗選択</a>
            <button type="submit" id="login-form-submit" class="btn btn-md btn-primary" style="width:100px;">ログイン</button>
        </div>

    </form>

    <div class="clearfix"></div>

</div>
</body>
</html>
