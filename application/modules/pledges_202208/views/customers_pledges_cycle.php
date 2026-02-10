<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/12/16
 * Time: 09:50 ص
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$backs_url=base_url("$MODULE_NAME/$TB_NAME/index/1/2"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$get_code_url  =base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$isCreate =isset($pledge_data) && count($pledge_data)  > 0 ?false:true;
$get_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$rs=($isCreate)? array() : $pledge_data;
$fid = (count($rs)>0)?$rs['FILE_ID']:0;
$public_return_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/cancel"); //$action
$stop_pledge_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/stop"); //$action
$lost_pledge_url =  $back_url=base_url("$MODULE_NAME/$TB_NAME/lost"); //$action
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$cycle_url =base_url("$MODULE_NAME/$TB_NAME/public_gcc_structure_cycle");
$department_url =base_url("$MODULE_NAME/$TB_NAME/public_gcc_structure_dep");
//echo "<pre>" ; print_r($rs);
$class_type=(count($rs)>0)? $rs['CLASS_TYPE']: 0 ;
echo AntiForgeryToken();
?>


<select  name="cycle_st_id" id="dp_cycle_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
<option value="">___________________________________</option>

                            <?php foreach($gcc_structure_show_c as $row) :?>
    <option  value="<?= $row['ST_ID'] ?>" <?PHP  if (count($rs)>0) {if ($row['ST_ID']==$rs['CYCLE_ST_ID']){ echo " selected"; }} ?> ><?php echo $row['ST_NAME']  ?></option>
<?php endforeach; ?>
</select>