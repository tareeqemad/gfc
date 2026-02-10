<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url=base_url('technical/Holders');


if(!isset($result))
    $result= array();
$HaveRs = count($result) > 0;

$rs =$HaveRs? $result[0] : $result;

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>


        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-form-vertical" id="hpp_form" method="post" action="<?=base_url('technical/Holders/'.($HaveRs?'edit':'create'))?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الرقم  </label>
                            <div>
                                <input type="text"  readonly  value="<?= $HaveRs? $rs['HOLDER_ID'] : "" ?>"  name="holder_id" id="txt_holder_id" class="form-control">
                            </div>
                        </div>

                        <div class="form-group  col-sm-1">
                            <label class="control-label">الفرع </label>
                            <div>

                                <select type="text"   name="branch" id="dp_branch" class="form-control" >

                                    <?php foreach($branches as $row) :?>
                                        <option <?= $HaveRs?($rs['BRANCH'] ==$row['NO']?'selected':''):'' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>    </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label"> البيان   </label>
                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال البيان "    value="<?= $HaveRs? $rs['NOTES'] : "" ?>"  name="notes" id="txt_notes" class="form-control">
                            </div>
                        </div>


                        <div class="form-group col-sm-3">
                            <label class="control-label"> الصنف   </label>
                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال البيان "    value="<?= $HaveRs? $rs['CLASS_ID'] : "" ?>"  name="class_id" id="h_txt_class_id" class="form-control col-md-4">
                                <input readonly value="<?= $HaveRs? $rs['CLASS_ID_NAME'] : "" ?>" class="form-control  col-md-8"  id="txt_class_id" />
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label"> قاعدة العمود   </label>
                            <div>
                                <input type="text"    value="<?= $HaveRs? $rs['BASE_CLASS_ID'] : "" ?>"  name="base_class_id" id="h_txt_base_class_id" class="form-control  col-md-4">
                                <input readonly value="<?= $HaveRs? $rs['BASE_CLASS_ID_NAME'] : "" ?>" class="form-control  col-md-8"  id="txt_base_class_id" />
                            </div>
                        </div>
                        <hr>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> الشركة المصنعة  </label>
                            <div>
                                <input type="text"   value="<?= $HaveRs? $rs['COMPANY_NAME'] : "" ?>"  name="company_name" id="txt_company_name" class="form-control">
                            </div>
                        </div>



                        <div class="form-group col-sm-2">
                            <label class="control-label"> الخط المغذي </label>
                            <div>

                                <select   name="feeder_line" id="txt_feeder_line" class="form-control">
                                    <?php foreach($FEEDER_LINE as $row) :?>
                                        <option <?= $HaveRs?($rs['FEEDER_LINE'] ==$row['CON_NO']?'selected':''):'' ?> value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">حالة العمود </label>
                            <div>
                                <select   name="holder_case" id="dp_holder_case" class="form-control">

                                    <option <?= $HaveRs?($rs['HOLDER_CASE'] ==1?'selected':''):'' ?> value="1" >جديد</option>
                                    <option <?= $HaveRs?($rs['HOLDER_CASE'] ==2?'selected':''):'' ?> value="2" > مستعمل </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">حالة فاعدة العمود </label>
                            <div>

                                <select   name="base_holder_case" id="txt_base_holder_case" class="form-control">
                                    <option <?= $HaveRs?($rs['BASE_HOLDER_CASE'] ==1?'selected':''):'' ?> value="1" >جديد</option>
                                    <option <?= $HaveRs?($rs['BASE_HOLDER_CASE'] ==2?'selected':''):'' ?> value="2" > مستعمل </option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label">التأريض   </label>
                            <div>

                                <select   name="ground" id="txt_ground" class="form-control">
                                    <option <?= $HaveRs?($rs['GROUND'] ==1?'selected':''):'' ?> value="1" > مؤرضة</option>
                                    <option <?= $HaveRs?($rs['GROUND'] ==2?'selected':''):'' ?> value="2" > غير مؤرضة  </option>
                                </select>
                            </div>
                        </div>



                        <hr>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 control-label">الموقع (خريطة)</label>
                            <div class="col-sm-6">
                                <input type="text"  data-val="true" data-val-required="يجب إدخال الاحداثيات "    value="<?= $HaveRs? $rs['X'] : "" ?>"   name="x" id="txt_x" class="form-control">

                            </div>
                            <div class="col-sm-6">
                                <input type="text"   data-val="true" data-val-required="يجب إدخال الاحداثيات "   value="<?= $HaveRs? $rs['Y'] : "" ?>"   name="y" id="txt_y" class="form-control">

                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="col-sm-12 control-label" style="height: 25px;">  </label>
                            <button  type="button" class="btn green" onclick="javascript:_showReport('<?=base_url("maps/public_map/txt_x/txt_y") ?>');">
                                <i class="icon icon-map-marker"></i>
                            </button>

                        </div>
                        <hr>



                        <div class="form-group  col-sm-7">
                            <label class="control-label">الملاحظات</label>
                            <div >
                                <input type="text"   value="<?= $HaveRs? $rs['HINT'] : "" ?>"   name="hint" id="txt_hint" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;">
                        <?php echo modules::run('technical/Holders/public_get_equipments',$HaveRs?$rs['HOLDER_ID']:0); ?>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </form>

            </div>

        </div>
    </div>

<?php
$delete_url = base_url('technical/Holders/delete_equipments');
$adapters_url =base_url('projects/adapter/public_index');
$hpp_url =base_url('technical/HighPowerPartition/public_index');
$select_items_url=base_url("stores/classes/public_index");
$shared_script=<<<SCRIPT
      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                reload_Page();

            },'html');
        }
    });

        $('#txt_adapter_serial').click(function(){
            _showReport('$adapters_url/'+$(this).attr('id')+'/');
        });

function _showPopup(a){

var type = $(a).closest('tr').find('select[name*="d_class_id"]').val();
var obj = $(a).closest('tr').find('input[name*="d_feeder_line"]').attr('id').replace('h_','');

console.log('',type);

if(type == 1)
             _showReport('$adapters_url/'+obj+'/');
    else if(type == 2)
 _showReport('$hpp_url/'+obj+'/');
}


reBind();

function reBind(){
        $('#txt_class_id,#txt_base_class_id,input[id*="txt_base_d_class_id"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });}


 $('#h_txt_class_id,#h_txt_base_class_id').keyup(function(){
    $('#'+$(this).attr('id').replace('h_','')).val('');
 });

 function delete_details(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }

SCRIPT;


$create_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;



$edit_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

if($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>