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
        管理ページ - StoreTaskManager
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
                width: 400,
                start_open: false,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });


            $('#submit-company-name-form').click(function () {
                if($('#company-name').val() == ''){
                    $('#error-name-empty').show();
                    return false;
                }

                if(confirm('社名を'+$('#company-name').val()+'に変更してもよろしいですか？')) {
                    var n = $('#company-name').val();
                    var data = {
                        action : 'company-name-change',
                        name : n,
                    };

                    $.ajax({
                        url: '../stores/edit',
                        type: 'post',
                        data: data,
                    }).done(function (data, status, jqXHR) {
                        var res = JSON.parse(data);
                        if (res.res != 'error') {

                            alert("社名を"+n+"に変更しました。");
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

        function setFocus(sel){
            setTimeout(function(){
                //webkit,geckoにはfocus()にsetTimeoutが必要
                $(sel).focus();//入力欄にフォーカス
            },200);
        }




    </script>
</head>

<body style="background-color:#B2EBF6">





<div id="modaal-company-name-edit-form" class="modaal modaal-company-name-edit-form" style="display: none;margin: 10px auto;">

    <div class="form-group" style="text-align: left">

        <h3 style="text-align: center">社名を変更</h3>

        <!--form action="user" method="post" id="role-form"-->

        <div class="form-control">

            <label for="company-name">社名：</label><span id="error-name-empty" style="display:none; color:red; font-size: 13px;">入力してください</span>
            <input type="text" id="company-name" name="name" value="">
            <input type="hidden" name="id" value="1">
            <input type="hidden" name="action" value="store-name-change">

            <div class="form-group" style="text-align: center">
                <button class="form-control btn btn-info" style="width:120px;" type="button" onclick="$('.modaal').modaal('close')">キャンセル</button>
                <button class="form-control btn btn-primary" id="submit-company-name-form" style="margin-left:15px;width:120px;" type="button">実行</button>
            </div>

        </div>

        <!--/form-->

    </div>

</div>


<div id="container" class="shadow" style="padding:30px;width: 360px; background-color: white; margin: 150px auto; text-align: center">

    <p style="text-align: center;"><h3><?= $storeParentName ?></h3><h5>全体管理</h5></p>


    <div class="form-group">

        <p><a class="form-control btn btn-primary btn-lg modaal" onclick="setFocus('#company-name');" href="#modaal-company-name-edit-form">社名変更</a> </p>
        <p><a class="form-control btn btn-primary btn-lg" href="./edit">店舗リスト</a> </p>
        <p><a class="form-control btn btn-primary btn-lg" href="./add">店舗追加</a> </p>
        <p><a class="form-control btn btn-primary btn-lg" href="../users/edit">マネージャーリスト</a> </p>
        <p><a class="form-control btn btn-primary btn-lg" href="../users/add">マネージャー追加</a> </p>

        <p><a class="form-control btn btn-info btn-lg" href="../stores/logout">ログアウト</a> </p>

    </div>


    <div class="clearfix"></div>

</div>
</body>
</html>
