<?php
$MODULE_NAME = 'settings';
$TB_NAME = "Users";
$update_emp_data_url = base_url("$MODULE_NAME/$TB_NAME/update_emp_data");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
        </ul>

    </div>
    <div class="form-body">

        <div id="container">
            <form class="form-form-vertical" id="projects_form" method="post"
                  action="<?= base_url('settings/Users/') . ($HaveRs ? 'public_edit_emp_data' : 'public_create_emp_data') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="row">
                        <div class="form-group  col-sm-1">
                            <label class="control-label"> رقم الموظف </label>
                            <div>
                                <input type="hidden" value="<?= $HaveRs ? $rs['EMP_NO'] : $data_info[0]['EMP_NO'] ?>"
                                       name="h_emp_no" id="h_emp_no" class="form-control">

                                <input type="text" value="<?= $HaveRs ? $rs['EMP_NO'] : $data_info[0]['EMP_NO'] ?>" readonly
                                       name="txt_emp_no" id="txt_emp_no" class="form-control">
                            </div>
                        </div>

                        <div class="form-group  col-sm-1">
                            <label class="control-label">رقم الهوية</label>
                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['EMP_ID'] : "" ?>"
                                       name="txt_emp_id" id="txt_emp_id" class="form-control">
                            </div>
                        </div>

                        <div class="form-group  col-sm-1">
                            <label class="control-label">رقم الجوال</label>
                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['JAWAL_NO'] : "" ?>"
                                       name="txt_jawal_no" id="txt_jawal_no" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

    function checkidno(idno){
        MultID  = [1,2,1,2,1,2,1,2];
        SumID=0;i=0;X=0;
        r = ""+idno;//.replace(/\s/g,'');
        intRegex = /^\d+$/;
        if (r.length==9 && (intRegex.test(r)) )
        {
            for (var i=0;i<MultID.length;i++)
            {
                x=MultID[i] * r[i];
                if (x>9)
                    x=parseInt(x.toString()[0]) + parseInt(x.toString()[1]);
                SumID=SumID+x;
            }
            if (SumID % 10 != 0)
                SumID = ( 10 * (Math.floor(SumID / 10) + 1 )) - SumID
            else
                SumID = 0;
            if (SumID == r[8])
                return true;
            else
                return false;
        }
        else
            return false;
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(checkidno($('#txt_emp_id').val())){
        if(confirm('هل تريد حفظ البيانات ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
       }else{
            	danger_msg('تحذير','يرجى التأكد من  رقم الهوية');
       }
    });


</script>
SCRIPT;
sec_scripts($scripts);
?>