<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 19/06/18
 * Time: 10:12 ص
 */


?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url . '/' ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a>
                </li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">


                <div class="form-group col-sm-1">
                    <label class="control-label"> Project Tec</label>

                    <div>
                        <input type="text" name="request_code" id="txt_request_code" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> From date</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> To date</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="t_date" id="txt_to_date"
                               class="form-control">
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                <sub><i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  name="rep_type_id" value="xls">
                <sub><i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  name="rep_type_id" value="doc">
                <sub><i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="rep_master" onclick="javascript:print_report2();" class="btn btn-primary">Show Report</button>

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">Search</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default"> Reset
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">

            <?php echo modules::run('technical/TransformersInstallation/get_page', $page, $action); ?>

        </div>

    </div>

</div>
