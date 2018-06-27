$(function() {

    /*
        Start: Tasks => View
     */

    $('#task-edit-submit').click(function () {


        if($('#task-edit-caption').val() == ''){
            $('#task-edit-empty-caption').show();

            return false;
        }/* else if($('#task-edit-caption').val().length > 13){
            $('#task-edit-large-caption').show();
            return false;
        }*/

        var act = $('#action-task').val();
        if(act == 'task-add'){
            if($('#task-edit-worker-div :checked').length == 0){
                alert('作業者がいません！');
                return false;
            }

            if(!confirm('タスクを追加してもよろしいですか？')){
                return false;
            }
            alert('追加しました！');
            $('.modaal').modaal('close');
        } else if(act == 'task-edit'){
            if($('#task-edit-worker-div :checked').length == 0){
                alert('作業者がいません！');
                return false;
            }

            if(!confirm('タスクを更新してもよろしいですか？')){
                return false;
            }
            //alert('タスクを更新しました。');
            $('.modaal').modaal('close');
        }
    });

    $('#task-edit-delete').click(function () {
        $('#action-task').val('task-delete');



        if(!confirm('タスクを削除してもよろしいですか？')){
            return false;
        }

        //alert('タスクを削除しました。');
        $('.modaal').modaal('close');
    });

    $('#task-edit-success').click(function () {
        $('#action-task').val('task-success');

        if(!confirm('タスクを完了してもよろしいですか？')){
            return false;
        }

        alert('お疲れ様でした！');
        $('.modaal').modaal('close');
    });

    $('#msg-edit-submit').click(function () {
        if($('#msg-detail').val() == ''){
            $('#msg-edit-empty-detail').show();

            return false;
        }

        var act = $('#msg-action').val();
        if(act == 'msg-add'){
            if(!confirm('引き継ぎメッセージを追加してもよろしいですか？')){
                return false;
            }
            //alert('引き継ぎメッセージを追加しました。');
            $('.modaal').modaal('close');
        } else if(act == 'msg-edit'){
            if(!confirm('引き継ぎメッセージを更新してもよろしいですか？')){
                return false;
            }
            //alert('引き継ぎメッセージを更新しました。');
            $('.modaal').modaal('close');
        }
    });

    $('#msg-edit-delete').click(function () {
        $('#msg-action').val('msg-delete');
        if(!confirm('引き継ぎメッセージを削除してもよろしいですか？')){
            return false;
        }
        //alert('引き継ぎメッセージを削除しました。');
        $('.modaal').modaal('close');
    });

    $('#dailytask-edit-submit').click(function () {
        var err = false;

        if($('#dailytask-edit-caption').val() == ''){
            $('#dailytask-edit-empty-caption').show();

            err = true;
        } else {
            $('#dailytask-edit-empty-caption').hide();
        }
        if($('#worktime-h0').prop('selected') && $('#worktime-m0').prop('selected')){
            //alert('作業時間がおかしいです。');
            $('#msg-edit-empty-worktime').show();
            err = true;
        } else {
            $('#msg-edit-empty-worktime').hide();
        }

        if(err) return false;

        var act = $('#dailytask-action').val();
        if(act == 'dailytask-add'){
            if(!confirm('今日やったことを追加してもよろしいですか？')){
                return false;
            }
            //alert('今日やったことを追加しました。');
            $('.modaal').modaal('close');
        } else if(act == 'dailytask-edit'){
            if(!confirm('今日やったことを更新してもよろしいですか？')){
                return false;
            }
            //alert('今日やったことを更新しました。');
            $('.modaal').modaal('close');
        }
    });

    $('#dailytask-edit-delete').click(function () {
        $('#dailytask-action').val('dailytask-delete');
        if(!confirm('今日やったことを削除してもよろしいですか？')){
            return false;
        }
        //alert('今日やったことを削除しました。');
        $('.modaal').modaal('close');
    });

    /*
     End: Tasks => View
     */



});