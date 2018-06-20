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
        店舗パスワード変更 - StoreTaskManager
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
            $('.modaal-control-panel').modaal({
                animation_speed: 50,
                width: 450,
                start_open: true,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });

            $('#form-submit').click(function () {
                $('#error-password-empty').hide();
                $('#error-oldpassword-empty').hide();
                var err = false;
                if($('#password').val() == ''){
                    $('#error-password-empty').show();
                    err = true;
                }
                if($('#oldpassword').val() == ''){
                    $('#error-oldpassword-empty').show();
                    err = true;
                }
                if(err) return false;


                if (confirm('変更してもよろしいですか？')) {
                    var data = {
                        action: 'edit-role',
                        oldpassword: $('#oldpassword').val(),
                        password: $('#password').val()
                    };

                    $.ajax({
                        url: './password',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        // 成功時の処理
                        var res = JSON.parse(data);
                        if (res.res != 'error') {
                            $('.modaal').modaal('close');
                            alert("パスワードを変更しました。");
                            location.reload();
                        } else {
                            alert(res.message);
                            //$('.modaal').modaal('close');
                        }
                    }).fail(function (jqXHR, status, error) {
                        // 失敗時の処理
                        alert('Error : ' + error);
                    });


                    return true;
                }


                /*if(confirm('変更してもよろしいですか？')){
                    alert('変更しました。');
                    return true;
                }
                return false;*/
            });
        });
    </script>
</head>
<body>

<div style="text-align: center" id="modaal-control-panel" class="modaal-control-panel" style="margin: 10px auto;">

    <h3 style="text-align: center">店舗パスワード変更</h3>

    <p id="error-msg" style="display: <?= empty($errmsg) ? 'none;' : 'block;'; ?> color: red;"><?= $errmsg ?></p>

    <div>
        <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
        <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>

            <label for="oldpassword" style="text-align: left;">現在のパスワード：</label><span id="error-oldpassword-empty" style="display:none; text-align: left; color:red; font-size: 13px;">現在のパスワードが入力されていません</span>
            <input type="password" id="oldpassword" name="oldpassword" value=""/>
            <label for="password" style="text-align: left;">新しいパスワード：</label><span id="error-password-empty" style="display:none; text-align: left; color:red; font-size: 13px;">新しいパスワードが入力されていません</span>
            <input type="password" id="password" name="password" value=""/>
            <div style="text-align: center;">
            <input type="button" onclick="location.href='./admin'" class="btn btn-info" value="戻る">
            <input type="submit" id="form-submit" class="btn btn-primary" value="変更">
            </div>



    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>