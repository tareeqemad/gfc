<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'bonds';
$TB_NAME1= 'issues';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$popup_url =base_url("$MODULE_NAME/$TB_NAME/public_get_url");
$options_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_bonds");
$create_issues_url =base_url("$MODULE_NAME/$TB_NAME1/create");
$report_url = base_url("JsperReport/showreport?sys=financial_11");
$report_sn= report_sn();
$count = $offset;
?>


<input type="hidden" value="<?=$popup_url;?>" id="popup_url">
<input type="hidden" value="<?=$report_url;?>" id="report_url">
<input type="hidden" value="<?=$report_sn;?>" id="report_sn">
<div class="tbl_container">

    <div class="alert alert-secondary" role="alert">
    <?php echo 'إجمالي عدد السجلات :'.$numrecorde;?>
    </div>
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>رقم هوية المشترك</th>
            <th>رقم السند</th>
            <th>حالة الاعتماد</th>
            <th>الفرع</th>
            <th>حالة الاستلام</th>
            <th>تاريخ الاستلام/الرفض</th>
            <th>تحرير</th>
            <th>عرض الشحنات</th>
			<th>مرفقات</th>
            <?php if(HaveAccess($options_part_url)) {?>
            <th>خيارات</th>
            <?php }?>
            <?php if(HaveAccess($create_issues_url)) {?>
            <th>رفع قضية</th>
            <?php }?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <?php if($row['TO_ISSUES'] == 1 )
            {
                $bgcolor='#c9f8ff';
            }
            else
            {
                $bgcolor='#FFFFFF';
            }
            ?>
            <tr id="tr_<?=$row['SER']?>" style="background-color: <?=$bgcolor?>" >
                <td><?=$row['RN']?></td>
                <td><?=$row['SUB_NO']?></td>
                <td><?=$row['SUB_NAME']?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['BOND_NO']?> / <?=$row['BOND_YEAR']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['DELIVERS_STATUS_NAME']?></td>
                <td><?=$row['DELIVERS_DATE']?></td>

                <td> <a href="<?=base_url("issues/bonds/get_bond_info/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a></td>
                <td><?php if($row['IS_PREPAID'] == 1)
                     {?>
             <a href="javascript:;" onclick="javascript:_showReport('<?= base_url("issues/bonds/charge_index/{$row['SUB_NO']}") ?>');">
                     
                <i class="icon icon-file"><?php }?></i>
                </td>
                <td>
                    <?php echo modules::run('attachments/attachment/indexInlineno',$row['SER'],'issues',0); ?>
                
                </td>
                <?php if(HaveAccess($options_part_url)) {?>
                <td>
                    <?php if($row['ADOPT'] == 2)
                    {?>
                        <i class="fa fa-print" onclick="print_report(<?=$row['SER']?>);"></i>
                        <i class="fa fa-file" onclick="show_doc(<?=$row['SER']?>);"></i>
                   <!-- <select name="options" id="dp_options" class="form-control">
                        <option value="0">-</option>

                        <option value="1" data-id="<?=$row['SER']?>">طباعة إخطار</option>


                        <option value="2" data-id="<?=$row['SER']?>">استلام السند</option>

                    </select>
                    -->

                    <?php
                    }
                    ?>

                </td>
                <?php
                }
                ?>
                <?php if(HaveAccess($create_issues_url)) {?>
               <td>
                  <?php if($row['PERIOD_DATE'] == 1){
                      if($row['TO_ISSUES']==0)
                      {
                      $url_to_issues='issues/issues/create/'.$row['SER'].'/1';
                  ?>
                   <a href="<?=base_url($url_to_issues)?>">رفع قضية من قبل الشركة</a>
                   <?php
                   }
                      else if($row['TO_ISSUES']==1)
                      {
                         $info_to_issues= modules::run( base_url("issues/bonds/to_issues_get"),$row['SER']);
                         if($info_to_issues['ISSUE_TYPE']==4)
                          $url_to_issues='issues/issues/get_exec_issue_info/'.$info_to_issues['SER'];
                           else
                          $url_to_issues='issues/issues/get_issue_info/'.$info_to_issues['SER'];
                          ?>
                          <a href="<?=base_url($url_to_issues)?>">رفع قضية من قبل الشركة</a>
                      <?php
                      }
                     }

                   ?>
               </td>
                <?php
                }
                ?>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>




<script type="text/javascript">

  if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }

 /* $('#dp_options').select2().on('change',function(){
      if($( this ).val() == '1')
      {
          var ser=$(this).find(':selected').data("id");
          var url=$('#report_url').val()+'&report_type=pdf&report=ordered_supply&p_ser='+ser+'&sn='+$('#report_sn').val();
          _showReport(url,true);

      }
      else if($( this ).val() == '2')
      {
         var  val=$(this).find(':selected').data("id");
          _showReport($('#popup_url').val()+'/'+val);
          //$('#bondsModal').modal();
      }
  });*/
    function print_report(ser)
    {

        var url=$('#report_url').val()+'&report_type=pdf&report=ordered_supply&p_ser='+ser+'&sn='+$('#report_sn').val();
        _showReport(url,true);

    }

  function show_doc(val)
  {

      _showReport($('#popup_url').val()+'/'+val);

  }

</script>

