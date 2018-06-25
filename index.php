<?php
/**
 * Created by: momomo
 * Date: 26/06/2018
 */

session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon.png" type="image/png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
              integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/travelling_style.css">
        <script type='text/javascript' src='https://code.jquery.com/jquery-latest.min.js'></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
                integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBGgsmfRsGYRyPqWKmwwKFFw__1XWBmOA&libraries=geometry">
        </script>
        <script type='text/javascript' src='js/travelling_script.js'></script>
        <title>Artificial Intelligence project: a genetic travelling salesman optimizer (by momomo)</title>
    </head>
    <body>
        <div>
            <h1>Artificial Intelligence project<span class="smaller"> a genetic travelling salesman optimizer</span></h1>
            <hr/>
            <hr/>
            <div>
                <h2>Set the number of breed iterations</h2>
                <p>
                    <input type="number" id="iteration_number" value="1000">
                </p>
            </div>
            <hr/>
            <div>
                <h2>Optimizin' section</h2>

                <a id="detect-button" data-toggle="modal" href="#conclusion" class="btn btn-secondary btn-lg">Detect!</a>

                <div class="modal fade" id="conclusion" role="dialog" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div id="title-result"></div>
                                <button type="button" class="close" data-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <img id="img-result">
                                <div id="text-result"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="routeReady" value="nok">
            </div>
        </div>
    </body>
</html>
