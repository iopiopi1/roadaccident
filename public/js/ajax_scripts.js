/**
 * Created by Ярослав on 28.02.2016.
 */
$(document).ready(function(){
    $('#user').submit(function(event){
		$('#warning_alert_mailsent').css({display:'none'});
		$('#warning_alert_username').css({display:'none'});
		$('#warning_alert_email').css({display:'none'});
        event.preventDefault();
		var pass1 = $('#password').val();
		var pass2 = $('#password_repeat').val();

		if(pass1 !== pass2){
			$('#warning_alert_login').css({display:'block'});
			return false;
		}
		if($('#agreementCheck').prop('checked') == false){
			$('#warning_alert_agreement').css({display:'block'});
			return false;
		}
		
        var userData = $('#user').serialize();
        $.ajax({
            url: baseUrl + "user/registerajax",
            method: "POST",
            data: userData,
            success: function (result) {
				if(result.state == 'success'){
					//window.location.href = baseUrl + "index";
					$('#warning_alert_mailsent').css({display:'block'});
				}
				else if(result.errNumber == 2){
					$('#warning_alert_username').css({display:'block'});
					return false;
				}
				else if(result.errNumber == 3){
					$('#warning_alert_email').css({display:'block'});
					return false;
				}
            }
        });
    });
	
	$('#agreementCheck').click(function(){
		if($('#agreementCheck').prop('checked') == true){
			$('#warning_alert_agreement').css({display:'none'});
		}
	});

    $('#vehicleaddsubmitbutton').click(function(event){
        event.preventDefault();
        var vehicleData = $('#vehicle').serialize();
        var photoData = $('#fileupload').serialize();
		console.log(vehicleData);
		console.log(photoData);
		if($('#vehicle_search_id').val() !== '' && $('#vehicleaddregnumId').val() !== '' && ($('.files img').length > 0 || $('div#links img').length > 0 )){
			$.ajax({
				url: "/vehicle/addvehicleimagesajax",
				method: "POST",
				data: {vehicleData: vehicleData, photoData: photoData},
				success: function (result) {
					if(result.state == 'success'){
						window.location.href = baseUrl + "vehicle/index/" + result.id;
					}
				}
			});
		}
		else{
			alert('Заполните данные о ТС, а также загрузите фотографии');
		}
    });
	
	$('#login').submit(function(event){
		$('#warning_alert_login').css({visibility:'hidden'});
        event.preventDefault();
        var loginData = $('#login').serialize();
        $.ajax({
            url: baseUrl + "user/checklogin",
            method: "POST",
            data: loginData,
            success: function (result) {
                if(result.state == 'success'){
					if(result.isAdmin == 0){
						window.location.href = baseUrl + "index";
					}
					else
					{
						window.location.href = baseUrl + "admin";
					}
				}
				else{
					//alert(result.errorMsg);
                    $('#warning_alert_login').css({visibility:'visible'});
				}
            }
        });
    });
	
	$('#vehicle_search_id').keydown(function(){
		var inputValue = $('#vehicle_search_id').val();
		if (inputValue != ''){
			$.ajax({
				url: baseUrl + "vehicle/getvehiclesmatchedfrominputajax",
				method: "POST",
				data: {vehicleData: inputValue},
				success: function (vehicles) {

                    $("#searchVehicleUl_id li").remove();
                    var result = [];
                    for (var key in vehicles){
                        var value = vehicles[key];
                        if(value.name !== undefined){
                            $("#searchVehicleUl_id").append('<li class="optli">'+value.name+'<input class="hd_id" type="hidden" value="'+value.brand_id+'"/></li>');
                        }
                    }
                    if($("#searchVehicleUl_id").is(':hidden')){
                        $("#searchVehicleUl_id").show();
                    }
                    $(".optli").click(function(){
                        var index = $("#searchVehicleUl_id li").index(this);
                        var vehicleName = $("#searchVehicleUl_id li").eq(index).text();
                        var vehicleId = $(".hd_id").eq(index).val();

                        $('#vehicle_search_id').val(vehicleName);
                        $('#vehicle_add_id').val(vehicleId);
                    });
                    $(document).click(function(){
                        if($("#searchVehicleUl_id").is(':visible')){
                            $("#searchVehicleUl_id").hide();
                        }
                    });
				}

			});

		}
	});
	
	$('#brand').submit(function(event){
        event.preventDefault();

        var brandData = $('#brand').serialize();
        $.ajax({
            url: baseUrl + "api/addbrandajax",
            method: "POST",
            data: brandData,
            success: function (result) {
				if(result.state == 'success'){
					window.location.href = baseUrl + "admin/addbrand";
				}
				if(result.state == 'duplicated'){
					alert('Марка с таким названием уже существует в БД!');
				}
            }
        });
    });
	
	$('#supplier').submit(function(event){
        event.preventDefault();

        var supplierData = $('#supplier').serialize();
        $.ajax({
            url: baseUrl + "api/addsupplierajax",
            method: "POST",
            data: supplierData,
            success: function (result) {
				if(result.state == 'success'){
					window.location.href = baseUrl + "admin/addsupplier";
				}
				if(result.state == 'duplicated'){
					alert('Производитель с таким названием уже существует в БД!');
				}
            }
        });
    });
	
});
