<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/10/16
 * Time: 09:31 ص
 */

$MODULE_NAME='hr';
$TB_NAME='eval_etest_order';

$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$get_ask_mark_url=base_url("$MODULE_NAME/$TB_NAME/get_ask_mark");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

// range style depend on brwoser
if($browser=='Firefox')
    $range_class='form-control';
else
    $range_class='';

if(count($result_details)==0 )
    die();

//select first element from array
$master_res = $result[0];
$details_res = $result_details[0];
$count = 0;

?>

<div class="row">
    <div class="toolbar">
        <div class="caption" style="width: 600px"><i class="glyphicon glyphicon-check"></i><?= $title.' / '.$master_res['EMP_ID_NAME'].' / '.$details_res['FORM_ID_NAME'].' للعام '.$master_res['THE_YEAR']; ?></div>
        <div class="caption">
        </div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div><!-- /.toolbar -->
</div>

<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$edit_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <div class="tbl_container">
                    <?php foreach($result_details as $row) :
                    if($count == 0 or $result_details[$count]['EAXES_ID']!=$result_details[$count-1]['EAXES_ID'] ){
                    $cnt=1;
                    ?>
                    <fieldset>
                        <legend><?=$row['EFA_ID_NAME']?></legend>
                        <div id="container">
                        <div class="tbl_container">
                        <table class="table" id="page_tb" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 2%">#</th>
                            <th style="width: 30%">السؤال</th>
                            <th style="width: 20%">التقييم</th>
                            <th style="width: 17%">الوصف</th>
                            <th style="width: 17%">الانجاز</th>
                            <th style="width: 17%">ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php } ?>
                    <tr data-marks='<?=($row['EAXES_ID']!='')? modules::run($get_ask_mark_url, $row['ELEMENT_ID']):''?>'>
                        <td><?=$cnt?>
                            <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                            <input type="hidden" name="element_id[]" value="<?=$row['ELEMENT_ID']?>" />
                        </td>
                        <td style="text-align: right"><?=$row['ELEMENT_ID_NAME']?></td>
                        <td>
                            <input style="float: right; width: 85%" <?=($action=='manager')?'disabled':'' ?> class="<?=$range_class?>" id="txt_new_mark" name="mark[]" type="range" min="0" max="100" step="1" value="<?=$row['MARK']?>" />
                            <input style="float: right; width: 11%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="<?=$row['MARK']?>" />
                        </td>
                        <td><?=($row['EAXES_ID']!='') ? "<input class='form-control mark_desc' readonly type='text' value='' />" : ''?></td>

                        <td><input class="form-control" name="achievement[]" type="text" title="<?=$row['ACHIEVEMENT']?>" value="<?=$row['ACHIEVEMENT']?>"></td>
                        <td><input class="form-control" name="hint[]" type="text" title="<?=$row['HINT']?>" value="<?=$row['HINT']?>"></td>
                    </tr>

                    <?php $count++; $cnt++;
                    if( $count ==count($result_details) or $result_details[$count]['EAXES_ID']!=$result_details[$count-1]['EAXES_ID'] ){ ?>
                        </tbody>
                        </table>
                        </div>
                        </div>
                        </fieldset>
                    <?php } endforeach;?>

                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($edit_url) and $action=='edit' )   : ?>
                    <button type="submit" data-action="submit" id='btn_update' class="btn btn-primary edit">تعديل التقييم</button>
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
        if (ask_tr.attr('data-marks')=='') return 0;

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

    $('button[data-action="submit"].edit').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد تعديل التقييم !')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data>=1 ){
                    success_msg('رسالة','تم تعديل التقييم بنجاح ...');
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

</script>
SCRIPT;
sec_scripts($scripts);
?>
