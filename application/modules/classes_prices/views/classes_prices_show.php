<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/02/20
 * Time: 11:54 ص
 */
$MODULE_NAME= 'classes_prices';
$TB_NAME= 'classes_prices';
/*
$print_url=  'http://itdev:801/gfc.aspx?data='.get_report_folder().'&' ;
$print_url1=  'http://itdev:801/gfc.aspx?data=&' ;


$report_url = base_url("JsperReport/showreport?sys=purchases");
$report_sn= report_sn();
*/
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");

$select_items_url = base_url("stores/classes/public_index");
$get_class_url = base_url('stores/classes/public_get_id');


$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");


if ($action=='index') {


    $ser='';
    $type=2;
    $price_date=date('d/m/Y');
    $price='';
    $notes='';
    $class_id='';
    $class_id_name='';
    $adopt=0;
    $entry_date=date('d/m/Y');
    $type_name='سعر السوق';
}else{
    $rs= $orders_data[0];


    $ser=isset($rs['SER'])? $rs['SER'] :'';
    $type=isset($rs['TYPE'])? $rs['TYPE'] :0;
    $price_date=isset($rs['PRICE_DATE'])? $rs['PRICE_DATE'] :'';
    $price =isset($rs['PRICE'])? $rs['PRICE'] :'';
    $notes=isset($rs['NOTES'])? $rs['NOTES'] :'';
    $class_id=isset($rs['CLASS_ID'])? $rs['CLASS_ID'] :'';
    $class_id_name=isset($rs['CLASS_ID_NAME'])? $rs['CLASS_ID_NAME'] :'';
    $entry_date=isset($rs['ENTRY_DATE'])? $rs['ENTRY_DATE'] :'';
    $adopt =isset($rs['ADOPT'])? $rs['ADOPT'] :'';
    $order_id =isset($rs['ORDER_ID'])? $rs['ORDER_ID'] :'';
    $type_name=isset($rs['TYPE_T'])? $rs['TYPE_T'] :'';

}
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));

$adopt1_url=base_url("$MODULE_NAME/$TB_NAME/adopt1");
$adopt2_url=base_url("$MODULE_NAME/$TB_NAME/adopt2");
$adopt3_url=base_url("$MODULE_NAME/$TB_NAME/adopt3");


echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul>
            <?php //HaveAccess($back_url)
            if( TRUE):  ?><li><a  href="<?= $back_url ?>?type=<?=$type?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>


    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">


                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم المسلسل </label>
                        <div>
                            <input type="text" readonly value="<?=$ser?>"  name="ser"  id="txt_ser" class="form-control" />
                            <input type="hidden" name="type" value="<?=$type?>"  id="dp_type">
                        </div>
                    </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">  نوع التسعير  </label>
                    <div >
                        <input type="text" readonly value="<?=$type_name?>" name="type_name"  id="txt_type_name" class="form-control ">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> تاريخ السند  </label>
                    <div >
                        <input type="text" readonly value="<?=$entry_date?>" name="entry_date"  id="txt_entry_date" class="form-control ">

                    </div>
                </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الصنف  </label>
                        <div><input type="hidden" id="h_data_search"/>
                            <input type="text"   value="<?=$class_id?>"  name="h_class_id"  id="h_class_id" class="form-control" />
                        </div>
                    </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم الصنف  </label>
                    <div>
                        <input type="text" readonly   value="<?=$class_id_name?>"  name="class_id"  id="class_id" class="form-control" />
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">السعر</label>
                    <div >
                        <input type="text"  value="<?=$price?>"  name="price"  id="txt_price" class="form-control" />

                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ السعر</label>
                    <div >
                        <input type="text"  value="<?=$price_date?>" name="price_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_price_date" class="form-control ">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <textarea rows="3" name="notes" id="txt_notes" class="form-control" ><?=$notes?></textarea>

                     </div>
                </div>

            </div>

            <div class="modal-footer">
                <?php if ( ( HaveAccess($create_url)||HaveAccess($edit_url)) && (($adopt==1  )|| ($adopt==0  ))  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($adopt2_url)) and ($action=='edit') && ($adopt==1  )  ) : ?>
                    <button type="button" onclick='javascript:adopt2();' class="btn btn-primary">اعتماد</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($adopt1_url)) and ($action=='edit') && ($adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:adopt1();' class="btn btn-primary">إلغاء اعتماد</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($adopt3_url)) and ($action=='edit') && ($adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:adopt3();' class="btn btn-primary">اعتماد نهائي</button>
                <?php endif; ?>


                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>

            </div>

        </form>
    </div>
</div>



<?php
$shared_js = <<<SCRIPT




$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){

    if(parseInt(data)>=1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }

    },"html");
});


 $('input[name="class_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_items_url/'+$(this).attr('id')+'/1'+ $('#h_data_search').val() );
    });
$('input[name="h_class_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_items_url/'+$('#class_id').attr('id')+'/1'+ $('#h_data_search').val() );
    });
       
 $('input[name="h_class_id"]').change(function(e){
       var id_v=$(this).val();
 
         get_data('{$get_class_url}',{id:id_v, type:1},function(data){
              /////
                if (data.length == 1){

                    var item= data[0];
                     if(item.CLASS_STATUS==1){
                     $('#class_id').val(item.CLASS_NAME_AR);

                 }else{
                    $('#h_class_id').val('');
                    $('#class_id').val('');
                 }
              } else{ $('#h_class_id').val('');
                    $('#class_id').val('');}
         });
  });

      




SCRIPT;



if(HaveAccess($create_url) and $action=='index' ){
    $scripts = <<<SCRIPT
<script>

 // $(function() {
  //      $( "#orders_detailTbl tbody" ).sortable();
 //   });
  {$shared_js}


    function clear_form(){
        clearForm($('#{$TB_NAME}_from'));

    }



</script>

SCRIPT;
    sec_scripts($scripts);

}
else
    if(HaveAccess($edit_url) and $action=='edit'){


        $edit_script = <<<SCRIPT


<script>

  {$shared_js}


function adopt1(){

    if(confirm('هل تريد إتمام العملية ؟')){

    get_data('{$adopt1_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}

function adopt2(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt2_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}




function adopt3(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt3_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}



</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>
