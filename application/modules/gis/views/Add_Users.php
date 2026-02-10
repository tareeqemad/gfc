<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 11/02/19
 * Time: 12:26 م
 */
$MODULE_NAME= 'gis';
$TB_NAME="User_Controller";
$status_url=base_url("$MODULE_NAME/$TB_NAME/status");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
//$count = $offset;
?>
<style>
    body {}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */


    }

    /* Modal Content */
    .modal-content {
        background-color: #b2cde3;
        margin: auto;
        padding:50px;
        border: 2px solid #99a3e3;
        width: 60%;

    }
    h2{
        color: #0000ff;
        text-align: center;
       font-family: bold;
    }

    /* The Close Button */
    .close {

        float: left;
        font-size: 50px;
        font-weight: bold;
        color: #f20d0d ;
    }

    .close:hover,
    .close:focus {
        color: #f20d0d;
        text-decoration: none;

    }
    #myBtn{
        background-color: #2493EB;
        color: #e9ebf2;
        height: 40px;

    }
    .control-labell{
        color: #4974ff;
        text-align: center;
        font-size: 15px;
    }
</style>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>

            <!----------------------------------------------->
                <li><button id="myBtn">Add New Users </button></li>
            <!-- The Modal -->
            <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">

                    <span class="close">&times;</span>
                    <p><h2>Add New User</h2></p><hr>
                    <form class="form-vertical" id="<?=@$TB_NAME?>_form" method="post" action="<?=@$post_url?>" role="form" novalidate="novalidate">
                        <div class="modal-body inline_form">
                            <!------------------------------------------------------------------->
                            <div class="form-group col-sm-3">
                                <label class="control-labell"> USER_ID</label>
                                <div>
                                    <input type="text" value="" name="USER_ID_1" placeholder="USER_ID" id="txt_USER_ID" class="form-control"
                                           >
                                </div>
                            </div>

                            <!------------------------------------------------------------------->
                            <div class="form-group col-sm-3">
                                <label class="control-labell"> PassWord</label>
                                <div>
                                    <input type="text" value="" name="PASS_WORD_1" placeholder="password" id="txt_PassWord" class="form-control"
                                          >
                                </div>
                            </div>
                            <!------------------------------------------------------------->
                            <div class="form-group col-sm-3">
                                <label class="control-labell">UserName</label>
                                <div>
                                    <input type="text" name="USER_NAME_1" placeholder="username" id="txt_UserName" class="form-control"
                                           />
                                </div>
                            </div>
                            <br>
                            <!------------------------------------------------------->

                            <div class="modal-footer">

                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            </div>






            </div>

            <script>
                // Get the modal
                var modal = document.getElementById('myModal');

                // Get the button that opens the modal
                var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks the button, open the modal
                btn.onclick = function() {
                    modal.style.display = "block";
                }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>

<!------------------------------------------------------------------>



    </div>
</div>
<li><a  href="<?= @$back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>
        </div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?=@$TB_NAME?>_form" method="post" action="<?=@$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <!------------------------------------------------------------------->


               <!-- <div class="form-group col-sm-2">
                    <label class="control-label"> PassWord</label>
                    <div>
                        <input type="password" name="PASS_WORD"placeholder="password" id="txt_PassWord" class="form-control"
                               value="<?php echo @$rs['PASS_WORD'] ;?>">
                    </div>
                </div>
                <!------------------------------------------------------------->

                <div class="form-group col-sm-3">
                    <label class="control-label"><h4>UserName</h4></label>
                    <div>
                        <input  type="text"   name="USER_NAME" placeholder="USERNAME" id="txt_USER_NAME" class="form-control"
                               value="<?php echo @$rs['USER_NAME'] ;?>"/>
                    </div>
                </div>

<!----------------------------------------------------------------------------->
            </div>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            </div>


            <!------------------------------------------------------------------------>
        </form>
        <div id="msg_container"> </div>
        <div id="container">
            <?=modules::run($get_page_url,$page,$USER_NAME); ?>
        </div>
        <!-------------------------------------------------------------------------->
    </div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


 function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

 function values_search(add_page){
        var values=
        {page:1, USER_NAME:$('#txt_USER_NAME').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

  function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{

                console.log(data);
                //alert();
                    //  danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });




function return_adopt (User_id,type){

alert(User_id);

                if(type == 10){

					   get_data('{$adopt_url}',{ID:User_id},function(data){

           if(data =='1')
           {
                            success_msg('رسالة','تم اعتماد بنجاح ..');
                          reload_Page();
                          }
                    },'html');


				                }
                            if(type == 20){
                    get_data('{$un_adopt_url }',{ID:User_id},function(data){
                    if(data =='1')
                    {
                            success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                            reload_Page();
                            }
                    },'html');

                    }
                }





</script>
SCRIPT;
sec_scripts($scripts);
?>


