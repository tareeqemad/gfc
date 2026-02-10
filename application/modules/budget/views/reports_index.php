<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 22/10/14
 * Time: 08:37 ص
 */

$report_path = base_url('/reports');
$report_url = base_url("JsperReport/showreport?sys=financial");
$section_url =base_url('budget/budget/public_get_sections');
$item_url =base_url('budget/budget/public_get_items');

?>

<?= AntiForgeryToken() ?>
<style type="text/css">
    /* .rep_red{ background-color: #ffdede
    } */

    .display_none{
        display: none;
    }
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title?></div>

        <ul>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="modal-body inline_form">
            <div class="form-group col-sm-8">
                <label class="col-sm-1 control-label"> التقرير</label>
                <div class="col-sm-5">
                    <select name="report" class="form-control" style="width: 250px" id="dp_report">
                        <option value="-1"> </option>
                       <!-- <option data-op="#branch,#year" value="16">الموازنة العامة - ما قبل التنسيب
                       </option>
                       -->
                        <!--  <option data-op="#branch,#year,#pdf,#xls" value="18">الموازنة العامة - شامل قبل التنسيب </option>  -->
                       <!-- <option data-op="#branch,#year" value="1"> الموازنة العامة بالنسب</option>
                       -->
                        <!--  <option data-op="#branch,#year,#pdf,#xls" value="22"> الموازنة العامة </option>  -->
                        <!--<option data-op="#branch,#year" value="10">هيكل تفصيل الموازنة العامة</option>
                        -->
                        <!--<option data-op="#branch,#year" value="13">الموزانة حسب الفصول</option>
                        -->
                        <!--  <option data-op="#branch,#year,#pdf,#xls" value="14"> النقدية</option>  -->
                       <!-- <option data-op="#branch,#year" value="16"> الموازنة لحركة نشاط الشركة</option>
                        <option data-op="#branch,#year" value="17"> الهيبات و المنح</option>
-->
                        <!--<option data-op="#year" value="11">الموازنة حسب الفروع</option>
                        -->
                      <!--  <option data-op="#year" value="12">الموازنة بالنسب</option>
                       <option data-op="#branch,#year" value="2">الموازنة الرأسمالية  </option>
                        <option data-op="#branch,#year" value="3"> الموازنة التشغيلية</option>
                        -->
                        <!--  <option data-op="#chapters,#pdf,#xls" value="4">اسعار الاصناف </option>  -->
                        <option class="rep_red" data-op="#branch,#year,#type_exp_all,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="15">الموازنة العامة  </option>
                        <option class="rep_red" data-op="#from_chapters,#to_chapters,#year,#from_section,#to_section,#positions,#branch,#month,#oper_type,#pdf,#xls" value="5"> الإيرادات التفصيلية </option>
                        <option class="rep_red" data-op="#from_chapters,#to_chapters,#year,#from_section,#to_section,#positions,#branch,#month,#oper_type,#pdf,#xls" value="6"> النفقات  </option>
                        <option class="rep_red" data-op="#year,#positions,#branch,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="19">موازنات الادارات و الفروع ايرادات</option>
                        <option class="rep_red" data-op="#year,#positions,#branch,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="20">موازنات الادارات و الفروع النفقات</option>
                        <option class="rep_red" data-op="#year,#positions,#branch,#from_chapters,#to_chapters,#from_section,#to_section,#type_exp_all,#oper_type,#pdf,#xls" value="21">موازنات الادارات و الفروع</option>
                        <option class="rep_red" data-op="#year,#branch,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="7"> إجمالي الإيرادات </option>
                        <option class="rep_red" data-op="#year,#branch,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="8"> إجمالي النفقات </option>
                        <!--  <option data-op="#month,#year,#branch,#new_type,#pdf,#xls" value="9">  تفصيل ايرادات تحصيلات فاتورة كهرباء </option>  -->
                        <option class="rep_red" data-op="#year,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="23"> البيانات التاريخية </option>
                        <option class="rep_red" data-op="#branch,#type_exp_all,#to_chapters,#from_chapters,#year,#type_adopt,#oper_type,#pdf,#xls" value="40"> السقف المالي </option>
                        <option class="rep_red" data-op="#branch,#chapters,#sections,#items,#year,#oper_type,#pdf,#xls" value="41">اسم المستخدم</option>
                        <option class="rep_red" data-op="#year,#rep,#rep_type" value="42">صافي الفائض والعجز - أبواب</option>
                        <option class="rep_red" data-op="#year,#rep,#rep_type" value="43">صافي الفائض والعجز - فصول</option>
                        <option class="rep_red" data-op="#branch,#to_branch,#date_from,#date_to,#from_chapters,#to_chapters,#from_section,#to_section,#oper_type,#pdf,#xls" value="44"> مقارنة الموازنة </option>
                        <option class="rep_red" data-op="#branch,#to_branch,#date_from,#date_to,#from_chapters,#to_chapters,#oper_type,#pdf,#xls" value="45"> مقارنة الموازنة - فصول </option>
                        <option class="rep_red" data-op="#branch,#to_branch,#date_from,#date_to,#from_chapters,#to_chapters,#oper_type,#pdf,#xls" value="46"> مقارنة الموازنة - باب </option>
                        <option class="rep_red" data-op="#branch,#to_branch,#date_from,#date_to,#from_chapters,#to_chapters,#oper_type,#pdf" value="47"> مقارنة الموازنة - مخطط الباب/المحقق</option>
                        <option class="rep_red" data-op="#branch,#to_branch,#date_from,#date_to,#from_chapters,#to_chapters,#oper_type,#pdf" value="48"> مقارنة الموازنة - مخطط الباب/النسبة</option>
                    </select>

                </div>
            </div>
            <hr/>


            <div class="form-group col-sm-2 rp_prm display_none" id="branch" >
                <label class="col-sm-1 control-label"> الفرع</label>
                <div class="col-sm-2">
                    <select name="report" class="form-control"   id="dp_branch">
                        <option></option>
                        <?php foreach($branches as $row) :?>
                            <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="form-group col-sm-2 rp_prm display_none" id="to_branch" >
                <label class="col-sm-1 control-label"> الى فرع</label>
                <div class="col-sm-2">
                    <select name="report" class="form-control" id="dp_to_branch">
                        <option></option>
                        <?php foreach($branches as $row) :?>
                            <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="form-group col-sm-2 rp_prm display_none" id="type_exp_all" >
                <label class="col-sm-1 control-label"> نوع</label>
            <div class="col-sm-2">
                <select name='report_type_exp_all' id='dp_type_exp_all' class='form-control'>
                    <option></option>
                    <?php foreach($consts as $row) :?>
                        <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                </div>

            <div class="form-group col-sm-3 rp_prm display_none" id="type_exp" >
                <label class="col-sm-3 control-label"> نوع</label>
                <div class="col-sm-9">
                    <select name="report_type_exp1" class="form-control"   id="dp_type_exp">
                        <option></option>
                        <option data-dept="1" value="1">ايرادات</option>
                        <option data-dept="2" value="2">نفقات</option>
                    </select>

                </div>
            </div>

            <div style="clear: both"></div>

            <div class="form-group col-sm-3 rp_prm display_none" id="year"  >
                <label class="col-sm-3 control-label"> السنة</label>
                <div class="col-sm-6">
                    <input type="text" id="txt_year" class="form-control"/>
                </div>
            </div>

            <div class="form-group col-sm-3 rp_prm display_none" id="month"   >
                <label class="col-sm-3 control-label"> الشهر</label>
                <div class="col-sm-5">
                    <input type="text" id="txt_month" class="form-control"/>
                </div>
            </div>


            <div style="clear: both"></div>

            <div class="form-group col-sm-4 rp_prm display_none" id="from_chapters"  >
                <label class="col-sm-2 control-label">من باب</label>
                <div class="col-sm-9">
                    <select name="report" class="form-control sel2" id="dp_from_chapters">
                        <option value=""></option>
                        <?php foreach($chapters as $row) :?>
                            <option data-dept="<?= $row['T_SER'] ?>" value="<?= $row['T_SER'] ?>"><?=$row['T_SER'].': '.$row['NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm display_none" id="to_chapters" >
                <label class="col-sm-2 control-label">إلى باب</label>
                <div class="col-sm-9">
                    <select name="report" class="form-control sel2" id="dp_to_chapters">
                        <option value=""></option>
                        <?php foreach($chapters as $row) :?>
                            <option data-dept="<?= $row['T_SER'] ?>" value="<?= $row['T_SER'] ?>"><?=$row['T_SER'].': '.$row['NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="clear: both"></div>


            <div class="form-group col-sm-4 rp_prm display_none" id="from_section"  >
                <label class="col-sm-2 control-label">من فصل</label>
                <div class="col-sm-9">
                    <select name="report" class="form-control" id="dp_from_section">
                        <option value=""></option>
                        <?php foreach($sections as $row) :?>
                            <option data-dept="<?=$row['T_SER']?>" value="<?=$row['T_SER']?>"><?=$row['T_SER'].': '.$row['NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm display_none" id="to_section" >
                <label class="col-sm-2 control-label">الى فصل</label>
                <div class="col-sm-9">
                    <select name="report" class="form-control" id="dp_to_section">
                        <option value=""></option>
                        <?php foreach($sections as $row) :?>
                            <option data-dept="<?=$row['T_SER']?>" value="<?=$row['T_SER']?>"><?=$row['T_SER'].': '.$row['NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div style="clear: both"></div>

            <div class="form-group col-sm-4 rp_prm display_none" id="date_from"  >
                <label class="col-sm-2 control-label">من تاريخ</label>
                <div class="col-sm-9">
                    <input type="text" id="txt_date_from" value="01/01/<?=date('Y')?>" class="form-control"/>
                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm display_none" id="date_to"  >
                <label class="col-sm-2 control-label">الى تاريخ</label>
                <div class="col-sm-9">
                    <input type="text" id="txt_date_to" value="<?=date('d/m/Y')?>" class="form-control"/>
                </div>
            </div>


            <div style="clear: both"></div>


            <div class="form-group col-sm-2 rp_prm display_none" id="items" >
                <label class="col-sm-2 control-label"> البند</label>
                <div class="col-sm-2">

                    <select name="report" class="form-control" id="dp_items">

                    </select>

                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm display_none" id="positions"  >
                <label class="col-sm-2 control-label"> القسم</label>
                <div class="col-sm-8">

                    <input type="text"   name="user_position" id="txt_user_position" class="form-control easyui-combotree" data-options="url:'<?= base_url('budget/reports/public_get_gcc')?>',method:'get',animate:true,lines:true">


                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm display_none" id="new_type"  >
                <label class="col-sm-2 control-label"> الحالة</label>
                <div class="col-sm-2">
                    <select name="report" class="form-control"   id="dp_new_type">
                        <option data-dept="1" value="1">قبل التنسيب</option>
                        <option data-dept="2" value="2">بعد التنسيب</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-3 rp_prm display_none" id="type_adopt" >
                <label class="col-sm-3 control-label">الاعتماد</label>
                <div class="col-sm-9">
                    <select name="report_type_adopt1" class="form-control"   id="dp_type_adopt">
                        <option></option>
                        <option data-dept="3" value="3">اعتماد ادارة</option>
                        <option data-dept="1" value="2">معتمد</option>
                        <option data-dept="2" value="1">غير معتمد</option>

                    </select>

                </div>
            </div>

            <div style="clear: both"></div>

            <div class="form-group col-sm-4 rp_prm display_none" id="chapters"  >
                <label class="col-sm-2 control-label"> الباب</label>
                <div class="col-sm-9">

                    <select name="report" class="form-control"   id="dp_chapters">
                        <option value=""></option>
                        <?php foreach($chapters as $row) :?>
                            <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="form-group col-sm-3 rp_prm display_none" id="sections" >
                <label class="col-sm-2 control-label"> الفصول</label>
                <div class="col-sm-5">
                    <select name="report" class="form-control"   id="dp_sections">
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-2 rp_prm display_none" id="oper_type" >
                <label class="col-sm-3 control-label">نوع الفصل</label>
                <div class="col-sm-9">
                    <select name="oper_type" class="form-control"   id="dp_oper_type">
                        <option></option>
                        <option data-dept="3" value="1">رأسمالي</option>
                        <option data-dept="1" value="2">تشغيلي</option>
                    </select>
                </div>
            </div>

            <div style="clear: both"></div>

            <br/><br/>

            <div class="form-group rp col-sm-2 op " id="rep_type"  >
                <label class="control-label">Report Type</label>
                <div>
                    <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                    <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio"  name="rep_type_id" value="xls">
                    <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                    <!--&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio"  name="rep_type_id" value="doc">
                    <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i> -->
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" id="pdf" onclick="javascript:showReport(31);"  class="btn btn-success rp_prm display_none">PDF عرض التقرير</button>

            <button type="button" id="xls" onclick="javascript:showReport(36);"  class="btn btn-success rp_prm display_none" display_none>XLS عرض التقرير</button>

            <button type="button" id="rep" onclick="javascript:print_report();"  class="btn btn-success rp_prm display_none">Show Report <span class="glyphicon glyphicon-print"></span></button>
        </div>

    </div>

</div>


<?php

$scripts = <<<SCRIPT
<script>

    $(function(){
    
        $('.sel2:not("[id^=\'s2\']")').select2();
    
        $('#dp_report').on('change',function(){

            var val =parseInt( $(this).val());

            $('.rp_prm').hide();

            $($(this).find(':selected').attr('data-op')).slideDown();

        });

        $('#dp_chapters').change(function(){ 
            get_data('{$section_url}',{no: $(this).val()},function(data){
                $('#dp_sections').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_sections').append('<option value=' + item.id + '>' + item.text + '</option>');
                });
            });

        });

        $('#dp_sections').change(function(){
            get_data('{$item_url}',{no: $(this).val()},function(data){
                $('#dp_items').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_items').append('<option value=' + item.id + '>' + item.text + '</option>');
                });
            });
        });
        
        $('#dp_from_chapters').change(function(){
            var this_chapter= $(this).val();
            $("#dp_from_section option").hide();
            $("#dp_from_section option[value^='"+this_chapter+"']").show();  
        });
        
        $('#dp_to_chapters').change(function(){
            var this_chapter= $(this).val();
            $("#dp_to_section option").hide();
            $("#dp_to_section option[value^='"+this_chapter+"']").show();  
        });
        

    });

    // check if var have value or null //
    function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }
    // End check if var have value or null //

    function showReport(type){

        var val =parseInt( $('#dp_report').val());

        var url = '$report_path';
        
        var type_jspr= '';
        if(type== 31){
            type_jspr= 'pdf';
        }else if(type== 36){
            type_jspr= 'xls';
        }

        if(val == 1){
            url  = url +'?type='+type+'&report=TOTALBUDGECT&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=';
        }

        if(val == 2){
            url  = url +'?type='+type+'&report=TOTALBUDGECT&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=1';
        }

        if(val == 3){
            url  = url +'?type='+type+'&report=TOTALBUDGECT&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=2';
        }

        if(val == 4){
            url  = url +'?type='+type+'&report=ITEMSPRICE&params[]='+$('#dp_chapters').val();
        }

        if(val == 5){            
            url = '{$report_url}&report_type='+type_jspr+'&report=rep_exp_rev_'+type_jspr+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_user_position='+$('input[name="user_position"]').val()+'&p_month='+$('#txt_month').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_section_to='+$('#dp_to_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_adopt=1'+'&p_exp_rev_type=1' ;
        }

        if(val == 6){
            url = '{$report_url}&report_type='+type_jspr+'&report=rep_exp_rev_'+type_jspr+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_user_position='+$('input[name="user_position"]').val()+'&p_month='+$('#txt_month').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_section_to='+$('#dp_to_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_adopt=1'+'&p_exp_rev_type=2';
        }

        if(val == 7){
            url = '{$report_url}&report_type='+type_jspr+'&report=budget_khosomat_rep'+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_exp_rev_type='+1+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val();
        }

        if(val == 8){
            url = '{$report_url}&report_type='+type_jspr+'&report=budget_khosomat_rep'+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_exp_rev_type='+2+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val();
        }

        if(val == 9){
            if($('#dp_branch').val()=='' || $('#txt_year').val()=='' ) { alert('اختر الفرع والسنة'); return 0; }

            url  = url +'?type='+type+'&report=budget_revenue_details_REP&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_year').val()+'&params[]='+$('#txt_month').val()+'&params[]='+$('#dp_new_type').val();
        }

        if(val == 10){
            url  = url +'?type='+type+'&report=MOAZANAH3MA&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val();
        }


        if(val == 11){
            url  = url +'?type='+type+'&report=MOAZANAHFRO3&params[]='+$('#txt_year').val();
        }


        if(val == 12){
            url  = url +'?type='+type+'&report=MOAZANAHNESAB&params[]='+$('#txt_year').val();
        }

        if(val == 13){
            url  = url +'?type='+type+'&report=MOAZANAH3MA_P&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val();
        }

        if(val == 14){
            url  = url +'?type='+type+'&report=NAQDEE&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=1';
        }

        /* if(val == 16){
            // url  = url +'?type='+type+'&report=NAQDEE&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=2';
             url  = url +'?type='+type+'&report=BEFOR_TANSEEB_BUDGET&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val();
        }*/

        if(val == 17){
            url  = url +'?type='+type+'&report=NAQDEE&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=3';
        }

        if(val == 15){
            url = '{$report_url}&report_type='+type_jspr+'&report=public_budget'+'&p_year='+$('#txt_year').val()+'&p_branch='+$('#dp_branch').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_section_to='+$('#dp_to_section').val()+'&p_oper_type='+$('#dp_oper_type').val() ;
        }
        /* if(val == 16){
            url  = url +'?type='+type+'&report=BEFOR_TANSEEB_BUDGET&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val();
        }*/

        if(val == 18){
            url  = url +'?type='+type+'&report=BEFOR_TANSEEB_BUDGET&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val();
        }
    
        if(val == 19){ // old-202109- budget_branches
            url = '{$report_url}&report_type='+type_jspr+'&report=budget_branches2'+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_dep='+$('input[name="user_position"]').val()+'&p_exp_rev_type='+1+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val() ; 
        }
        
        if(val == 20){ // old-202109- budget_branches
            url = '{$report_url}&report_type='+type_jspr+'&report=budget_branches2'+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_dep='+$('input[name="user_position"]').val()+'&p_exp_rev_type='+2+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val() ;
        }
        
        if(val == 21){
            url = '{$report_url}&report_type='+type_jspr+'&report=budget_branches1'+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_dep='+$('input[name="user_position"]').val()+'&p_exp_rev_type='+$('#dp_type_exp_all').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val();
        }
        
        if(val == 22){
            url  = url +'?type='+type+'&report=MOAZANAH3MA_1&params[]='+$('#txt_year').val()+'&params[]='+$('#dp_branch').val()+'&params[]=';

        }
           if(val == 23){
            url = '{$report_url}&report_type='+type_jspr+'&report=history_data_show'+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_section_to='+$('#dp_to_section').val();

        }
            if(val == 40){
                if($('#dp_from_chapters').val()=='' && $('#txt_year').val()==''  )
                    {
                        alert('يتوجب عليك تحديد كل من السنة و الباب');
                        return;
                    }
                else {
                    url = '{$report_url}&report_type='+type_jspr+'&report=financial_ceiling_'+type_jspr+'&p_branch='+$('#dp_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_no='+get_sections()+'&p_type='+$('#dp_type_exp_all').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_adopt='+have_no_val($('#dp_items').val())+'';
                }
            }

            if(val == 41){
                if($('#txt_year').val()=='')
                    {
                        danger_msg('تحذير ..','يجب إدخال السنة');
                        return;
                    }
                else {
                    url = '{$report_url}&report_type=pdf&report=items_user_name&P_Year='+$('#txt_year').val()+'&P_Branch='+$('#dp_branch').val()+'&P_Chapter_No='+$('#dp_chapters').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&P_Section_No='+have_no_val($('#dp_sections').val())+'&P_Item_No='+have_no_val($('#dp_items').val())+'';
                }
            }
            
            if(val == 44){  
             url = '{$report_url}&report_type='+type_jspr+'&report=budget_comparison'+'&p_branch='+$('#dp_branch').val()+'&p_to_branch='+$('#dp_to_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_section_from='+$('#dp_from_section').val()+'&p_section_to='+$('#dp_to_section').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_date_from='+$('#txt_date_from').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_date_to='+$('#txt_date_to').val() ;
        }
        
        if(val == 45){  
             url = '{$report_url}&report_type='+type_jspr+'&report=budget_comparison_section'+'&p_branch='+$('#dp_branch').val()+'&p_to_branch='+$('#dp_to_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_date_from='+$('#txt_date_from').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_date_to='+$('#txt_date_to').val() ;
        }
        
        if(val == 46){  
             url = '{$report_url}&report_type='+type_jspr+'&report=budget_comparison_chapter'+'&p_branch='+$('#dp_branch').val()+'&p_to_branch='+$('#dp_to_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_date_from='+$('#txt_date_from').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_date_to='+$('#txt_date_to').val() ;
        }
        
        if(val == 47){  
             url = '{$report_url}&report_type='+type_jspr+'&report=budget_comparison_chapter_chart'+'&p_branch='+$('#dp_branch').val()+'&p_to_branch='+$('#dp_to_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_date_from='+$('#txt_date_from').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_date_to='+$('#txt_date_to').val() ;
        }
        
        if(val == 48){  
             url = '{$report_url}&report_type='+type_jspr+'&report=budget_comparison_chapter_chart_2'+'&p_branch='+$('#dp_branch').val()+'&p_to_branch='+$('#dp_to_branch').val()+'&p_year='+$('#txt_year').val()+'&p_chapter_from='+$('#dp_from_chapters').val()+'&p_chapter_to='+$('#dp_to_chapters').val()+'&p_type='+$('#dp_type_exp_all').val()+'&p_date_from='+$('#txt_date_from').val()+'&p_oper_type='+$('#dp_oper_type').val()+'&p_date_to='+$('#txt_date_to').val() ;
        }

        _showReport(url);

    }

    function get_sections(){
        var rs= $('#dp_sections').val();

        if(rs=='null' || rs == undefined || rs == '')
            return '';
        else return rs;

    }

    function print_report(type){
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var val =parseInt( $('#dp_report').val());
        if(val == 42){
                if($('#txt_year').val()=='') {
                danger_msg('تحذير ..','يجب إدخال السنة');
                return;
            }else {
                url = '{$report_url}&report_type='+rep_type+'&report=total_budget_chapter_'+rep_type+'&p_year='+$('#txt_year').val()+'';
            }
        }

        if(val == 43){
            if($('#txt_year').val()=='') {
                danger_msg('تحذير ..','يجب إدخال السنة');
                return;
            }else {
                url = '{$report_url}&report_type='+rep_type+'&report=total_budget_section_'+rep_type+'&p_year='+$('#txt_year').val()+'';
            }
        }

        _showReport(url);
    }

</script>
SCRIPT;

sec_scripts($scripts);

?>