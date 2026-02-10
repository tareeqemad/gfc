<?php
$MODULE_NAME='tasks';
$TB_NAME='task';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$delete_url=base_url("$MODULE_NAME/$TB_NAME/delete");
$isCreate= 1;
$back_url='';
$counter=0;
$latestatus= "متاخرة";
$date_array = getdate (time());

?>
<style>
.modal {
  background: rgba(0,0,0,0.5);
}
    #myInput {
        background-position: 10px 10px;
        background-repeat: no-repeat;
        width: 50%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }



    #searchinput {
        width: 400px;
    }
    #searchclear, #search {
        position: absolute;
        left: 5px;
        top: 0;
        bottom: 0;
        height: 14px;
        margin: auto;
        font-size: 14px;
        cursor: pointer;
        color: #ccc;
    }

    .STATUS_2 {
        background-color: green; 

    }
    .STATUS_3 {
        background-color: #F1C40F; 
    }
    .STATUS_4 {
        background-color: #ed6b75;
    }
    #cloo{
          background-color: green;
      }


.container-table100 {
    padding: 5px 1px;
}

</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
    var tb_name_to_excel= 'myTable';
</script>
  
              
<div class="btn-group">
    <i class="material-icons" id="searchclear" style = "display:none;" style="font-size:15px;color:Grey">close</i>
    <input type="text" name="search" id="searchinput"   placeholder="البحث عن المهام" class="form-control" />  
    <i class="material-icons" id="search"  style="font-size:15px;color:Grey">search</i>

    <!--<input id="searchinput" type="text" class="form-control" onkeyup="myFunction()">-->
 
</div>


<?php if(count($tasks) > 0 ){?>

&nbsp; &nbsp;
<!-- <i class="fa fa-clock-o" id="late" title="المهام المتأخرة" style="font-size:24px;color:red" aria-hidden="true"></i>-->
<!-- <button type="button" id="late" class="btn btn-danger"> متاخرة </button> -->
<!--<button type="button" id="done" class="btn btn-success"> منجزة </button>-->
<i class="material-icons" title="ترحيل لاكسل " style="font-size:24px; color:#126ded; cursor: pointer" onclick="$('#'+tb_name_to_excel).tableExport({type:'excel',escape:'false'});" >file_download</i>
<i class="material-icons"id="done" title="المهام المنجزة" style="font-size:24px; color:green; cursor: pointer">done_all</i>
<i class="material-icons" id="late" title="المهام المتأخرة" style="font-size:24px; color:#ff4f4f; cursor: pointer">error</i>
<!-- <button type="button" id="wait" class="btn btn-primary"> قيد الانجاز </button> -->
<i  class="fa fa-spinner fa-spin00" id="wait" title="قيد الانجاز" style="font-size:24px; color:#148df5; cursor: pointer"></i>
<!---<button type="button" id="hint" class="btn btn-warning">التنبيهات</button>-->
<i class="material-icons"id="hint" title="التنبيهات" style="font-size:24px; color:#ffac2b; cursor: pointer">notifications</i>
<?php } ?>

<i class="material-icons" href="#form_modal2" data-toggle="modal" data-target="#myModal"  id="add" title="مهمة جديدة" style="font-size:24px;color:#58ccff;cursor: pointer">add</i>

<?php if(count($tasks) > 0){?>

<div class="limiter">
<div class="container-table100">
<div class="wrap-table100">
<div class="table100">

    <table class="table"  id="myTable" data-container="container">

        <thead class="color-head">
        <tr>
            <th style="width: 7px"></th>
            <th>رقم المهمة</th>
            <th>
                <i class="fa fa-text-height"></i> عنوان المهمة
            </th>

            <?php
            if($p_type==1){
                ?>
                <th>
                    <i class="fa fa-user"></i> من
                </th>
                <th3 style="display: none; mk2021: hide" >
                <i  class="glyphicon glyphicon-edit"> الحركات</i>
            </th3>
                
            <?php
            }
            else
            {
                ?>
                <th>
                    <i class="fa fa-user"></i> الى
                </th>
                <th3 style="display: none; mk2021: hide" >
                <i class="glyphicon glyphicon-edit"> الحركات</i>
            </th3>
            <?php
            }
            ?>
           
            <th>
                <i class="fa fa-calendar"></i> التاريخ
            </th>

            <th>
                <i class=""></i>تاريخ الانجاز
            </th>

            <th>الحالة</th>
            <th>الأهمية</th>

        </tr>
        </thead>
        <tbody>

        <?php foreach ($tasks as $row) :
      

            if(1) {?>


                <tr id="tr_<?=$row['TASK_NO']?>">

                <td class='<?='STATUS_'.$row['STATUS']; ?>'><?//= $row['STATUS_NAME']; ?></td>



                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                    <td width="40%" onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_TITLE'] ?></td>
                    <?php
                    //الوارد  ...من
                    if($p_type==1){
                        ?>
                        <td><?= $row['EMP_NAME'] ?></td>
                        <td style="display: none; mk2021: hide"  onclick="javascript:get_transaction(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-edit" data-toggle="modal"  data-target="#show_transaction" ></span>

                        <!-- Modal -->
                        <div id="show_transaction" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi_transaction">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                           <!-- Modal ---------->
                           
                    <?php
                    }
                    else
                    {
                        //  الصادر ...الى
                        ?>
    

    <td  onclick="javascript:get_task_id(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-user" data-toggle="modal"  data-target="#showmsgrec" ><?= $row['TRANSFER_CNT'] ?> <span style="display: none"> <?= $row['TRANSFER_NAMES'] ?> </span> </span>
    <td style="display: none; mk2021: hide"  onclick="javascript:get_transaction(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-edit" data-toggle="modal"  data-target="#show_transaction" ></span>


                        <!-- Modal -->
                            <div id="showmsgrec" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                           <!-- Modal -->
                          <!-- Modal -->
                                <div id="show_transaction" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi_transaction">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                           <!-- Modal ---------->

                        </td>
                    <?php
                    }
                    ?>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['ENTRY_DATE'] ?></td>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TAS_DONT_DATE'] ?></td>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['STATUS_NAME'] == "جديدة"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-success" > <?= $row['STATUS_NAME'] ?></span>
                        <?php
                        } elseif ($row['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                            <span id="cloo"  class="todo-tasklist-badge badge badge-roundless badge-info " > <?= $row['STATUS_NAME'] ?></span>
                        <?php }elseif ($row['STATUS_NAME'] == "مؤجلة"){  ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  }elseif ($row['STATUS_NAME'] == "متأخرة"){  ?>
                            <span class="badge badge-danger"> <?= $row['STATUS_NAME'] ?></span>
            <?php  } ?>
                    </td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['PRIORITY_NAME'] == "عادية"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $row['PRIORITY_NAME'] ?></span>
                        <?php
                        } else { ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?> </span>

                        <?php    }

                        ?>
                    </td>
                </tr>
            <?php } endforeach; ?>

        </tbody>
        <?php
        ?>


    </table>
    

    <?php }else {

        print_r("<br><br><br>لا يوجد مهام واردة .. ");
    }
    ?>
</div>


<table class="table" id="table_late" style="display:none" data-container="container">

    <thead>
    <tr>
        <th></th>
        <th>رقم المهمة</th>
        <th>
            <i class="fa fa-text-height"></i> عنوان المهمة
        </th>

        <?php
        if($p_type==1){
            ?>
            <th>
                <i class="fa fa-user"></i> من
            </th>
        <?php
        }
        else
        {
            ?>
            <th>
                <i class="fa fa-user"></i> الى
            </th>
        <?php
        }
        ?>

        <th>
            <i class="fa fa-calendar"></i> التاريخ
        </th>

        <th>
            <i class=""></i>تاريخ الانجاز
        </th>

        <th>الحالة</th>
        <th>الأهمية</th>

    </tr>
    </thead>
    <tbody>


    <?php foreach ($tasks as $row) :

        if($row['PARENT_TASK'] ==0) {
                // date from 24/06/2021 to 20210624
            if(  ( date_format( (date_create( str_replace("/","-", $row['TAS_DONT_DATE']) )) ,"Ymd") )  <= date("Ymd") && $row['STATUS_NAME'] != 'منجزة / مغلقة' && $row['STATUS_NAME'] != 'جديدة' ) {

                ?>

                <tr id="tr_<?=$row['TASK_NO']?>">
                <td class='<?='STATUS_'.$row['STATUS']; ?>'><?//= $row['STATUS_NAME']; ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_TITLE'] ?></td>
                    <?php
                    //الوارد  ...من
                    if($p_type==1){
                        ?>
                        <td><?= $row['EMP_NAME'] ?></td>
                    <?php
                    }
                    else
                    {
                        //  الصادر ...الى
                        ?>

<td  onclick="javascript:get_task_id(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-user" data-toggle="modal"  data-target="#showmsgrec" ><?= $row['TRANSFER_CNT'] ?> <span style="display: none"> <?= $row['TRANSFER_NAMES'] ?> </span> </span>


                        

                            <!-- Modal -->
                            <div id="showmsgrec" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </td>
                    <?php
                    }
                    ?>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['ENTRY_DATE'] ?></td>
                    <td  class="col" onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TAS_DONT_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                    <?php if ($row['STATUS_NAME'] == "جديدة"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-success" > <?= $row['STATUS_NAME'] ?></span>
                        <?php
                        } elseif ($row['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                            <span id="cloo" class="todo-tasklist-badge badge badge-roundless badge-info"> <?= $row['STATUS_NAME'] ?></span>
                        <?php }elseif ($row['STATUS_NAME'] == "مؤجلة"){  ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $row['STATUS_NAME'] ?></span>
                    <?php  }elseif ($row['STATUS_NAME'] == "متأخرة"){  ?>
                        <span class="badge badge-danger"> <?= $row['STATUS_NAME'] ?></span>
                    <?php  } ?>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['PRIORITY_NAME'] == "عادية"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $row['PRIORITY_NAME'] ?></span>
                        <?php
                        } else { ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?> </span>

                        <?php    }

                        ?>
                    </td>


                </tr>



            <?php  }} endforeach; ?>

    </tbody>
    <?php
    ?>

    <!--<p><?php echo $links; ?></p>
help 1 2 3 -->

</table>

<!-- منجزة -->

<table class="table" id="table_done" style="display:none" data-container="container">

    <thead>
    <tr>
        <th></th>                    
        <th>رقم المهمة</th>
        <th>
            <i class="fa fa-text-height"></i> عنوان المهمة
        </th>

        <?php
        if($p_type==1){
            ?>
            <th>
                <i class="fa fa-user"></i> من
            </th>
        <?php
        }
        else
        {
            ?>
            <th>
                <i class="fa fa-user"></i> الى
            </th>
        <?php
        }
        ?>

        <th>
            <i class="fa fa-calendar"></i> التاريخ
        </th>

        <th>
            <i class=""></i>تاريخ الانجاز
        </th>

        <th>الحالة</th>
        <th>الأهمية</th>

    </tr>
    </thead>
    <tbody>

    <?php foreach ($tasks as $row) :

        if($row['PARENT_TASK'] ==0) {

            if( ($row['STATUS_NAME'] == 'منجزة / مغلقة') ) {

                ?>

                <tr id="tr_<?=$row['TASK_NO']?>">
                <td class='<?='STATUS_'.$row['STATUS']; ?>'><?//= $row['STATUS_NAME']; ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_TITLE'] ?></td>
                    <?php
                    //الوارد  ...من
                    if($p_type==1){
                        ?>
                        <td><?= $row['EMP_NAME'] ?></td>
                    <?php
                    }
                    else
                    {
                        //  الصادر ...الى
                        ?>

<td  onclick="javascript:get_task_id(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-user" data-toggle="modal"  data-target="#showmsgrec" ><?= $row['TRANSFER_CNT'] ?> </span>


                            <!-- Modal -->
                            <div id="showmsgrec" class="modal fade" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </td>
                    <?php
                    }
                    ?>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['ENTRY_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TAS_DONT_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['STATUS_NAME'] == "جديدة"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-success"> <?= $row['STATUS_NAME'] ?></span>
                        <?php
                        } elseif ($row['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                            <span id="cloo" class="todo-tasklist-badge badge badge-roundless badge-info"> <?= $row['STATUS_NAME'] ?></span>
                        <?php }elseif ($row['STATUS_NAME'] == "مؤجلة"){  ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  }elseif ($row['STATUS_NAME'] == "متأخرة"){  ?>
                            <span class="badge badge-danger"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  } ?>
                    </td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['PRIORITY_NAME'] == "عادية"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $row['PRIORITY_NAME'] ?></span>
                        <?php
                        } else { ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?> </span>

                        <?php    }

                        ?>
                    </td>


                </tr>



            <?php  }} endforeach; ?>

    </tbody>
    <?php
    ?>

    <!--<p><?php echo $links; ?></p>
help 1 2 3 -->

</table>



<!-- قيد الانجاز-->

<table class="table" id="table_wait" style="display:none" data-container="container">

    <thead>
    <tr>
         <th></th>                   
        <th>رقم المهمة</th>
        <th>
            <i class="fa fa-text-height"></i> عنوان المهمة
        </th>

        <?php
        if($p_type==1){
            ?>
            <th>
                <i class="fa fa-user"></i> من
            </th>
        <?php
        }
        else
        {
            ?>
            <th>
                <i class="fa fa-user"></i> الى
            </th>
        <?php
        }
        ?>

        <th>
            <i class="fa fa-calendar"></i> التاريخ
        </th>

        <th>
            <i class=""></i>تاريخ الانجاز
        </th>

        <th>الحالة</th>
        <th>الأهمية</th>

    </tr>

    <tbody>

    <?php foreach ($tasks as $row) :

        if($row['PARENT_TASK'] ==0) {

            if(($row['STATUS_NAME'] == 'جديدة') ) {

                ?>

                <tr id="tr_<?=$row['TASK_NO']?>">
                <td class='<?='STATUS_'.$row['STATUS']; ?>'><?//= $row['STATUS_NAME']; ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_TITLE'] ?></td>
                    <?php
                    //الوارد  ...من
                    if($p_type==1){
                        ?>
                        <td><?= $row['EMP_NAME'] ?></td>
                    <?php
                    }
                    else
                    {
                        //  الصادر ...الى
                        ?>

<td  onclick="javascript:get_task_id(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-user" data-toggle="modal"  data-target="#showmsgrec" ><?= $row['TRANSFER_CNT'] ?> </span>


                            <!-- Modal -->
                            <div id="showmsgrec" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </td>
                    <?php
                    }
                    ?>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['ENTRY_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TAS_DONT_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['STATUS_NAME'] == "جديدة"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-success"> <?= $row['STATUS_NAME'] ?></span>
                        <?php
                        } elseif ($row['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                            <span id="cloo" class="todo-tasklist-badge badge badge-roundless badge-info"> <?= $row['STATUS_NAME'] ?></span>
                        <?php }elseif ($row['STATUS_NAME'] == "مؤجلة"){  ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  }elseif ($row['STATUS_NAME'] == "متأخرة"){  ?>
                            <span class="badge badge-danger"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  } ?>

                    </td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['PRIORITY_NAME'] == "عادية"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $row['PRIORITY_NAME'] ?></span>
                        <?php
                        } else { ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?> </span>

                        <?php    }

                        ?>
                    </td>


                </tr>



            <?php  }} endforeach; ?>

    </tbody>
    <?php
    ?>

    <!--<p><?php echo $links; ?></p>
help 1 2 3 -->

</table>


<!--  Notifications -->



<table class="table" id="table_not" style="display:none" data-container="container">

    <thead>
    <tr>
         <th></th>                   
        <th>رقم المهمة</th>
        <th>
            <i class="fa fa-text-height"></i> عنوان المهمة
        </th>

        <?php
        if($p_type==1){
            ?>
            <th>
                <i class="fa fa-user"></i> من
            </th>
        <?php
        }
        else
        {
            ?>
            <th>
                <i class="fa fa-user"></i> الى
            </th>
        <?php
        }
        ?>

        <th>
            <i class="fa fa-calendar"></i> التاريخ
        </th>

        <th>
            <i class=""></i>تاريخ الانجاز
        </th>

        <th>الحالة</th>
        <th>الأهمية</th>

    </tr>
    </thead>
    <tbody>

    <?php foreach ($tasks as $row) :

        if($row['PARENT_TASK'] ==0) {

            if(($row['STATUS_NAME'] == 'مؤجلة')) {
                ?>

                <tr id="tr_<?=$row['TASK_NO']?>">
                <td class='<?='STATUS_'.$row['STATUS']; ?>'><?//= $row['STATUS_NAME']; ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_TITLE'] ?></td>
                    <?php
                    //الوارد  ...من
                    if($p_type==1){
                        ?>
                        <td><?= $row['EMP_NAME'] ?></td>
                    <?php
                    }
                    else
                    {
                        //  الصادر ...الى
                        ?>

<td  onclick="javascript:get_task_id(<?= $row['TASK_NO'] ?>)" ><span id="count_rceiver" class="glyphicon glyphicon-user" data-toggle="modal"  data-target="#showmsgrec" ><?= $row['TRANSFER_CNT'] ?> </span>

                            <!-- Modal -->
                            <div id="showmsgrec" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </td>
                    <?php
                    }
                    ?>

                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['ENTRY_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TAS_DONT_DATE'] ?></td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['STATUS_NAME'] == "جديدة"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-success"> <?= $row['STATUS_NAME'] ?></span>
                        <?php
                        } elseif ($row['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                            <span id="cloo" class="todo-tasklist-badge badge badge-roundless badge-info"> <?= $row['STATUS_NAME'] ?></span>
                        <?php }elseif ($row['STATUS_NAME'] == "مؤجلة"){  ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  }elseif ($row['STATUS_NAME'] == "متأخرة"){  ?>
                            <span class="badge badge-danger"> <?= $row['STATUS_NAME'] ?></span>
                        <?php  } ?>
                    </td>
                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)">
                        <?php if ($row['PRIORITY_NAME'] == "عادية"){ ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $row['PRIORITY_NAME'] ?></span>
                        <?php
                        } else { ?>
                            <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?> </span>

                        <?php    }

                        ?>
                    </td>


                </tr>



            <?php  }} endforeach; ?>

    </tbody>
    <?php
    ?>

    <!--<p><?php echo $links; ?></p>
help 1 2 3 -->

</table>

</div>
		</div>
	</div>
<script>
    $(document).ready(function(){

        // hide form on page load
        $('#table_late').hide();
        $('#table_done').hide();
        $('#table_wait').hide();
        $('#table_not').hide();

        // when button is pressed
        $('#done').on('click',function(){
            $('#table_done').show();
            $('#myTable').hide();
            $('#table_not').hide();
            $('#table_late').hide();
            $('#table_wait').hide();
            tb_name_to_excel= 'table_done';
        });

        $('#late').on('click',function(){
            $('#table_done').hide();
            $('#myTable').hide();
            $('#table_not').hide();
            $('#table_wait').hide();
            $('#table_late').show();
            tb_name_to_excel= 'table_late';
        });

        $('#wait').on('click',function(){
            $('#table_done').hide();
            $('#myTable').hide();
            $('#table_not').hide();
            $('#table_wait').show();
            $('#table_late').hide();
            tb_name_to_excel= 'table_wait';
        });

        $('#hint').on('click',function(){
            $('#table_done').hide();
            $('#myTable').hide();
            $('#table_not').show();
            $('#table_wait').hide();
            $('#table_late').hide();
            tb_name_to_excel= 'table_not';
        });
    });
</script>

<!---------------------------------------------------------------->



<script>

  /*  function myFunction() {
        $("#searchclear").show();
        $("#search").hide();
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchinput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }*/

    $(document).ready(function(){  
           $('#searchinput').keyup(function(){  
                search_table($(this).val());  
                $("#searchclear").show();
        $("#search").hide();
           });  
         
           function search_table(value){
                $('#'+tb_name_to_excel+' tbody tr').each(function(){
                     var found = 'false';  
                     $(this).each(function(){
                         var value_arr =value.split(" ");

                          if(  $(this).text().toLowerCase().indexOf(value_arr[0].toLowerCase()) >= 0
                              && ( typeof value_arr[1] == 'undefined' || $(this).text().toLowerCase().indexOf(value_arr[1].toLowerCase()) >= 0)
                              && ( typeof value_arr[2] == 'undefined' || $(this).text().toLowerCase().indexOf(value_arr[2].toLowerCase()) >= 0)
                          )
                          {  
                               found = 'true';  
                          }  
                     });  
                     if(found == 'true')  
                     {  
                          $(this).show();
                     }  
                     else  
                     {  
                          $(this).hide();
                     }  
                });  
           }  
      });  

    $("#searchclear").click(function(){

        $("#searchinput").val("");
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchinput");
        filter = input.value.toUpperCase();
        table = document.getElementById(tb_name_to_excel);
        /*th =table.getElementById("th"); */
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
        /*$("#searchclear").hide();
        $("#search").show();*/
    });

    $("#searchclear").click(function(){
        $("#searchinput").val('');
    });

    /*$('#showmsgrec').appendTo("body").modal('show');*/



</script>
