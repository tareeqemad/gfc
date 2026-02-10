<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/31/14
 * Time: 11:21 AM
 */


$delete_url =base_url('budget/budget/delete');
$get_url =base_url('budget/budget/get_id');
$edit_url =base_url('budget/budget/edit');
$create_url =base_url('budget/budget/create');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:budget_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?> <li><a  onclick="javascript:budget_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li><?php endif;?>
            <?php if( HaveAccess($delete_url)): ?> <li><a  onclick="javascript:budget_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree2" class="form-control" placeholder="اكتب كلمة البحث، ثم اضغط Enter ..">
            </div>
        </div>


        <?= $tree ?>

    </div>

</div>
<?= $this->load->view('budget_modal')?>
<?php

$select_parent_url =base_url('financial/accounts/public_select_parent');
$select_items_url=base_url("stores/classes/public_index");

$scripts = <<<SCRIPT
<script>
function after_selected(){
	$("#sectionsModal #txt_name").val($('#txt_account_id').val());
	}
$(function () {
	
	

    // Mkilani
    $('#search-tree2').keyup(function(e){
        if (e.keyCode == 13 ) {
            $.fn.tree.filter($(this).val());
        }
    });
    // Mkilani

    $('#budgetModal').on('shown.bs.modal', function () {
        $('#txt_name').focus();
    })

   $('input[id="txt_d_class_id"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });
      $('#h_txt_account_id').keyup(function(){
            get_account_name($(this));
     });

       $('#txt_account_id').click(function(e){
             _showReport('$select_parent_url/'+$(this).attr('id')+'/-1' );
        });
    $('#budget').tree();

    $('.tab-pane#details input[data-val-required]').keyup(function(){
        $(this).removeClass('input-validation-error');
    });

    $('input[name="activity_type"]').click(function(){
        $('input[name="grant_type"]').prop('checked',false);
    });

     $('input[name="grant_type"]').click(function(){
        $('input[name="activity_type"]').prop('checked',false);
    });

       $('input[name="has_details"]').click(function(){
        $('input[name="special"]').prop('checked',false);
    });

     $('input[name="special"]').click(function(){
        $('input[name="has_details"]').prop('checked',false);
    });
});



function budget_create(){



    if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 5){

        if($.fn.tree.level() >= 4){

            warning_msg('تحذير ! ','غير مسموح بإدراج تفريع جديد ..');
        }else{
            warning_msg('تحذير ! ','يجب إختيار الفرع الأب ..');
        }


        return;
    }



    var parentId =$.fn.tree.selected().attr('data-id');
    var productionId= $.fn.tree.lastElem().attr('data-id');
    var parentName = $('.tree li > span.selected').text();
var p_tser=$.fn.tree.selected().attr('data-tser');

    if( $.fn.tree.level() == 1){

        clearForm($('#constant_from'));
        $('#constant_from').find('#txt_level').val( $.fn.tree.level());
        $('#constant_from').attr('action','{$create_url}');
        $('#constantModal').modal();

    }
    else  if($.fn.tree.level() == 2){
        clearForm($('#chapter_from'));
        $('#chapter_from').find('#txt_level').val( $.fn.tree.level());
        $('#chapter_from').find('#txt_type').val(p_tser );
        $('#chapter_from').attr('action','{$create_url}');
        $('#chaptersModal').modal();
    }
    else if($.fn.tree.level() == 3){
        clearForm($('#section_from'));

        $('#txt_chapter_no').val(parentId);
        $('#txt_chapter_no_name').val(parentName);
        $('#section_from').find('#txt_level').val( $.fn.tree.level());
    $('#section_from').find('select[name="budget_ttype"]').select2("val",'');
     $('#section_from').find('select[name="chain_ttype"]').select2("val",'');
        $('#section_from').attr('action','{$create_url}');
        $('#sectionsModal').modal();
    } else if($.fn.tree.level() == 4){
        clearForm($('#item_from'));

        $('#txt_section_no').val(parentId);
        $('#txt_section_no_name').val(parentName);
        $('#item_from').find('#txt_level').val( $.fn.tree.level());

        $('#item_from').attr('action','{$create_url}');
        $('#itemsModal').modal();
    }

}

function budget_delete(){

    if(confirm('هل تريد إتمام الحذف  ؟!!!')){
        var elem =$.fn.tree.selected();
        var id = elem.attr('data-id');
        var url = '{$delete_url}';

        ajax_delete_any(url,{id:id,level:$.fn.tree.level()},function(data){
            if(data == '1'){

                $.fn.tree.removeElem(elem);
                success_msg('رسالة','تم حذف السجلات بنجاح ..');
            }
        });
    }

}

function budget_get(id){

    get_data('{$get_url}',{id:id,level:$.fn.tree.level()},function(data){

        try{

        var item =data[0];
        console.log('',item);
        //$.each(data, function(i,item){

            var level = $.fn.tree.level()  ;
                if(level == 2){

                $('#constant_from').find('#txt_t_ser').val( item.T_SER);
                $('#constant_from').find('#txt_no').val( item.NO);
                $('#constant_from').find('#txt_name').val(item.NAME);
                $('#constant_from').find('#txt_level').val( $.fn.tree.level());

                $('#constant_from').attr('action','{$edit_url}');

                $('#constantModal').modal();
            }else if(level == 3){
                $('#chapter_from').find('#txt_t_ser').val( item.T_SER);
                $('#chapter_from').find('#txt_no').val( item.NO);
                $('#chapter_from').find('#txt_name').val(item.NAME);
                $('#chapter_from').find('#txt_ser').val(item.SER);
                 $('#chapter_from').find('#txt_type').val(item.TYPE);
                $('#chapter_from').find('#txt_level').val( $.fn.tree.level());

                $('#chapter_from').attr('action','{$edit_url}');

                $('#chaptersModal').modal();
            }else  if(level == 4){
                $('#section_from').find('#txt_t_ser').val( item.T_SER);
                $('#section_from').find('#txt_no').val(item.NO);
                $('#section_from').find('#txt_name').val(item.NAME);
                  $('#section_from').find('#txt_con_no').val(item.txt_con_no);
                    $('#section_from').find('#txt_chapter_no').val(item.CHAPTER_NO);
                      $('#section_from').find('#txt_chapter_no_name').val($.fn.tree.nodeText(item.CHAPTER_NO));
                        $('#section_from').find('#txt_chapter_no').val(item.CHAPTER_NO);
                          $('#section_from').find('#txt_budget_type').val(item.BUDGET_TYPE);

               var temp = new Array();
              temp=item.BUDGET_TYPE.split(",");
             $('select[name="budget_ttype"]').select2("val",temp);

              $('#txt_chain_type').val(item.CHAIN_TYPE);

             var temp1 = new Array();
              temp1=item.CHAIN_TYPE.split(",");
             $('select[name="chain_ttype"]').select2("val",temp1);

                $('#section_from').find('#txt_level').val( $.fn.tree.level());
                if(item.OPER_TYPE == 2)
                    $('#rd_oper_type_2').prop('checked',true);
                else
                    $('#rd_oper_type_1').prop('checked',true);

                if(item.GRANT_TYPE == 1)
                    $('#rd_grant_type_2').prop('checked',true);
                else if(item.GRANT_TYPE == 2)
                    $('#rd_grant_type_1').prop('checked',true);


                if(item.ACTIVITY_TYPE == 1)
                    $('#rd_activity_type_1').prop('checked',true);
                else if(item.ACTIVITY_TYPE == 2)
                    $('#rd_activity_type_2').prop('checked',true);
                else if(item.ACTIVITY_TYPE == 3) $('#rd_activity_type_3').prop('checked',true);

                 $('#dp_gcc_st_no').combotree('setValue', item.COMPETENT_SIDE);
                 $('#dp_gcc_st_no').combotree('setText', $('.tree-title[data-id="'+item.COMPETENT_SIDE+'"]').text());
                 $('#h_txt_account_id').val(item.ACCOUNT_ID);
                 $('#txt_account_id').val(item.ACCOUNT_ID_NAME);


                $('#section_from').attr('action','{$edit_url}');

                $('#sectionsModal').modal();
            }else  if(level == 5){
                $('#item_from').find('#txt_t_ser').val( item.T_SER);
                $('#item_from').find('#txt_no').val(item.NO);
                $('#item_no').text(item.NO);
                $('#txt_con_id').val(item.CON_ID);
                $('#item_from').find('#txt_name').val(item.NAME);
                $('#txt_price').val(item.PRICE);
                $('#dp_unit_no').select2("val",item.UNIT_NO);
                $('#txt_section_no').val(item.SECTION_NO);
                $('#txt_section_no_name').val($.fn.tree.nodeText(item.SECTION_NO));
                $('#item_from').find('#txt_level').val( $.fn.tree.level());
                $('#dp_item_type').val( item.ITEM_TYPE );
                $('#h_txt_d_class_id').val( item.ITEM_NO );
                $('#txt_d_class_id').val( item.CLASS_ID_NAME );
                $('#dp_active').val( item.ACTIVE);


                  console.log('',item);

                if(item.SPECIAL == 2)
                    $('#rd_special_2').prop('checked',true);
                else  if(item.SPECIAL == 1)
                    $('#rd_special_1').prop('checked',true);

                if(item.HAS_DETAILS == 2)
                    $('#rd_has_details_2').prop('checked',true);
                else  if(item.HAS_DETAILS == 1)
                    $('#rd_has_details_1').prop('checked',true);


                if(item.HAS_HISTORY == 2)
                    $('#rd_has_history_2').prop('checked',true);
                else  if(item.HAS_HISTORY == 1) $('#rd_has_history_1').prop('checked',true);

                $('#item_from').attr('action','{$edit_url}');

                $.each(data['details'], function(i,obj){

                     var html = '<tr data-id="'+obj.NO+'"><td></td><td>'+
                        '<input type="text" data-val="true" data-val-required="حقل مطلوب" value="'+obj.SPECIAL_ITEM_NO+'" name="special_item_no" id="txt_special_item_no" class="form-control">'+
                        '<span class="field-validation-valid" data-valmsg-for="special_item_no" data-valmsg-replace="true"></span>'+
                        '</td><td>'+
                        '<input type="text" data-val="true" data-val-required="حقل مطلوب" value="'+obj.CCOUNT+'" name="ccount" id="txt_ccount" class="form-control">'+
                        '<span class="field-validation-valid" data-valmsg-for="ccount" data-valmsg-replace="true"></span>'+
                        '</td><td>'+
                          '<a href="javascript:;" class="btn green" onclick="javascript:update_item_details('+obj.NO+',this)">حفظ</a> '+
                        '<a href="javascript:;" class="btn red" onclick="javascript:delete_item_details('+obj.NO+',this)">حذف</a>'+
                        '</td></tr>';

                         $('#tbl_items_details > tbody > tr:first').after(html);

                });

                $('#itemsModal').modal();
            }


       // });
       }catch(e){
       console.log('error : ',e);
       }
    });
}

$('button[data-action="submit"]').click(function(e){

    e.preventDefault();


    var form = $(this).closest('form');

    var isCreate = form.attr('action').indexOf('create') >= 0;

    ajax_insert_update(form,function(data){


        var level = $.fn.tree.level() -1;
        if(isCreate){

            var obj = jQuery.parseJSON(data);

            $.fn.tree.add(obj.t_ser+' :'+form.find('input[name="name"]').val(),obj.id,"javascript:budget_get('"+obj.id+"');");
            level = level+1;
           form.find('input[name="t_ser"]').val(obj.t_ser);
        }else{ if(data == '1'){

            $.fn.tree.update(form.find('input[name="t_ser"]').val()+' :'+form.find('input[name="name"]').val());
        }
        }



        if(level == 1){
            $('#constantModal').modal('hide');
        }else if(level == 2){
            $('#chaptersModal').modal('hide');
        }else  if(level == 3){
            $('#sectionsModal').modal('hide');
        } else if(level == 4){
            $('#itemsModal').modal('hide');
        }

        success_msg('رسالة','تم حفظ البيانات بنجاح ..');

    },"json");

});

function create_item_details(a){

    var special_item_no = $(a).closest('tr').find('input[name="special_item_no"]').val();

    var ccount = $(a).closest('tr').find('input[name="ccount"]').val();

    var item_no = $(a).closest('div#my-tab-content').find('#txt_no').val();

    if(special_item_no == ''){
        $(a).closest('tr').find('input[name="special_item_no"]').addClass('input-validation-error');

    }
    else
        $(a).closest('tr').find('input[name="special_item_no"]').removeClass('input-validation-error');

    if(ccount == ''){
        $(a).closest('tr').find('input[name="ccount"]').addClass('input-validation-error');

    }
    else
        $(a).closest('tr').find('input[name="ccount"]').removeClass('input-validation-error');

    if(special_item_no == '' || ccount == '')
        return;

     get_data('$create_url', {level:4,special_item_no:special_item_no,ccount:ccount,item_no:item_no}, function(data){

try{
             var obj = jQuery.parseJSON(data);

             var html = '<tr data-id="'+obj.id+'"><td></td><td>'+
                        '<input type="text" data-val="true" data-val-required="حقل مطلوب" value="'+special_item_no+'" name="special_item_no" id="txt_special_item_no" class="form-control">'+
                        '<span class="field-validation-valid" data-valmsg-for="special_item_no" data-valmsg-replace="true"></span>'+
                        '</td><td>'+
                        '<input type="text" data-val="true" data-val-required="حقل مطلوب" value="'+ccount+'" name="ccount" id="txt_ccount" class="form-control">'+
                        '<span class="field-validation-valid" data-valmsg-for="ccount" data-valmsg-replace="true"></span>'+
                        '</td><td>'+
                        '<a href="javascript:;" class="btn green" onclick="javascript:update_item_details(this)">حفظ</a> '+
                        '<a href="javascript:;" class="btn red" onclick="javascript:delete_item_details('+obj.id+',this)">حذف</a>'+
                        '</td></tr>';
                        $('#tbl_items_details > tbody > tr:first').after(html);

                            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
 $(a).closest('tr').find('input').val('');
                        }catch(e){

                        }

     });

}

function update_item_details(id,a){
 var special_item_no = $(a).closest('tr').find('input[name="special_item_no"]').val();

    var ccount = $(a).closest('tr').find('input[name="ccount"]').val();

    var item_no = $(a).closest('div#my-tab-content').find('#txt_no').val();



    if(special_item_no == ''){
        $(a).closest('tr').find('input[name="special_item_no"]').addClass('input-validation-error');

    }
    else
        $(a).closest('tr').find('input[name="special_item_no"]').removeClass('input-validation-error');

    if(ccount == ''){
        $(a).closest('tr').find('input[name="ccount"]').addClass('input-validation-error');

    }
    else
        $(a).closest('tr').find('input[name="ccount"]').removeClass('input-validation-error');

    if(special_item_no == '' || ccount == '')
        return;

     get_data('$edit_url', {level:5,no:id,special_item_no:special_item_no,ccount:ccount,item_no:item_no}, function(data){

if(data == '1')
    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

     });

}

function delete_item_details(id,a){

if(confirm('هل تريد حذف السجل ؟')){

var tr = $(a).closest('tr');

  ajax_delete_any('$delete_url',{id:id,level:5},function(data){
            if(data == '1'){

                success_msg('رسالة','تم حذف السجلات بنجاح ..');

                $(tr).remove();

            }
        });

}

}

 $('select[name="budget_ttype"],select[name="chain_ttype"],select[name="unit_no"]').select2();
    $('select[name="budget_ttype"]').change(function(){
          $('input[name="budget_type"]').val( $(this).val() );
    });
        $('select[name="chain_ttype"]').change(function(){
          $('input[name="chain_type"]').val( $(this).val() );
    });
</script>
SCRIPT;

sec_scripts($scripts);



?>
