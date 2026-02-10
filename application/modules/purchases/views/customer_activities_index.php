<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'customers_activity';
$MODULE_NAME1 = 'payment';
$TB_NAME1 = 'customers';

$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$content_url = base_url("$MODULE_NAME/$TB_NAME/index");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">

            <div class="modal-body inline_form">
                <div class="form-group col-sm-4">
                    <label class="control-label">المورد</label>
                    <div>
                        <select  name="customer_id" id="dp_customer_id"
                                 class="form-control">
                            <option></option>
                            <?php foreach ($customer_id as $row) : ?>
                                <option value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">النشاط</label>
                    <div>
                        <select  name="activity_no" id="dp_activity_no"
                                 class="form-control">
                            <option></option>
                            <?php foreach ($activities as $row) : ?>
                                <option value="<?= $row['SER'] ?>"><?= $row['ACTIVITY_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


            </div>
            <div class="modal-footer">

                <button type="button" onclick="search_data();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="clearForm_any($('fieldset'));search_data();"
                        class="btn btn-default"> تفريغ الحقول
                </button>
            </div>


        <div id="msg_container"></div>

        <div id="container">
            <?= modules::run($get_page_url, $page); ?>
        </div>

    </div>

</div>

<?php
$edit = '';
if (HaveAccess($edit_url))
    $edit = 'edit';

$scripts = <<<SCRIPT
<script>
    $('#dp_customer_id,#dp_activity_no').select2();
    function select_customer(ser){
    get_to_link('{$get_url}'+'/'+ser+'/'+'{$edit}');
    }

      $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({customer_id : $('#dp_customer_id').val(),customer_name:$('#dp_activity_no').val() });

    }

    function LoadingData(){

    ajax_pager_data('#page_tb > tbody',{customer_id : $('#dp_customer_id').val(),activity_no:$('#dp_activity_no').val() });

    }


   function search_data(){

        get_data('{$get_page_url}',{page:1,customer_id : $('#dp_customer_id').val(),activity_no:$('#dp_activity_no').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
    
       function show_row_details(id){

        get_to_link('{$get_url}/'+id);

    }



</script>
SCRIPT;

sec_scripts($scripts);

?>


