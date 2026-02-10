<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 20/06/16
 * Time: 12:42 م
 */

$MODULE_NAME='hr';
$TB_NAME='evaluation_emps_start';
$TB_NAME_POST='evaluation_emp_order';

$get_asks_page_url=base_url("$MODULE_NAME/$TB_NAME/get_asks");
$get_extra_asks_page_url=base_url("$MODULE_NAME/$TB_NAME/get_extra_asks");
$post_url= base_url("$MODULE_NAME/$TB_NAME_POST/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME_POST/get");

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if(0 and HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if(0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>

<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <div class="tbl_container">

                    <fieldset><legend style="background-color: #ff4c4c" >تنويه</legend>
                        سيتم تقييم البنود المتعلقة بـ: <br>
                        - الالتزام بالدوام الرسمي. <br>
                        - العقوبات والجزاءات الإدارية. <br>
                        بشكل آلي، وبالتالي سيتم تجميد هذين البندين في نماذج التقييم.

                    </fieldset>

                    <input type="hidden" name="evaluation_order_id" value="<?=$EVALUATION_ORDER_ID?>" />
                    <input type="hidden" name="emp_no" value="<?=$EMP_NO?>" />
                    <input type="hidden" name="manager_no" value="<?=$MANAGER_NO?>" />

                <?php
                IF($FORM_TYPE==1){

                if(count($axes)==0){
                    echo "لا يوجد نموذج";
                    die();
                }
                if($eextra_id==-3){
                    echo "يجب ادخال المحور الاشرافي في التقييمات الاضافية اولا";
                    die();
                }
                else if($eextra_id==-2){
                    echo "خطأ في اجمالي الوزن النسبي للمحور الاشرافي";
                    die();
                }
                foreach($axes as $row ){
                    // EVALUATION_FORM_ID
                ?>
                    <fieldset>
                    <legend><?=$row['EAXES_ID_NAME']?></legend>

                        <div id="container">
                            <?=modules::run($get_asks_page_url, $row['EFA_ID'], $FORM_ID);?>
                        </div>

                    </fieldset>
                <?php
                }
                ?>

                <?php
                if($eextra_id != -1){ ?>
                        <fieldset>
                            <legend>المحور الإشرافي</legend>
                            <div id="container">
                                <?=modules::run($get_extra_asks_page_url, $eextra_id);?>
                            </div>
                        </fieldset>
                    <?php
                }

                }elseif($FORM_TYPE==2){
                    if($brother_form==0){
                        echo "خطأ في النموذج";
                        die();
                    }
                ?>
                    <fieldset>
                        <legend> تقييم الزملاء </legend>
                        <div id="container">
                            <?=modules::run($get_extra_asks_page_url, $brother_form);?>
                        </div>
                    </fieldset>
                <?php
                }elseif($FORM_TYPE==3){
                    if($grandson_form==0){
                        echo "خطأ في النموذج";
                        die();
                    }
                    ?>
                    <fieldset>
                        <legend> تقييم المرؤسيين </legend>
                        <div id="container">
                            <?=modules::run($get_extra_asks_page_url, $grandson_form);?>
                        </div>
                    </fieldset>
                <?php
                }
                ?>
                </div>
            </div>
              <div class="modal-footer">
                  <?php if (HaveAccess($post_url)) : ?>
                      <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                  <?php endif; ?>
              </div>
        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('table#page_tb tbody tr').each(function(){
        ask_mark_desc( $(this) );
    });

    $('input[type="range"]').on("change mousemove keypress", function() { // change mousemove keypress  mouseup keyup
        var ask_val= parseInt($(this).val());
        $(this).next().val(ask_val);
        var ask_tr= $(this).closest('tr');
        ask_mark_desc(ask_tr);
    });

    function ask_mark_desc(ask_tr){
        var marks_json= JSON.parse( ask_tr.attr('data-marks') );
        var ask_val= parseInt( ask_tr.find('input[type="range"]').val() );
        $.each(marks_json, function( index, data ) {
            if(ask_val >= parseInt(data.MARK_FROM) && ask_val <= parseInt(data.MARK_TO) ){
                ask_tr.find('.mark_desc').val( marks_json[index].RANGE_ORDER_NAME+': '+ marks_json[index].MARK_RANGE_DESCRIPTION);
                ask_tr.find('.mark_desc').css('background-color',range_bk_color(parseInt(marks_json[index].RANGE_ORDER)));
            }
        });
    }

    function range_bk_color(bk_color) {
        switch (bk_color) {
        case 1:
            bk_color = '#F8696B';
            break;
        case 2:
            bk_color = '#FCBF7B';
            break;
        case 3:
            bk_color = '#E9E583';
            break;
        case 4:
            bk_color = '#B1D580';
            break;
        case 5:
            bk_color = '#63BE7B';
        }
        return bk_color;
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ التقييم ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ التقييم بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ التقييم بنجاح ..');
                    //get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

</script>
SCRIPT;
sec_scripts($scripts);
?>

