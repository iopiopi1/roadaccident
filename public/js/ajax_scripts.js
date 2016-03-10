/**
 * Created by Ярослав on 28.02.2016.
 */
$(document).ready(function(){
    $('#user').submit(function(event){
        event.preventDefault();
        var userData = $('#user').serialize();
        $.ajax({
            url: baseUrl + "user/registerajax",
            method: "POST",
            data: userData,
            success: function () {

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
            success: function () {

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

});
