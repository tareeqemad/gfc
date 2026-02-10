<?php

?>

//<script>


$('.sel2').select2();
$('.result_emp').hide();
$('#result').hide();
$('#true').hide();
$('#false').hide();


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
			$('#result').hide();
			$('#true').hide();
			$('#false').hide();
        if(checkidno($('#txt_id').val()))
        {
            $.ajax({
                url:"https://im-server.gedco.ps:8001/apis/GetData/"+$('#txt_id').val(),
                type: "GET",
                data:{},
                dataType:'json',
                success: (function (data) {
                    $('#txt_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
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
            $('#txt_id').val('');

        }
    });
	
	



 $(document).ready(function() {
		$('button[data-action="submit"]').click(function(e){
			e.preventDefault();

			if(checkidno($('#txt_id').val()))
			{
				let mob = $('#txt_mobile').val();
				str = mob.substring(0, 3);
				var check_num_mob = isNaN($('#txt_mobile').val());
				if($('#txt_mobile').val().length == 10 && (str == '059' || str =='056') && check_num_mob == false ){
				  var form = $(this).closest('form');
					ajax_insert_update(form,function(data){
						if(parseInt(data)>=1){
							success_msg('رسالة','تم حفظ البيانات بنجاح ..');
							if(parseInt(data)==1){
								$('#result').show();
								$('#true').show();
								$('#false').hide();
								
							}else{
								$('#result').show();
								$('#true').hide();
								$('#false').show();
							}
							
						}else{
							danger_msg('تحذير..',data);
						}
					},'html');
				}else{
					danger_msg('تحذير..','يرجى التاكد من رقم المحمول');
				}
			}else{
				danger_msg('تحذير','يرجى التأكد من  رقم الهوية');
			}
		
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });




   });
   
	   // reformat date  IN= 08-MAY-12   OUT= 08/05/2012
	function reformat_date(date_in){
		 return formatDate(new Date(  getDateFromFormat(date_in,'dd-MMM-yy')  ),'dd/MM/yyyy') ;
	}
	
	
	
	function search(){
		var id = $('#txt_id').val();
		showLoading();
		$.ajax({
			url:"https://im-server.gedco.ps:8008/api/downloadData/covidResult",
			type: "POST",
			data:{patientId:id},
			dataType: 'JSON',
            success: (function (data) {
				$('#positive_tb tbody').empty();
				$('#negative_tb tbody').empty();
				$.each(data.data, function(i){
					$('.result_emp').show();
					if(data.data[i].TEST_RESULT == 'Negative'){
						var result = 'غير مصاب';
						var request_date = reformat_date(data.data[i].REQUEST_DATE);
						var resultt_date = reformat_date(data.data[i].RESULT_DATE);
						$('<tr><td class="d-none d-sm-table-cell"> ' +data.data[i].PATIENT_ID + '' +
                        '</td><td class="d-none d-sm-table-cell">' +data.data[i].PATIENT_NAME+ '</td>'+
                        '<td class="d-none d-md-table-cell">' +request_date+'</td>'+
                        '<td class="d-none d-md-table-cell">' +resultt_date+'</td>'+
						'<td  class="d-none d-sm-table-cell"><span class="badge badge-2">' +result+ '</span></td>  ' +
                        '</td>' +
                        '</tr>').appendTo('#negative_tb');
						
					}else{
						var result = 'مصاب / مخالط';
						var request_date = reformat_date(data.data[i].REQUEST_DATE);
						var resultt_date = reformat_date(data.data[i].RESULT_DATE);
						$('<tr><td class="d-none d-sm-table-cell"> ' +data.data[i].PATIENT_ID + '' +
                        '</td><td class="d-none d-sm-table-cell">' +data.data[i].PATIENT_NAME+ '</td>'+
                        '<td class="d-none d-md-table-cell">' +request_date+'</td>'+
                        '<td class="d-none d-md-table-cell">' +resultt_date+'</td>'+
						'<td  class="d-none d-sm-table-cell"><span class="badge badge-4">' +result+ '</span></td>  ' +
                        '</td>' +
                        '</tr>').appendTo('#positive_tb');
						
					}

				});


				
			}),error: (function (e) {
                    alert('حدث خطأ');
            }),
			complete: function() {
				HideLoading();
			}
		});	
	}


   
   
    $("#dp_emp_no").change(function () {
        var end = this.value;
		$('#txt_id').val(end);
    });
	
	$('#txt_id').keyup(function() { 
		$("#dp_emp_no").select2('val', 0);
    });



    //</script>
