<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/12/19
 * Time: 08:18 ص
 */



$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Subscriber_info';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$excePage_url = base_url("$MODULE_NAME/$TB_NAME/import");
$page=1;
echo AntiForgeryToken();

?>
    <script> var show_page=true; </script>
    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >ببيانات فواتير وسداد المشتركين</legend>

                <div class="modal-body inline_form">

                    <div class="form-group col-sm-2">
                        <label class="control-label">المقر</label>
                        <div>
                            <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                <option value="0">____________</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>"  > <?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الاشتراك</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="رقم الاشتراك"
                                     name="sub_no"
                                   id="txt_sub_no" class="form-control"  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المشترك</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="اسم المشترك"
                                    name="sub_name"
                                   id="txt_sub_name" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">شهر الفاتورة</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="شهر الفاتورة"
                                    name="for_month" readonly value="<?=date('Ym', strtotime('-1 month'))?>"
                                   id="txt_for_month" class="form-control" value=""  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">غير مسدد منذ</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="<?=date('Ym', strtotime('-2 month'))?>"
                                   name="payment_status"
                                   id="txt_payment_status" class="form-control"  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">القيمة المطلوبة</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="المطلوب"
                                   name="net_to_pay"
                                   id="txt_net_to_pay" class="form-control" value=""  >
                        </div>
                    </div>



                    <!--

                    <div class="form-group col-sm-2">
                        <label class="control-label">نوع الفاز</label>
                        <div>
                            <select name="phase_type" id="dp_phase_type" class="form-control sel2">
                                <option></option>
                                <?php foreach ($phase_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المؤسسة</label>
                        <div>

                            <select name="org" id="dp_org" class="form-control sel2">
                                <option></option>
                                <?php foreach ($org_name as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div> -->

                    <br>
                </div>

                <!--<div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">تسديد الي</label>
                        <div>
                            <select name="payment_type" id="dp_payment_type" class="form-control sel2">
                                <option></option>
                                <?php foreach ($payment_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">القيمة المطلوبة</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="المطلوب"
                                   name="net_to_pay"
                                   id="txt_net_to_pay" class="form-control" value=""  >
                        </div>
                    </div>
                    <br>
                </div> -->

                </fieldset>
        </form>





            <div class="modal-footer">
                <!--<button type="button" onclick="javascript:excelFilePage()" class="btn btn-warning"> ملف اكسل</button> -->
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <button type="button"  id="btn_adopt"  class="btn btn-primary"> اعتماد</button>

            </div>
            <div id="msg_container"></div>

            <div id="container">


                <?php //echo  modules::run($get_page,$page); ?>
            </div>
        </div>

    </div>

    <div id="Details" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">بيانات الكشف</h3>
                </div>
                <form class="form-horizontal" id="sub_form" method="post" action="<?= $adopt_url ?>" role="form" >
                    <div class="modal-body">
                            <div class="modal-body">

                                <div class="row">
                                    <input type="hidden" name="subs_no" id="h_txt_subs_no">
                                    <input type="hidden" name="count_sub" id="h_txt_count_sub">
                                    <br>
                                    <label class="col-sm-2 control-label">رقم الكشف</label>
                                    <div class="col-sm-3">
                                        <input type="text"
                                               data-val="true"
                                               readonly
                                               name="disclosure_ser"
                                               id="txt_disclosure_ser"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <br>
                                    <label class="col-sm-2 control-label">تاريخ فتح الكشف</label>
                                    <div class="col-sm-9">
                                       <input type="text"
                                               data-val="true"
                                               readonly
                                               value="<?php echo date("d/m/Y");?>"
                                               name="disclosure_open_date"
                                               id="txt_disclosure_open_date"
                                               class="form-control">
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    <br>
                                    <label class="col-sm-2 control-label">تاريخ اغلاق الكشف</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               data-val="true"
                                               readonly
                                               value="<?php echo date('d/m/Y', strtotime('+1 month'));?>"
                                               name="disclosure_close_date"
                                               id="txt_disclosure_close_date"
                                               class="form-control">
                                    </div>
                                </div>



                            </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    $('.sel2').select2();
    $('#btn_adopt').hide();
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }
	
	


    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    $('.pagination li').click(function(e){
            e.preventDefault();
    });

        function values_search(add_page){
                var values=
                {page:1, branch_no:$('#dp_branch_no').val(), sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val()
                , for_month:$('#txt_for_month').val(), payment_status:$('#txt_payment_status').val()
                , net_to_pay:$('#txt_net_to_pay').val()};
                if(add_page==0)
                delete values.page;
                return values;
        }


         function search(){
                var values= values_search(1);
                get_data('{$get_page}',values ,function(data){
                    $('#container').html(data);
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
                },'html');
                $('#btn_adopt').show();
				
				
				
         }



    $('#btn_adopt').click(function(e){
        e.preventDefault();
        var x='0/0';
            var count = 0;
            var tbl = '#page_tb';
            var container = $('#' + $(tbl).attr('data-container'));
            var val = [];

            $(tbl + ' .checkbox:checked').each(function (i) {
                x = x+','+$(this).val();
                count++;
            });


            $('#h_txt_subs_no').val(x);
            $('#h_txt_count_sub').val(count);
			

          if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $('#sub_form');
            ajax_insert_update(form,function(data){
            console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم الاعتماد بنجاح');
                     $('#Details').modal();
                    $('#txt_disclosure_ser').val(parseInt(data));

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('#btn_adopt').removeAttr('disabled');
        }, 3000);


    });

    function excelFilePage() {
        get_to_link('{$excePage_url}');
    }
	






</script>

SCRIPT;

sec_scripts($scripts);

?>