<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:18 ص
 */
$count = 1;
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">
       <div id="msg_container"></div>

        <div class="container">
            <table class="table" id="billsTbl" data-container="container">
                <thead>
                <tr>
                    <th  >#</th>
                    <th>المنطقة</th>
                    <th >  المشترك  </th>
                    <th >اسم المشترك</th>
                    <th  >  عن شهر</th>

                    <th  >  صندوق </th>
                    <th  >   عملية</th>
                    <th  > عن فاتورة</th>
                    <th  class="price">المبلغ</th>


                </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $row) :?>
                    <tr >

                        <td><?= $count ?></td>
                        <td><?= $row['REGION'] ?></td>
                        <td><?= $row['SUBSCRIBER'] ?></td>
                        <td><?= $row['NAME'] ?></td>
                        <td><?= $row['FOR_MONTH'] ?></td>
                        <td><?= $row['CACHIER'] ?></td>
                        <td><?= $row['OPERATION_NO'] ?></td>
                        <td><?= $row['BILL_NO'] ?></td>
                        <td class="price"><?= n_format($row['VALUE']) ?> </td>


                        <?php $count++ ?>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

</div>