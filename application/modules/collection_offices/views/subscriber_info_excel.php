<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 03/03/20
 * Time: 10:22 ص
 */



$MODULE_NAME= 'collection_offices';
$TB_NAME= 'subscriber_info';
$showExcel= base_url('collection_offices/subscriber_info/showImport');
$page=1;
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
echo AntiForgeryToken();

?>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <style>

        .fileuploader{
            position: relative;
            width: 100%;
            height: 200px;
            border: 1px solid #e9e9e9;
        }
        .fileuploader #upload-label{
            background: #2196F3;
            color: #fff;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            padding: 16px;
            position: absolute;
            top: 45%;
            left: 0;
            right: 0;
            margin-right: auto;
            margin-left: auto;
            min-width: 20%;
            text-align: center;
            padding-top: 40px;
            transition: 0.8s all;
            -webkit-transition: 0.8s all;
            -moz-transition: 0.8s all;
            cursor: pointer;
        }
        .fileuploader.active{
            background: #2196F3;
        }
        .fileuploader.active #upload-label{
            color: #2196F3;
        }
        .fileuploader #upload-label span.title{
            font-size: 1.1em;
            font-weight: bold;
            display: block;
        }
        .fileuploader #upload-label i{
            text-align: center;
            display: block;
            background: white;
            color: #2196F3;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            border-radius: 100%;
            width: 80px;
            height: 80px;
            font-size: 60px;
            padding-top: 10px;
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            margin-right: auto;
            margin-left: auto;
        }
        /** Preview of collections of uploaded documents **/
        .preview-container{
            position: fixed;
            right: 10px;
            bottom: 0px;
            width: 300px;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            visibility: hidden;
        }
        .preview-container #previews{
            max-height: 400px;
            overflow: auto;
        }
        .preview-container #previews .zdrop-info{
            width: 88%;
            margin-right: 2%;
        }
        .preview-container #previews.collection{
            margin: 0;
        }
        .preview-container #previews.collection .actions a{
            width: 1.5em;
            height: 1.5em;
            line-height: 1;
        }
        .preview-container #previews.collection .actions a i{
            font-size: 1em;
            line-height: 1.6;
        }
        .preview-container .header{
            background: #2196F3;
            padding: 8px;
        }
        .preview-container .header i{
            float: right;
            cursor: pointer;
        }
    </style>
</head>
    <script> var show_page=true; </script>
    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-horizontal" id="import_form" method="post"
                  enctype="multipart/form-data" role="form" novalidate="novalidate">

                <fieldset  class="field_set">
                    <legend ><?=$title?></legend>
                    <!--<input type="file" name="file"  id="file" required accept=".xls, .xlsx" /></p> -->
                    <div id="zdrop" class="fileuploader ">
                        <div id="upload-label" style="width: 100px;">
                            <i class="material-icons">cloud_upload</i>
                            <span class="title"> </span>
                            <span><input type="file" name="file"  id="file" required accept=".xls, .xlsx" /><span/>

                        </div>
                    </div>


                </fieldset>
                <div class="modal-footer">
                    <input type="submit" disabled name="import" value="عرض البيانات" class="btn btn-info" />
                    <button type="button"  id="btn_adopt"  class="btn btn-primary"> اعتماد</button>
                </div>
            </form>





            <div id="msg_container"></div>

            <div id="container">


            </div>
        </div>

    </div>


<div id="Details" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="title" class="modal-title">بيانات الكشف</h3>
            </div>
            <form class="form-horizontal" id="sub_form" method="post" action="<?= $adopt_url ?>" role="form" >
                <div class="modal-body">
                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" name="subs_no" id="h_txt_subs_no">
                            <input type="hidden" name="count_sub" id="h_txt_count_sub">
                            <br>
                            <label class="col-sm-2 control-label">رقم الكشف</label>
                            <div class="col-sm-3">
                                <input type="text"
                                       data-val="true"
                                       readonly
                                       name="disclosure_ser"
                                       id="txt_disclosure_ser"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">تاريخ فتح الكشف</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       data-val="true"
                                       readonly
                                       value="<?php echo date("d/m/Y");?>"
                                       name="disclosure_open_date"
                                       id="txt_disclosure_open_date"
                                       class="form-control">
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">تاريخ اغلاق الكشف</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       data-val="true"
                                       readonly
                                       value="<?php echo date('d/m/Y', strtotime('+1 month'));?>"
                                       name="disclosure_close_date"
                                       id="txt_disclosure_close_date"
                                       class="form-control">
                            </div>
                        </div>



                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $('.sel2').select2();
    $('#btn_adopt').hide();

        $('input:file').change(
            function(){
                if ($(this).val()) {
                    $('input:submit').attr('disabled',false);
                }
            }
        );


    $('#btn_adopt').click(function(e){
        e.preventDefault();
        var x='0/0';
            var count = 0;
            var tbl = '#page_tb';
            var container = $('#' + $(tbl).attr('data-container'));
            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                x = x+','+$(this).val();
                count++;
            });




            $('#h_txt_subs_no').val(x);
            $('#h_txt_count_sub').val(count);

           if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $('#sub_form');
            ajax_insert_update(form,function(data){
            console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم الاعتماد بنجاح');
                     $('#Details').modal();
                    $('#txt_disclosure_ser').val(parseInt(data));

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('#btn_adopt').removeAttr('disabled');
        }, 3000);

        $('#btn_adopt').hide();
        $('input:submit').attr('disabled',true);

    });

    $('#import_form').on('submit', function(event){
            event.preventDefault();

            $.ajax({
                url:"{$showExcel}",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success:function(data){
                    $('#container').html(data);
                }
            })

            $('#btn_adopt').show();
    });


</script>

SCRIPT;

sec_scripts($scripts);

?>

