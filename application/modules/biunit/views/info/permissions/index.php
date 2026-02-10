

<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
            <h3 class="card-label">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:white!important;display: flex;padding-top: 20px!important;">
                        <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/index')?>"> الرئيسية </a></li>
                        <li class="breadcrumb-item active" aria-current="page"> صلاحيات المستخدمين </li>
                    </ol>
                </nav>
            </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <a href="<?=base_url('/biunit/info/add_permission')?>" class="btn btn-primary font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon--></span> اضافة صلاحية جديدة
            </a>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Search Form-->
        <br><br><br> <br> <br><br>

        <form id="committe_add_edit" action="" method="">
            <!--begin: Datatable-->
            <div class="table-responsive" style="margin-top: -100px;">
                <!--begin: Datatable-->
                <div id="kt_datatable3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-separate table-head-custom table-checkable dataTable no-footer" style="min-width: 800px; width: 1237px;"  role="grid">
                                <thead style="">
                                <tr role="row">
                                    <th>اسم المستخدم</th>
                                    <th> اسم الصلاحية</th>
                                    <th>إضافة</th>
                                    <th>تعديل</th>
                                    <th>حذف</th>
                                    <th>تاريخ الانشاء</th>
                                    <th>منشأ الصلاحية</th>
                                    <th>الإجراءات</th>
                                </tr>
                                </thead>
                                <tbody>

                            <?php foreach ($permissions as $key=>$row ){ ?>
                                <tr role="row" class="odd">
                                    <td style="">
                                        <a style="font-family: 'Cairo', Helvetica; font-size: 16px;color: #181c32;font-weight: 500;">
                                           <?= $row['USER_NAME'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a style="font-family: 'Cairo', Helvetica; font-size: 16px;color: #181c32;font-weight: 500;">
                                            <?= $row['NAME'] ?>
                                        </a>
                                    </td>
                                    <td><input type="checkbox"   <?= $row['ADD_R']==1?'checked':'' ?>  disabled> </td>
                                    <td><input type="checkbox"   <?= $row['EDIT_R']==1?'checked':'' ?>  disabled> </td>
                                    <td><input type="checkbox"   <?= $row['DELETE_R']==1?'checked':'' ?>  disabled> </td>
                                    <td><a href="" style="font-family: 'Cairo', Helvetica; font-size: 16px;color: #181c32;font-weight: 500;">
                                            <?= $row['CREATED_DATE'] ?>
                                        </a>
                                    </td>
                                    <td><a href="" style="font-family: 'Cairo', Helvetica; font-size: 16px;color: #181c32;font-weight: 500;">
                                            <?= $row['CREATED_BY'] ?>
                                        </a>
                                    </td>

                                    <td  style="width: 10%">
                                        <a href="" class="btn btn-sm btn-clean btn-icon" data-toggle="modal" data-target="<?= '#exampleModal'.$row['ID']?>" title="حذف الصلاحية">
                                            <i class="fa  fa-trash" style="font-size: 14px"></i>
                                        </a>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="<?= 'exampleModal'.$row['ID']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الصلاحية</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        هل تريد حذف الصلاحية؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <a href="<?= base_url('/biunit/Permission/delete?id='.$row['ID'])?>" class="btn btn-primary">تأكيد</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                            <?php  } ?>





                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Datatable-->
        </form>
    </div>
</div>




<!-- bootstrap5 dataTables js cdn -->
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "pageLength": 10,
            info: true,
            "dom": 'rtip',
            "ordering": false,
            language: {
                searchPlaceholder: "Search records"
               }

        });
    });
</script>