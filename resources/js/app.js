require('./bootstrap');

$(document).ready(function () {

    let carSelect = document.getElementById('car_select');
    carSelect.addEventListener('change', function (e) {
        let carId = this.value;
        let carImei = e.target.options[e.target.selectedIndex].dataset.imei;
        console.log(carId);
        console.log(carImei);
        showDistance(carId);

    });

    function showDistance(carId) {
        $.ajax({
            url: "./distance/" + carId,
            type: "GET",
            dataType: 'json',
            beforeSend: function() {
                $('.preloader').addClass('show')
            },
            success: function (response) {
                console.log(response);
                $('.preloader').removeClass('show');
                $('#distance').text(response.distance);
            }
        })
    }


});
