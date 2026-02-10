/**
 * Created by mkilani on 22/10/14.
 */

var val=0;

function attachment_form(url){
    get_data(url,{n:1}, function(ret){
        $('#uploadModal .modal-body').html(ret);
    }, 'html');
}

$('#uploadModal :file').change(function(){
    val=0;
    var file = this.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    var types= ['application/msword','image/jpeg','image/tiff','image/png','image/gif','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/pdf','application/vnd.ms-outlook','application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation',''];
 
    if(!file)
        alert('اختر ملف لرفعه');
    else if(size > 1024*1024*150 ) // B
        alert('الحد الاقصى المسموح لحجم الملف هو 150 ميجا بايت');
    else if($.inArray(type,types)==-1)
        alert('غير مسموح بهذا النوع من الملفات');
    else
        val=1;
});

$('#uploadModal :submit').click(function(e){
    e.preventDefault();
    if(val){
        var form= $(this).closest('form');
        var form_index= $('form').index(form);
        var form_url= form.attr('action');
        var formData = new FormData($('form')[form_index]);

        $.ajax({
            url: form_url,
            type: 'POST',

            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },

            //Ajax events
            beforeSend: function() {
                $('#uploadModal #msg').html('يتم الان رفع الملف..');
                $('#progress').show();
                $('#uploadModal :file').attr('disabled','disabled');
                $('#uploadModal :submit').hide();
            },
            success: function(data){
                if(data == '1')
                    window.location.reload();
                else
                $('#uploadModal #msg').html(data);
            },
            error: function(data){
                $('#uploadModal #msg').text('لم يتم رفع الملف');
            },
            complete: function(){
                $('#progress').hide();
            },
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    }
});

function attachment_delete(url,id){
    var values= {id:id};
    if(confirm('هل تريد بالتأكيد حذف الملف ؟!!')){
        ajax_delete_any(url, values ,function(data){
            if(data==1){
                success_msg('رسالة','تم حذف الملف بنجاح ..');
                $('#uploadModal #attachments_tb #tr-'+id).hide();
            }else
                danger_msg( 'تحذير',data);
        });
    }
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}
