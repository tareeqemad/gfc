<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 13/06/16
 * Time: 11:57 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_emps_start';
$TB_NAME_POST='evaluation_emp_order';
$show_form_asks_url =base_url("$MODULE_NAME/$TB_NAME/show_form_asks");
$get_url= base_url("$MODULE_NAME/$TB_NAME_POST/get");
$get_etest_url= base_url("$MODULE_NAME/eval_etest_order/get");

$count = 0;
echo AntiForgeryToken();

// check if employee had evaluate
function check_ser ($emp,$type){
    $get_serial= "hr/evaluation_emp_order/public_get_serial";
    $serials= modules::run($get_serial, $emp, $type);

    if(count($serials)==1){
        return $serials[0]['EVALUATION_ORDER_SERIAL'];
    }elseif(count($serials)==0){
        return 0;
    }else{
        return -1;
    }
}

function check_adopt($id){
    if($id>0){
        $get= "hr/evaluation_emps_start/public_get";
        $res = modules::run($get, $id);
        if($res==2) return 1;
        elseif($res==1) return 0;
    }else return -1;
}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title ?></div>
    </div><!-- /.toolbar -->

    <?php
        if($effective_order==-1){
            die('لا يوجد امر تقييم فعال');
        }
    ?>

    <?php if((count($get_child) > 0) or (count($get_brother) > 0) or (count($get_grandson) > 0)){ ?>
        <div class="form-body">
            <div class="slide">
                <div id="tabs">
                    <ul id="nav">
                        <li><a id="tab-1" href="javascript:;">بيان التقييم</a></li>
                        <li><a id="tab-2" href="javascript:;">شروط التقييم (سياسة التقييم)</a></li>
                        <li><a id="tab-3" href="javascript:;">الجدول الزمني</a></li>
                        <li><a class="current" id="tab-4" href="javascript:;">بدء التقييم</a></li>

                    </ul>
                    <div id="content">
                        <div id="slide-1" class="tab-slide">
                            <?php if((count($get_child) > 0) or (count($get_brother) > 0) or (count($get_grandson) > 0)){ ?>
                                    <div class="modal-body inline_form">
                                        <div class="tbl_container">
                                                <p><?=$effective_order['NOTE']?></p>
                                        </div> <!-- /.tbl_container -->
                                    </div> <!-- /.modal-body inline_form -->
                            <?php } ?>
                        </div>

                        <div id="slide-2" class="tab-slide">
                            <?php if((count($get_child) > 0) or (count($get_brother) > 0) or (count($get_grandson) > 0)){ ?>
                                <div class="modal-body inline_form">
                                    <div class="tbl_container">
                                        <?php echo '<p> ' . nl2br($effective_order['CONDITIONS']) . '</p>'; ?>
                                    </div> <!-- /.tbl_container -->
                                </div> <!-- /.modal-body inline_form -->
                            <?php } ?>
                        </div>

                        <div id="slide-3" class="tab-slide">
                            <?php if((count($get_child) > 0) or (count($get_brother) > 0) or (count($get_grandson) > 0)){ ?>
                                <div class="modal-body inline_form">
                                    <div class="tbl_container">
                                        <?php echo '<p> ' . nl2br($effective_order['TIME_TABLE']) . '</p>'; ?>
                                    </div> <!-- /.tbl_container -->
                                </div> <!-- /.modal-body inline_form -->
                            <?php } ?>
                        </div>

                        <div id="slide-4" class="tab-slide" style="display: block">
                            <form class="form-vertical" id="<?=$TB_NAME?>_form" >

                                <div style="display: none; padding: 5px; color: #c10b0b">
                                    ملاحظة/ عند الحاجة لالغاء اعتماد تقييم، يرجى التواصل مع الشؤون الادارية
                                    <a href="mailto:mzaanoon@gedco.ps?subject=الغاء اعتماد تقييم&body=الاخ محمد الزعنون المحترم ، يرجى الغاء اعتماد تقييم الموظف .."><i class="glyphicon glyphicon-envelope"> مراسلة</i></a>
                                </div>

                                <?php if(count($get_child) > 0){ $count = 1; ?>
                                    <fieldset>
                                        <legend style='background-color:#57B257;'>تقييم المَرْؤُوسين المباشرين</legend>
                                        <div class="modal-body inline_form">
                                            <div class="tbl_container">
                                                <table class="table" id="page_tb" data-container="container">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 2%">#</th>
                                                        <th style="width: 10%">الرقم الوظيفي</th>
                                                        <th style="width: 50%">اسم الموظف</th>
                                                        <th style="width: 15%">التقييم التجريبي </th>
                                                        <th style="width: 15%">العملية</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($get_child as $row) :?>
                                                        <tr>
                                                            <td><?=$count?></td>
                                                            <td><?=$row['EMPLOYEE_NO']?></td>
                                                            <td><?=$row['EMPLOYEE_NO_NAME']?></td>
                                                            <td><?=($row['ETEST_ORDER_SERIAL'])?'<a style="text-decoration: none;" class="btn-xs btn-primary" href="'.$get_etest_url.'/'.$row['ETEST_ORDER_SERIAL'].'/manager"><i class=""></i>عــرض التقييـم</a>':''?></td>
                                                            <td>
                                                                <?php
                                                           $check_res= check_ser($row['EMPLOYEE_NO'],1);
                                                           $check_adopt= check_adopt($check_res);
                                                            if($check_res==0){ ?>
                                                                <a style="text-decoration: none;"  class="btn-xs btn-success" href="<?=$show_form_asks_url.'/'.$row['EMPLOYEE_NO'].'/1'?>"><i class="glyphicon glyphicon-check"></i>بــــدء الـتـقـييــم</a>
                                                            <?php }elseif($check_res>=1 and $check_adopt == 0){
                                                                if(0 and  $row['EMPLOYEE_NO']==$this->user->emp_no)$action='me'; else $action='edit'; ?>
                                                                <a style="text-decoration: none;" class="btn-xs btn-info" href="<?=$get_url.'/'.$check_res.'/'.$action?>"><i class="glyphicon glyphicon-check"></i> تعديل التقييم </a>
                                                            <?php }elseif($check_res>=1 and $check_adopt == 1){
                                                                if(0 and  $row['EMPLOYEE_NO']==$this->user->emp_no)$action='me'; else $action='edit'; ?>
                                                                <a style="text-decoration: none;" class="btn-xs btn-warning" href="<?=$get_url.'/'.$check_res.'/'.$action?>"><i class="glyphicon glyphicon-check"></i>عــرض التقييـم</a>
                                                            <?php }else{ ?>
                                                                <span></span>
                                                            <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $count++ ?>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /.tbl_container -->
                                        </div> <!-- /.modal-body inline_form -->
                                    </fieldset>
                                <?php } ?>

                                <?php if(false and count($get_brother) > 0 and $effective_order['ORDER_TYPE']==1){ $count = 1; // false = تم ايقاف تقييم الزملاء حسب طلب اكرم شاهين 2021/01  ?>
                                    <fieldset>
                                        <legend style='background-color:#57B257;' title='تقييم الزملاء ذوي المستوى الإداري المساوي'>تقييم الزملاء (الأقران)</legend>
                                        <div class="modal-body inline_form">
                                            <div class="tbl_container">
                                                <table class="table" id="page_tb" data-container="container">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 2%">#</th>
                                                        <th style="width: 15%">الرقم الوظيفي</th>
                                                        <th style="width: 50%">اسم الموظف</th>
                                                        <th style="width: 15%">العملية</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($get_brother as $row) :?>
                                                        <tr>
                                                            <td><?=$count?></td>
                                                            <td><?=$row['EMPLOYEE_NO']?></td>
                                                            <td><?=$row['EMPLOYEE_NO_NAME']?></td>
                                                            <td>
                                                                <?php
                                                                $check_res= check_ser($row['EMPLOYEE_NO'],2);
                                                                if($check_res==0){ ?>
                                                                    <a style="text-decoration: none;"  class="btn-xs btn-success" href="<?=$show_form_asks_url.'/'.$row['EMPLOYEE_NO'].'/2'?>"><i class="glyphicon glyphicon-check"></i>بــــدء الـتـقـييــم</a>
                                                                <?php }elseif($check_res>=1 and check_adopt($check_res) == 0){ ?>
                                                                    <a style="text-decoration: none;" class="btn-xs btn-info" href="<?=$get_url.'/'.$check_res.'/'.'edit'?>"><i class="glyphicon glyphicon-check"></i> تعديل التقييم </a>
                                                                <?php }elseif($check_res>=1 and check_adopt($check_res) == 1){
                                                                    if(0 and  $row['EMPLOYEE_NO']==$this->user->emp_no)$action='me'; else $action='edit'; ?>
                                                                    <a style="text-decoration: none;" class="btn-xs btn-warning" href="<?=$get_url.'/'.$check_res.'/'.$action?>"><i class="glyphicon glyphicon-check"></i>عــرض التقييـم</a>
                                                                <?php }else{ ?>
                                                                    <span></span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $count++ ?>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /.tbl_container -->
                                        </div> <!-- /.modal-body inline_form -->
                                    </fieldset>
                                <?php } ?>

                                <?php if(count($get_grandson) > 0 and $effective_order['ORDER_TYPE']==1){ $count = 1; ?>
                                    <fieldset>
                                        <legend style='background-color:#57B257;'>تقييم المَرْؤُوسين غير المباشرين</legend>
                                        <div class="modal-body inline_form">
                                            <div class="tbl_container">
                                                <table class="table" id="page_tb" data-container="container">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 2%">#</th>
                                                        <th style="width: 15%">الرقم الوظيفي</th>
                                                        <th style="width: 50%">اسم الموظف</th>
                                                        <th style="width: 15%">العملية</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($get_grandson as $row) :?>
                                                        <tr>
                                                            <td><?=$count?></td>
                                                            <td><?=$row['EMPLOYEE_NO']?></td>
                                                            <td><?=$row['EMPLOYEE_NO_NAME']?></td>
                                                            <td>
                                                                <?php
                                                                $check_res= check_ser($row['EMPLOYEE_NO'],3);
                                                                if($check_res==0){ ?>
                                                                    <a style="text-decoration: none;"  class="btn-xs btn-success" href="<?=$show_form_asks_url.'/'.$row['EMPLOYEE_NO'].'/3'?>"><i class="glyphicon glyphicon-check"></i>بــــدء الـتـقـييــم</a>
                                                                <?php }elseif($check_res>=1 and check_adopt($check_res) == 0){ ?>
                                                                    <a style="text-decoration: none;" class="btn-xs btn-info" href="<?=$get_url.'/'.$check_res.'/'.'edit'?>"><i class="glyphicon glyphicon-check"></i> تعديل التقييم </a>
                                                                <?php }elseif($check_res>=1 and check_adopt($check_res) == 1){
                                                                    if(0 and  $row['EMPLOYEE_NO']==$this->user->emp_no)$action='me'; else $action='edit'; ?>
                                                                    <a style="text-decoration: none;" class="btn-xs btn-warning" href="<?=$get_url.'/'.$check_res.'/'.$action?>"><i class="glyphicon glyphicon-check"></i>عــرض التقييـم</a>
                                                                <?php }else{ ?>
                                                                    <span></span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $count++ ?>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div> <!-- /.tbl_container -->
                                        </div> <!-- /.modal-body inline_form -->
                                    </fieldset>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.form-body -->
    <?php } ?>
</div><!-- /.row -->

<?php
$scripts = <<<SCRIPT
<script>

    if('{$show_btn}'==0){
        $('.btn-xs').hide();
        $('.main-menu').hide();
    }

    /* Tab menu */
    $(function(){
        $('#tabs #nav li a').click(function(){
            var currentNum = $(this).attr('id').slice(-1);
            $('#tabs #nav li a').removeClass('current');
            $(this).addClass('current');

            $('#tabs #content .tab-slide').hide();
            $('#tabs #content #slide-'+currentNum+'.tab-slide').show();
        });

		$('#tabsSlide #nav li a').click(function(){
            var currentNum = $(this).attr('id').slice(-1);
            $('#tabsSlide #nav li a').removeClass('current');
            $(this).addClass('current');

            $('#tabsSlide #content .tab-slide').slideUp(300);
            $('#tabsSlide #content #slide-'+currentNum+'.tab-slide').slideDown(300);
            });
            });
    /* End Tab menu */
</script>
SCRIPT;
sec_scripts($scripts);
?>

