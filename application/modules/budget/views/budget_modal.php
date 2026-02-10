<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 8:07 AM
 */
?>
<div class="modal fade" id="constantModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات نوع الموازنة</h4>
            </div>
            <form class="form-horizontal" id="constant_from" method="post" action="<?=base_url('budget/budget/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="form-group">
                    <label class="col-sm-3 control-label">الرقم</label>
                    <div class="col-sm-6">
                        <input type="text" readonly  name="t_ser"  id="txt_t_ser" class="form-control" >
                    </div>
                    </div>
                    <div class="form-group">

                        <label class="col-sm-3 control-label">نوع الموازنة </label>
                        <div class="col-sm-6">
                            <input type="hidden" name="level" id="txt_level" />
                            <input type="hidden" name="no" id="txt_no" />
                            <input type="text"  name="name" data-val="true"  data-val-required="حقل مطلوب"     id="txt_name" class="form-control" >
                            <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="chaptersModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الباب</h4>
            </div>
            <form class="form-horizontal" id="chapter_from" method="post" action="<?=base_url('budget/budget/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الباب </label>
                        <div class="col-sm-1">
                            <input type="text" readonly  name="t_ser"  id="txt_t_ser" class="form-control" >
                        </div>
                        <div class="col-sm-5">
                            <input type="hidden" name="level" id="txt_level" />
                            <input type="hidden" name="no" id="txt_no" />
                            <input type="text"  name="name" data-val="true"  data-val-required="حقل مطلوب"     id="txt_name" class="form-control" >
                            <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الترتيب</label>
                        <div class="col-sm-4">
                            <input type="text" name="ser"  id="txt_ser" class="form-control ltr">
                            <input type="hidden" name="type"  id="txt_type" class="form-control ltr">
                        </div>
                    </div>


               <!--     <div class="form-group">
                        <label class="col-sm-3 control-label">النوع</label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="type" id="rd_type_1" value="1">
                                    إيراد
                                </label>

                                <label>
                                    <input type="radio" name="type" id="rd_type_2" value="2">
                                    نفقة
                                </label>


                            </div>
                        </div>
                    </div>
-->


                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="sectionsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الفصل</h4>
            </div>
            <form class="form-horizontal" id="section_from" method="post" action="<?=base_url('budget/budget/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الباب </label>
                        <div class="col-sm-9">
                            <input type="hidden" name="level" id="txt_level" />
                            <input type="hidden" name="no" id="txt_no" />
                            <input type="hidden"  name="chapter_no" readonly id="txt_chapter_no" class="form-control" >
                            <input type="text"  name="chapter_no_name" readonly id="txt_chapter_no_name" class="form-control" >
                        </div>

                    </div>
					
					   <div class="form-group" >
                        <label class="col-sm-3 control-label">الحساب</label>
                        <div class="col-sm-3">
                            <input type="text" name="account_id"  id="h_txt_account_id"  readonly class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_account_id" class="form-control"  name="txt_account_id" /><!--onchange="alert($(this).val());$('#txt_name').val($(this).val()); return false;";--> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الفصل </label>
                        <div class="col-sm-3">
                            <input type="text" readonly  name="t_ser"  id="txt_t_ser" class="form-control" >
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="name"   id="txt_name" class="form-control" readonly ><!--data-val="true"  data-val-required="حقل مطلوب" -->
                         <!--   <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>-->
                        </div>

                    </div>
					
					  


                    <div class="form-group">
                        <label class="col-sm-3 control-label"> نوع التشغيل </label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="oper_type" id="rd_oper_type_1" value="1">
                                    رأسمالية
                                </label>

                                <label>
                                    <input type="radio" name="oper_type" id="rd_oper_type_2" value="2">
                                    تشغيلية
                                </label>


                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> نشاط شركة  </label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio"  name="activity_type" id="rd_activity_type_1" value="1">
                                    نقدي
                                </label>

                                <label>
                                    <input type="radio" name="activity_type" id="rd_activity_type_2" value="2">
                                    غير نقدي
                                </label>

                                <label>
                                    <input type="radio" name="activity_type" id="rd_activity_type_3" value="3">
                                    تحصيلات فاتورة كهرباء
                                </label>

                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">منح و هيبات</label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="grant_type" id="rd_grant_type_1" value="2">
                                    نقدي
                                </label>

                                <label>
                                    <input type="radio" name="grant_type" id="rd_grant_type_2" value="1">
                                    غير نقدي
                                </label>


                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">نوع الموازنة</label>
                        <div class="col-sm-9">
                            <select name="budget_ttype" id="txt_budget_ttype" class="form-control" multiple dir="rtl" >
                                <?php foreach($BUDGET_TYPES as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="budget_type" id="txt_budget_type" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">مصادر القيود المالية </label>
                        <div class="col-sm-9">
                            <select name="chain_ttype" id="txt_chain_ttype" class="form-control" multiple dir="rtl" >
                                <?php foreach($CHAIN_TYPES as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="chain_type"  id="txt_chain_type" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم الاستحقاق في الرواتب </label>
                        <div class="col-sm-4">
                            <input type="text" name="con_no"  id="txt_con_no" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">  مركز المسئولية   </label>
                        <div class="col-sm-5">
                            <input name="competent_side" id="dp_gcc_st_no" class="form-control easyui-combotree" data-options="url:'<?= base_url('settings/gcc_structure/public_get_structure_json')?>',method:'get',animate:true,lines:true,required:true"/>
                        </div>

                    </div>

               
                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="itemsModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title"> بيانات البند</h4>
</div>

<!--   <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
       <li class="active"><a href="#main-data" data-toggle="tab">البيانات الأساسية</a></li>
       <li><a href="#details" data-toggle="tab">التفصيل</a></li>

   </ul>-->
<div id="my-tab-content" class="tab-content">
<div class="tab-pane active" id="main-data">
    <form class="form-horizontal" id="item_from" method="post" action="<?=base_url('budget/budget/create')?>" role="form" novalidate="novalidate">
        <div class="modal-body">

            <div class="form-group">
                <label class="col-sm-3 control-label">الفصل </label>
                <div class="col-sm-7">
                    <input type="text"  name="section_no_name" readonly id="txt_section_no_name" class="form-control">
                    <input type="hidden" name="level" id="txt_level" />
                    <input type="hidden" name="no" id="txt_no" />
                    <input type="hidden"  name="section_no" readonly id="txt_section_no" class="form-control ltr" >
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">البند </label>
                <div class="col-sm-1">
                    <input type="text" readonly name="t_ser"   id="txt_t_ser" class="form-control ">

                </div>
                <div class="col-sm-5">
                    <input type="text" name="name" data-val="true"  data-val-required="حقل مطلوب"   id="txt_name" class="form-control ">
                    <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                </div>
                    <div  class="form-control col-sm-1" id="item_no">


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">سعر الصنف </label>
                <div class="col-sm-2">
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="price" id="txt_price" class="form-control">
                    <span class="field-validation-valid" data-valmsg-for="price" data-valmsg-replace="true"></span>
                </div>

                <label class="col-sm-3 control-label">الوحدة </label>
                <div class="col-sm-2">
                    <select type="text"   name="unit_no" id="dp_unit_no" class="form-control" >
                        <?php foreach($UNIT_NOS as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                  <span class="field-validation-valid" data-valmsg-for="unit_no" data-valmsg-replace="true"></span>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">صنف خاص؟</label>
                <div class="col-sm-9">
                    <div class="radio">
                        <label> <input type="radio" name="special" id="rd_special_1" value="1">
                            نعم
                        </label>

                        <label>
                            <input type="radio" name="special" id="rd_special_2" value="2">
                            لا
                        </label>


                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">له تفاصيل </label>
                <div class="col-sm-9">
                    <div class="radio">
                        <label> <input type="radio" name="has_details" id="rd_has_details_1" value="1">
                            نعم
                        </label>

                        <label>
                            <input type="radio" name="has_details" id="rd_has_details_2" value="2">
                            لا
                        </label>


                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">له بيانات تاريخية</label>
                <div class="col-sm-9">
                    <div class="radio">
                        <label> <input type="radio" name="has_history" id="rd_has_history_1" value="1">
                            نعم
                        </label>

                        <label>
                            <input type="radio" name="has_history" id="rd_has_history_2" value="2">
                            لا
                        </label>


                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">رقم الثابت </label>
                <div class="col-sm-4">
                    <input type="text"  name="con_id" id="txt_con_id" class="form-control">

                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">نوع البند  </label>
                <div class="col-sm-4">
                    <select type="text"   name="item_type" id="dp_item_type" class="form-control" >

                        <?php foreach($ITEM_TYPE as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">رقم البند</label>
                <div class="col-sm-7">
                    <input class="form-control col-sm-4"  type="text" id="h_txt_d_class_id" name="item_no">
                    <input  class="form-control col-sm-8"  readonly type="text" id="txt_d_class_id"  >

                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">حالة البند   </label>
                <div class="col-sm-3">
                    <select type="text"   name="active" id="dp_active" class="form-control" >

                        <option  value="1">فعال</option>
                        <option  value="0">غير فعال</option>
                    </select>

                </div>
            </div>


        </div>
        <div class="modal-footer">
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

        </div>
    </form>
</div>
<!--      <div class="tab-pane" id="details">

          <div class="form-horizontal" id="item_details_from">
              <div class="modal-body">

                  <table class="table" id="tbl_items_details">
                      <thead>
                      <tr>
                          <th style="width: 20px;">#</th>
                          <th style="width: 180px;">رقم البند الخاص</th>
                          <th style="width: 150px;">العدد</th>
                          <th></th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>



                          <td><input type="hidden" name="level" value="4"/></td>

                          <td>

                              <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="special_item_no" id="txt_special_item_no" class="form-control">
                              <span class="field-validation-valid" data-valmsg-for="special_item_no" data-valmsg-replace="true"></span>
                          </td>
                          <td>


                              <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="ccount" id="txt_ccount" class="form-control">
                              <span class="field-validation-valid" data-valmsg-for="ccount" data-valmsg-replace="true"></span>

                          </td>
                          <td>

                              <a href="javascript:;" class="btn green" onclick="javascript:create_item_details(this)">حفظ</a>

                          </td>




                      </tr>



                      </tbody>

                  </table>

              </div>
          </div>

          <div class="modal-footer">
              <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

          </div>
          </form>
      </div>-->
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->