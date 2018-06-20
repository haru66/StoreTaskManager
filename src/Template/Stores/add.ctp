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
        新規店舗追加 - StoreTaskManager
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
            $('.modaal').modaal({
                animation_speed: 50,
                width: 500,
                start_open: false,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });


            $('#form').submit(function () {
                $('#error-name-empty').hide();
                $('#error-password-empty').hide();
                $('#error-password2-empty').hide();
                $('#error-password-differed').hide();
                $('#error-password-length').hide();
                $('#error-area-empty').hide();

                var err = false;
                if($('#name').val() == ''){
                    $('#error-name-empty').show();
                    err = true;
                }
                if($('#password').val() == ''){
                    $('#error-password-empty').show();
                    err = true;
                }else
                if($('#password').val().length < 4){
                    $('#error-password-length').show();
                    err = true;
                }
                if($('#password2').val() == ''){
                    $('#error-password2-empty').show();
                    err = true;
                }else
                if($('#password2').val() != $('#password').val()){
                    $('#error-password-differed').show();
                    err = true;
                }
                var check_count = $('#area-div :checked').length;
                if(check_count == 0){
                    $('#error-area-empty').show();
                    err = true;
                }


                if(err) return false;
            });
        });


    </script>
</head>
<body style="background-color:#B2EBF6">

<div id="modaal-role-form" class="modaal modaal-role-form" style="display: none;margin: 10px auto;">

    <div class="form-group" style="text-align: center">


    </div>

</div>



<div style="margin: 10px auto;">



    <div id="container" class="shadow" style="padding:30px;width: 440px; background-color: white; margin: 150px auto; text-align: center">


        <p style="text-align: center">
            <?php

            if($done){
                echo '<h5 style="text-align:center;color: red;">追加しました</h5>';
            }

            ?>
        <h3 style="display: inline-block;">新規店舗追加</h3>
        </p>

        <div>

            <div class="form-group">


            </div>

            <div class="form-group" style="text-align: left">

                <form id="form" action="" method="post">
                    <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                    <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>

                    <label for="name">店舗名：</label><span id="error-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                    <input class="form-control" type="text" id="name" name="name">

                    <div class="form-control">
                        <label for="area-div">エリア：</label><span id="error-area-empty" style="display:none; color:red; font-size: 13px;">選択してください</span>

                        <div id="area-div" class="form-control">

                        <?php


                        $cnt = 0;
                        foreach($area as $a){
                            if($a == NULL) continue;

                            $id = (intval($cnt)+1);
                            echo "<input type='radio' name='area' value='{$id}' id='radio{$id}' />";
                            echo "<label for='radio{$id}' class='radio form-inline'>{$a}</label>";

                            $cnt++;
                        }


                        ?>

                        </div>

                    </div>

                    <div style="padding-top: 15px;">

                        <label for="password">店舗パスワード：</label><span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                        <span id="error-password-length" style="display:none; color:red; font-size: 13px;">パスワードは4文字以上必要です</span>
                        <input class="form-control" type="password" id="password" name="password">

                        <label for="password2">店舗パスワード(確認用)：</label>
                        <span id="error-password2-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                        <span id="error-password-differed" style="display:none; color:red; font-size: 13px;">パスワードが一致しません</span>
                        <input class="form-control" type="password" id="password2">

                    </div>

                    <div class="form-group" style="text-align:center; padding-top:30px;">
                        <a class="btn btn-info" style=" width: 130px;" href="./management">戻る</a>
                        <button type="submit" class="btn btn-primary" style="text-align: center; padding-left: 10px;width: 130px;" onclick="">店舗追加</button>
                    </div>
                </form>

            </div>


        </div>

    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>