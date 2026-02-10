<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/01/15
 * Time: 05:18 م
 */

$count=0;

function show_os($uagent){
    $oses = array(
        'WinXP' => '(Windows NT 5.1)|(Windows XP)',
        'WinServer2003' => '(Windows NT 5.2)',
        'WinVista' => '(Windows NT 6.0)',
        'Win7' => '(Windows NT 6.1)',
        'Win8' => '(Windows NT 6.2)',
        'Win8.1' => '(Windows NT 6.3)',
        'Win10' => 'Windows NT 10.0',
    );

    foreach ($oses as $os => $pattern){
        if (preg_match('/' . $pattern . '/i', $uagent)){
            return $os;
            break;
        }
    }
    return '';
}

function cut_name($name){
    $name= explode(' ',trim($name));
    $name= $name[0].' '.array_pop($name);
    return $name;
}

function cut_branch($name){
    $name= explode(' ',trim($name));
    $name= array_pop($name);
    return $name;
}

function class_name($s){
    if($s==1){
        return 'glyphicon glyphicon-eye-open color_green';
    }elseif($s==0){
        return 'glyphicon glyphicon-eye-close color_red';
    }
}

$users= array();
$user_ip= array();

?>

<div class="tbl_container">
    <table class="table" style="width: 99%" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 1%">م</th>
            <th style="width: 7%"> المستخدم</th>
            <th style="width: 5%">الفرع </th>
            <th style="width: 5%">IP</th>
            <th style="width: 5%">النظام</th>
            <th style="width: 5%">المتصفح</th>
            <th style="width: 5%">رقم الجلسة</th>
            <th style="width: 10%">AGENT</th>
            <th style="width: 12%">الصفحة</th>
            <th style="width: 12%">الحدث</th>
            <th style="width: 4%"> الدخول</th>
            <th style="width: 4%"> النشاط</th>
            <th style="width: 2%">الحالة</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :
            $count++;
            $crnt_user_ip= $row['USER_ID'].'-'.$row['IP_ADDRESS'];
            $class_case= (in_array($row['USER_ID'], $users))?0:1; // المستخدم مكرر
            $class_case= (in_array($crnt_user_ip, $user_ip))?2:$class_case; // اكثر من متصفح
            $users[]= $row['USER_ID'];
            $user_ip[]= $crnt_user_ip;
        ?>
            <tr class="case_<?=$class_case?>">
                <td><?=$count?></td>
                <td title="<?=$row['USER_ID'].': '.$row['USER_NAME']?>"><a target="_blank" href="<?=base_url("settings/users/login_by_user/{$row['USER_ID']}")?>" ><?=cut_name($row['USER_NAME'])?></a></td>
                <td title="<?=$row['BRANCH_NAME']?>"><?=cut_branch($row['BRANCH_NAME'])?></td>
                <td><?=$row['IP_ADDRESS']?></td>
                <td><?=show_os($row['USER_AGENT'])?></td>
                <td><?=$row['BROWSER']?></td>
                <td style="font-size: 8px" title="<?=$row['SESSION_ID']?>"><?=$row['SESSION_ID']?></td>
                <td class="user_agent"><div style="display: none"><?=$row['USER_AGENT']?></div></td>
                <td><a target="_blank" href="<?=base_url($row['LOCATION_URL'])?>" ><?=$row['LOCATION_MENU_NAME']?></a></td>
                <td><a target="_blank" href="<?=base_url($row['LAST_ACTION'])?>" ><?=$row['ACTION_MENU_NAME']?></a></td>
                <td title="<?=$row['LAST_LOGIN_ALL']?>"><?=$row['LAST_LOGIN']?></td>
                <td title="<?=$row['LAST_ACTIVITY_ALL']?>"><?=$row['LAST_ACTIVITY']?></td>
                <td id="td<?=$row["USER_ID"]?>"><div style="display: none"><?=$row["STATUS"]?></div> <li><a onclick="javascript:edit_status(<?=$row["USER_ID"]?>, <?=$row["STATUS"]?>);" href="javascript:;" ><i class="<?=class_name($row["STATUS"])?>"></i> </a> </li> </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#page_tb').dataTable({
            "lengthMenu": [ [-1,50], ["الكل","50"] ]
        });

        $('.user_agent').click(function(){
            $(this).find('div').toggle();
        });

        $('.user_agent').dblclick(function(){
            $('.user_agent div').toggle();
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
