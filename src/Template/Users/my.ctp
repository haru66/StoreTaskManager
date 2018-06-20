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
        マイページ - StoreTaskManager
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


        $(function(){



            $('.modaal-form').modaal({
                animation_speed: 50,
                width: 400,
                start_open: true,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });


            $('#edit-password-form-submit').click(function() {
                if($("#edit-password").val() == ''){
                    $("#error-password-empty").show();
                    $("#error-password-length").hide();
                    return false;
                }
                if($("#edit-password").val().length < 4){
                    $("#error-password-length").show();
                    $("#error-password-empty").hide();
                    return false;
                }
                if(confirm('変更してもよろしいですか？')){
                    var data = {
                        action: 'edit-password',
                        password: $('#edit-password').val()
                    };

                    $.ajax({
                        url: './my',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        // 成功時の処理
                        var res = JSON.parse(data);
                        if (res.res != 'error') {
                            $('.modaal').modaal('close');
                            alert("パスワードを変更しました。");
                            $('#edit-password-div').hide('fast');
                            //location.reload();
                        } else {
                            alert(res.message);
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

            $('#mode-change-submit').click(function(){
                //$("#mode-change-id").val();
                if(confirm('変更してもよろしいですか？')){
                    //alert('変更しました！');
                    var data = {
                        action: 'mode-change',
                    };

                    $.ajax({
                        url: './my',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        // 成功時の処理
                        var res = JSON.parse(data);
                        if (res.res != 'error') {
                            $('.modaal').modaal('close');
                            alert("変更しました。");
                            /*$('#edit-password-require-div').hide('fast');
                            var old = $('#use-password-setting').text();
                            var new_ = $('#mode-change-submit').val();
                            $('#use-password-setting').text(new_);
                            $('#mode-change-submit').val(old);*/
                            location.reload();
                        } else {
                            alert(res.message);
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


    </script>
</head>
<body>

<div id="modaal-form" class="modaal-form" style="margin: 10px auto;">

        <p><h3 style="text-align: center;">ユーザー設定</h3></p>


    <div class="form-group form-control">

        <label for="user-name">ユーザー名：</label>
        <p id="user-name" class="form-control"><?= $user ?></p>

        <label for="user-dep">担当部門：</label>

        <div class="form-control" id="user-dep">

            <?php

                if($my->role == 1) {

                    foreach ($departments as $dep) {

                        if (!$dep->is_sub) {

                            $cnt = 0;
                            foreach ($dep->sub as $sub) {
                                $a = explode(',', $my->department);
                                if (in_array($sub->id, $a)) {
                                    if ($cnt == 0) {
                                        echo "<div><span style='font-weight: bold; font-size: 18px;'>" . $dep->name . "</span>";
                                        echo "<div style='padding-left: 15px;'>";
                                    }
                                    echo "<span style='padding-right: 10px;display: inline-block;'>{$sub->name}</span>";
                                    $cnt++;
                                }
                            }

                            if ($cnt != 0) echo "</div>";

                        }

                        if ($cnt != 0) echo "</div>";
                    }

                } else {
                    echo '<span>全部門</span>';
                }

            ?>

        </div>

        <label for="user-role">権限：</label>
        <p id="user-role" class="form-control"><?= $role ?></p>

    </div>

    <p><h4 onclick="$('#edit-password-div').toggle('fast');$('#edit-password').focus()" style="cursor:pointer;text-decoration:underline;color:royalblue;text-align:center; padding-top: 25px;">パスワード変更</h4></p>

        <div class="form-group form-control" style="display: none;" id="edit-password-div">

            <!--form id="edit-password-form" action="my" method="post">

                <!--p><a class="btn btn-primary btn-lg form-control" href="password">パスワード設定</a></p-->
            <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
            <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                <label for="edit-password">新しいパスワード：</label>
                <span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                <span id="error-password-length" style="display:none; color:red; font-size: 13px;">パスワードは4文字以上必要です</span>
                <input type="password" class="form-control" id="edit-password" name="password" value="">
                <input type="hidden" class="form-control" id="edit-password" name="action" value="edit-password">


                <input type="submit" id="edit-password-form-submit" class="form-control btn btn-primary btn-group-lg" value="変更">

            <!--/form-->

        </div>

    <div id="password-require-form-div" style="<?= $role != "アルバイト" ? "display:none;" : "" ?>">

        <p><h4 onclick="$('#edit-password-require-div').toggle('fast')" style="cursor:pointer;text-decoration:underline;color:royalblue;text-align:center; padding-top: 25px;">ログイン方法設定</h4></p>

        <div class="form-group form-control" id="edit-password-require-div" style="display: none;">


                <!--p><a class="btn btn-primary btn-lg form-control" href="password">パスワード設定</a></p-->

                <label for="use-password-setting">現在の設定：</label>
                <p id="use-password-setting" class="form-control">ログインにパスワードを<span style="font-weight: bold;">要求<?= $usepw ?></span></p>




                <input type="submit" id="mode-change-submit" class="form-control btn btn-primary btn-group-lg" value="パスワードを要求<?= $usepw == "する" ? "しない" : "する" ?>ように変更">


        </div>

    </div>

    <p style="margin-top: 50px;"><a class="btn btn-info btn-lg form-control" href="../">タスク一覧へ戻る</a></p>

        <!--div class="form-group">

        </div>


        <div class="form-group" style="text-align: center;">

        </div-->


    <div class="clearfix"></div>

</div>
</body>
</html>
