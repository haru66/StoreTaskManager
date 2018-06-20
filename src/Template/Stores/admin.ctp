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
        自店舗管理 - StoreTaskManager
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
                width: 400,
                start_open: true,
                is_locked: true,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });

        });
    </script>
</head>
<body>

<div id="modaal-control-panel" class="modaal-control-panel" style="margin: 10px auto;">


    <p><h3 style="text-align: center;"><span style='display: inline-block;'><?= $storeParentName ?></span> <span style='display: inline-block;'><?= $storeName ?></span> </h3></p>

    <div style="text-align: center;">

        <p><a class="btn btn-primary btn-lg form-control" href="./user">スタッフ管理</a></p>

        <p><a class="btn btn-primary btn-lg form-control" href="./department">部門管理</a></p>

        <p><a class="btn btn-primary btn-lg form-control" href="./password">店舗パスワード変更</a></p>

        <p><a class="btn btn-info btn-lg form-control" href="../">シートへ戻る</a></p>

    </div>

    <div class="clearfix"></div>

</div>
</body>
</html>