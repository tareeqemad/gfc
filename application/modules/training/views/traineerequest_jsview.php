<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 11:33 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#traineeRequest_form'));
        $('.sel2').select2('val','');
        $('#person_div').hide();
        $("#company_div").hide();
    }

    $('#dp_trainee_type').on('change', function() {
        if (this.value == '1')
        {
            $("#company_div").show();
            $('#person_div').hide();
            $("#txt_id_number").val('');
            $("#txt_name").val('');
        }
        else if (this.value == '2')
        {
            $('#person_div').show();
            $("#company_div").hide();
            $("#txt_company_type").val('');
            $("#txt_company_name").val('');
            $("#txt_license_number").val('');
        }
        else{
            $('#person_div').hide();
            $("#company_div").hide();
        }
    });


    function values_search(add_page){
        var values=
        {page:1, trainee_type:$('#dp_trainee_type').val(), adver_id:$('#dp_advers').val(), license_number:$('#txt_license_number').val(),
            company_name:$('#txt_company_name').val(),company_type:$('#txt_company_type').val(),
            id_number:$('#txt_id_number').val(),name:$('#txt_name').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        if($('#dp_trainee_type').val() != ''){
            var values= values_search(1);
            get_data('<?= base_url('training/traineeRequest/get_page')?>',values ,function(data){
                $('#container').html(data);
            },'html');
        }else{
            alert("يجب تحديد الجهة المعنية");
        }
    }


    function assign_(type ,ser ){
        $('#myModal').modal();
        $('#txt_trainee_ser').val(ser);
        $('#txt_trainee_type').val(type);
    }

    function financialOffer(ser) {
        $('#ModalFin').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_financial_offer') ?>', {id: ser}, function (data) {
            $('#div_fin_offer').html(data);
        }, 'html');
    }

    function technicalOffer(ser) {
        $('#ModalTec').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_technical_offer') ?>', {id: ser}, function (data) {
            $('#div_tec_offer').html(data);
        }, 'html');
    }

    function qualifications(ser) {
        $('#ModalQual').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_ac_qualifications') ?>', {id: ser}, function (data) {
            $('#div_qual').html(data);
        }, 'html');
    }

    function practExper(ser) {
        $('#ModalExper').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_pract_exper') ?>', {id: ser}, function (data) {
            $('#div_exper').html(data);
        }, 'html');
    }


    function courses(ser) {
        $('#ModalCourses').modal();
        get_data('<?= base_url('training/traineeRequest/public_get_trainee_course') ?>', {id: ser}, function (data) {
            $('#div_courses').html(data);
        }, 'html');
    }




    function save_(id){

        var values=
        {page:1, trainee_type:$('#txt_trainee_type').val() ,
          trainee_ser:$('#txt_trainee_ser').val()
        , ser_course: id
        };

        get_data('<?= base_url('training/traineeRequest/updateCourse') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم اسناد المدرب للدورة');
                $('#myModal').modal('hide');

            }

        }, 'html');

    }

    function values_search_gedco(add_page){
        var values=
        {page:1,id_number:$('#txt_id_number').val(),name:$('#txt_name').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search_gedco(){
        var values= values_search_gedco(1);
        get_data('<?= base_url('training/traineeRequest/gedco_get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }



    function checkidno(idno){
        MultID  = [1,2,1,2,1,2,1,2];
        SumID=0;i=0;X=0;
        r = ""+idno;//.replace(/\s/g,'');
        intRegex = /^\d+$/;
        if (r.length==9 && (intRegex.test(r)) )
        {
            for (var i=0;i<MultID.length;i++)
            {
                x=MultID[i] * r[i];
                if (x>9)
                    x=parseInt(x.toString()[0]) + parseInt(x.toString()[1]);
                SumID=SumID+x;
            }
            if (SumID % 10 != 0)
                SumID = ( 10 * (Math.floor(SumID / 10) + 1 )) - SumID
            else
                SumID = 0;
            if (SumID == r[8])
                return true;
            else
                return false;
        }
        else
            return false;
    }





    $('#txt_id').on('change',function () {
        if(checkidno($('#txt_id').val()))
        {
            $.ajax({
                url:"http://192.168.0.171:801/apis/GetData/"+$('#txt_id').val(),
                type: "GET",
                data:{},
                dataType:'json',
                success: (function (data) {
                    $('#txt_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                    $('#txt_name_eng').val(data.DATA[0].ENG_NAME);
                }),
                error: (function (e) {
                    alert('ER');

                })
            });
        }
        else
        {
            toastr.error('ادخال خاطئ لرقم الهوية');
            $('#txt_name').val('');
            $('#txt_name_eng').val('');
            $('#txt_id').val('');

        }
    });









    //</script>
