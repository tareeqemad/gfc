<?php


$MODULE_NAME='tasks';
$TB_NAME='task';

$base_url;
$back_url='';

?>
<style>
.field_set{
	 border-color:#81BEF7;
 
}

    .simple{
        margin-left: 250px
    }
    h5{
        color: #fbfbfb;
       text-align: center;
    }
</style>



<div class="panel panel-primary">
    <div class="panel-heading"><h5> <?= $task['TASK_TITLE'] ?></h5></div>
    <div class="panel-body">
    <div class="portlet-body">

    <div class="row">

        <div id="test_tasklist">

         
            <?php
            $count1= $count2= $count3 =0;
          
            foreach ($RedirectTasks as $row) :
                if($row['DIRECT_TYPE']==1){
                    $count1++;
                } elseif($row['DIRECT_TYPE']==2){
                    $count2++;
                }
            else{
                $count3++;
            }
            endforeach;

            if ($count1 != 0){
            ?>

            <p style="color:dodgerblue; font-size:16px; margin-left: 400px">الى </p>
            <?php
                foreach ($RedirectTasks as $row) :
                    if($row['DIRECT_TYPE']==1){
                        print "<p class='simple'>".$row['DIRECT_EMP_NO_NAME']."<br></p>";  }
                    
                    endforeach;

            }  
             if ($count2 != 0){?>

           <br>

                <p style="color:dodgerblue;margin-left: 400px; font-size:16px" >للمتابعة</p>
                <?php

                foreach ($RedirectTasks as $row) :
                    if($row['DIRECT_TYPE']==2){
                        print "<p class='simple'>".$row['DIRECT_EMP_NO_NAME']."<br></p>";

                    }
                endforeach; }
            if ($count3 != 0){
           ?>

                <br>

            <p style="color:dodgerblue; margin-left: 400px; font-size:16px">للاطلاع</p>
                <?php

                foreach ($RedirectTasks as $row) :
                    if($row['DIRECT_TYPE']==3){
                        print "<p class='simple'>".$row['DIRECT_EMP_NO_NAME']."<br></p>";

                    }

                endforeach; }?>

        </div>

    </div>
</div>

    </div>
</div>