<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 12:45 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'bouns_equivalent';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");

$adopt_all_url_eq= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt_all_url_eq= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$adopt_detail_eq = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
/*payroll_data/bouns_risk/CancelAdopt
/* U_BOUNS_RISK_MA,I_BOUNS_RISK_MA_AOPT */
//الغاء لااعتماد
$CancelAdopt_eq = base_url("$MODULE_NAME/$TB_NAME/CancelAdopt_eq");
$adopt_comm_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

echo AntiForgeryToken();
?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?>
            <li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </li>
            <?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

    <div class="form-body">
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">


                <?php if(HaveAccess ($branch_query_url)){ ?>
                    <div class="form-group col-sm-2">
                        <label> المقر</label>
                        <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                            <option value="">_______</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } elseif( ! HaveAccess ($branch_query_url)){ ?>
                    <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                <?php } ?>

                <div class="form-group col-sm-2">
                    <label>الشهر</label>
                    <div>
                        <input type="text" placeholder="الشهر"
                               name="month"
                               id="txt_month" class="form-control"  value="<?= date('Ym') ?>" >
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>الدائرة</label>
                    <div>
                        <select name="head_department" id="dp_head_department" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($head_department_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>نوع التعيين</label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>طبيعة العمل</label>
                    <div>
                        <select name="w_no" id="dp_w_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($w_no_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>حالة الاعتماد</label>
                    <div>
                        <select name="agree_ma" id="dp_agree_ma" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label>رأي اللجنة</label>
                    <div>
                        <select name="committee_case" id="dp_committee_case" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($committee_case as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <br>
            </div>
        </form>
        <div class="modal-footer">

            <button type="button" onclick="javascript:search();" class="btn btn-success">
                <i class="fa fa-search"></i>
                إستعلام
            </button>

            <?php  if ( HaveAccess($adopt_comm_url.'2') ){ ?>
                <button type="button" onclick="javascript:comm_modal();" class="btn btn-info">
                    <i class="fa fa-check"></i>
                    اعتماد المحدد
                </button>
            <?php } ?>

            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  إكسل
                <i class="fa fa-file-excel-o"></i>
            </button>

            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?php  echo  modules::run($get_page,$page); ?>

        </div>
    </div>
</div> <!--end row-->



<!--Modal NOTE Bootstrap -2- اعتماد اللجنة-->
<!--Start Modal -->
<div class="modal fade bd-example-modal-lg" id="comm_note"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> اعتماد اللجنة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <fieldset class="field_set">
                        <legend> رأي اللجنة النهائي بعد الاطلاع</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-sm-1 control-label">الحالة </label>
                                <div class="col-sm-2">
                                    <select name="committee_case" id="dps_committee_case" class="form-control" >
                                        <?php foreach ($committee_case as $row) : ?>
                                            <option value="<?=$row['CON_NO']?>" >
                                                <?= $row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="col-sm-1 control-label">ملاحظة اللجنة</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                           name="committee_note"
                                           id="txt_committee_note"
                                           data-val="true"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </div>
            <div class="modal-footer">
                <?php  if ( HaveAccess($adopt_comm_url.'2') ){ ?>
                    <button type="button" onclick="javascript:update_data();" class="btn btn-info">
                        <i class="fa fa-check"></i>
                        اعتماد المحدد
                    </button>
                <?php } ?>

                <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

  <?php
$scripts = <<<SCRIPT
<script type="text/javascript">


    $('.sel2').select2();


    var table = 'bouns_equivalent';
    var count = 0;

 
    $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
        });
    
   
    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function values_search(add_page){
        var values=
            {page:1, branch_no:$('#dp_branch_no').val(),
                month:$('#txt_month').val(),
                emp_no:$('#dp_emp_no').val(),
                head_department:$('#dp_head_department').val(),
                w_no:$('#dp_w_no').val(),
                emp_type:$('#dp_emp_type').val(),
                agree_ma:$('#dp_agree_ma').val(),
                committee_case:$('#dp_committee_case').val()
            };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        //console.log(values);
        get_data('{$get_page}',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    
    //فتح مودال اللجنة
    function comm_modal() {
       $('#comm_note').modal('show');
    }


    function clear_form(){
        clearForm($('#payroll_form'));
        $('.sel2').select2('val','');
    }
    

    
     function update_data(){
        
      var committee_note= $('#txt_committee_note').val();
      var committee_case= $('#dps_committee_case').val();
    
        var no= 2;
        var ser= 0;
        var cnt= 0;
        cnt= $('#page_tb .checkboxes:checked').length;
        var msg= 'هل تريد اعتماد جميع السجلات المحددة؟؟ #'+cnt;

        if(cnt==0){
            alert('يجب تحديد السجلات المراد اعتمادها اولا..');
            return 0;
        }

        if(confirm(msg)){
            //info_msg('تنويه..','سيتم تحديث الصفحة تلقائيا عند انتهاء العملية');
            $('button').prop('disabled',1);
            $('#page_tb .checkboxes:checked').each(function(i){
                ser= $(this).val();
                get_data('{$adopt_comm_url}'+no, {ser:ser,committee_case:committee_case,committee_note:committee_note} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت العملية بنجاح ');
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');

            }); // each

            setTimeout(function(){
                info_msg('تنويه..','جار تحديث الصفحة..');
                $('button').prop('disabled',0);
                $('#comm_note').modal('hide');
                search();
            },4000);

        } // confirm msg
    }
 

</script>
SCRIPT;
sec_scripts($scripts);
?>
