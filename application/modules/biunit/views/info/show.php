

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
                <h3 class="card-label">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:white!important;display: flex;padding-top: 20px!important;">
                            <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/index')?>"> الرئيسية </a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $category[0]['CATEGORY_NAME'] ?> </li>
                        </ol>
                    </nav>
                </h3>
            </div>
            <?php if($category[0]['ID']!=1){ ?>
               <?php foreach ($user_permissions as $key=>$row ){
                        if($row['ROLE_ID']==$category[0]['ID'] || $row['ROLE_ID']==1){ ?>
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <a href="<?= base_url('/biunit/info/add?category_id='. $category[0]['ID']) ?>" class="btn btn-primary font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon--></span> اضافة وثيقة جديدة
                            </a>
                            <!--end::Button-->
                        </div>
                    <?php }
                     } ?>
            <?php } ?>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <br><br><br> <br>

            <form id="committe_add_edit" action="" method="">
                <!--begin: Datatable-->
                <div class="table-responsive" style="margin-top: -100px;">
                    <!--begin: Datatable-->
                    <div id="kt_datatable3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-separate table-head-custom table-checkable dataTable no-footer" style="min-width: 800px; width: 1237px;"  role="grid">
                                    <thead style="display: none">
                                    <tr role="row">
                                        <th></th>
                                        <?php foreach ($user_permissions as $key=>$row ){
                                        if($row['ROLE_ID']==$category[0]['ID'] || $row['ROLE_ID']==1){ ?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <?php }
                                        } ?>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php foreach ($documents as $key=>$row ){
                                          //  if ($row['CATEGORY_ID']==$_GET['id']){
                                    ?>
                                      <tr role="row" class="odd">
                                          <td style="width: 90%" class="bb tit_<?= $row['ID'] ?>" data-id="<?= $row['ID'] ?>" data-tit="<?= $row['DOC_NAME'] ?>">
                                              <i class="fa  fa-file-pdf" style="color: #ef0303"></i>
                                              <?php echo   modules::run('attachments/attachment/indexInline',$row['FILES_CODE'],'controller22',0); ?>

                                          </td>

                                               <td>
                                                   <?php if($row['IS_ACTIVE']==1){ ?>
                                                       <span class="badge badge-success" style="background-color: #0b8d1a; padding: 7px 10px;">فعال</span>
                                                   <?php }
                                                   else{ ?>
                                                       <span class="badge badge-danger" style="background-color: #ce0808; padding: 7px 8px;">غير فعال</span>
                                                   <?php } ?>
                                               </td>
                                               <td>
                                                   <?php if($row['IS_VALID']==1){ ?>
                                                       <span class="badge badge-success" style="background-color: #0b8d1a; padding: 7px 10px;">سارية المفعول</span>
                                                   <?php }
                                                   else{ ?>
                                                       <span class="badge badge-danger" style="background-color: #ce0808; padding: 7px 8px;">منتهية المفعول</span>
                                                   <?php } ?>
                                               </td>
                                               <td>
                                                   <?php if($row['IS_APPROVED']==1){ ?>
                                                       <span class="badge badge-success" style="background-color: #0b8d1a; padding: 7px 10px;">معتمد</span>
                                                       <?php
                                                   }
                                                   elseif($row['IS_APPROVED']==2){
                                                       ?>
                                                       <span class="badge badge-danger" style="background-color: #ce0808; padding: 7px 8px;">مرفوض</span>
                                                       <?php
                                                   }
                                                   else{ ?>
                                                       <span class="badge badge-secondary" style="padding: 7px 8px;">غير معتمد</span>
                                                   <?php  }  ?>
                                               </td>
                                               <td  style="width: 10%">
                                                   <a href="<?= base_url('/biunit/info/edit?id='.$row['ID'])?>" class="btn btn-sm btn-clean btn-icon" title="تعديل الوثيقة">
                                                       <i class="fa  fa-edit" style="font-size: 14px"></i>
                                                   </a>
                                                   <a href="" class="btn btn-sm btn-clean btn-icon" data-toggle="modal" data-target="<?= '#exampleModal'.$row['ID']?>" title="حذف الوثيقة">
                                                       <i class="fa  fa-trash" style="font-size: 14px"></i>
                                                   </a>
                                                   <!-- Delete Modal -->
                                                   <div class="modal fade" id="<?= 'exampleModal'.$row['ID']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                       <div class="modal-dialog" role="document">
                                                           <div class="modal-content">
                                                               <div class="modal-header">
                                                                   <h5 class="modal-title" id="exampleModalLabel">حذف الوثيقة</h5>
                                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                       <span aria-hidden="true">&times;</span>
                                                                   </button>
                                                               </div>
                                                               <div class="modal-body">
                                                                   هل تريد حذف الوثيقة "<?= $row['DOC_NAME'] ?> "؟
                                                               </div>
                                                               <div class="modal-footer">
                                                                   <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                                   <a href="<?= base_url('/biunit/Document/delete?id='.$row['ID'].'&category_id='.$row['CATEGORY_ID'])?>" class="btn btn-primary">تأكيد</a>
                                                               </div>
                                                           </div>
                                                       </div>
                                                   </div>

                                               </td>

                                      </tr>
                                    <?php  //  }
                                           }
                                    ?>






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
             language: {
                searchPlaceholder: "Search records",
                    search: ""}

            });

            $( "tr" ).each(function() {
               // $('.icon-file').text($('i.icon-file').parent().parent().attr('data-tit'));
                var id = $(this).children(".bb").attr('data-id');
                $(this).children().children().children('.icon-file').text($(".tit_"+id).attr('data-tit'));

//                $('.tit').children().children(".icon-file").text($(".icon-file").parent().parent().attr('data-tit'));
            });

                // $('.icon-file').text($('i.icon-file').parent().parent().attr('data-tit'));
                // $('head').append('<style>.icon-file:before{content:'+offers+';}</style>');
                // console.log(offers);



        });


    </script>


