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
        店舗リスト - StoreTaskManager
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


            $("#store-id").change(function(){
                var id = $(this).val();

                if(id != 0) {
                    $('#edit-name-div').show('fast');
                    $('#edit-password-div').show('fast');
                    $('#store-delete-div').show();
                } else {
                    $('#edit-name-div').hide('fast');
                    $('#edit-password-div').hide('fast');
                    $('#store-delete-div').hide();
                }


                //alert(deplist[userlist[id]['department']]['name']);

                nowSelectId = id;

                nowStoreName = $('#store-' + id).text();

            });

            $('#edit-name-form').submit(function () {
                if ($('#edit-name').val() == '') {
                    $('#error-name-empty').show();
                    return false;
                }

                $('#edit-name-id').val(nowSelectId);

                if(confirm('店舗名を変更してもよろしいですか？')) {

                } else {
                    return false;
                }
            });

            $('#edit-password-form').submit(function () {
                if ($('#edit-password').val() == '') {
                    $('#error-password-empty').show();
                    return false;
                } else if ($('#edit-password').val() < 4) {
                    $('#error-password-length').show();
                    return false;
                }

                if(confirm('パスワードを変更してもよろしいですか？')) {


                    $('#edit-password-id').val(nowSelectId);
                } else {
                    return false;
                }
            });

            $('#store-delete-form').submit(function () {
                if(confirm(nowStoreName + 'を削除してもよろしいですか？')) {
                    if(confirm('この操作は取り消しできませんがよろしいですか？')){
                        $('#delete-store-id').val(nowSelectId);
                        return true;
                    }
                }
                return false;
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



    <div id="container" class="shadow" style="padding:30px;width: 420px; background-color: white; margin: 150px auto; text-align: center">


        <p style="text-align: center">

        <h3 style="display: inline-block;">店舗リスト</h3>
        <h5 style="color: red;text-align: center"><?= $msg ?></h5>
        </p>

        <div>

            <div class="form-group">


            </div>

            <div class="form-group" style="text-align: left">

                <div class="form-group">

                    <!--label for="store-id">店舗名:</label-->

                    <?php

                    echo '<select style="text-align: center;" class="form-control" id="store-id" name="store-id">';
                    echo "<option value='0'>店舗を選択</option>";

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
                            $print[$cnt] .= "<option id='store-{$store->id}' value='{$store->id}'>{$store->name}</option>";
                        }
                        if($print[$cnt] != NULL) $print[$cnt] .= "</optgroup>";
                        $cnt++;
                    }

                    foreach($print as $p){
                        echo $p;
                    }

                    echo '</select>';

                    ?>

                </div>


                <div class="form-group form-control" style="display: none;" id="edit-name-div">

                    <form id="edit-name-form" action="edit" method="post">

                        <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                        <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                        <label for="edit-name">店舗名を変更：</label>
                        <span id="error-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
<input type="text" class="form-control" id="edit-name" name="name" value="">
                        <input type="hidden" class="form-control" id="edit-name-id" name="id" value="">
                        <input type="hidden" class="form-control" name="action" value="store-name-change">


                        <input type="submit" class="form-control btn btn-primary btn-group-lg" value="変更">

                    </form>

                </div>


                <div class="form-group form-control" style="display: none;" id="edit-password-div">

                    <form id="edit-password-form" action="edit" method="post">

                    <input type="text" name="dummy_text"  disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                    <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                    <label for="edit-password">パスワードを変更：</label>
                    <span id="error-password-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
                    <span id="error-password-length" style="display:none; color:red; font-size: 13px;">パスワードは4文字以上必要です</span>
                    <input type="password" class="form-control" id="edit-password" name="password" value="">
                        <input type="hidden" class="form-control" id="edit-password-id" name="id" value="">
                    <input type="hidden" class="form-control" name="action" value="store-password-change">


                    <input type="submit" id="edit-password-form-submit" class="form-control btn btn-primary btn-group-lg" value="変更">

                    </form>

                </div>

                <div id="store-delete-div" style="display: none;" class="form-group">
                    <form id="store-delete-form" action="edit" method="post">
                        <input type="hidden" class="form-control" id="delete-store-id" name="id" value="">
                        <input type="hidden" class="form-control" name="action" value="delete-store">
                        <button style="color: white;" type="submit" class="form-control btn btn-danger btn-lg">店舗を削除</button>

                    </form>
                </div>
                <a style="color: white;margin-top:10px" href="./management" class="form-control btn btn-info">戻る</a>

            </div>


        </div>

    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>