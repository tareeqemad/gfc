<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/08/19
 * Time: 11:09 ص
 */



$MODULE_NAME= 'indicator';
$TB_NAME= 'indecator_info';
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>الموظف</th>
            <th>المسؤولية</th>
            <th>الفعالية</th>
           

        </tr>
        </thead>
        <tbody>

        <tr>
            <td>
                <select name="first_indecator2[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_first_indecator2_<?=$count?>" class="form-control">
                    <option value="">------</option>
                    <option >لانا</option>
                    <option>اروى</option>
                   
                </select>

                <input  type="hidden" name="h_txt_[]"   id="h_txt_<?=$count?>" data-val="false" data-val-required="required" >
                <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                <input type="hidden" name="h_count2[]" id="h_count2_<?=$count?>"  />
            </td>

            <td>
                <select  name="first_period2[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_first_period2_<?=$count?>" class="form-control">
                    <option value="">------</option>
                    <option value="1">متابعة</option>
                    <option value="2">تحديث</option>
                    
                </select>
            </td>
            <td  class="summary_indecator_<?=$count?>" >
                <select name="operation_summary[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_operation_summary_<?=$count?>" class="form-control">
                    <option value="">-----</option>
                    <option value="1">فعال</option>
                    <option value="2">غير فعال</option>
                   
                </select>

            </td>
           


        </tr>

        </tbody>

        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            
            

        </tr>
        <tr>
            <th>
                <a hidden  id="new_row2"  onclick="javascript:add_row(this,'select,input:hidden[name^=seq1],select,select,select,select,select,input:text,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            
           
            
        </tr>

        </tfoot>


    </table></div>
