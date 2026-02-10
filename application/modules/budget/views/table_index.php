<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/12/14
 * Time: 09:19 ص
 */

$report_url =base_url('reports?type=31');
$count = 1;
$balance = 0;
$z= 0;
$budget_details = base_url('budget/budget/public_table_details');
$section_url =base_url('budget/budget/public_get_sections');
$report_path ='https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';// base_url('/reports');
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tbl" data-set="badgetTabletbl" class="form-control" placeholder="بحث">
            </div>
        </div>

        <div class="modal-footer">
            <div class="btn-group">
                <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#badgetTabletbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#badgetTabletbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <button type="button" onclick="javascript:$('#reportFilterModal').modal();" class="btn btn-success">تقارير الموازنة</button>
        </div>

        <div id="container">

            <table class="table" id="badgetTabletbl" data-container="container">
                <thead>
                <tr>
                    <th>#</th>
                    <th> الباب </th>
                    <th>الفصل </th>
                    <th>المقر</th>
                    <th style="width: 120px;">المقدر </th>
                    <th style="width: 120px;"> المحقق</th>
                    <th style="width: 120px;">نسبة المحقق </th>
                    <th style="width: 120px;">  الغير محقق</th>
                    <th style="width: 120px;">نسبة الغير محقق </th>

                </tr>
                </thead>
                <tbody>

                <?php foreach($rows as $index => $row) :?>
                    <tr class="selected-red">

                        <td><?= $count ?></td>
                        <td class="align-right" colspan="3"><?= $row['C_T_SER'].":".$row['CNAME'] ?></td>

                        <td><?= n_format($row['TOTAL']) ?></td>
                        <td> <?php $balance =$row['BALANCE'] < 0 ?$row['BALANCE'] * -1 :$row['BALANCE'] ; ?><?= n_format($balance)?></td>
                        <td><?= $row['TOTAL'] == 0 ? 0 : number_format(($balance /$row['TOTAL'])*100,2) ?>%</td>
                        <td> <?php $z = $row['TOTAL'] - $balance; ?> <?= n_format($z) ?></td>
                        <td><?=$row['TOTAL'] == 0 ? 0 : number_format(($z/$row['TOTAL'])*100,2) ?>%</td>
                    </tr>

                    <tr data-chapter="<?=$row['CNO'] ?>">
                        <td colspan="9"  class="align-right">
                            جاري التحميل ..
                        </td>
                    </tr>
                    <?php $count++; ?>


                <?php endforeach;?>

                </tbody>
                <tfoot></tfoot>
            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="reportFilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">معير البحث</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group" id="date1" >
                        <label class="col-sm-3 control-label"> نوع التقرير </label>
                        <div class="col-sm-4">
                            <select id="dp_report_type" class="form-control">
                                <option value="1">عادي</option>
                                <option value="2">تفصيلي</option>
                                <option value="3">بالفصول</option>
                                <option value="4">بالأبواب</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="date1" >
                        <label class="col-sm-3 control-label"> التاريخ </label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group" id="date2" >
                        <label class="col-sm-3 control-label"> حتى</label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_2" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="chapters" >
                        <label class="col-sm-3 control-label"> الباب</label>
                        <div class="col-sm-4">

                            <select name="report" class="form-control"   id="dp_chapters">
                                <option value=""></option>
                                <?php foreach($chapters as $row) :?>
                                    <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>


                        </div>
                    </div>

                    <div class="form-group rp_prm" id="sections" >
                        <label class="col-sm-3 control-label"> الفصول</label>
                        <div class="col-sm-4">

                            <select name="report" class="form-control"   id="dp_sections">

                            </select>


                        </div>
                    </div>

                    <div class="form-group  rp_prm" id="branch" >
                        <label class="col-sm-3 control-label"> الفرع</label>
                        <div class="col-sm-4">
                            <select name="report" class="form-control"   id="dp_branch">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group  rp_prm" id="branch" >
                        <label class="col-sm-3 control-label"> نوع التشغيل</label>
                        <div class="col-sm-4">
                            <select name="oper_type" class="form-control"   id="dp_oper_type">
                                <option></option>
                                <option value="1">رأسمالية</option>
                                <option value="2" >تشغيلية</option>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-action="report" onclick="javascript:open_report(31);" class="btn btn-primary">عرض التقرير </button>
                        <button type="button" data-action="report" onclick="javascript:open_report(28);" class="btn btn-warning">عرض التقرير xls </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php

$script=<<<SC
<script>

        $('#dp_chapters').change(function(){
            get_data('{$section_url}',{no: $(this).val()},function(data){
                $('#dp_sections').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_sections').append('<option value=' + item.id + '>' + item.text + '</option>');
                });
            });

        });

    $('tr[data-chapter]').each(function(i){
        var obj = $(this);
        get_data('{$budget_details}',{cno: $(this).attr('data-chapter')},function(data){
            $(obj).after(data);
            $(obj).remove();
        },'html');
    });


    function open_report(tp){

        if(($('#txt_date_1').val() !='' && $('#txt_date_2').val() == '') || ($('#txt_date_2').val() !='' && $('#txt_date_1').val() == ''))
        {
        alert('يجب إدخال الفترة كاملة');
        return;
        }

        var report_type= 'BUGHT_BALANCE';

         if($('#dp_report_type').val() == '2')
         report_type= 'BUGHT_BALANCE_1'

         if($('#dp_report_type').val() == '3')
         report_type= 'BUGHT_BALANCE_2'

        if($('#dp_report_type').val() == '4')
         report_type= 'BUGHT_BALANCE_3'

        var url  = '{$report_path}' +'?type='+tp+'&report='+report_type+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#dp_chapters').val()+'&params[]='+get_sections()+'&params[]=&params[]='+$('#txt_date_1').val()+'&params[]='+$('#txt_date_2').val()+'&params[]='+$('#dp_oper_type').val();
        _showReport(url);
    }
  function get_sections(){
        var rs= $('#dp_sections').val();

        if(rs=='null' || rs == undefined || rs == '')
            return '';
        else return rs;

    }
</script>


SC;
sec_scripts($script);

?>

