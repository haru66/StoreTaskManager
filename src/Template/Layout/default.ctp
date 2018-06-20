<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?> - StoreTaskManager
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


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>




</head>
<body>
<nav style="position: fixed;top: 0;left:0; width: 100%; z-index: 9999;" class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 medium-4 columns">
        <li class="name">
            <h1 id="title" style="text-align: center" onclick="alert(modalOpen)"><?= $this->fetch('title') ?></h1>
        </li>
    </ul>

    <div class="top-bar-section">
        <ul  class="large-4 medium-4 columns">
            <li><a href="../Users/My"><?= $this->fetch('user') ?></a></li>
            <li><a href="../Users/Logout">ログアウト</a></li>
            <li><a href="../Stores/Logout">店舗切替</a></li>
            <?= $this->fetch('adminLink') ?>
            <li><a class="modaal" href="#memo" onclick="refreshMemo()">メモ</a></li>
        </ul>
        <ul class="right">
            <li <?= $this->fetch('showToggleBtn') ?>><a href="javascript:toggleView()" id="toggle-view-btn">担当部門のみ表示</a></li>
            <li><a href="?<?= $this->fetch('previousDay') ?>">＜前の日</a></li>
            <li><input value="<?= $this->fetch('sheetDate') ?>" type="text" id="sheet-date" style="cursor:pointer;width: 126px; background-color: black;color: white;border: none;"></li>
            <?= $this->fetch('nextDayLink') ?>

        </ul>
    </div>
</nav>
<?= $this->Flash->render() ?>
<div class="clearfix" style="margin-top: 45px;">
    <?= $this->fetch('content') ?>
</div>
<footer>
</footer>
</body>
</html>
