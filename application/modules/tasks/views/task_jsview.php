<?php

?>

//<script>



initData(1);

    function initData(type) {

        get_data('<?= base_url('tasks/task/public_get_tasks') ?>', {type: type}, function (data) {

            $('#ref_tasklist').html(data);

        }, 'html');


        get_data('<?= base_url('tasks/task/public_get_tasks_statistics') ?>', {}, function (data) {
            //console.log('',data[0]);
            $('a[value="1"] span').text(data[0].INCOME);
            $('a[value="2"] span').text(data[0].OUTCOME);
            $('a[value="3"] span').text(data[0].NEW_TASKS);
            $('a[value="4"] span').text(data[0].EXEC_TASKS);
            $('a[value="5"] span').text(0);
            $('a[value="6"] span').text(0);

        }, 'json');
    }


    function get_task_details(id) {

        get_data('<?= base_url('tasks/task/public_get_task') ?>', {id: id}, function (data) {

            $('#ref_tasklist').html(data);
            task_det_wid(1);
            $('#test').hide();
            $('.sel2:not("[id^=\'s2\']")').select2();
            mainEmpSelectedChange();
         }, 'html');
    }

///////////////////////////////////////////////////////////////////////////////////
function  get_task_id(id) {
      
     //  $('#vi').html(id);

     get_data('<?= base_url('tasks/task/public_get_task2') ?>', {id: id}, function (data) {

         $('#vi').html(data);

    }, 'html');


        }
            
     ///////////////
  


////////////////////////////////////////////////////////////////////

  
   // $("#txt_done_date" ).datepicker({
     // inline: true,
      //minDate: -10,
      //showOtherMonths: true,
     // dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
   
   // });
   // $("#txt_done_date").datepicker({ dateFormat: 'dd-mm-yy' });
////////////////////    ///////////////////////////////////////////////////////////////////////////////////
    function  get_transaction(id) {
      
      //  $('#vi').html(id);
 
      get_data('<?= base_url('tasks/task/public_get_task_transaction') ?>', {id: id}, function (data) {
 
          $('#vi_transaction').html(data);
 
     }, 'html');
 
         }
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////    ///////////////////////////////////////////////////////////////////////////////////
function  get_replay(task_no,emp_no) {  
      //  $('#vi').html(id);
   //  var TASK_NO = $('#task_no ').val();
   // var DIRECT_EMP_NO_NAME = $('#DIRECT_EMP_NO_NAME').val();
      get_data('<?= base_url('tasks/task/public_get_each_replay') ?>', {task_no:task_no,emp_no:emp_no}, function (data) {
 
          $('#show_replay_tr').html(data);
 
     }, 'html');
 
         }
//////////////////////////////////////////////////////////////////////////////////////////////////
    $('#ref_taskstatus a').click(function () {

        var type = $(this).attr('value');

        $('#ref_taskstatus li').removeClass('active');
        $(this).closest('li').addClass('active');

        initData(type);

    });
//////////////////////////////////////////////////////////////////////////////////////////
function tasks_search (){
  // initData(type);
    var search_task = $('#task_no ').val();
    //var type = $(this).attr('value');
    var p_type = $('#p_type').val();
    get_data('<?= base_url('tasks/task/public_get_tasks') ?>', {type:p_type, search_task: search_task}, function (data) {
       // alert( $('#task_no ').val());
      $('#ref_tasklist').html(data);
}, 'html');
    
}
//////////////////////////////////////////////////////////////////////////////////////////



// mkilani
$(document).on('click', "#task_list_hr_mk", function (e) {
    var type = $(this).attr('value');
    task_det_wid(type == true ? 0 : 1);

    $(this).attr('value', !type)
});

// mkilani
    function task_det_wid(type) {
        if (type == 1) { // OPEN
            $('#task_list_hr_mk span i').removeClass().addClass('fa fa-arrow-circle-left');
            $('#tasklist_MK').removeClass().addClass('col-md-2 col-sm-4');

            $('.todo-tasklist-date').css('font-size', '10px');
            $('.todo-tasklist-item-title').css('font-size', '12px').css('font-weight', '200');
            $('.todo-tasklist-item').css('padding', '3px').css('margin-bottom', '5px');
        } else { // CLOSE
            $('#tasklist_MK').removeClass().addClass('col-md-5 col-sm-4');
            $('#taskdet_MK').removeClass().addClass('col-md-7 col-sm-8 task-details');
            $('.todo-tasklist-item-text,.todo-tasklist-controls,.todo-userpic').show(500);
            $('.todo-tasklist-date').css('font-size', '14px');
            $('.todo-tasklist-item-title').css('font-size', '15px').css('font-weight', '400');
            $('.todo-tasklist-item').css('padding', '10px').css('margin-bottom', '15px');
        }
    }

    function insertReply() {

        if($('#reply_text').val().length <=0){
            alert('يجب إدخال نص الرد');
            return;
        }

        var isExtent = $('#is_extent:checkbox:checked').length > 0;

        get_data('<?= base_url('tasks/task/reply') ?>', {task_id: $('#task_id').val(), replay_to_emps: $('#dp_replay_to_emps').val(), text: $('#reply_text').val(), extent: isExtent ? 1 : 0 }, function (data) {

            get_task_details($('#task_id').val());
        });
    }




    function applySubTaskAction() {

        var postArray = Array();
        $('input[name="task_item[]"]').each(function(i){
            postArray.push({item_no : $(this).val(),status : $(this).prop('checked') ? 1 : 0});
        });

        get_data('<?= base_url('tasks/task/ApplySubTaskAction') ?>', {data: postArray }, function (data) {

            get_task_details($('#task_id').val());
        });
    }

    function mainEmpSelectedChange(){
        $('#dp_direct_1').change(function(){

            item_emps_select();
            $('.item_emps').each(function(){
                $(this).val('');
            });
        });
    }
    /* New Task Mkilani */

    $('.sel2:not("[id^=\'s2\']")').select2();

    mainEmpSelectedChange();

    function item_emps_select(){
        var emp_vals= "[value=''],";

        $.each( $('#dp_direct_1').select2('val') , function(k,v) {
            emp_vals+="[value='"+v+"'],";
        });

        emp_vals= emp_vals.slice(0, -1);

        $('.item_emps').each(function(){
            $(this).find('option').show().hide().filter(emp_vals).show();
        });
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        $.each( $('#task_form .task_directs') , function() {
            $(this).val( $('#task_form #dp_direct_'+$(this).attr('data-con')).val() );
        });

    /*
     var cats ='';
     $('form .checkboxes:checked').each(function (i) {
     cats += $(this).val()+',';
     });
     $('#h_cats').val(cats.slice(0, -1));
     */

    if(confirm('هل تريد ارسال المهمة، لا يمكن التعديل او التراجع؟!')){
        $(this).attr('disabled','disabled');
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(parseInt(data)>1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                //get_to_link('tasks/task/public_get_task/'+parseInt(data));

                get_to_link(window.location.href);
            }else if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link(window.location.href);
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    }
    setTimeout(function() {
        $('button[data-action="submit"]').removeAttr('disabled');
    }, 3000);
});

////////////////////////////////////////////////////////////////
function subtask(TASK_NO) {
if( TASK_NO > 0 ){
    $('#myModal').modal();
    $('#dropzoneModalTitle').text('مهمة فرعية');
    $('#h_parent_task').val(TASK_NO);
    //get_task_details($('#task_id').val());
    //$("a").attr('disabled', 'disabled');
  
  //  document.getElementById('Button1').disabled = false;
}

}

    
////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////

/* New Task Mkilani */

    function task_actions(action) {

        if (action == 'close' || action == 'delay') {

            var message = action == 'close' ? 'هل تريد اغلاق المهمة' : 'هل تريد تأجيل المهمة';

            if (confirm(message)) {
                get_data('<?= base_url('tasks/task/actions') ?>', {task_id: $('#task_id').val(), action: action , status :  action == 'close' ? 2 : 3 }, function (data) {
                    get_task_details($('#task_id').val());
                });
            }
        }else if(action == 'extent'){

            $('#extentModel').modal();

            $('#extent_date').datetimepicker({
                pickTime: false

            });

        }else if(action == 'transfer') {

            $('#transferModel').modal();

        }else if(action == 'reopen') {

            var message = 'اعادة فتح المهمة؟';

            if (confirm(message)) {
                get_data('<?= base_url('tasks/task/actions') ?>', {task_id: $('#task_id').val(), action: action , status : 1 }, function (data) {
                    get_task_details($('#task_id').val());
                });
            }

        }
    }

    function sendExtent(){
        $('#extentModel').modal('hide');
        get_data('<?= base_url('tasks/task/actions') ?>', {task_id: $('#task_id').val(), action: 'extent' , date : $('#extent_date').val()  }, function (data) {
            get_task_details($('#task_id').val());
        });
    }

    function sendTransfer(){
    $('#transferModel').modal('hide');
    var indicator_measurement = Array();
    $('#indicator_measurement tbody tr').each(function(i){
        var item_emp_no = $('select[name="item_emp_no[]"]',$(this)).val();
        var cat_no = $('select[name="cat_no[]"]',$(this)).val();
        var item_desc = $('input[name="item_desc[]"]',$(this)).val();
        indicator_measurement.push({item_emp_no : item_emp_no,cat_no : cat_no ,item_desc : item_desc });
    });

    var postArray = Array();
    $('.sel2:not("[id^=\'s2\']")').each(function(i){
        postArray.push({con_id : $(this).attr('data-con'),employees : $(this).select2('val')});
    });

    get_data('<?= base_url('tasks/task/actions') ?>', {task_id: $('#task_id').val() , action : 'transfer', data : postArray , indicator_measurement : indicator_measurement  }, function (data) {
        get_task_details($('#task_id').val());
    });
}

///////////////////////////
 $('#myModal').on('hidden', function () {
  document.location.reload();
})
/////////////////////////////////////////
//var today = new Date().toISOString().split('T')[0];
//document.getElementsByName("done_date")[0].setAttribute('min', today);



    //</script>