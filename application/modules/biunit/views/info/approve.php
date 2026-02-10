
<form id="committe_add_edit" action="<?= base_url('biunit/Document/approve') ?>" method="POST">
<div class="card card-custom">

      <div class="card-header">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
            <h3 class="card-label">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:white!important;display: flex;padding-top: 20px!important;">
                        <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/index')?>"> الرئيسية </a></li>
                        <li class="breadcrumb-item active" aria-current="page"> اعتماد الوثائق </li>
                    </ol>
                </nav>
            </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <button class="btn btn-primary font-weight-bolder" id="accept" name="accept" onclick="acceptFunction();">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon--></span> اعتماد
            </button>

            <button class="btn btn-danger font-weight-bolder" id="reject"  name="reject" onclick="rejectFunction();" style="margin-right: 10px">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon--></span> رفض
            </button>
            <!--end::Button-->

        </div>

    </div>
      <div class="card-body">
        <!--begin: Search Form-->
        <br><br><br>

            <!--begin: Datatable-->
            <div class="table-responsive" style="margin-top: -100px;">
                <!--begin: Datatable-->
                <div id="kt_datatable3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-separate table-head-custom table-checkable dataTable no-footer" style="min-width: 800px; width: 1237px;"  role="grid">
                                <thead style="">
                                <tr role="row">
                                    <th style="width: 10px">
                                       <input type="checkbox" class="check" id="checkAll">
                                    </th>
                                    <th style=""> عنوان الوثيقة </th>
                                    <th style=""> تصنيف الوثيقة</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($documents as $key=>$row ){
                                    //  if ($row['CATEGORY_ID']==$_GET['id']){
                                    ?>

                                    <tr role="row" class="odd">
                                        <td>
                                            <input type="checkbox" class="check" name="DOC[<?= $row['ID'] ?>]">
                                            <input type="hidden" name="" >
                                        </td>
                                        <td style="font-family: 'Cairo', Helvetica; font-size: 1.275rem;color: #181c32;font-weight: 500;" class="bb tit_<?= $row['ID'] ?>" data-id="<?= $row['ID'] ?>" data-tit="<?= $row['DOC_NAME'] ?>">
                                            <i class="fa  fa-file-pdf" style="color: #ef0303"></i>
                                            <?php echo   modules::run('attachments/attachment/indexInline',$row['FILES_CODE'],'controller22',0); ?>

                                        </td>
                                        <td>
                                            <?= $row['CATEGORY_NAME'] ?>
                                        </td>
                                        <td></td>


                                    </tr>
                                <?php
                                }
                                ?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Datatable-->

      </div>

</div>
</form>


<style>
    .icon-file{
        font-style: normal;
        font-weight: 500;
        font-family: 'Cairo', Helvetica;
        font-size: 1.275rem;
        color: #181c32;
    }
</style>
<!-- bootstrap5 dataTables js cdn -->
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "pageLength": 10,
            info: false,
            "dom": 'rtip',
            "ordering": false,
            language: {
                searchPlaceholder: "Search records",
                search: ""}

        });
        $( "tr" ).each(function() {
            var id = $(this).children(".bb").attr('data-id');
            $(this).children().children().children('.icon-file').text($(".tit_"+id).attr('data-tit'));
        });

        $("#checkAll").click(function () {
            $(".check").prop('checked', $(this).prop('checked'));
        });


    });
    function acceptFunction(){
        $('#accept').val("1");
    }
    function rejectFunction(){
        $('#reject').val("1");
    }


</script>