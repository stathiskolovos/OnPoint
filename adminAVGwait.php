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

        <!-- Charts -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

         <!-- Google Fonts -->
         <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700&display=swap" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/site_header.css">
        <link rel="stylesheet" type="text/css" href="css/adminAVGWait.css">
        <link rel="stylesheet" type="text/css" href="css/adminPage.css">

        <title>"Project Web 2020-2021"</title>
    </head>

    <body>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <div id="includedContent"></div>
        <script>
            $(function(){
                $("#includedContent").load("admin_basic_view.html"); 
            });
        </script>

        <div class="admin_wait">

            <div class="box1">
                
            <div class="cb_box">

                <input type="checkbox" id="all_checkboxes" value="">
                <label for="all_checkboxes">Select all</label><br>

                <div class="content_types">
                    <div class="cb_box_title">
                        <h>Content Types</h>
                    </div>
                    <input type="checkbox" id="all_ct" value="">
                    <label for="all_ct">Check All Content Types</label><br>

                    <script>
                        var content_types = [];
                        //Δημιουργία cb δυναμικα από data που έχει επιστρεψει η ajax
                        $.ajax({ 
                            url: "php/get_ct_labels.php",
                            type: "POST",
                            success: function(data) { 
                                content_types = data;
                                for (var value of content_types) {
                                    $('.content_types')
                                    .append(`<input type="checkbox" id="${value}" value="${value}">`)
                                    .append(`<label for="${value}">${value}</label></div>`)
                                    .append(`<br>`);
                                }
                            } 
                        });

                        
                    </script>
                </div>

                <div class="request_method">
                    <div class="cb_box_title">
                        <h>Request Method</h>
                    </div>
                    <input type="checkbox" id="all_methods" value="">
                    <label for="all_methods">All Methods</label><br>

                    <input type="checkbox" id="GET" value="GET">
                    <label for="GET">GET</label><br>

                    <input type="checkbox" id="POST" value="POST">
                    <label for="POST">POST</label><br>

                    <input type="checkbox" id="OPTIONS" value="OPTIONS">
                    <label for="OPTIONS">OPTIONS</label><br>

                    <input type="checkbox" id="PUT" value="PUT">
                    <label for="PUT">PUT</label><br>

                    <input type="checkbox" id="HEAD" value="HEAD" >
                    <label for="HEAD">HEAD</label><br>

                    <input type="checkbox" id="PATCH" value="PATCH">
                    <label for="PATCH">PATCH</label><br>

                    <input type="checkbox" id="DELETE" value="DELETE" >
                    <label for="DELETE">DELETE</label><br>
                </div>

                <div class="days">
                    <div class="cb_box_title">
                        <h>Day of the Week</h>
                    </div>
                    <input type="checkbox" id="all_week" value="">
                    <label for="all_week">All Week</label><br>

                    <input type="checkbox" id="monday" value="Monday">
                    <label for="monday">Monday</label><br>

                    <input type="checkbox" id="tuesday" value="Tuesday">
                    <label for="tuesday">Tuesday</label><br>

                    <input type="checkbox" id="wed" value="Wednesday">
                    <label for="wed">Wednesday</label><br>

                    <input type="checkbox" id="thu" value="Thursday">
                    <label for="thu">Thursday</label><br>

                    <input type="checkbox" id="fri" value="Friday">
                    <label for="fri">Friday</label><br>

                    <input type="checkbox" id="sat" value="Saturday">
                    <label for="sat">Saturday</label><br>

                    <input type="checkbox" id="sun" value="Sunday">
                    <label for="sun">Sunday</label><br>
                </div>

                <div class="isp">
                    <div class="cb_box_title">
                        <h>ISP</h>
                    </div>
                    <input type="checkbox" id="all_isp" value=""> 
                    <label for="all_isp">Check All ISPs</label><br>

                    <script>
                        var isp = [];
                        //Δημιουργία cb δυναμικα από data που έχει επιστρεψει η ajax
                        $.ajax({ 
                            url: "php/get_isp_labels.php",
                            type: "POST",
                            success: function(data) { 
                                isp = data;
                                for (var value of isp) {
                                    $('.isp')
                                    .append(`<input type="checkbox" id="${value}" value="${value}">`)
                                    .append(`<label for="${value}">${value}</label></div>`)
                                    .append(`<br>`);
                                }
                            } 
                        });

                    </script>

                    
                </div>

                <!-- Check all functions -->
                <script>
                    $("#all_checkboxes").click(function(){
                        $('input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $("#all_ct").click(function(){
                        $('.content_types > input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $("#all_methods").click(function(){
                        $('.request_method > input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $("#all_week").click(function(){
                        $('.days > input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $("#all_isp").click(function(){
                        $('.isp > input:checkbox').not(this).prop('checked', this.checked);
                    });
                </script>

                <button id="apply_filters" type="button" class="btn btn-info btn-lg">Apply Filters</button>

                </div>


            </div>

            <div class="box2">

                <div id="graph_box" class="graph_box">

                    <div id="canvas_container">
                        <canvas id="wait_chart" height=230></canvas>
                    </div>

                    <script type="text/javascript">
                        var content_types = [];
                        var methods = [];
                        var days = [];
                        var isp = [];

                        var myChart;

                        var chart_labels = [];
                        for (var i = 0; i <= 24; i++) {
                            chart_labels.push(i);
                        }
                        var chart_data = [];
                        var myarr = [];

                        createGragh();

                        function createGragh(){
                            //έλεγχος για το ποια cb είναι checked και επιστροφή το value τους σε πίνακες
                            //οι πίνακες με ajax σε sql για την εκτέλεση της εντολής με απαραίτητα where
                            var ct_boxes = document.querySelectorAll('.content_types > input[type=checkbox]:checked');
                            var method_boxes = document.querySelectorAll('.request_method > input[type=checkbox]:checked');
                            var days_boxes = document.querySelectorAll('.days > input[type=checkbox]:checked');
                            var isp_boxes = document.querySelectorAll('.isp > input[type=checkbox]:checked');

                            for(var i=0; i<ct_boxes.length; i++){
                                content_types.push(ct_boxes[i].value);
                            }

                            for(var i=0; i<method_boxes.length; i++){
                                methods.push(method_boxes[i].value);
                            }

                            for(var i=0; i<days_boxes.length; i++){
                                days.push(days_boxes[i].value);
                            }

                            for(var i=0; i<isp_boxes.length; i++){
                                isp.push(isp_boxes[i].value);
                            }

                            content_types = JSON.stringify(content_types).replace(/\[/g, "(").replace(/\]/g, ")"); 
                            methods = JSON.stringify(methods).replace(/\[/g, "(").replace(/\]/g, ")");
                            days = JSON.stringify(days).replace(/\[/g, "(").replace(/\]/g, ")");
                            isp = JSON.stringify(isp).replace(/\[/g, "(").replace(/\]/g, ")");

                            //console.log(content_types);

                            $.ajax({ 
                                url: "php/fill_wait_graph.php",
                                type: "POST",
                                data: {
                                    content_types : content_types,
                                    methods : methods,
                                    days : days,
                                    isp : isp
                                },
                                success: function(data) { 
                                myarr = data;//2D array 0 ώρα και 1 avg
                                // console.log(myarr);

                                    var i;
                                    var j;

                                    //αντοιστοίχηση δεδομένων με ώρες για γράφημα
                                    for(i=0; i <= 24; i++){
                                        for(j=0; j < myarr.length; j++){
                                            if(myarr[j][0] == chart_labels[i]){
                                                chart_data[i] = myarr[j][1];
                                                break;
                                            }else{
                                                chart_data[i] = 0;
                                            }
                                        }
                                        
                                    }//δημιουργία πίνακα με 25 κελιά

                                    // console.log(chart_data);
                                    //άδεισμα σε περίπτωση που ο χρήστης θέλει κι άλλο γράφημα
                                    content_types = [];
                                    methods = [];
                                    days = [];
                                    isp = [];

                                    var ctx = document.getElementById("wait_chart").getContext('2d');
                                    myChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: chart_labels,
                                        datasets: [{
                                            data: chart_data,
                                            backgroundColor: "#F08080",
                                        }]
                                        },
                                        options : {
                                            legend:{
                                                display : false,                    
                                            },
                                            title: {
                                                display: true,
                                                text: 'Average Request Wait Time per Hour',
                                                fontFamily: "'Archivo', sans-serif"
                                            }    
                                        }
                                    });    
                                } 
                            });      
                        }
                        
                        $('#apply_filters').on('click', function(){
                            $('#canvas_container').empty().append('<canvas id="wait_chart" height="230"></canvas>');
                            createGragh();
                            
                        })

                    </script>

                </div>

            </div>

            

        </div>
    </body>
</html>