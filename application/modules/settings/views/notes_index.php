<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/01/15
 * Time: 09:56 ص
 */
?>

<div class="modal fade" id="notesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> ملاحظات</h4>
            </div>
            <div id="msg_container_alt"></div>

            <div class="form-group col-sm-12">

                <div class="">
                    <textarea type="text" data-val="true" rows="5"    id="txt_g_notes" class="form-control "></textarea>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button"  onclick="javascript:apply_action();" class="btn btn-primary">حفظ البيانات</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
