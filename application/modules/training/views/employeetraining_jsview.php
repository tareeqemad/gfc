<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 22/01/20
 * Time: 11:56 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#employeeTraining_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),branch_no_dp:$('#dp_branch_no').val(),
            course_no:$('#txt_course_no').val(),course_date:$('#txt_course_date').val(),
            course_name:$('#txt_course_name').val(),
            course_name_eng:$('#txt_course_name_eng').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('training/employeeTraining/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }



    function coursesDates(ser) {
        $('#ModalDates').modal();
        /* get_data('<?= base_url('training/employeeTraining/public_get_trainee_courses_dates') ?>', {id: ser}, function (data) {
         $('#div_course_Date').html(data);
         }, 'html');*/

    }

    function coursesEmployee(ser) {
        $('#ModalEmployee').modal();
    }




    function return_adopt (type){
        if(type == 1){
            get_data('<?= base_url('training/employeeTraining/adopt')?>',{id: $('#txt_ser').val()},function(data){
                if(data =='1')
                {
                    success_msg('رسالة','تم اعتماد بنجاح ..');
                    reload_Page();
                }
            },'html');
        }

        if(type == 2){
            get_data('<?= base_url('training/employeeTraining/unadopt')?>',{id:$('#txt_ser').val()},function(data){
                if(data =='1')
                {
                    success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                    reload_Page();
                }
            },'html');
        }
    }

    function assign_(ser ){
        $('#myModal').modal();
        $('#txt_course_ser').val(ser);
    }

    $('#filterText').on('change', function() {
        if (this.value == '2')
        {
            $('#person').show();
            $('#company').hide();
			$('#empGedco').hide();
            $('#traineeGedco').hide();
        }
        else if (this.value == '1'){
            $('#person').hide();
            $('#company').show();
			$('#empGedco').hide();
            $('#traineeGedco').hide();
        }
		else if (this.value == '3'){
			$('#person').hide();
            $('#traineeGedco').hide();
			$('#company').hide();
            $('#empGedco').show();
		}else{
            $('#person').hide();
            $('#company').hide();
            $('#empGedco').hide();
            $('#traineeGedco').show();
        }

    });

    function save_(trainee_ser , trainee_type){
        // alert($('#txt_trainee_ser').val());

        var values=
        {page:1, trainee_type:trainee_type , trainee_ser:trainee_ser, ser_course: $('#txt_course_ser').val()
        };

        get_data('<?= base_url('training/traineeRequest/updateCourse') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم اسناد المدرب للدورة');
                $('#myModal').modal('hide');
                reload_Page();
            }

        }, 'html');

    }

    function showEmpsModal(id){

        _showReport('<?= base_url('training/employeeTraining/public_select_emp') ?>'+'?id='+id);

    }

    function saveEmpName(id, count){

        var values=  { id:id, name:$("#txt_emp_name_eng_"+count).val() };
        get_data('<?= base_url('training/employeeTraining/updateName') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم حفظ الاسم بنجاح  ');
				reload_Page();
                //reload_div($('#h_txt_course_ser').val());
            }

        }, 'html');
    }
	
	function saveEmpTraine(){
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $('#_emps_form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
					success_msg('رسالة', 'تم الحفظ بنجاح');
                    reload_Page();
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    }

    function emp_courses_search(){
		
        var values= values_emp_search(1);
        get_data('<?= base_url('training/employeeTraining/public_get_emp_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function values_emp_search(add_page){
        var values=
        {page:1, emp_id:$('#dp_emp_no').val(),
            start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    //select all checkboxes
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
	
	
	function saveEmpAttendance(id, count, emp_no){
        var attendance = 0;

        if($('#btn_attendance_'+count).is(':checked')){
            attendance = 1;
        }else{
            attendance = 0;
        }

        var values=  { id:id, end_time:$("#txt_end_time_"+count).val() , start_time:$("#txt_start_time_"+count).val() ,
		notes:$("#txt_notes_"+count).val(), emp_no_: emp_no , date_no_: $("#txt_date_no_").val() ,
		course_no_:  $("#txt_course_no_").val(),attendance: attendance};
		
        get_data('<?= base_url('training/employeeTraining/updateAttendance') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم حفظ البيانات بنجاح  ');
				reload_Page();
            }

        }, 'html');
    }


    $('.emp-train').click(function(){
        var tr = $(this).closest('tr');

        if($(this).prop("checked") == false){
            $(tr).find("input[name='start_time[]']").val('-');
            $(tr).find("input[name='end_time[]']").val('-');
        }else{
            $(tr).find("span[name='errorSec[]']").removeClass('hidden');
        }

    });



    //</script>
