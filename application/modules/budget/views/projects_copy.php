<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('budget/projects/delete');
$get_url =base_url('budget/projects/get_id');
$edit_url =base_url('budget/projects/edit');
$create_url =base_url('budget/projects/create');
$get_page_url = base_url('budget/projects/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a   href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <form class="form-form-vertical" id="projects_form" method="post" action="<?=base_url('budget/projects/copyFromProjects')?>" role="form" novalidate="novalidate">

            <fieldset>

                <legend>البيانات</legend>
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-1">
                        <label class="control-label">الرقم الفني</label>
                        <div>
                            <input type="text"  data-val="true" data-val-required="حقل مطلوب"  name="tec_num" id="txt_tec_num"   class="form-control">
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">الشهر </label>
                        <div>

                            <select type="text"   name="month" id="dp_month" class="form-control" >

                                <?php for($i = 1; $i <= 12 ;$i++) :?>
                                    <option   value="<?= $i ?>"><?= months($i) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-3">
                        <label class="control-label">الفصل </label>
                        <div>

                            <select type="text"  data-val="true" data-val-required="حقل مطلوب"   name="section_no" id="dp_section_no" class="form-control" >

                                <?php foreach($sections as $row) :?>
                                    <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                </div>

            </fieldset>
        </form>
        <div id="msg_container"></div>



    </div>

</div>




<?php


$scripts = <<<SCRIPT

<script>

 $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');

            if($('button[data-action="submit"]').length > 0)
                $(form).attr('action',$(form).attr('action').replace('Maintenance','edit'));


            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                    reload_Page();

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });
</script>
SCRIPT;

sec_scripts($scripts);



?>
