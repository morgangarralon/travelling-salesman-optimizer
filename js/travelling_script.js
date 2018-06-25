$(function() {
    $('#optimize')
        .mouseenter(function() {
       $(this).fadeTo(100, 0.7);
    })
        .mouseleave(function() {
       $(this).fadeTo(100, 1);
    });

    $('#optimize').click(function(event) {
        url                         = './genetic_conclusion.php';
        iteration_number            = $('#iteration_number').val();
        data                        = { 'iteration_number': iteration_number };

        $("#title-result").html('<span class="text-secondary">Loading...</span>');
        $('#text-result').hide();
        $("#img-result").show();
        $("#img-result").attr('src', 'img/loading.gif');

        $.ajax({
            url: url,
            data: data,
            type: "POST",
            dataType: "JSON",
            success: function(distanceAndItinerary) {
                // $("#conclusion-text").html(data['text']);
                // $("#routeReady").val("ok").trigger({ type: 'change', route: distanceAndItinerary['itinerary'] });
                $("#img-result").hide();
                $("#title-result").html('<span class="display-4 text-secondary">Route<span/>');
                startCity = distanceAndItinerary['itinerary'].shift();
                endCity = distanceAndItinerary['itinerary'].pop();
                route = "<b>Start</b>: " + startCity.name + "<br/>";

                Object.keys(distanceAndItinerary['itinerary']).forEach(function(key) {
                    if(key < 9) {
                        displayKey = "0" + (parseInt(key) + 1);
                    } else {
                        displayKey = parseInt(key) + 1;
                    }
                    route += "<b>" + displayKey + "</b>: " + distanceAndItinerary['itinerary'][key].name + "<br/>";
                });
                route += "<b>End</b>: " + endCity.name + "<br/>";

                $("#text-result").html('<p class="text-secondary"><b>Travel distance: ' + Math.round(distanceAndItinerary['distance']) + 'km</b></p>' + route);
                $("#text-result").show();

                console.log(distanceAndItinerary);

                // url                         = './maps_conclusion.php';
                // iteration_number            = $('#iteration_number').val();
                // data                        = {'genetic_conclusion': distanceAndItinerary['itinerary'] };
                //
                // $.ajax({
                //     url: url,
                //     data: data,
                //     type: "POST",
                //     dataType: "JSON",
                //     success: function(mapsRoute) {
                //         console.log(mapsRoute);
                //     }
                // });
            }
        });
    });

    // $('#routeReady').change(function(event) {
    //     waypoints   = [];
    //     travelMode  = google.maps.DirectionsTravelMode.DRIVING;
    //     provideRouteAlternatives =  false;
    //     endCity     = event.route[event.route.length - 1];
    //     startCity   = event.route[0];
    //     unitSystem  = google.maps.UnitSystem.METRIC;
    //     origin      = new google.maps.LatLng(startCity.lat, startCity.lng);
    //     destination = new google.maps.LatLng(endCity.lat, endCity.lng);
    //     event.route.forEach(function(city) {
    //         location = new google.maps.LatLng(city.lat, city.lat);
    //         waypoint = [{ location: location }];
    //         waypoints.push(waypoint);
    //     });
    //     var routeRequest = {
    //         origin: origin,
    //         waypoints: waypoints,
    //         destination: destination,
    //         provideRouteAlternatives: provideRouteAlternatives,
    //         unitSystem: unitSystem,
    //         travelMode: travelMode
    //     };
    //
    //     directionsService = new google.maps.DirectionsService();
    //     directionsService.route(routeRequest, function(response, status) {
    //         if (status == google.maps.DirectionsStatus.OK) {
    //             console.log(response);
    //         } else {
    //             $("#routeReady").val("nok");
    //         }
    //     });
    // });
});