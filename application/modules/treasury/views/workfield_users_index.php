<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 9/9/2021
 * Time: 9:54 AM
 */ 
 $MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$update_user_account_url= base_url("$MODULE_NAME/$TB_NAME/update_user_account");
$update_user_password_url= base_url("$MODULE_NAME/$TB_NAME/update_user_password");
 ?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($update_user_password_url)) { ?>
                <li><a onclick="javascript:_showNewModal('<?= base_url('treasury/workfield/addNewUser') ?>','بيانات المحصل');" href="javascript:;"><i
                                class="glyphicon glyphicon-plus"></i>جديد </a></li>
            <?php } ?>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>

        </ul>
    </div>

    <div class="form-body">
        <table class="table" data-table="true">
            <thead>
            <tr>
                <th>الرقم الوظيفي</th>
                <th>الاسم</th>
                <!--<th>حساب الصندوق</th>-->
                <!--<th>حساب الايراد</th>-->
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $row) : ?>
                <tr>
                    <td><?= $row['NO'] ?></td>
                    <td><?= $row['NAME'] ?></td>
                    <!--<td>
                        <div class="input-group">

                              <span class="input-group-prepend">
                                <input data-id="account"
                                       value="<?= $row['ACCOUNT_ID'] ?>"
                                       type="text"
                                       class="form-control"
                                       placeholder="رقم الحساب"/>
                              </span>

                            <input id="name_<?= $row['NO'] ?>"
                                   value="<?= $row['ACOUNT_NAME'] ?>"
                                   readonly type="text"
                                   class="form-control"
                                   placeholder="اسم الحساب"/>

                        </div>
                    </td> -->
                    <!--<td>
                        <div class="input-group">

                              <span class="input-group-prepend">
                                <input data-id="income-account"
                                       value="<?= $row['INCOME_ACCOUNT_ID'] ?>"
                                       type="text"
                                       class="form-control"
                                       placeholder="رقم الحساب"/>
                              </span>

                            <input id="income_name_<?= $row['NO'] ?>"
                                   value="<?= $row['INCOME_ACCOUNT_ID_NAME'] ?>"
                                   readonly type="text"
                                   class="form-control"
                                   placeholder="اسم الحساب"/>
                        </div>
                    </td>-->
                    <td>
                        <!--<?php if( HaveAccess($update_user_account_url)):  ?>
                            <button type="button" class="btn btn-success" onclick="javascript:update_user_account(this,<?= $row['NO'] ?>);">حفظ </button>
                        <?php endif; ?> -->

                        <?php if( HaveAccess($update_user_password_url) /*&& $row['INCOME_ACCOUNT_ID'] != ""*/ && $row['MOBILE'] != "" ):  ?>
                            <button type="button" class="btn btn-danger" onclick="javascript:update_password(this,<?= $row['NO'] ?>);">اعادة تعيين كلمة المرور </button>
                        <?php endif; ?>

                        <?php if( HaveAccess($update_user_password_url) /*&& $row['INCOME_ACCOUNT_ID'] != ""*/ ):  ?>
                            <button type="button"
                                    class="btn btn-warning"
                                    onclick="javascript:_showNewModal('<?= base_url('treasury/workfield/userData/'.$row['NO']) ?>','بيانات المحصل');"> بيانات المحصل
                            </button>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>