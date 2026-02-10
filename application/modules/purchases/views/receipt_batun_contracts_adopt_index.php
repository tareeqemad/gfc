<?php
$MODULE_NAME= 'purchases';
$TB_NAME= 'Receipt_batun_contracts';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_adopt_page");
$get_url=base_url("$MODULE_NAME/$TB_NAME/get_adopt");
$customer_url =base_url('payment/customers/public_index');
$back_to_prepare=base_url("$MODULE_NAME/$TB_NAME/back_to_prepare");
echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>

                <?php  if(HaveAccess($back_to_prepare)) echo "<li><a onclick='javascript:back_to_prepare();' href='javascript:;'><i class='glyphicon glyphicon-forward'></i>فك الإعتماد-(ارجاع للإعداد)</a> </li>"; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>

        <div class="form-body">
            <div class="modal-body inline_form">
            </div>
            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم امر التوريد</label>
                        <div>
                            <input type="text" data-val="true" name="order_id" id="txt_order_id" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مسلسل رقم امر التوريد(s)</label>
                        <div>
                            <input type="text" data-val="true" name="order_id_text" id="txt_order_id_text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مسلسل رقم امر التوريد(الفعلي)</label>
                        <div>
                            <input type="text" data-val="true" name="real_order_id" id="txt_real_order_id" class="form-control">
                        </div>
                    </div>



                    <div class="form-group col-sm-3">
                        <label class="control-label">المورد</label>
                        <div>
                            <input name="customer_name" data-val="true" class="form-control" readonly
                                   id="txt_customer_name" value="">
                            <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">

                        </div>
                    </div>


                </div>
            </form>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ الحقول</button>
            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?=modules::run($get_page_url);?>

            </div>

        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script>

    $(function(){
        reBind();
    });

    function reBind(){
       ajax_pager({

             order_id:$('#txt_order_id').val(),order_id_text:$('#txt_order_id_text').val(),real_order_id:$('#txt_real_order_id').val(), customer_resource_id:$('#h_txt_customer_name').val()        });

    }

    function LoadingData(){

    ajax_pager_data('#page_tb > tbody',{
              order_id:$('#txt_order_id').val(),order_id_text:$('#txt_order_id_text').val(),real_order_id:$('#txt_real_order_id').val(), customer_resource_id:$('#h_txt_customer_name').val()        });
    }


   function search(){

        get_data('{$get_page_url}',{page: 1,
              order_id:$('#txt_order_id').val(),order_id_text:$('#txt_order_id_text').val(),real_order_id:$('#txt_real_order_id').val(), customer_resource_id:$('#h_txt_customer_name').val()
        
        },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

  $('#txt_customer_name').bind("focus",function(e){

          selectAccount(this);

        });

        $('#txt_customer_name').click(function(e){

             selectAccount(this);

        });


    function selectAccount(obj){
        _showReport('$customer_url/'+$(obj).attr('id')+'/1');
    }

   function show_row_details(id){

        get_to_link('{$get_url}/'+id);

    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }
    
        
    function back_to_prepare(){
        var url = '{$back_to_prepare}';
        var tbl = '#page_tb';

        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        var first_val=[];
        var x;
        $(tbl + ' .checkboxes:checked').each(function (i) {
            
            val[i] = $(this).val();
            first_val[i]=$(this).attr("data-val");
          
            
        });
x=val[0];
for (v=1;v<val.length;v++){
x=x+','+ val[v];

}

        if(val.length >= 1){
            if(confirm('هل تريد بالتأكيد ارجاع '+val.length+'  المحاضر للإعداد ؟!!')){
/////////////////
                    get_data('{$back_to_prepare}',{id:x,first_val:first_val },function(data){
                    if(Number(data)>0 )
                         
                          setTimeout(function(){
                           success_msg('رسالة','تمت عملية الإجاع بنجاح ..');
                           /*get_to_link('{$get_to_url}/'+data);*/
          $(tbl + ' .checkboxes:checked').each(function (i) {
            
                      $(this).attr('disabled', 'true');
          
            
                 });

                             }, 1000);

                    },'html');
//////////////////


            }
        }else
            alert('يجب تحديد السجلات المراد ارجاعها');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>