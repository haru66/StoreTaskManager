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
        店舗ログイン - StoreTaskManager
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
            setFocus("#store-password");

            $('.modaal-login-form').modaal({
                animation_speed: 50,
                width: 300,
                start_open: true,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });

            $('#login-form').submit(function(){
                if($("select option:selected").text() == '店舗を選択'){
                    alert('店舗を選択してください。');
                    return false;
                }

                if($('#store-password').val() == ""){
                    $('#error-password-empty').show();
                    return false;
                }

                return true;
            });

            $('#store-id').change(function(){
                setFocus("#store-password");
            });
        });


        function setFocus(sel){
            setTimeout(function(){
                //webkit,geckoにはfocus()にsetTimeoutが必要
                $(sel).focus();//入力欄にフォーカス
            },200);
        }
    </script>
</head>
<body>

<div id="modaal-login-form" class="modaal-login-form" style="margin: 10px auto;">

    <p style="text-align: center">
        <!--span style='display: inline-block;'>タスク管理ツール</span>
        <span style='display: inline-block;'>StoreTaskManager</span-->
    </p>

    <p><h5 style="text-align: center;">店舗ログイン</h5></p>

    <p id="error-msg" style="display: <?= empty($errmsg) ? 'none;' : 'block;'; ?> color: red;"><?= $errmsg ?></p>

    <form id="login-form" action="Login" method="post">
        <div class="form-group">

            <!--label for="store-id">店舗名:</label-->

            <?php
/*
            echo '<select style="text-align: center;" class="form-control" id="store-id" name="store-id">';

            echo "<optgroup label='店舗'>";
            echo "<option value='0'>店舗を選択</option>";


            foreach($stores as $store){
                if($store->id == 1) continue;
                $s = '';
                if($this->request->getCookie("store") == $store->id){
                    $s = 'selected="selected"';
                } else if($store->id == $storeId){
                    $s = 'selected="selected"';
                }
                echo "<option {$s} value='{$store->id}'>{$store->name}</option>";
            }
            echo "</optgroup>";

            echo "<optgroup label='システム'>";
            echo "<option value='1'>管理画面</option>";
            echo "</optgroup>";

            echo '</select>';
*/
            ?>


            <?php

            echo '<select style="text-align: center;" class="form-control" id="store-id" name="store-id">';
            echo "<option value='0' selected='selected'>店舗を選択</option>";




            $print = array();
            $cnt = 0;
            foreach($area as $a){
                if($a == NULL) continue;

                $print[$cnt] = NULL;
                foreach($stores as $store){
                    if($store->area != (intval($cnt)+1) || $store->id == 1) continue;
                    if($print[$cnt] == NULL){
                        $print[$cnt] = "<optgroup label='{$a}'>";
                    }
                    $s = "";
                    if($this->request->getCookie("store") == $store->id){
                        $s = 'selected="selected"';
                    }
                    $print[$cnt] .= "<option {$s} value='{$store->id}'>{$store->name}</option>";
                }
                if($print[$cnt] != NULL) $print[$cnt] .= "</optgroup>";
                $cnt++;
            }

            foreach($print as $p){
                echo $p;
            }

            echo "<optgroup label='システム'><option value='1'>店舗管理</option></optgroup>";

            echo '</select>';

        ?>

        </div>

        <div class="form-group">

            <label for="store-password">パスワード:</label>
            <span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
            <input type="password" class="form-control" name="password" id="store-password">
        </div>


        <div class="form-group" style="text-align: center;">

            <button type="submit" id="login-form-submit" class="btn btn-md btn-primary" style="width:100px;">ログイン</button>
        </div>

    </form>

    <div class="clearfix"></div>

</div>
</body>
</html>
