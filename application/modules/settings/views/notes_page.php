<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 16/12/14
 * Time: 03:33 م
 */

$count = 0;
?>

<?php if(count($rows) > 0 ) : ?>
    <a href="javascript:;" onclick="javascript:$('#notes_pageModal').modal();" class="icon-btn">
        <i class="icon icon-comments"></i>
        <div>
            الملاحظات
        </div>
												<span class="badge badge-danger">
												<?= count($rows) ?> </span>
    </a>


    <div class="modal fade" id="notes_pageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> ملاحظات</h4>
                </div>

                <div class="modal-body">
                    <div class="tbl_container">
                        <table class="table" id="notesTbl" data-container="container">
                            <thead>
                            <tr>
                                <th style="width: 20px;"  > #</th>
                                <th style="width: 200px;" >المدخل</th>
                                <th style="width: 80px;" >التاريخ</th>
                                <th>الملاحظة  </th>

                            </tr>
                            </thead>
                            <tbody>



                            <?php foreach($rows as $row) :?>
                                <?php $count++; ?>
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>
                                        <?= $row['ENRTY_USER_NAME'] ?>
                                    </td>
                                    <td>
                                        <?= $row['ENTRY_DATE'] ?>
                                    </td>

                                    <td>  <?= $row['NOTES'] ?> </td>
                                </tr>
                            <?php endforeach;?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script>
        if (typeof initFunctions == 'function') {
            initFunctions();
        }


    </script>

<?php endif; ?>