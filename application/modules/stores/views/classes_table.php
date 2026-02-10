<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/01/15
 * Time: 11:53 ص
 */

$get_amount_url=base_url("stores/classes/public_get_class_amount");
$get_header_url=base_url("stores/classes/public_table_h");
$refresh_page_url=base_url("stores/classes/public_delete_cache_files");
$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;

function case_no($type=0,$len=0){
    if($type==1 and $len==8)
        return ' case_4';
}

?>

<script>
    var head = document.getElementsByClassName("navbar-nav");
    head[0].innerHTML = ' <li><a href="javascript:;" >الرئيسية</a> </li> ';
</script>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a onclick="refresh_page();" href="javascript:;"><i class="glyphicon glyphicon-refresh"></i>تحديث</a> </li>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>
                <input type="text" id="search-tbl2" data-set="classes_tb" class="form-control" placeholder="اكتب كلمة البحث، ثم اضغط Enter ..">
            </div>
        </div>
        <div id="container">

        <?php /*
            <ul id="classes_tb" class="ul-table" data-container="container">
                <ul class="table-head">
                    <li>رقم الصنف</li>
                    <li>اسم الصنف</li>
                    <li>سعر الصنف</li>
                    <li>الكمية</li>
                    <li>حركات الصنف</li>
                    <li>الرصيد</li>
                </ul>

                <?php foreach($classes as $row) : ?>
                    <ul class="tb-row level-<?=strlen($row['CLASS_ID'])?>" >
                        <li class="class_id"><?=$row['CLASS_ID']?></li>
                        <li><?=$row['CLASS_NAME_AR']?></li>
                        <li><?=number_format($row['CLASS_PURCHASING'])?></li>
                        <li id="amount<?=$row['CLASS_ID']?>" class="amount" ></li>
                        <li><a target="_blank" href="<?=base_url("stores/class_amount/actions_index/1/-1/-1/{$row['CLASS_ID']}")?>" ><i class='glyphicon glyphicon-cog'></i></a></li>
                        <li> <i onclick="javascript:print_rep(<?=$row['CLASS_ID']?>);" class="glyphicon glyphicon-print"></i> </li>
                    </ul>
                <?php endforeach;?>
               </ul>

            */ ?>

            <div class="btn-group" style="width: 90px; float: right">
                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="javascript:;" onclick="$('#classes_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="javascript:;" onclick="$('#classes_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>

            <div style="margin-top: 5px; color: #A91515; float: right; width: 350px;" >  لتحديث كمية صنف معين، انقر مزدوجا على الكمية..  </div>

            <div style="clear: both"></div>

            <table class="table selected-red" id="classes_tb" data-container="container">
                <thead>
                <tr>
                    <th>رقم الصنف</th>
                    <th>اسم الصنف</th>
                    <th>وحدة الصنف</th>
                    <th>سعر الصنف</th>
                    <th>الكمية</th>
                    <th>حركات الصنف</th>
                    <th>الرصيد</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($classes as $row) :?>
                    <tr class="level-<?=strlen($row['CLASS_ID']).case_no($row['CLASS_ACOUNT_TYPE'],strlen($row['CLASS_ID']))?>" >
                        <td class="class_id"><?=$row['CLASS_ID']?></td>
                        <td class="align-right" ><?=$row['CLASS_NAME_AR']?></td>
                        <td><?=$row['CLASS_UNIT_NAME']?></td>
                        <td id="price<?=$row['CLASS_ID']?>"><? //number_format($row['CLASS_PURCHASING'],2)?></td>
                        <td id="amount<?=$row['CLASS_ID']?>" class="amount" ></td>
                        <td><a target="_blank" href="<?=base_url("stores/class_amount/actions_index/1/-1/-1/{$row['CLASS_ID']}")?>" ><i class='glyphicon glyphicon-cog'></i></a></td>
                        <td> <i onclick="javascript:print_rep(<?=$row['CLASS_ID']?>);" class="glyphicon glyphicon-print"></i> </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
    $(document).ready(function() {
        $('ul.navbar-nav').load('{$get_header_url}');

        setTimeout(function() {
            //$('.loader').remove();
            get_dataWithOutLoading('{$get_amount_url}', {}, function(ret){
                var all_cnt =ret.length;
                var part= 100;
                var num_of_call= Math.ceil(all_cnt/part);
                var part_data;

                var i=0;
                function myLoop() {
                   setTimeout(function () {
                      if (i < num_of_call) {
                         part_data = ret.slice(i*part,i*part+part);
                         show_amount(part_data);
                         myLoop();
                      }
                      i++;
                   }, 1)
                }
                myLoop();

                /*
                for(var i=0; i<num_of_call; i++){
                    part_data = ret.slice(i*part,i*part+part);
                    show_amount(part_data);
                }
                */
            });
        }, 10);

    });

    function show_amount(data){
        $.each(data, function(i,row){
            $('#price'+row.CLASS_ID).text( num_format(parseFloat( row.CLASS_PURCHASING )) );
            $('#amount'+row.CLASS_ID).text( num_format(parseFloat( row.AMOUNT )) );
        });
    }

    $('td.amount').dblclick(function(){
        $(this).text('');
        var this_tr= $(this).closest('tr');
        get_data('{$get_amount_url}', {class_id: this_tr.find('td.class_id').text() }, function(ret){
            if(ret.length==1){
                this_tr.find('td.amount').text( num_format(parseFloat( ret[0].AMOUNT )) );
            }
        });
    });

    /******
    $('li.amount').dblclick(function(){
        $(this).text('');
        var this_ul= $(this).closest('ul');
        get_data('{$get_amount_url}', {class_id: this_ul.find('li.class_id').text() }, function(ret){
            if(ret.length==1){
                this_ul.find('li.amount').text( num_format(parseFloat( ret[0].AMOUNT )) );
            }
        });
    });
    *******/

    // refresh page..
    function refresh_page(){
        if(confirm('سيتم اعادة تحميل الشاشة ! هل تريد بالتأكيد التحديث ؟!')){
            get_data('{$refresh_page_url}',{action:'del'} ,function(data){
                if(data==1)
                    get_to_link(window.location.href);
                else
                    alert('خطأ');
            },'html');
        }
    }

    function print_rep(class_id){
        _showReport('$print_url"+"report=ITEM_STORE_AMOUNT_REP&params[]='+class_id);
    }

    $('#search-tbl2').focus();
    $('#search-tbl2').keyup(function(e){

        var DataSet =$('#'+$(this).attr('data-set'));

        var text = $(this).val();
        if (e.keyCode == 13 ) {
            if($(DataSet).prop('tagName') !='UL'){
                $('tr > td.filterd',DataSet).removeClass('filterd');

                $('tr > td:contains("'+text+'")',DataSet).each(function() {
                    $(this).addClass('filterd');
                });

                $('tr',DataSet).hide();
                $('thead >tr:first-child',DataSet).show();
                $('tr > td.filterd',DataSet).parents('tr').show();

            }else{

                $('.tb-row > li.filterd',DataSet).removeClass('filterd');

                $('.tb-row > li:contains("'+text+'")',DataSet).each(function() {
                $(this).addClass('filterd');
                });

                $('.tb-row',DataSet).hide();

                $('.tb-row > li.filterd',DataSet).parents('.tb-row').show();
            }
        }
    });

</script>
SCRIPT;
sec_scripts($scripts);
?>
