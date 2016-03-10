/**
 * Created by Ярослав on 28.02.2016.
 */
$(document).ready(function(){
    $('#user').submit(function(event){
        event.preventDefault();
        var userData = $('#user').serialize();
        $.ajax({
            url: "registerajax",
            method: "POST",
            data: userData,
            success: function ($success) {
                alert('success!');
            }
        });
    });

    $('#vehicle').submit(function(event){
        event.preventDefault();
        var vehicleData = $('#vehicle').serialize();
        var photoData = $('#fileupload').serialize();
        $.ajax({
            url: "addvehicleimagesajax",
            method: "POST",
            data: {vehicleData: vehicleData, photoData: photoData},
            success: function ($success) {
                alert('success!');
            }
        });
    });

});
