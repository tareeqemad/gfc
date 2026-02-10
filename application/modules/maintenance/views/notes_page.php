<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 16/12/14
 * Time: 03:33 م
 */

$count = 0;
?>

<?php if (count($rows) > 0) : ?>
    <a href="javascript:;" onclick="javascript:$('#notes_pageModal').modal('show');" class="icon-btn">
        <i class="fa fa-comments"></i>
        <div>
            الملاحظات
        </div>
        <span class="badge bg-danger">
<?= count($rows) ?>
</span>

    </a>

    <!-- Modal -->
    <div class="modal fade" id="notes_pageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ملاحظات</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="notesTbl">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>المدخل</th>
                                <th>التاريخ</th>
                                <th>الملاحظة</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $row) : ?>
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
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>