<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:00 AM
 */
$create_url =base_url('budget/overtime/create');

$get_page_url =base_url("budget/overtime/get_page?case={$case}");

$update_adapt_url =base_url('budget/overtime/update_adapt');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( /*HaveAccess($create_url,$get_page_url) && */$case == 1): ?>  <li><a  onclick="javascript:save_overtime();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>
            <?php if(/* HaveAccess($update_adapt_url,$get_page_url) && */$case == 2): ?>  <li><a  onclick="javascript:update_adapt(2);" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>اعتماد مدير الدائرة</a> </li><?php endif;?>
            <?php if( /*HaveAccess($update_adapt_url,$get_page_url) &&*/ $case == 3): ?>  <li><a  onclick="javascript:update_adapt(3);" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>اعتماد مدير المقر</a> </li><?php endif;?>
            <?php if(/* HaveAccess($update_adapt_url,$get_page_url) && */$case == 4): ?>  <li><a  onclick="javascript:update_adapt(4);" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>اعتماد المقر الرئيسي</a> </li><?php endif;?>
            <?php if(/* HaveAccess($update_adapt_url,$get_page_url) && */$case == 5): ?>  <li><a  onclick="javascript:update_adapt(5);" href="javascript:;"><i class="glyphicon glyphicon-saved"></i> اعتماد المدير العام </a> </li><?php endif;?>
            <?php if(/* HaveAccess($update_adapt_url,$get_page_url) && */$case == 6): ?>  <li><a  onclick="javascript:update_adapt(6);" href="javascript:;"><i class="glyphicon glyphicon-saved"></i> اعتماد مجلس الإدارة </a> </li><?php endif;?>
            <?php if( /*HaveAccess($update_adapt_url,$get_page_url) &&*/ $case != 1): ?>  <li><a  onclick="javascript:update_adapt(1);" href="javascript:;"><i class="glyphicon glyphicon-remove"></i> إلغاء الإعتماد </a> </li><?php endif;?>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div >
            <div class="form-group col-sm-8">
                <label class="col-sm-1 control-label"> الموظف</label>
                <div class="col-sm-5">
                    <select name="user_no" style="width: 250px" id="dp_user_no">
                        <option></option>
                        <?php foreach($employees as $row) :?>
                            <option data-dept="<?= $row['GCC_ST_NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>



        </div>
        <div class="clearfix"><hr/></div>
        <div   id="container">

            <div class="alert alert-info" role="alert"><strong> تبيه! </strong> لا يوجد بيانات في الوقت الحالي , أختر الموظف أولاً </div>

        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT
<script>

    $(function(){
        $('#dp_user_no').select2().on('change',function(){


            get_data('$get_page_url',{user:$('#dp_user_no').val()} ,function(data){


                $('#container').html(data);

            },"html");

        });
    });


    function update_adapt(val){

    if(val == 1)
     if(!confirm('هل تريد إلغاء الإعتماد ؟!!!')){
return;
            }

        get_data('$update_adapt_url',{'case':val,user:$('#dp_user_no').val(),__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()} ,function(data){

            if(data=='1')
                success_msg('رسالة','تم حفظ السجلات بنجاح ..');

        },"html");

    }


    function save_overtime(){

        var months =[];

        $('input[name="month[]"]').each(function (i) {
            months[i] = $(this).val();

        });

        var hours =[]; $('input[name="hours[]"]').each(function (i) {
            hours[i] = $(this).val();

        });
        var vals =[]; $('input[name="val[]"]').each(function (i) {
            vals[i] = $(this).val();

        });


        get_data('$create_url',{'months[]':months,'hours[]':hours,'vals[]':vals,user:$('#dp_user_no').val() ,dept:$('#dp_user_no').find(':selected').attr('data-dept'),__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()} ,function(data){

            $('#container').html(data);
            success_msg('رسالة','تم حفظ السجلات بنجاح ..');

        },"html");


    }

</script>
SCRIPT;

sec_scripts($scripts);



?>

