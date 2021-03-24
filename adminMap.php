<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: loginPage.php");
    exit;


}

require_once "php/config.php"

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700&display=swap" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/adminPage.css">
        <link rel="stylesheet" type="text/css" href="css/adminMap.css">
        <link rel="stylesheet" type="text/css" href="css/dropDown.css">
        <link rel="stylesheet" type="text/css" href="css/site_header.css">

        <!-- Map -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
        <script src = "scripts/leaflet.heat.js" ></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css">
        <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/heatmapjs@2.0.2/heatmap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet-heatmap@1.0.0/leaflet-heatmap.js"></script>

        <title>"Project Web 2020-2021"</title>
    </head>

    <body>
        <div id="includedContent"></div>
        <script>
            $(function(){
                $("#includedContent").load("admin_basic_view.html"); 
            });
        </script>

        <div class="admin_map_box">
            <div id="map"></div>
            <script>
                let mymap = L.map("map",{
                    minZoom : 1.5
                });
                let osmUrl = "https://tile.openstreetmap.org/{z}/{x}/{y}.png";
                let osmAttrib =
                'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
                let osm = new L.TileLayer(osmUrl, { attribution: osmAttrib });
                mymap.addLayer(osm);
                mymap.setView([11,11],2);

                var map_coords = [];

                $.ajax({
                    url: "php/admin_map.php",
                    type: "POST",
                    success: function(data){
                        map_coords = data;//πίνακας κάθε γραμμή με lat long 0 kai 1 αντιστοιχα gia user & 2 kai 3 server 4 ποσοστό εμφάνισης
                                          //για να μην ζωγραφίσει πολλές φορές την ίδια γραμμή αλλά με βάση αυτό κανονικοποιείται το πάχος γραμμής
                        show_polylines();
                    }
                })

                function show_polylines(){

                    var userIcon = new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [30, 50],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });


                    for (i=0; i<map_coords.length; i++){
                        latlngs = [[map_coords[i][0], map_coords[i][1]], [map_coords[i][2], map_coords[i][3]]];
                        marker1 = L.marker([map_coords[i][0], map_coords[i][1]], {icon: userIcon} ).addTo(mymap);//χρήστη
                        marker2 = L.marker([map_coords[i][2], map_coords[i][3]]).addTo(mymap);//σερβερ
                        var thickness;
                        
                        //κανονικοποίηση πάχους γραμμών
                        if(map_coords[i][4] < 1){//αν λιγότερο από 1%
                            thickness = 0.25;
                        }else if(map_coords[i][4] >= 1 && map_coords[i][4] < 5){//αν ανάμεσα από 1 και 5%
                            thickness = 0.5;
                        }else{
                            thickness = map_coords[i][4]/10;//αλλιώς πάχος γραμμής = ποσοστό/10(πχ 45% -> 4.5 πάχος)
                        }

                        // console.log(thickness);
                        var polyline = L.polyline(latlngs, {color: 'blue', weight: thickness, smoothFactor: 0.5}).addTo(mymap);
                    }
                }

            </script>
        </div>

    </body>
</html>