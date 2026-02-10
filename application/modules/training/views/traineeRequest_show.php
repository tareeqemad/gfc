<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 11:33 ص
 */


$MODULE_NAME = 'training';
$TB_NAME = 'traineeRequest';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$TRAIN_AC_QUALIFICATIONS = 'public_get_ac_qualifications';
$TRAIN_FINANCIAL_OFFER = 'public_get_financial_offer';
$TRAIN_TECHNICAL_OFFER = 'public_get_technical_offer';
$TRAIN_PRACTICAL_EXPERIENCES = 'public_get_pract_exper';
$TRAIN_TRAINEE_DETAILS = 'public_get_trainee_details';
$TRAIN_TRAINEE_COURSES = 'public_get_trainee_course';
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url = base_url("$MODULE_NAME/$TB_NAME/unadopt");
$post_url = base_url("$MODULE_NAME/$TB_NAME/updateCourse");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;


?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

        </div>

    </div>
<?php if($HaveRs == false){ ?>
    <div class="form-body">
        <div id="container">
            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post"  role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <fieldset class="field_set">
                        <legend>السيرة الذاتية</legend>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">لم يتم تعبئة السيرة الذاتية .. </label>
                        </div>
                    </fieldset>



                </div>

            </form>


        </div>
    </div>

  <?php  } else { ?>

    <?php if($type == 1){ ?>

        <div class="form-body">

            <div id="container">
                <form class="form-horizontal"id="<?= $TB_NAME ?>_form" method="post"  role="form"
                      novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <fieldset class="field_set">
                            <legend>بيانات الشركة</legend>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">رقم المسلسل</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">رقم الترخيص</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="license_number"
                                           value="<?= $HaveRs ? $rs['LICENSE_NUM'] : "" ?>"
                                           id="txt_license_number" class="form-control">
                                </div>

                                <label class="col-sm-1 control-label">اسم الشركة</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="company_name"
                                           value="<?= $HaveRs ? $rs['COMPANY_NAME'] : "" ?>"
                                           id="txt_company_name" class="form-control">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">نوع الشركة</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="company_type"
                                           value="<?= $HaveRs ? $rs['COMPANY_TYPE'] : "" ?>"
                                           id="txt_company_type" class="form-control">
                                </div>

                                <label class="col-sm-1 control-label">اسم المفوض</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="owner_company_name"
                                           value="<?= $HaveRs ? $rs['OWNER_NAME'] : "" ?>"
                                           id="txt_owner_company_name" class="form-control">
                                </div>

                            </div>

                            <br>

                            <div class="form-group">
                                <fieldset class="field_set"  >
                                    <legend >المرشحين من الشركة</legend>
                                    <div class="form-group">
                                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$TRAIN_TRAINEE_DETAILS",
                                            $HaveRs ? $rs['SER'] : 0  ); ?>
                                    </div>
                                </fieldset>
                            </div>

                            <!-- <div class="modal-footer">
                                <?php
                                if (  HaveAccess($post_url)   ) : ?>
                                    <button type="button" onclick="javascript:assign_(1,'<?=$rs['SER']?>');" class="btn btn-primary">اسناد لدورة</button>
                                <?php  endif; ?>
                            </div> -->

                        </fieldset>



                    </div>

                </form>

            </div>
        </div>



    <?php } else { ?>


        <div class="form-body">

            <div id="container">
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post"  role="form"
                      novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <fieldset class="field_set">
                            <legend>البيانات الشخصية</legend>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">رقم المسلسل</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">رقم الهوية</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="id_number" value="<?= $HaveRs ? $rs['ID'] : "" ?>" id="txt_id_number" class="form-control">
                                </div>

                                <label class="col-sm-1 control-label">الاسم</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="name" value="<?= $HaveRs ? $rs['NAME'] : "" ?>" id="txt_name" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-1 control-label">تاريخ الميلاد</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="birth_date" value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>" id="txt_birth_date" class="form-control">
                                </div>

                                <label class="col-sm-1 control-label">الهاتف</label>
                                <div class="col-sm-2">
                                    <input type="text" readonly name="phone" value="<?= $HaveRs ? $rs['MOBILE'] : "" ?>" id="txt_phone" class="form-control">
                                    <br>
                                </div>

                            </div>


                            <?php if($type == 2 &&  $check != 0 ) { ?>

                                <div class="modal-footer">
                                    <button type="button" onclick="javascript:financialOffer(<?=$rs['SER'] ?>)"  class="btn btn-success"> العرض المالي</button>
                                    <button type="button" onclick="javascript:technicalOffer(<?=$rs['SER'] ?>)" class="btn btn-primary"> العرض الفني</button>
                                    <button type="button" onclick="javascript:qualifications(<?=$rs['ID'] ?>)" class="btn btn-warning">المؤهلات العلمية</button>
                                    <button type="button" onclick="javascript:practExper(<?=$rs['ID'] ?>)" class="btn btn-info">الخبرات العملية</button>
                                    <button type="button" onclick="javascript:courses(<?=$rs['ID'] ?>)" class="btn btn-danger">الدورات</button>
                                </div>


                            <?php } ?>



                            <?php if($type == 0 || $check == 0) { ?>

                                <div class="modal-footer">
                                    <button type="button" onclick="javascript:qualifications(<?=$rs['ID'] ?>)" class="btn btn-warning">المؤهلات العلمية</button>
                                    <button type="button" onclick="javascript:practExper(<?=$rs['ID'] ?>)" class="btn btn-info">الخبرات العملية</button>
                                    <button type="button" onclick="javascript:courses(<?=$rs['ID'] ?>)" class="btn btn-danger">الدورات</button>
                                </div>

                            <?php } ?>

                        </fieldset>

                    </div>

                </form>



            </div>
        </div>


    <?php } ?>


<?php } ?>

    <div class="modal fade" id="ModalFin">
        <div id="ModalFin" role="dialog">
            <div class="modal-dialog"  style="width: 900px">

                <div  class="modal-content">
                    <div class="modal-header">
                        <h4 id="title" class="modal-title">العرض المالي</h4>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row" id="div_fin_offer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalTec">
        <div id="ModalTec" role="dialog">
            <div class="modal-dialog"  style="width: 900px">

                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row" id="div_tec_offer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalQual">
        <div id="ModalQual" role="dialog">
            <div class="modal-dialog"  style="width: 900px">

                <div  class="modal-content">
                    <div class="modal-header">
                        <h4 id="title" class="modal-title">المؤهلات العلمية</h4>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row" id="div_qual">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalExper">
        <div id="ModalExper" role="dialog">
            <div class="modal-dialog"  style="width: 900px">

                <div  class="modal-content">
                    <div class="modal-header">
                        <h4 id="title" class="modal-title">الخبرات العملية</h4>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row" id="div_exper">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCourses">
        <div id="ModalCourses" role="dialog">
            <div class="modal-dialog" style="width: 900px">

                <div  class="modal-content">
                    <div class="modal-header">
                        <h4 id="title" class="modal-title">الدورات </h4>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="row" id="div_courses">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

$('.sel2').select2();


   });

</script>

SCRIPT;
sec_scripts($scripts);
?>