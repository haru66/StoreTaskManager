<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        部門リスト - StoreTaskManager
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

        $(function() {

            $('.modaal').modaal({
                animation_speed: 50,
                width: 500,
                background: "#B2EBF6",
                loading_content: 'Loading content, please wait.'
            });


            $('#dep-add-submit').click(function(){
                if($('#add-dep-name').val() == ''){
                   $('#error-name-empty').show();
                   return false;
                }

                var name = $('#add-dep-name').val();
                var parent = $('#add-dep-parent').val();

                var data = {
                    action : 'add',
                    name : name,
                    parent : parent,
                };

                $.ajax({
                    url: './edit',
                    type: 'post',
                    data: data,
                }).done(function (result, status, jqXHR) {
                    // 成功時の処理
                    //var res = JSON.parse(result);
                    //alert(res[0].name);
                    alert('部門 '+name+' を追加しました！');
                    $('.modaal').modaal('close');
                    location.reload();
                }).fail(function (jqXHR, status, error) {
                    // 失敗時の処理
                    alert('Error : ' + error);
                });

                return false;
            });


            $('#edit-dep-name-submit').click(function(){
                if($('#edit-dep-name').val() == ''){
                    $('#error-edit-name-empty').show();
                    return false;
                }


                var name = $('#edit-dep-name').val();
                var id = $('#edit-dep-id').val();

                var data = {
                    action : 'edit-name',
                    name : name,
                    id : id,
                };

                $.ajax({
                    url: './edit',
                    type: 'post',
                    data: data,
                }).done(function (result, status, jqXHR) {
                    // 成功時の処理
                    $('.modaal').modaal('close');

                    if(result){
                        $('#dep-'+id+'-draw').text(result);
                    }
                }).fail(function (jqXHR, status, error) {
                    // 失敗時の処理
                    alert('Error : ' + error);
                });

                return false;
            });
        });


        function showDepAdd(parent = 0){
            $('#add-dep-name').val('');
            $('#error-name-empty').hide();
            $('#add-dep-parent').val(parent);

            var appendTo = "メイン部門";
            if(parent != 0) appendTo = $('#dep-'+parent+'-draw').text();
            $('#append-to').text('追加先：' + appendTo);
        }

        function showDepEdit(id){
            $('#edit-dep-name').val('');
            $('#title-edit-dep-name').text($('#dep-'+id).attr('dep-name'));
            $('#edit-dep-id').val(id);
        }

        function showDepDelete(){

        }

        function changeIndex(replace, target, main = 0){
            var parent = $('#dep-'+replace).attr('parent');
            var index = $('#dep-'+replace).attr('index');
            if(main == 1) parent = 0;

            var targetIndex = target == 0 ? (parseInt(index)-1) : (parseInt(index)+1);

            //alert(target);
            var targetId = $("[index="+targetIndex+"][parent="+parent+"]").attr('dep-id');

            //alert('Me:' + replace + ' Target:' + targetId);

            var data = {
                action : 'change-index',
                replace : replace,
                target : targetId,
            };

            $.ajax({
                url: './edit',
                type: 'post',
                data: data,
            }).done(function (data, status, jqXHR) {
                // 成功時の処理
                if(data){
                    location.reload();
                }
            }).fail(function (jqXHR, status, error) {
                // 失敗時の処理
                alert('Error : ' + error);
            });

            return false;
        }

        function deleteDep(id){
            if(!confirm($('#dep-'+id).attr('dep-name')+' を削除してもよろしいですか？')){
                return false;
            }

            var data = {
                action : 'delete',
                id : id
            };

            $.ajax({
                url: './edit',
                type: 'post',
                data: data,
            }).done(function (data, status, jqXHR) {
                // 成功時の処理
                //alert(data);

                if($('#dep-'+id).attr('parent') != 0){
                    location.reload();
                } else {
                    $('#dep-' + id).hide();
                }
            }).fail(function (jqXHR, status, error) {
                // 失敗時の処理
                alert('Error : ' + error);
            });

            return false;
        }

    </script>

</head>
<body style="background-color: #B2EBF6;">

<data id="form-dep-id" value="" />

<div id="modaal-add-form" class="modaal" style="margin: 10px auto; display: none;">

    <div class="form-group" style="">
        <div class="form-inline"><h3>部門追加</h3><span id="append-to" style="padding-left: 10px;">追加先：</span></div>
        <label for="add-dep-name">部門名：</label><span id="error-name-empty" style="display:none; color:red; font-size: 13px;">部門名が入力されていません</span>
        <input type="text" id="add-dep-name" value="">
        <data value="" id="add-dep-parent">
        <p style="text-align: center;">
            <button onclick="$('.modaal').modaal('close')" class="btn btn-md">キャンセル</button>
            <button onclick="" id="dep-add-submit" class="btn btn-md btn-primary">追加</button>
        </p>
    </div>
</div>

<div id="modaal-edit-form" class="modaal" style="margin: 10px auto; display: none;">

    <div class="form-group" style="">
        <h3 id="title-edit-dep-name"></h3>
        <label for="edit-dep-name">部門名変更</label><span id="error-edit-name-empty" style="display:none; color:red; font-size: 13px;">部門名が入力されていません</span>
        <input type="text" id="edit-dep-name" value="">
        <input type="hidden" id="edit-dep-id" value="">
        <p style="text-align: center;">
            <button onclick="$('.modaal').modaal('close')" class="btn btn-md">閉じる</button>
            <button onclick="" id="edit-dep-name-submit" class="btn btn-md btn-primary">変更</button>
        </p>
    </div>
</div>

<div id="container" class="shadow" style="width: 705px; background-color: white; margin: 30px auto; padding: 30px 54px 10px;">

    <div class="form-inline" style=" padding-bottom: 20px;">
        <h3 style="margin-left: 126px; padding-right: 20px;">部門リスト・編集</h3>
        <button class="btn btn-primary modaal" style="margin-top: -5px;" onclick="showDepAdd()" href="#modaal-add-form">部門を追加</button>
    </div>

<?php

$deplist = array();
$depCount = count($departments);
$l = 0;

foreach ($departments as $dep){

    $deplist[$dep->id]['name'] = $dep->name;
    $deplist[$dep->id]['parent'] = 0;
    $deplist[$dep->id]['is_sub'] = $dep->is_sub;



    echo "<div class='main' id='dep-{$dep->id}' parent='0' index='{$l}' dep-name='{$dep->name}' dep-id='{$dep->id}' is-sub='false' style='padding-bottom: 23px;'>";

    echo '<p><a id="dep-'.$dep->id.'-draw" style="font-weight: bold; color:blue; font-size:20px;" onclick="$(\'#container-'.$dep->id.'\').toggle(\'fast\')">'.$dep->name.'</a>';

    //echo "<div style='' class='form-inline'>";
    if($l == 0){
        echo "<button class='btn btn-info btn-sm' style='margin-left: 30px; margin-top: -5px; margin-right: 2px;' onclick='changeIndex({$dep->id},1,1)'>↓</button>";
    }
    else if($l == intval($depCount)-1){
        echo "<button class='btn btn-info btn-sm' style='margin-left: 30px; margin-top: -5px; margin-right: 2px;' onclick='changeIndex({$dep->id},0,1)'>↑</button>";
    } else {
        echo "<button class='btn btn-info btn-sm' style='margin-left: 30px; margin-top: -5px; margin-right: 2px;' onclick='changeIndex({$dep->id},1,1)'>↓</button>";
        echo "<button class='btn btn-info btn-sm' style='margin-left: 30px; margin-top: -5px; margin-left: 0px;' onclick='changeIndex({$dep->id},0,1)'>↑</button>";
    }

    echo '<button style="margin-left: 25px; margin-top: -6px;" onclick="showDepEdit('.$dep->id.')" class="btn btn-sm btn-info modaal" href="#modaal-edit-form">部門名変更</button><button style="margin-top: -6px; margin-left:10px;" onclick="showDepAdd('.$dep->id.')" class="btn btn-sm btn-primary modaal" href="#modaal-add-form">サブ部門追加</button>';
    if($depCount != 1) {
        echo '<button style="margin-left:10px;margin-top: -6px;" class="btn btn-sm btn-danger" onclick="deleteDep('.$dep->id.')">削除</button>';
    } else {

    }



    echo '</p>';

    $l++;

    if(!$dep->is_sub){

        echo "<div  class='sub' id='container-{$dep->id}' style='padding-left: 50px'>";

        $i = 0;
        $last = count($dep->sub)-1;

        foreach($dep->sub as $sub){

            $deplist[$sub->id]['name'] = $sub->name;
            $deplist[$sub->id]['is_sub'] = $sub->is_sub;
            $deplist[$sub->id]['parent'] = $sub->parent;


            $up = "<button class='btn btn-info btn-sm' style='{STYLE1} {STYLE2}' onclick='changeIndex({$sub->id},0)'>↑</button>";
            $down = "<button class='btn btn-info btn-sm' style='{STYLE1} {STYLE2}' onclick='changeIndex({$sub->id},1)'>↓</button>";
            $j = 0;
            $a = '';
            if($i != $last){
                $a .= $down;
                $j++;
            }
            if($i != 0){
                $a .= $up;
                $j++;
            }

            $s1 = $a != '' ? 'margin-right: 6px;' : '';
            $s2 = $j == 1 ? 'margin-left: 18px;' : '';
            $a = str_replace("{STYLE1}", $s1, $a);
            $a = str_replace("{STYLE2}", $s2, $a);

            echo "<div class='form-inline' style='padding-bottom: 8px' dep-name='{$sub->name}' index='{$i}' id='dep-{$sub->id}' dep-id='{$sub->id}' parent='{$sub->parent}' is-sub='true'>";

            echo "<div style='width:150px;'>";
            echo "<h5 style='font-weight: bold;' id='dep-{$sub->id}-draw' href='#modaal-edit-form'>{$sub->name}</h5>";
            echo "</div>";

            echo "<div style='width:90px;'>";
            echo $a;
            echo "</div>";

            echo "<div style='margin-left: -10px;'>";
            echo "<button style='margin-left:10px;' href='#modaal-edit-form' class='btn btn-sm btn-info modaal' onclick='showDepEdit({$sub->id})'>部門名変更</button>";
            echo "</div>";

            if($last != 0) {
                echo "<div style='margin-left: -10px;'>";
                echo "<button style='margin-left:30px;' class='btn btn-sm btn-danger' onclick='deleteDep({$sub->id})'>削除</button>";
                echo "</div>";
            }

            echo "</div>";

            $i++;
        }

        echo '</div>';

    }



    echo '</div>';

}

?>


    <p style="text-align: center;"><button onclick="history.back()" class="btn btn-primary btn-lg">管理ページへ戻る</button> </p>

</div>
</body>
</html>