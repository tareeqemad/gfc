<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/05/15
 * Time: 10:34 ص
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'class_codes';

$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");


$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$print_case_url= base_url("$MODULE_NAME/$TB_NAME/print_case");
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$rep_url = base_url('greport/reports/public_class_barcode');
$rep1_url = base_url('greport/reports/public_class_barcode_tlp');
$rep2_url = base_url('greport/reports/public_class_barcode_tlp1');
$input_id=intval($input_id);
$auto_search=($input_id>0 || $store_id>0)?1:0;

echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>

        <ul>
        </ul>

    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">السنة</label>
                    <div>
                        <input name="entry_date" placeholder="السنة" type="text" id="txt_entry_date" class="form-control" value="<?=date('Y')?>" >
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">كود العهدة</label>
                    <div>
                        <input name="class_code_ser" placeholder="كود العهدة" type="text" id="txt_class_code_ser" class="form-control" >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند الادخال</label>
                    <div>
                        <input value="<?=$auto_search?$input_id:''?>" name="receipt_class_input_id" placeholder="رقم سند الادخال" type="text" id="txt_receipt_class_input_id" class="form-control" >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">اختيار</label>
                    <div>
                        <select  name="code_case" id="dp_code_case"  data-curr="false"  class="form-control" data-val="true" data-val-required="حقل مطلوب" >
                            <option value="0">الكل</option>
                            <?php foreach($code_case_all as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" <?php if ($code_case==$row['CON_NO']){ echo " selected"; } ?> ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف</label>
                    <div>
                        <input name="class_id" placeholder="رقم الصنف" value="<?= isset($class_id) ? $class_id : ''; ?>" type="text" id="txt_class_id" class="form-control" >
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المخزن</label>
                    <div>
                        <select name="store_id" id="store_id" style="width: 250px" id="dp_store_id" data-val="true"
                                data-val-required="حقل مطلوب">
                            <option></option>
                            <?php foreach ($stores as $row) : ?>
                                <option value="<?= $row['STORE_ID'] ?>" <?PHP if ($store_id==$row['STORE_ID']){ echo " selected"; } ?>><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



                <div class="modal-footer">
                <button id="btn_search" type="button" onclick="javascript:search();" class="btn btn-default"> إستعلام</button>
                <?php if ( HaveAccess($print_case_url) ) : ?>
                  <!--  <button id="btn_print" disabled onclick="javascript:print();" type="button" class="btn btn-success">  طباعة </button> -->
                <?php endif; ?>
                <?php //if ( HaveAccess($print_case_url) ) : ?>
                  <!--  <button id="btn_print1"  onclick="javascript:print1();" type="button" class="btn btn-success"> TPL2844 طباعة </button>
               -->
                <?php //endif; ?>
                    <?php //if ( HaveAccess($print_case_url) ) : ?>
                    <button id="btn_print2"  onclick="javascript:print2();" type="button" class="btn btn-success"> TPL2844 طباعة </button>
                    <?php //endif; ?>
                <button id="btn_new" disabled onclick="javascript:get_to_link('<?=$index_url?>');" type="button" class="btn btn-primary"> جديد </button>

                </div>

            </div>

        </form>

        <div id="container"></div>

        <div id="msg_container"></div>

    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الصنف</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/edit")?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                   <div class="form-group">
                        <label class="col-sm-1 control-label"> رقم الباركود  </label>
                       <div class="col-sm-2">
                           <input type="text" readonly data-val="true"   data-val-required="حقل مطلوب" name="class_code_serr" id="txt_class_code_serr" class="form-control">
                            </div>
                    </div>

                    <div class="form-group">
                            <label class="col-sm-1 control-label"> اسم الصنف  </label>
                            <div class="col-sm-9">
                                <input type="text"  readonly data-val="true"   data-val-required="حقل مطلوب" name="class_id_name" id="txt_class_id_name" class="form-control">
                                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="ser" id="txt_ser" class="form-control">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم الشركة المصنعة</label>
                            <div class="col-sm-3">
                                <input type="text"   data-val="true"   data-val-required="حقل مطلوب" name="serial" id="txt_serial" class="form-control">
                                <span class="field-validation-valid" data-valmsg-for="serial" data-valmsg-replace="true"></span>

                            </div>
                    </div>

                    <div class="modal-footer">
                        <?php if( HaveAccess($edit_url)){?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php } ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    if({$auto_search}) search();
    
        
  
    function search(){
        if( {$auto_search}==1 || parseInt( $('#txt_receipt_class_input_id').val()) > 0 || parseInt( $('#txt_class_code_ser').val()) > 0 || parseInt( $('#txt_class_id').val()) > 0  || parseInt( $('#store_id').val()) > 0){
            var values= {entry_date: $('#txt_entry_date').val(),receipt_class_input_id: $('#txt_receipt_class_input_id').val(),class_code_ser: $('#txt_class_code_ser').val(),store_id: $('#store_id').val(),class_id: $('#txt_class_id').val(),code_case:$('#dp_code_case').val()};
            get_data('{$get_page_url}',values ,function(data){
                $('#container').html(data);
                if(data!='error'){
                    $('#txt_receipt_class_input_id').prop('readonly',1);
                    //$('#btn_search').prop('disabled',1);
                    $('#btn_new').prop('disabled',0);
                  /*  if(parseInt( $('#txt_class_code_ser').val()) > 0 ){
                        $('#btn_print').prop('disabled',1);
                    }*/
                }
            },'html');
        }
    }

    function print(){
        var msg= 'لا يمكن طباعة أكواد العهد الا مرة واحدة، هل تريد بالتأكيد الطباعة ؟!';
        if(confirm(msg)){
            _showReport('$rep_url/'+$('#txt_receipt_class_input_id').val()+'/'+$('#txt_class_id').val()+'/'+$('#store_id').val()+'/'+$('#txt_class_code_ser').val()); 
            var print_url= '{$print_case_url}';
            var values= {receipt_class_input_id: $('#txt_receipt_class_input_id').val(), store_id: $('#store_id').val(), class_id: $('#txt_class_id').val()};

            setTimeout(function(){
                get_data(print_url, values, function(ret){
                    if(ret==1){
                        //success_msg('رسالة','تم  ..');
                        //_showReport('$print_url'.'report=FIXED_REP/CLASS_CODES_TB_REP&params[]='+$('#txt_receipt_class_input_id').val());
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');
            }, 2200);
        }
    }
 /*function print1(){
      var code_ser ;
     //   var msg= 'لا يمكن طباعة أكواد العهد الا مرة واحدة، هل تريد بالتأكيد الطباعة ؟!';
     //   if(confirm(msg)){
     if ($('#txt_class_id').val()=='') class_id=-1;  else class_id=$('#txt_class_id').val() ;
if ($('#store_id').val()=='') store_id=-1;  else store_id=$('#store_id').val() ;
if ($('#txt_class_code_ser').val()=='') code_ser=-1;  else code_ser=$('#txt_class_code_ser').val() ;
if ($('#txt_entry_date').val()=='') entry_date=-1;  else entry_date=$('#txt_entry_date').val() ;
if ($('#txt_receipt_class_input_id').val()=='') receipt=0;  else receipt=$('#txt_receipt_class_input_id').val() ;
      
         
            _showReport('$rep1_url/'+receipt+'/'+entry_date+'/'+class_id+'/'+store_id+'/'+code_ser+'/'+$('#dp_code_case').val());

         
    }
*/

   
     function print2(){
      var code_ser ;
     //   var msg= 'لا يمكن طباعة أكواد العهد الا مرة واحدة، هل تريد بالتأكيد الطباعة ؟!';
     //   if(confirm(msg)){
     if ($('#txt_class_id').val()=='') class_id=-1;  else class_id=$('#txt_class_id').val() ;
if ($('#store_id').val()=='') store_id=-1;  else store_id=$('#store_id').val() ;
if ($('#txt_barcode').val()=='') code_ser=-1;  else code_ser=$('#txt_class_code_ser').val() ;
if ($('#txt_entry_date').val()=='') entry_date=-1;  else entry_date=$('#txt_entry_date').val() ;
if ($('#txt_receipt_class_input_id').val()=='') receipt=0;  else receipt=$('#txt_receipt_class_input_id').val() ;
//alert('$rep1_url/'+$('#txt_receipt_class_input_id').val()+'/'+class_id+'/'+store_id+'/'+$('#txt_class_code_ser').val()+'/'+$('#dp_code_case').val());
          
         
            _showReport('$rep2_url/'+receipt+'/'+entry_date+'/'+class_id+'/'+store_id+'/'+code_ser+'/'+$('#dp_code_case').val());

          /*  var print_url= '{$print_case_url}';
            var values= {receipt_class_input_id: $('#txt_receipt_class_input_id').val(), store_id: $('#store_id').val(), class_id: $('#txt_class_id').val()};

            setTimeout(function(){
                get_data(print_url, values, function(ret){
                    if(ret==1){
                        //success_msg('رسالة','تم  ..');
                        //_showReport('$print_url'.'report=FIXED_REP/CLASS_CODES_TB_REP&params[]='+$('#txt_receipt_class_input_id').val());
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');
            }, 2200);*/
   //     }
    }


      function class_codes_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){

                $('#txt_class_code_serr').val('');
                $('#txt_class_id_name').val( '');
                $('#txt_ser').val( '');
                $('#txt_serial').val( '');

                $('#txt_class_code_serr').val(item.CLASS_CODE_SER);
                $('#txt_class_id_name').val( item.CLASS_ID+':'+item.CLASS_ID_NAME);
                $('#txt_ser').val( item.SER);
                $('#txt_serial').val( item.SERIAL);


                $('#{$TB_NAME}_from').attr('action','{$edit_url}');

                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }

 $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });
$('#store_id').select2();
</script>
SCRIPT;
sec_scripts($scripts);
?>
