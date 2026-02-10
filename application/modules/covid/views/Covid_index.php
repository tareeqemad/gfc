<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/04/21
 * Time: 11:56 ص
 */


$MODULE_NAME= 'Covid';
$TB_NAME= 'Covid';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

            <div class="toolbar">

                <div class="caption"><?= $title ?></div>

                <ul>
                    <?php if(HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة زائر</a> </li><?php endif; ?>
                    <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
                </ul>

            </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">


                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الهوية</label>
                        <div>
                            <input type="text" data-val-required="حقل مطلوب"
                                   name="id"
                                   id="txt_id" class="form-control"
                                >
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ الزيارة</label>
                        <div>
                            <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                   data-date-format="DD/MM/YYYY" name="visit_date"
                                   id="txt_visit_date" class="form-control"
                                >
                        </div>
                    </div>
					
					 <div class="form-group col-sm-2">
                            <?php
                            if($this->user->branch==1)
                            { ?>
                                <label class="control-label"> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($branches as $row) :?>
                                        <?php
                                        if($row['NO']<>1)
                                        { ?> <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                        <?php
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </select>

                            <?php
                            } else {?>
                                <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">
                            <?php }?>
                        </div>
						
                    <br>
                </div>
            </form>


            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php echo  modules::run("$MODULE_NAME/$TB_NAME/get_page",$page); ?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">



$('.sel2').select2();
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

        function values_search(add_page){
                var values=
                {page:1, id:$('#txt_id').val(), visit_date:$('#txt_visit_date').val(), branch:$('#dp_branch').val()};
                if(add_page==0)
                delete values.page;
                return values;
        }


 function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }



</script>

SCRIPT;

sec_scripts($scripts);

?>