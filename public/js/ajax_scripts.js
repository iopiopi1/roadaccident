/**
 * Created by Ярослав on 28.02.2016.
 */
$(document).ready(function(){
    $('#user').submit(function(event){
        event.preventDefault();
		var pass1 = $('#password').val();
		var pass2 = $('#password_repeat').val();

		if(pass1 !== pass2){
			$('#warning_alert_login').css({visibility:'visible'});
			return false;
		}
        var userData = $('#user').serialize();
        $.ajax({
            url: baseUrl + "user/registerajax",
            method: "POST",
            data: userData,
            success: function (result) {
				if(result.state == 'success'){
					window.location.href = baseUrl + "index";
				}
            }
        });
    });

    $('#vehicle').submit(function(event){
        event.preventDefault();
        var vehicleData = $('#vehicle').serialize();
        var photoData = $('#fileupload').serialize();
        $.ajax({
            url: baseUrl + "vehicle/addvehicleimagesajax",
            method: "POST",
            data: {vehicleData: vehicleData, photoData: photoData},
            success: function (result) {
				if(result.state == 'success'){
					window.location.href = baseUrl + "vehicle/index/" + result.id;
				}
            }
        });
    });
	
	$('#login').submit(function(event){
        event.preventDefault();
        var loginData = $('#login').serialize();
        $.ajax({
            url: baseUrl + "user/checklogin",
            method: "POST",
            data: loginData,
            success: function (result) {
                if(result.state == 'success'){
					window.location.href = baseUrl + "index";
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

	
});
