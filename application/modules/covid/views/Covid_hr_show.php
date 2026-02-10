<?php

$MODULE_NAME= 'Covid';
$TB_NAME= 'Covid';
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$post_url = base_url("$MODULE_NAME/$TB_NAME/create");
?>

<div class="row">
    <div class="toolbar">
	    <div class="caption">فحص كورونا - الشؤون الادارية</div>

        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form"  action="<?php echo $post_url ?>" method="post" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <div class="col-md-12">
                    <div class="form-group col-sm-2">
					
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value="0">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					<div class="form-group col-sm-2">
                        <label class="control-label">رقم الهوية</label>
                        <div>
							<input type="text" name="id"  id="txt_id" class="form-control">
                        </div>
                    </div>
					
					<br>
					<br>
                </div>
				<div class="col-md-6 alert alert-success result_emp">
					<strong style="margin-right: 45%"> مخالطين / سلبي </strong>
					<table class="table" id="negative_tb" data-container="container">
						<thead>
							<tr>
								<th>رقم الهوية</th>
								<th>الاسم</th>
								<th>تاريخ الفحص</th>
								<th>تاريخ نتيجة الفحص</th>
								<th>نتيجة الفحص</th>
							</tr>
						 </thead>
						 <tbody>
								
						 </tbody>
					</table>
				</div>
				<div class="col-md-6 alert alert-danger result_emp">
					<strong style="margin-right: 45%"> مخالطين / ايجابي </strong>
					<table class="table" id="positive_tb" data-container="container">
						<thead>
							 <tr>
								<th>رقم الهوية</th>
								<th>الاسم</th>
								<th>تاريخ الفحص</th>
								<th>تاريخ نتيجة الفحص</th>
								<th>نتيجة الفحص</th>
							</tr>
						</thead>
						<tbody>
								
						</tbody>
					</table>
				</div>
            </div>
		</form>
		<div class="modal-footer">
			<button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
		</div>
		
            
    </div>
</div>


