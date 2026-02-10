<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/12/19
 * Time: 09:52 ص
 */

$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
$count = 1;
$get_page = base_url("$MODULE_NAME/$TB_NAME/transform_get_page");

?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>

    </div>


    <div class="form-body">

    <div class="modal-body inline_form">
    </div>
            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >التحويل لمكاتب التحصيل </legend>
                    <input type="hidden" value="<?php //echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الكشف</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="رقم الكشف "
                                   data-val-required="حقل مطلوب"  name="disclosure_ser"
                                   id="txt_disclosure_ser" class="form-control"  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الاشتراك</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="رقم الاشتراك"
                                   data-val-required="حقل مطلوب"  name="sub_no"
                                   id="txt_sub_no" class="form-control"  >
                        </div>
                    </div>


                </fieldset>


            </form>


            <div class="modal-footer">
                <button type="button" onclick="javascript:trans_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>

            <div id="msg_container"></div>

            <div id="container">
                <?php //echo  modules::run($get_page,$page); ?>
            </div>

            <!----------------------------------->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="title" class="modal-title">اسناد لشركة متعاقدة</h3>
                        </div>
                        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="" role="form" >

                            <input type="hidden" name="sub_ser" id="txt_sub_ser">
                            <div class="modal-body">

                                <div class="row">
                                    <table class="table" id="page_tb" data-container="container">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الترخيص</th>
                                            <th>اسم الشركة</th>
                                            <th></th>
                                            <?php
                                            $count++;
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(count($details) > 0): ?>
                                            <tr >
                                                <td colspan="12"  class="page-sector"></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php foreach ($details as $row_det) : ?>
                                            <tr id="tr_<?=$row_det['SER']?>" >
                                                <td><?=$count?></td>
                                                <td><?=$row_det['LICENSE_NO']?></td>
                                                <td><?=$row_det['COMPANY_NAME']?></td>
                                                <?php //if(HaveAccess($adopt_url)) {?>
                                                    <td>
                                                        <button  type="button" onclick="javascript:save_('<?=$row_det['SER']?>');"
                                                                 class="btn btn-warning btn-xs" href='javascript:;'><i
                                                                class='glyphicon glyphicon-cloud-upload'></i>اسناد</button>

                                                        </a>
                                                    </td>
                                                <?php //}
                                                $count++;
                                                ?>
                                            </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>

                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-------------------------------------------->











<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


 function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

 function search(){
        $('#div_page').show();
 }

 $('#tr_1_unblock').hide();
        $('#tr_1_transform').hide();
        $('#tr_2_unblock').hide();
        $('#tr_3_unblock').hide();
        $('#tr_4_unblock').hide();
        $('#tr_5_unblock').hide();
        $('#tr_6_unblock').hide();


        $('#dp_status').on('change',function(){
			 if($(this).val()=='1'){
				 $('.div_page1').show();
				 $('.div_page2').hide();
				 $('.div_page3').hide();
			 }
			 else if($(this).val()=='2'){
			     $('.div_page2').show();
			     $('.div_page1').hide();
			     $('.div_page3').hide();
			 }
			 else{
			     $('.div_page2').hide();
			     $('.div_page1').hide();
			 }
        });

        function block(){
            success_msg('رسالة','تم حظر التعامل مع الاشتراك بنجاح');
            $('#tr_1_unblock').show();
            $('#tr_1_transform').show();
		    $('#tr_1_block').hide();
        }

        function unblock(){
            success_msg('رسالة','تم فك حظر التعامل مع الاشتراك بنجاح');
            $('#tr_1_unblock').hide();
            $('#tr_1_transform').hide();
		    $('#tr_1_block').show();
        }

        function transform(){
            $('#myModal').modal();
        }

        function attach(){
            success_msg('رسالة','تم اسناد الاشتراك');
            $('#btn_attach_1').hide();
            $('#btn_attach_2').hide();
            $('#btn_attach_3').hide();

        }

</script>

SCRIPT;
sec_scripts($scripts);
?>