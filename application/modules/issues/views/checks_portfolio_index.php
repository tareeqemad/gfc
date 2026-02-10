<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/03/19
 * Time: 08:37 ص
 */

$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>


    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title; ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الشيك</label>
                    <div>
                        <input type="text" name="check_no"  id="txt_check_no" class="form-control ">
                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الشكوى</label>
                    <div>
                        <input type="text" name="complaint_no"  id="txt_complaint_no" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">سنة الشكوى</label>
                    <div>
                        <input type="text" name="complaint_year"  id="txt_complaint_year" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاعتماد</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control">
                            <option value="">-</option>
                            <?php foreach($adopt as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">

                    <?php
                    if($this->user->branch==1)
                    {
                    ?>
                    <label class="control-label">الفرع</label>

                    <div>

                        <select name="branch" id="dp_branch" class="form-control">

                            <option value="">-</option>
                            <?php foreach($branches as $row) :?>
                                <?php
                                if($row['NO']<>1)
                                {
                                    ?>

                                    <option value="<?= $row['NO'] ?>"  >
                                        <?= $row['NAME'] ?>
                                    </option>
                                <?php
                                }
                                ?>

                            <?php endforeach; ?>
                        </select>
                        <?php
                        }
                        else
                        {
                        ?>
                        <div>
                            <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch" id="txt_branch">

                            <?php

                            }
                            ?>
                        </div>


                    </div>

                </div>



            </div>


        </form>


        <div class="modal-footer">

            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?php echo ''; //modules::run($get_page_url,$page,$sub_no,$sub_name,$id,$for_month);?>
        </div>
    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

              $('#dp_adopt').select2().on('change',function(){
        });
                  $('#dp_branch').select2().on('change',function(){
        });

        function values_search(add_page){
                var values=

                {page:1, check_no:$('#txt_check_no').val(), complaint_no:$('#txt_complaint_no').val(), complaint_year:$('#txt_complaint_year').val(),
                 adopt:$('#dp_adopt').val(),branch:$('#dp_branch').val()};
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