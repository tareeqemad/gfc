<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 16/10/16
 * Time: 11:06 ص
 */
$MODULE_NAME= 'budget';
$TB_NAME= 'reports';
$get_page_url= base_url("$MODULE_NAME/$TB_NAME/class_get_page");
$section_url =base_url('budget/budget/public_get_sections');
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>

        <ul>
        </ul>

    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post"  role="form" novalidate="novalidate">
            <div class="modal-body inline_form">




                    <div class="form-group col-sm-1">
                        <label class="control-label"> الفرع </label>
                        <div>
                            <select name='branch' id='dp_branch' class='form-control'>
                                <option selected value="0">---</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">الباب</label>
                    <div>
                       <select name="chapter" class="form-control"   id="dp_chapter">
                            <option value="">--</option>
                            <?php foreach($chapters as $row) :?>
                                <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>


                    </div>
                </div>

                <div class="form-group col-sm-5 ">
                    <label class="control-label"> القسم</label>
                    <div >
                        <input type="text"  name="user_position" id="txt_user_position" class="form-control easyui-combotree" data-options="url:'<?= base_url('settings/gcc_structure/public_get_structure_json')?>',method:'get',animate:true,lines:true,required:true">
                    </div>
                </div>

                <div  hidden="hidden" class="form-group col-sm-3 rp_prm" id="sections"   >
                    <label class="col-sm-2 control-label"> الفصول</label>
                    <div class="col-sm-10">

                        <select name="report" class="form-control" name="section"   id="dp_sections">

                        </select>


                    </div>
                </div>

            <!--    <div   class="form-group col-sm-3 rp_prm" id="departments"   >
                    <label class="col-sm-2 control-label"> الإدارات و الدوائر</label>
                    <div class="col-sm-10">

                        <select name="report" class="form-control" name="department"   id="dp_sections">

                        </select>


                    </div>
                </div>-->


            </div>

            <div class="modal-footer">


                    <button type="button" onclick="javascript:search();" class="btn btn-default"> إستعلام</button>


                <button class="btn btn-warning dropdown-toggle" onclick="$('#budget_class_detTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>


            </div>


            <div id="container"></div>
        </form>

        <div id="msg_container"></div>

    </div>

</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {

        $(' #dp_branch, #dp_chapter, #dp_sections').select2();


/*
 $('#dp_chapter').change(function(){
            get_data('{$section_url}',{no: $(this).val()},function(data){
                $('#dp_sections').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_sections').append('<option value=' + item.id + '>' + item.text + '</option>');
                });
            });

        });*/






  });
   function search(){

     if ($('#dp_chapter').select2('val')>0){
            var values= { section: $('#dp_sections').select2('val'), chapter: $('#dp_chapter').select2('val'), branch: $('#dp_branch').select2('val'),user_position:$('input[name="user_position"]').val() };
            get_data('{$get_page_url}',values ,function(data){
                $('#container').html(data);
            },'html');
        }else    danger_msg('تحذير..','يجب اختيار الباب');
}
</script>
SCRIPT;
sec_scripts($scripts);
?>
