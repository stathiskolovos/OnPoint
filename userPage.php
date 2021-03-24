<?php
// Έναρξη session για έλεγχο αν υπάρχει χρήστης συνδεδεμένος
session_start();
 
// Αν δεν υπάρχει -> loginPage
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: loginPage.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/forms.css">
        <link rel="stylesheet" type="text/css" href="css/dropDown.css">
        <link rel="stylesheet" type="text/css" href="css/userPage.css">
        <link rel="stylesheet" type="text/css" href="css/PopUp.css">
        <link rel="stylesheet" type="text/css" href="css/site_header.css">

        <!-- Map -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
        <script src="https://cdn.jsdelivr.net/npm/heatmapjs@2.0.2/heatmap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet-heatmap@1.0.0/leaflet-heatmap.js"></script>
        <script src = "scripts/leaflet.heat.js" ></script>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700&display=swap" rel="stylesheet">

        <!-- Github script για να βάλουμε data στο graph από το ajax -->
        <script type="text/javascript" src="scripts/geojson.min.js"></script>

        <title>"Project Web 2020-2021"</title>
    </head>

    <body>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <div class="header_box">
            <div>
                <img class="header_logo" src="css/icons/logo2.png">
            </div>
            <h class="header_title">OnPoint</h>
            <a id="logout_btn" class="btn btn-danger" href="logout.php">Log Out</a>
        </div>

        <div class="profile_box">

            <div id="options" class="dropdown">
                <button onclick="myFunction()" class="dropbtn">Menu</button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="userSettings.php">Settings</a>
                    <a id="upload">Har Parser</a>
                </div>

                <script>
                    function myFunction() {
                        document.getElementById("myDropdown").classList.toggle("show");
                    }

                    // Close the dropdown if the user clicks outside of it
                    window.onclick = function(event) {
                        if (!event.target.matches('.dropbtn')) {
                            var dropdowns = document.getElementsByClassName("dropdown-content");
                            var i;
                            for (i = 0; i < dropdowns.length; i++) {
                                var openDropdown = dropdowns[i];
                                if (openDropdown.classList.contains('show')) {
                                    openDropdown.classList.remove('show');
                                }
                            }
                        }
                    }
                </script>

            </div>
        
        
            <div>
                <img class="profile_pic" src="css/icons/profile.png">
            </div>

            <div class="profile_info">
                <div id="username" style="font-size: 22px;">
                    <?php echo $_SESSION['username']; ?> 
                </div>
                <div id="email" style="font-size: 14px;">
                    <script>
                        $.ajax({ 
                                url: "php/user_email.php",
                                type: "GET",  
                                success: function(data) { 
                                    document.getElementById("email").innerHTML = data;
                                } 
                            }); 
                    </script>
                </div>
                <div style="font-size: 20px; margin-top: 20px;">Last Parse</div>
                <div id="last_upload" style="font-size: 15px;">
                    <script>
                        $.ajax({ 
                                url: "php/user_lu.php",
                                type: "GET",  
                                success: function(data) { 
                                    document.getElementById("last_upload").innerHTML = data;
                                } 
                            }); 
                    </script>
                </div>
                <div style="font-size: 20px; margin-top: 20px;">Number of Entries</div>            
                <div id="total_entries" style="font-size: 18px;">
                    <script>
                        $.ajax({ 
                                url: "php/no_entries.php",
                                type: "GET",  
                                success: function(data) { 
                                    document.getElementById("total_entries").innerHTML = data;
                                } 
                            }); 
                    </script>
                </div>
            </div>
                
            <div>
                <p class="team_info">Devs O.Ampelikiotis E.Kolovos</p>
            </div>

        </div>

        <div class="map_box">
            <div id="map"></div>
            <script>
                var ajax_arr = [];
                $.ajax({ 
                        url: "php/user_map.php",
                        type: "GET", 
                        success: function(data) { 
                            ajax_arr = data;
                            //console.log(data);
                            //on success πάρε data και ζωγράφισε το γράφημα
                            draw_heatmap();
                        } 
                    });
                    
                function draw_heatmap(){
                    //Όπως γυρίζει ο πίνακας από το ajax το lat & long σε κάθε γραμμή είναι στο 0 και 1 αντίστοιχα
                    //parse ajax_arr -> GeoJSON
                    var geojson = GeoJSON.parse(ajax_arr, {Point: ['0', '1']});

                    let mymap = L.map("map", { minZoom : 1.5}).setView([11,11],2);
                    let osmUrl = "https://tile.openstreetmap.org/{z}/{x}/{y}.png";
                    let osmAttrib =
                    'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
                    let osm = new L.TileLayer(osmUrl, { attribution: osmAttrib });
                    mymap.addLayer(osm);

                    //F για να 'parsαρουμε' τα δεδομένα από το GeoJSON
                    geoJson2heat = function(geojson, intensity) {
                        return geojson.features.map(function(feature) {
                        return [parseFloat(feature.geometry.coordinates[1]), 
                                parseFloat(feature.geometry.coordinates[0]), intensity];
                        });
                    };

                    var putin_map = geoJson2heat(geojson, 50);
                    var heatMap = L.heatLayer(putin_map,{ radius: 20, maxZoom: 11 ,maxOpacity: 0.8, scaleRadius: false, useLocalExtrema: false, valueField: 'count'});
                    mymap.addLayer(heatMap);

                }
                

            </script>
        </div>

        <!-- Modals -->

        <!--Upload Modal-->
        <div id="uploadModal" class="modal">
            <!-- Upload Modal content -->
            <div class="modal-content">
                <span class="close">x</span>
                <img class="modal_fields" src="css/icons/logo2.png" style="width: 10%; margin-left: 45%; margin-right: 45%;">
                <input type="file" id="file_to_Upload" class="modal_file" accept=".har">
                <div class="modal_check">
                    <u>
                        <li><label><input type="checkbox" id="saveToServer"/>Save to Server</label>
                        <li><label><input type="checkbox" id="saveLocally"/>Save Locally</label>
                    </u>
                </div>
                <button type="button" class="modal_btn" id="submitFile">Submit File</button>
            </div>
        </div>

        <!--Wait for Upload-->
        <div id="waitModal" class="modal">
            <!-- Wait Modal content -->
            <div class="modal-content">
                <img class="modal_fields" src="css/icons/logo2.png" style="width: 10%; margin-left: 45%; margin-right: 45%;">
                <div class="modal_fields" style="margin-top: 100px;">Please wait until procedure is complete<div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="scripts/upload.js"></script>
        <script src="har_parser.js"></script>

    </body>
</html>