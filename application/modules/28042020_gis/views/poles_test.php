<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 16/01/19
 * Time: 11:08 ص
 */

$rs=($isCreate)? array() : $poles_data[0];
?>

<div class="form-group col-sm-1">
                    <label class="control-label">رقم العمود</label>
                        <input type="text" id="POLE_MATERIAL_ID_dp" name="POLE_MATERIAL_ID" class="form-control"
                               value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>" >
                             <span id="id_validate"> </span>
                             </div>