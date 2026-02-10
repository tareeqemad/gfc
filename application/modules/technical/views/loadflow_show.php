<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:30 ص
 */

$back_url = base_url('technical/loadflow');

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
                  action="<?= base_url('technical/loadflow/' . ($HaveRs ? ($action == 'index' ? 'edit' : $action) : 'create')) ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> No. </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['SER'] : "" ?>" readonly name="ser"
                                   id="txt_ser" class="form-control">

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

                            <span class="input-group-addon" onclick="javascript:getProjectDetials();"
                                  style="cursor: pointer;">
											<i class="icon icon-search"></i>
											</span>
                        </div>

                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Study Type </label>


                        <?php if ($HaveRs): ?>
                            <input type="hidden" value="<?= $HaveRs ? $rs['STUDY_TYPE'] : "" ?>"
                                   name="study_type">
                        <?php endif; ?>

                        <select name="study_type" id="study_type" class="form-control"
                                data-val-required="required"
                            <?= $HaveRs ? 'disabled' : '' ?>
                                data-val="true">
                            <option></option>
                            <?php foreach ($study_type as $row) : ?>
                                <option <?= $HaveRs ? ($row['CON_NO'] == $rs['STUDY_TYPE'] ? 'selected' : '') : '' ?>
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>


                    </div>

                    <hr>

                    <div class="form-group  col-sm-3">
                        <label class="control-label"> Project Name </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_NAME'] : "" ?>" readonly
                                   name="project_tec_name"
                                   data-val-required="required"
                                   data-val="true"
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

                    <hr/>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Gov - Area </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_BRANCH'] : "" ?>" readonly
                                   name="project_tec_branch"
                                   data-val-required="required"
                                   data-val="true"
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
                                   data-val-required="required"
                                   data-val="true"
                                   id="entry_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Prepared and Designed By</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_USER'] : "" ?>" readonly
                                   name="project_tec_user"
                                   data-val-required="required"
                                   data-val="true"
                                   id="project_tec_user" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">No. Customers</label>

                        <div>
                            <input type="number" value="<?= $HaveRs ? $rs['CUSTOMERS'] : "" ?>"
                                   name="customers"
                                   data-val-required="required"
                                   data-val="true"
                                   id="customers" class="form-control">
                        </div>
                    </div>

                    <hr/>

                    <div data-show="1,2" class="alert alert-info" data-type="mv-connection">
                        Load Flow Results Before Reconductoring
                    </div>

                    <div data-show="3,4" class="alert alert-info" style="display: none;" data-type="mv-connection-show">
                        Load Flow Results
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Loss-KW</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['LOSS'] : "" ?>"
                                   name="loss"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="loss" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Loss-KVar</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['LOSS_VAR'] : "" ?>"
                                   name="loss_var"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="loss_var" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> Voltage (LL) of Weakest Node (KV) </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['VW_WEAKEST_NODE'] : "" ?>"
                                   name="vw_weakest_node"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="vw_weakest_node" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> Vd% </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['VD'] : "" ?>" readonly
                                   name="vd"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="vd" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2" data-show="2">
                        <label class="control-label"> Voltage (LN) of Weakest Node (KV)</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['VW_WEAKEST_NODE_LN'] : "" ?>"
                                   name="vw_weakest_node_ln"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="vw_weakest_node_ln" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1" data-show="2">
                        <label class="control-label"> Vd% </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['VD_LN'] : "" ?>" readonly
                                   name="vd_ln"
                                   style="direction:ltr;"
                                   data-val-required="required"
                                   data-val="true"
                                   id="vd_ln" class="form-control">
                        </div>
                    </div>

                    <hr/>
                    <div data-type="mv-connection">
                        <div data-show="1,2" class="alert alert-warning">
                            Load Flow Results After Reconductoring
                        </div>
                        <div data-show="1,2" class="form-group  col-sm-1">
                            <label class="control-label"> Loss-KW</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['ALOSS'] : "" ?>"
                                       name="aloss"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="aloss" class="form-control">
                            </div>
                        </div>
                        <div data-show="1,2" class="form-group  col-sm-1">
                            <label class="control-label">Loss-KVar</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['ALOSS_VAR'] : "" ?>"
                                       name="aloss_var"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="aloss_var" class="form-control">
                            </div>
                        </div>
                        <div data-show="1,2" class="form-group  col-sm-2">
                            <label class="control-label"> Voltage (LL) of Weakest Node (KV)</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['AVW_WEAKEST_NODE'] : "" ?>"
                                       name="avw_weakest_node"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="avw_weakest_node" class="form-control">
                            </div>
                        </div>
                        <div data-show="1,2" class="form-group  col-sm-1">
                            <label class="control-label"> Vd% </label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['AVD'] : "" ?>" readonly
                                       name="avd"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="avd" class="form-control">
                            </div>
                        </div>
                        <div data-show="2,4" class="form-group  col-sm-2">
                            <label class="control-label"> Voltage (LN) of Weakest Node (KV)</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['AVW_WEAKEST_NODE_LN'] : "" ?>"
                                       name="avw_weakest_node_ln"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="avw_weakest_node_ln" class="form-control">
                            </div>
                        </div>
                        <div data-show="2,4" class="form-group  col-sm-1">
                            <label class="control-label"> Vd% </label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['AVD_LN'] : "" ?>" readonly
                                       name="avd_ln"
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="avd_ln" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div data-show="1,2" class="form-group  col-sm-1" data-type="mv-connection">
                            <label class="control-label"> KW Saving</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['KW_SAVING'] : "" ?>"
                                       name="kw_saving"
                                       readonly
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="kw_saving" class="form-control">
                            </div>
                        </div>
                        <div data-show="1,2" class="form-group  col-sm-1" data-type="mv-connection">
                            <label class="control-label"> KVar Saving</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['KVAR_SAVING'] : "" ?>"
                                       name="kvar_saving"
                                       readonly
                                       style="direction:ltr;"
                                       data-val-required="required"
                                       data-val="true"
                                       id="kvar_saving" class="form-control">
                            </div>
                        </div>
                        <hr/>
                        <div>
                            <div data-show="1,2" class="alert alert-warning">
                                Economic Feasibility
                            </div>
                            <div data-show="1,2" class="form-group  col-sm-1">
                                <label class="control-label"> Project Cost (NIS)</label>

                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['RECONDUCTORING_COST'] : "" ?>"
                                           name="reconductoring_cost"
                                           style="direction:ltr;"
                                           data-val-required="required"
                                           data-val="true"
                                           readonly
                                           id="reconductoring_cost" class="form-control">
                                </div>
                            </div>
                            <div data-show="1,2" class="form-group  col-sm-1">
                                <label class="control-label"> Electricity Hours/Day</label>

                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['ELECTRICITYH'] : "" ?>"
                                           name="electricityh"
                                           style="direction:ltr;"
                                           data-val-required="required"
                                           data-val="true"
                                           id="electricityh" class="form-control">
                                </div>
                            </div>
                            <div data-show="1,2" class="form-group  col-sm-2" data-show="1,2">
                                <label class="control-label">KW Saving Cost (NIS)</label>

                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['KWSAVING'] : "" ?>" readonly
                                           name="kwsaving"
                                           style="direction:ltr;"
                                           data-val-required="required"
                                           data-val="true"
                                           id="kwsaving" class="form-control">
                                </div>
                            </div>
                            <div data-show="1,2" class="form-group  col-sm-2">
                                <label class="control-label"> Payback Period (Month)</label>

                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['PAYBACK'] : "" ?>" readonly
                                           name="payback"
                                           style="direction:ltr;"

                                           data-val-required="required"
                                           data-val="true"
                                           id="payback" class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr/>
                    </div>

                    <div>
                        <hr>
                        <div data-show="5" class="form-group  col-sm-2">
                            <label class="control-label"> Loss-KW Cost (NIS)</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['LOSS_KW_COST'] : "" ?>"
                                       name="loss_kw_cost"
                                       readonly
                                       id="loss_kw_cost" class="form-control">
                            </div>
                        </div>

                        <div data-show="5" class="form-group  col-sm-2">
                            <label class="control-label"> Average K.W.H</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['AVERAGE_KWH'] : "" ?>"
                                       name="average_kwh"
                                       id="average_kwh" class="form-control">
                            </div>
                        </div>

                        <div data-show="5" class="form-group  col-sm-2">
                            <label class="control-label"> Cost of K.W.H</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['COST_KWH'] : "" ?>"
                                       name="cost_kwh"
                                       readonly
                                       id="cost_kwh" class="form-control">
                            </div>
                        </div>

                        <div data-show="5" class="form-group  col-sm-2">
                            <label class="control-label"> Net Profit (NIS) / Month</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['NET_PROFIT'] : "" ?>"
                                       name="net_profit"
                                       readonly
                                       id="net_profit" class="form-control">
                            </div>
                        </div>

                        <hr>
                    </div>

                    <div class="form-group  col-sm-12">
                        <label class="control-label"> Notes</label>

                        <div>
                            <textarea name="notes" id="notes"
                                      class="form-control"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                        </div>
                    </div>

                    <hr/>


                    <div style="clear: both;">

                        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['SER'], 'loadflow') : ''; ?>
                        <hr/>
                    </div>


                    <div class="alert alert-info">
                        Conductors Data & Real Measurements
                    </div>

                    <?php echo modules::run('technical/LoadFlow/public_get_tools', $HaveRs ? $rs['SER'] : 0); ?>


                </div>


                <div class="modal-footer">

                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                    <?php if ($HaveRs && $loadFlow && $loadFlowAccess): ?>
                        <button type="button" onclick="javascript:loadflow_adopt(1);" class="btn btn-success"> إعتمد
                        </button>

                    <?php endif ?>

                    <?php if ($HaveRs && !$loadFlow && $loadFlowAccess): ?>

                        <button type="button" onclick="javascript:loadflow_adopt(0);" class="btn btn-danger">إرجاع
                        </button>
                    <?php endif ?>

                </div>
        </div>
        </form>

    </div>
</div>
</div>


<?php

$projectTecUrl = base_url('projects/projects/publicGetProjectByTec');
$projectItemTecUrl = base_url('technical/LoadFlow/publicGetProjectItem');
$loadFlowAdopt = base_url('technical/LoadFlow/Adopt');

$PROJECT_TEC = $HaveRs ? $rs['PROJECT_TEC'] : '';

$script = <<<SCRIPT
 

<script>
    //get project details by technical code
    function getProjectDetials() {

        var project_tec = $('#project_tec').val().toLowerCase();
        if ( study_type == '' || project_tec == '') return;

        var study_type = $('#study_type').val();

        console.log('',study_type);
        console.log('',project_tec);

        if (

            !( project_tec.match("^it") && study_type == 1 && study_type != 3) &&
                !((project_tec.match("^ct") || project_tec.match("^t") || (project_tec.match("^it") && study_type != 1)) && study_type == 3) &&
                !(((project_tec.match("^r") &&  !project_tec.match("^rsp")) || project_tec.match("^pwbm ") || project_tec.match("^isp")) && study_type == 2) &&
                !((project_tec.match("^sp") || project_tec.match("^rsp")) && (study_type == 4 || study_type == 2))

            ) {

            alert('رقم المشروع غير صحيح');
            return;
        }

        get_data('{$projectTecUrl}', {project_tec: project_tec}, function (data) {

            $('#project_tec_name').val(data.PROJECT_NAME);
            $('#project_tec_branch').val(data.BRANCH_NAME);
            $('#project_tec_user').val(data.ENTRY_USER_NAME);
            $('#reconductoring_cost').val(data.TOTAL);
        });


    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                reload_Page();

            },'html');
        }
    });


    function reBind(){

        $('select[name="cross_section[]"]').change(function(){
            var amount = $(this).find(':selected').attr('data-amount');
            $(this).closest('tr').find('input[name="length[]"]').val(amount);
        });

    }

    //calc Vd% before reconductoring
    $('#vw_weakest_node').keyup(function(){
        var divideBy = $('#study_type').val() == 2 ||  $('#study_type').val() == 4 ? 0.4 : 22 ;
        $('#vd').val(((1-($(this).val()/divideBy)) * 100).toFixed(2));
    });

    //calc Vd% after reconductoring
    $('#avw_weakest_node').keyup(function(){

        var divideBy = $('#study_type').val() == 2 ||  $('#study_type').val() == 4 ? 0.4 : 22 ;

        $('#avd').val(((1-($(this).val()/divideBy)) * 100).toFixed(2));
    });


    //calc Vd% before reconductoring
    $('#vw_weakest_node_ln').keyup(function(){
        var divideBy = 0.231 ;
        $('#vd_ln').val(((1-($(this).val()/divideBy)) * 100).toFixed(2));
    });

    //calc Vd% after reconductoring
    $('#avw_weakest_node_ln').keyup(function(){

        var divideBy = 0.231 ;

        $('#avd_ln').val(((1-($(this).val()/divideBy)) * 100).toFixed(2));
    });

    $('#loss,#aloss').keyup(function(){

        $('#kw_saving').val((($('#loss').val()-$('#aloss').val())).toFixed(2));
    });


    $('#loss_var,#aloss_var').keyup(function(){

        $('#kvar_saving').val((($('#loss_var').val()-$('#aloss_var').val())).toFixed(2));
    });


    $('#kw_saving,#electricityh').keyup(function(){

        $('#kwsaving').val((($('#kw_saving').val()*$('#electricityh').val())*30*0.5).toFixed(2));
    });


    $('#reconductoring_cost,#kwsaving,#electricityh').keyup(function(){

         if($('#reconductoring_cost').val() > 0 && $('#kwsaving').val() > 0) {
             var val = (($('#reconductoring_cost').val()/$('#kwsaving').val()));
              $('#payback').val( CheckFinite(val) || isNaN(val) ?0 : val.toFixed(0));
        } else  $('#payback').val(0);
    });


    $('#study_type').change(function(){
        studyTypeChange();
    });

    function studyTypeChange(){

        var study_type = $('#study_type').val();

        $('div[data-show]').hide();
        
        if(study_type == 3 || study_type == 4){
             
            $('div[data-show="3,4"]').show();
        }

        if (study_type == 4){
            $('div[data-show="1,2,4"],div[data-show="4"],div[data-show="3,4"],div[data-show="2,4"]').show();
        }
        
        if (study_type == 2){
            $('div[data-show="2"],div[data-show="2,4"]').show();
        }
        
        if (study_type <= 2){
            $('div[data-show="1,2,4"],div[data-show="1,2"]').show();
        }
        
        
        lv_inputs(study_type == 2 || study_type == 4);
        rightSideCross(study_type == 3 || study_type == 4);

        if(study_type == 2 || study_type == 4){

            $('#txt_desc').val('');
            $('select[name="cross_section[]"] option[data-type="1"] , select[name="across_section[]"] option[data-type="1"] ').hide();
            $('select[name="cross_section[]"] option[data-type="2"] , select[name="across_section[]"] option[data-type="2"] ').show();

        }else {

            $('select[name="cross_section[]"] option[data-type="2"] , select[name="across_section[]"] option[data-type="2"] ').hide();
            $('select[name="cross_section[]"] option[data-type="1"] , select[name="across_section[]"] option[data-type="1"] ').show();

        }


    }

    function lv_inputs(show){

        if(show){
            $('input[name="v_ln[]"],input[name="av_ln[]"]').closest('td').show();
            $('th[data-type="ln-connection"]').show(); 

            $('#before_after #before').prop('colspan','6');
        }
        else {
            $('input[name="v_ln[]"],input[name="av_ln[]"]').closest('td').hide();
            $('th[data-type="ln-connection"]').hide();
           
            $('#before_after #before').prop('colspan','5');
        }

    }

    function rightSideCross(show){

        if(!show){
              $('th[data-hide="3,4"],td[data-hide="3,4"]').show();
        }else {
              $('th[data-hide="3,4"],td[data-hide="3,4"]').hide();
        }
    }

    studyTypeChange();

    $('#toolsTbl th , #loadflow_form .control-label').prop('dir','ltr');

    function loadflow_adopt (c){

            if( confirm('هل تريد إعتماد السند ؟!')){
                get_data('{$loadFlowAdopt}',{tec:'{$PROJECT_TEC}' , case : c},function(data){
                        if(data =='1'){
                            if(c == 0)
                                success_msg('رسالة','تم  إرجاع السند بنجاح ..');
                            else 
                                success_msg('رسالة','تم  اعتماد السند بنجاح ..');   
                            reload_Page();
                        }
                           
                    },'html');
            }

    }


    function delete_details(a,id,url){
        if(confirm('هل تريد حذف البند ؟!')){

            get_data(url,{id:id},function(data){
                if(data == '1'){
                    restInputs($(a).closest('tr'));
                }else{
                    danger_msg( 'تحذير','فشل في العملية');
                }
            },'html');
        }
    }
    
    
    
    
        $('#loss,#electricityh').keyup(calcLossKWCost);
        $('#loss,#electricityh').change(calcLossKWCost);

        $('#average_kwh').keyup(calcCostOfKWH);
        $('#average_kwh').change(calcCostOfKWH);

        $('#customers,#cost_kwh,#loss_kw_cost').keyup(calcNetProfit);
        $('#customers,#cost_kwh,#loss_kw_cost').change(calcNetProfit);

        $('#reconductoring_cost,#net_profit').keyup(calcPayback);
        $('#reconductoring_cost,#net_profit').change(calcPayback);
    

    function calcLossKWCost() {

      $('#loss_kw_cost').val( ($('#loss').val()*$('#electricityh').val()*30*0.5).toFixed(0));
        calcNetProfit();
    }
    
    function calcCostOfKWH() {
       $('#cost_kwh').val( ($('#average_kwh').val()*0.5).toFixed(0));
        calcNetProfit();
    }

    function calcNetProfit() {

       $('#net_profit').val( ($('#customers').val()*$('#cost_kwh').val()-$('#loss_kw_cost').val()).toFixed(0));
        calcPayback();
    }
    
    function calcPayback() {

      var val = ($('#reconductoring_cost').val()/$('#kwsaving').val());
        
      $('#payback').val( CheckFinite(val) || isNaN(val) ? 0:val.toFixed(0));
    }

    function CheckFinite(result) {
      return result == Number.POSITIVE_INFINITY || result == Number.NEGATIVE_INFINITY;
    }
</script>

SCRIPT;

sec_scripts($script);

?>

