<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 22/05/18
 * Time: 09:23 ص
 */


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;


?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>

    <div class="form-body">
        <div id="msg_container"></div>
        <div id="container">
            <form class="form-form-vertical" id="loadflow_form" method="post"
                  action="<?= base_url('technical/TransformersInstallation/action') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> No. </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['SER'] : "" ?>" readonly name="ser"
                                   id="txt_ser" class="form-control">

                            <input type="hidden" name="action" value="<?= $action ?>"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Project No </label>

                        <div class="input-group">
                            <input type="text" name="project_tec" id="project_tec" class="form-control"
                                   data-val-required="required"
                                   data-val="true"
                                <?= $HaveRs ? 'readonly' : '' ?>
                                   value="<?= $HaveRs ? $rs['PROJECT_TEC'] : "" ?>">

                            <span class="input-group-addon" onclick="javascript:getProjectDetails();"
                                  style="cursor: pointer;">
											<i class="icon icon-search"></i>
											</span>
                        </div>

                    </div>

                    <hr>

                    <div class="form-group  col-sm-3">
                        <label class="control-label"> Project Name </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_NAME'] : "" ?>" readonly
                                   name="project_tec_name"


                                   id="project_tec_name" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Donation Name </label>

                        <div>

                            <select name="donation_name" id="donation_name" class="form-control">
                                <option></option>
                                <?php foreach ($donation_name as $row) : ?>

                                    <option <?= $HaveRs ? ($row['CON_NO'] == $rs['DONATION_NAME'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Budget Year </label>

                        <div>


                            <select name="budget_year" id="budget_year" class="form-control">
                                <option></option>
                                <?php for ($i = 2018; $i < 2028; $i++) : ?>

                                    <option <?= $HaveRs ? ($i == $rs['BUDGET_YEAR'] ? 'selected' : '') : '' ?>
                                            value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">No. Customers</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['CUSTOMERS'] : "" ?>"
                                   name="customers"

                                   id="customers" class="form-control">
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Gov - Area </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_BRANCH'] : "" ?>" readonly
                                   name="project_tec_branch"

                                   id="project_tec_branch" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Feeder Name </label>


                        <select name="feeder_name" id="feeder_name"
                                data-val-required="required"
                                data-val="true"
                                class="form-control">
                            <option></option>
                            <?php foreach ($feeder_name as $row) : ?>

                                <option <?= $HaveRs ? ($row['CON_NO'] == $rs['FEEDER_NAME'] ? 'selected' : '') : '' ?>
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>


                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Date</label>

                        <div>

                            <input type="text" value="<?= $HaveRs ? $rs['ENTRY_DATE'] : date('d/m/Y') ?>" readonly
                                   name="entry_date"

                                   id="entry_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Prepared and Designed By</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_USER'] : "" ?>" readonly
                                   name="project_tec_user"

                                   id="project_tec_user" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-warning">
                        Economic Feasibility
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label" style="direction:ltr;"> Project Cost (NIS)</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['RECONDUCTORING_COST'] : "" ?>"
                                   name="reconductoring_cost"
                                   style="direction:ltr;"

                                   readonly
                                   id="reconductoring_cost" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Electricity Hours/Day</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['ELECTRICITYH'] : "" ?>"
                                   name="electricityh"
                                   style="direction:ltr;"

                                   id="electricityh" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1" data-type="mv-connection">
                        <label class="control-label"> KW Saving</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['KWSAVING'] : "" ?>"
                                   name="kw_saving"
                                   readonly
                                   style="direction:ltr;"
                                   id="kw_saving" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label" style="direction:ltr;">KWH Saving monthly</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['KWH_SAVING'] : "" ?>" readonly
                                   name="kwsaving"
                                   style="direction:ltr;"

                                   id="kwsaving" class="form-control">
                        </div>
                    </div>


                    <div class="form-group  col-sm-12">
                        <label class="control-label"> Notes</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['NOTES'] : "" ?>"
                                   name="notes"
                                   id="notes" class="form-control">
                        </div>
                    </div>


                    <hr>
                    <div style="clear: both;">

                        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['SER'], 'transformers_installation') : ''; ?>
                    </div>

                    <hr>

                    <ul class="nav nav-tabs ">

                        <li class="tab1 active">
                            <a href="#tab_2" data-toggle="tab"> MV Conductors Data & Real Measurements </a>
                        </li>
                        <li>
                            <a href="#tab_1" data-toggle="tab"> New Transformer Data </a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab"> Existing Loaded Transformers Before Load Mitigation </a>
                        </li>
                        <li>
                            <a href="#tab_4" data-toggle="tab"> Existing Loaded Transformers After Load Mitigation </a>
                        </li>
                        <li>
                            <a href="#tab_5" data-toggle="tab">LV Networks </a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane  " id="tab_1">
                            <?php echo modules::run('technical/TransformersInstallation/public_transformer_data', $HaveRs ? $rs['SER'] : 0); ?>
                        </div>
                        <div class="tab-pane active" id="tab_2">
                            <?php echo modules::run('technical/TransformersInstallation/public_mv_conductor', $HaveRs ? $rs['SER'] : 0); ?>
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <?php echo modules::run('technical/TransformersInstallation/public_existing_load_transformer', $HaveRs ? $rs['SER'] : 0); ?>
                        </div>
                        <div class="tab-pane" id="tab_4">
                            <?php echo modules::run('technical/TransformersInstallation/public_after_existing_load_transformer', $HaveRs ? $rs['SER'] : 0); ?>
                        </div>
                        <div class="tab-pane" id="tab_5">
                            <?php echo modules::run('technical/TransformersInstallation/public_lv_networks', $HaveRs ? $rs['SER'] : 0); ?>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">

                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                </div>

            </form>

        </div>
    </div>
</div>
