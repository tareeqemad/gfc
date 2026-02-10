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
        <form action="<?= base_url('bills/bills_payments') ?>" method="post" >
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">


                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ</label>
                    <div>
                        <input type="text"  name="entry_date" data-type="date" value="<?= date('d/m/Y') ?>"  data-date-format="DD/MM/YYYY"  id="txt_entry_date" class="form-control"  >
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الفرع</label>
                    <div>
                        <select type="text"   name="branch" id="dp_branch" class="form-control" >
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="submit" class="btn btn-success"> إستعلام</button>

            </div>
        </fieldset>
        </form> <div id="msg_container"></div>

        <div class="container">
            <table class="table" id="billsTbl" data-container="container">
                <thead>
                <tr>
                    <th  >#</th>
                    <th>المقر</th>
                    <th >  رقم المستند </th>
                    <th >البنك</th>
                    <th  > تاريخ السداد</th>
                    <th  class="price">الإجمالي</th>
                    <th >   عدد الفواتير </th>

                </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $row) :?>
                    <tr ondblclick="javascript:_showReport('<?= base_url('bills/bills_payments/details/').'/'.$row['BANK_VOUCHER_NO'] ?>')">

                        <td><?= $count ?></td>
                        <td><?= $row['BRANCH_NAME'] ?></td>
                        <td><?= $row['BANK_VOUCHER_NO'] ?></td>
                        <td><?= $row['BANK'] ?></td>
                        <td><?= $row['ENTRY_DATE'] ?></td>
                        <td class="price"><?= n_format($row['SUM_VALUE']) ?> </td>
                        <td><?= $row['COUNT'] ?></td>

                        <?php $count++ ?>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

</div>