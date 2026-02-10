<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/10/14
 * Time: 09:06 ص
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'exp_rev_total';
$TB_NAME2= 'exp_rev_sec';
$TB_NAME3= 'exp_rev_itm';
$TB_NAME4= 'exp_rev_history';
$TB_NAME5= 'exp_rev_bra_total_ch';
$TB_NAME6= 'exp_rev_bra_total_sec';


$get_chp= base_url("$MODULE_NAME/$TB_NAME/get_chapters");
$get_sec= base_url("$MODULE_NAME/$TB_NAME/get_sections");
$get_itm= base_url("$MODULE_NAME/$TB_NAME/get_items");
$get_history= base_url("$MODULE_NAME/$TB_NAME/get_history");
$position_url= base_url("$MODULE_NAME/$TB_NAME/select_position");
$attachment_data_url= base_url("$MODULE_NAME/$TB_NAME/attachment_get");
$get_bran_ch= base_url("$MODULE_NAME/$TB_NAME/get_total_branches_chapters");
$get_bran_sec= base_url("$MODULE_NAME/$TB_NAME/get_total_branches_section");
?>

<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption">اجمالي النفقات والايرادات لعام <?=$year?></div>

        <ul>
            <li id="get_data"><a onclick='javascript:<?=$TB_NAME?>_get_data();' href='javascript:;'><i class='glyphicon glyphicon-import'></i>استعلام</a></li>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" role="form" novalidate="novalidate">

                <div id="selects">
                    <div id="type" class="col-sm-3">
                        <select name='type' id='txt_type' class='form-control'>
                            <?php foreach($consts as $row) :?>
                                <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                            <option value='0' >الفائض و العجز</option>
                        </select>
                    </div>
                    <div class="col-sm-3">

                        <select name="budget_ttype" id="txt_budget_ttype" class="form-control"  dir="rtl" >
                            <option value='0' >جميع أنواع الموازنة</option>
                            <?php foreach($BUDGET_TYPES as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div id="branch" class="col-sm-3"><?=$select_branch?></div>
                    <div id="position" class="col-sm-3"></div>





                </div>


                <div id="data"></div>
                <?=AntiForgeryToken(); ?>
            </form>
        </div>
    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME2?>Modal">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div style="clear: both" ></div>
            <button type="button" class="btn btn-warning" onclick="$('#exp_rev_sections_tb').tableExport({type:'excel',escape:'false'});"> تصدير الفصول لاكسل </button>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="<?=$TB_NAME3?>Modal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div style="clear: both" ></div>
            <button type="button" class="btn btn-warning" onclick="$('#exp_rev_items_tb').tableExport({type:'excel',escape:'false'});"> تصدير البنود لاكسل </button>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="<?=$TB_NAME4?>Modal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="<?=$TB_NAME5?>Modal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div style="clear: both" ></div>
            <button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_branch_tb').tableExport({type:'excel',escape:'false'});"> تصدير التفاصيل لاكسل </button>

            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="<?=$TB_NAME6?>Modal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div style="clear: both" ></div>
            <button type="button" class="btn btn-warning" onclick="$('#exp_rev_total_sec_branch_tb').tableExport({type:'excel',escape:'false'});"> تصدير التفاصيل لاكسل </button>

            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">المرفقات</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {
        $('#txt_type').select2();
        $('#txt_branch').select2();
        $('#txt_budget_ttype').select2();


        $('#txt_branch').change(function(){
            {$TB_NAME}_get_position();
        });

        $('#txt_type').change(function(){
            {$TB_NAME}_get_position();
        });

    });

    function {$TB_NAME}_get_position(){
        $('#position').text('');
        $('#container #data').text('');
            if($('#txt_branch').val() !=0 ){
                var values= $("#{$TB_NAME}_form").serialize();
                get_data('$position_url', values, function(ret){ $('#position').html(ret); }, 'html');
            }
    }

    function {$TB_NAME}_get_data(){
        $('#container #data').text('');
        if( ($('#txt_type').val() > 0 || $('#txt_type').val() == 0 ) ){
            var values= $("#{$TB_NAME}_form").serialize();
                 get_data("$get_chp", values, function(ret){
                $('#container #data').html(ret);
            }, 'html');
        } else alert('يجب اختيار نوع الموازنة');
    }

    function {$TB_NAME}_get_sec(chp, adopt){
        $('#{$TB_NAME2}Modal .modal-body').text('');
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME}_form #tdc-'+chp+adopt).text() );
        get_data('$get_sec', {type:$('#txt_type').val(), branch:$('#txt_branch').val(), position:$('#txt_position').val(), chapter:chp, adopt:adopt,budget_ttype:$('#txt_budget_ttype').val()}, function(ret){ $('#{$TB_NAME2}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME2}Modal').modal();
    }
        function {$TB_NAME}_get_sec_1(chp, adopt){
        $('#{$TB_NAME2}Modal .modal-body').text('');
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME}_form #tdc-'+chp+adopt).text() );
        get_data('$get_sec', {type:1, branch:$('#txt_branch').val(), position:$('#txt_position').val(), chapter:chp, adopt:adopt,budget_ttype:$('#txt_budget_ttype').val()}, function(ret){ $('#{$TB_NAME2}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME2}Modal').modal();
    }
        function {$TB_NAME}_get_sec_2(chp, adopt){
        $('#{$TB_NAME2}Modal .modal-body').text('');
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME}_form #tdc-'+chp+adopt).text() );
        get_data('$get_sec', {type:2, branch:$('#txt_branch').val(), position:$('#txt_position').val(), chapter:chp, adopt:adopt,budget_ttype:$('#txt_budget_ttype').val()}, function(ret){ $('#{$TB_NAME2}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME2}Modal').modal();
    }
        function {$TB_NAME}_get_bran_total_ch(chp, adopt){
        $('#{$TB_NAME5}Modal .modal-body').text('');
        $('#{$TB_NAME5}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME}_form #tdc-'+chp+adopt).text() );
        get_data('$get_bran_ch', {type:$('#txt_type').val(), branch:$('#txt_branch').val(), position:$('#txt_position').val(), chapter:chp, adopt:adopt,budget_ttype:$('#txt_budget_ttype').val()}, function(ret){ $('#{$TB_NAME5}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME5}Modal').modal();
    }
         function {$TB_NAME}_get_bran_total_sec(sec, adopt){
        $('#{$TB_NAME6}Modal .modal-body').text('');
      $('#{$TB_NAME6}Modal .modal-title').text('تفاصيل ' + $('#tds-'+sec).text() );
        get_data('$get_bran_sec', {type:$('#txt_type').val(), branch:$('#txt_branch').val(), position:$('#txt_position').val(), section_no:sec, adopt:adopt,budget_ttype:$('#txt_budget_ttype').val()}, function(ret){ $('#{$TB_NAME6}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME6}Modal').modal();
    }

    function {$TB_NAME}_get_itm(sec, adopt,type1){
        $('#{$TB_NAME3}Modal .modal-body').text('');
        $('#{$TB_NAME3}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME2}Modal #tds-'+sec).text() );
        get_data('$get_itm', {type:type1, branch:$('#txt_branch').val(), position:$('#txt_position').val(), section:sec, adopt:adopt}, function(ret){ $('#{$TB_NAME3}Modal .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME3}Modal').modal();
    }

    function {$TB_NAME}_get_history(sec){
        if(  $('#txt_position').val()==0  ||  $('#txt_position').val()== undefined ){
            $('#{$TB_NAME4}Modal .modal-body').text('');
            $('#{$TB_NAME4}Modal .modal-title').text('البيانات التاريخية -  ' + $('#{$TB_NAME2}Modal #tds-'+sec).text() );
            get_data('$get_history', {branch:$('#txt_branch').val(), section:sec}, function(ret){ $('#{$TB_NAME4}Modal .modal-body').html(ret); }, 'html');
            $('#{$TB_NAME4}Modal').modal();
        }else
            alert('اختر جميع الدوائر اولا..');
    }

    function attachment_get(item){
        if(item !=0 && item != null && $('#txt_branch').val() !=0 && $('#txt_branch').val() != null  ){
            var values= {type:$('#txt_type').val(), branch:$('#txt_branch').val(), item:item};
            get_data("{$attachment_data_url}", values, function(ret){
                    $('#uploadModal .modal-body').html(ret);
                    $('#uploadModal').modal();
            }, 'html');
        }
    }

</script>

SCRIPT;

sec_scripts($scripts);
?>
