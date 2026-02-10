<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 31/01/2019
 * Time: 09:09 ص
 */
$MODULE_NAME= "pledges_t";
$TB_NAME="pledges_cont";
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
                  data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_d");
$status_update_url = base_url("$MODULE_NAME/$TB_NAME/statusupdate");
?>

    <script> var show_page=true; </script>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
           <li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
        </ul>

    </div>


    <div class="form-body">
        <div>
            <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                <div  class="modal-body inline_form">

                    <div class="form-group col-sm-2">
                        <label class="control-label"> اسم الموظف </label>
                        <div>
                            <select name="customer_id" id="txt_customer_id" class="form-control" />
                            <option></option>
                            <?php foreach($customer_ids as $row) :?>
                                <option value="<?=$row['CUSTOMER_ID']?>"><?=$row['COMPANY_DELEGATE_ID'].'-'.$row['CUSTOMER_NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                            <!--  <input type="text" name="customer_id" id="txt_customer_id" class="form-control">-->
                        </div>
                    </div>



                </div>
            </form>


            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" id="btn_clear" onclick="clear_form()" class="btn btn-default"> تفريغ الحقول</button>
                       </div>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $emp_pledges,  $customer_id);?>

        </div>

    </div>

</div>
<?php
$scripts = <<<SCRIPT
<script>
    $(document).ready(function() {

        $('#txt_customer_id').select2();
        $('#s_customer_id').select2();
        $('#t_s_customer_id').select2();

  });
    
    function update_status(file_id){
          get_data('{$status_update_url}',{'file_id':file_id} ,function(data){
            alert('تم التعديل بنجاح');
        },'html');
    }
 
     function values_search(add_page){
        var values=  {page:1, customer_id:$('#txt_customer_id').val()};
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
    
    
    
      function clear_form(){
        clearForm($('#pledges_cont_form'));
         $('#btn_clear').click(function() {
    $("#txt_customer_id").val(null).trigger("change"); 
});         
    }

    
</script>
SCRIPT;
sec_scripts($scripts);
?>