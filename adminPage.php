<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: loginPage.php");
    exit;
}

include "php/config.php";
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
        <link rel="stylesheet" type="text/css" href="css/adminPage.css">
        <link rel="stylesheet" type="text/css" href="css/dropDown.css">
        <link rel="stylesheet" type="text/css" href="css/site_header.css">

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

        <div class="chart_box">

            <div class="chart_hbox1">
                <div class="pie_chart_container">
                        <canvas id="method_chart"></canvas>
                </div>

                <div class="pie_chart_container">
                        <canvas id="status_chart"></canvas>
                </div>
            </div>

            <div class="chart_hbox2">
                <div class="bar_chart_container">
                    <canvas id="avg_chart" height=230></canvas>
                </div>
            </div>

            <script type="text/javascript">
                var method_data = [];
                //δεδομένα για πίτα μεθόδων και δημιουργία
                $.ajax({ 
                    url: "php/fill_method_pie.php",
                    type: "GET", 
                    success: function(data) { 
                        method_data = data;
                        method_pie_maker();
                    } 
                });
                    
                function method_pie_maker(){
                    var ctx = document.getElementById("method_chart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ["GET", "POST", "PUT", "HEAD", "DELETE", "PATCH", "OPTIONS"],
                            datasets: [{
                                backgroundColor: [
                                    "#2ecc71",
                                    "#3498db",
                                    "#95a5a6",
                                    "#9b59b6",
                                    "#f1c40f",
                                    "#e74c3c",
                                    "#34495e"
                                ],
                                data: method_data
                            }]
                            },
                            options: {
                                legend: { 
                                    display : true,
                                    position : 'bottom',
                                    labels: {
                                        fontSize :10,
                                        fontFamily: "'Archivo', sans-serif"
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Entries per Request Method',
                                    fontFamily: "'Archivo', sans-serif"
                                }
                            }
                        });
                    }
            </script>

            <script type="text/javascript">
                    var status_data = [];
                    //δεδομένα για πίτα status και δημιουργία graph
                    $.ajax({ 
                        url: "php/fill_status_pie.php",
                        type: "POST",
                        success: function(data) { 
                            status_data = data;
                            status_pie_maker();
                        } 
                    });

                    function status_pie_maker(){
                        var ctx = document.getElementById("status_chart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ["1xx informational", "2xx success", "3xx redirection", "4xx client errors", "5xx server errors"],
                                datasets: [{
                                backgroundColor: [
                                    "#2ecc71",
                                    "#3498db",
                                    "#95a5a6",
                                    "#9b59b6",
                                    "#f1c40f",
                                ],
                                data: status_data
                                }]
                            },
                            options : {
                                legend:{
                                    display : true,
                                    position : 'bottom',
                                    labels: {
                                        fontSize :10,
                                        fontFamily: "'Archivo', sans-serif"
                                    }
                                    
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Entries per Response Status',
                                    fontFamily: "'Archivo', sans-serif"
                                }
                            }
                        });
                    }
            </script>

            <script type="text/javascript">
                    var graph_labels = [];
                    var graph_data = [];

                    $.ajax({ 
                        url: "php/get_ct_labels.php",
                        type: "POST",
                        success: function(data) { 
                            graph_labels = data;//επιστρέφει όλα τα content types για δημιουργία labels γραφήματος
                            //με επιτυχία πάρε με ajax τα δεδομένα 
                            $.ajax({ 
                                url: "php/fill_ct_graph.php",
                                type: "POST",
                                success: function(data) { 
                                    graph_data = data;
                                    //ζωγράφισε
                                    ct_graph_maker();
                                } 
                            });
                        } 
                    });

                    function ct_graph_maker(){
                        var ctx = document.getElementById("avg_chart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: {
                                labels: graph_labels,
                                datasets: [{
                                    data: graph_data,
                                    backgroundColor: "#F08080",
                                }]
                            },
                            options : {
                                legend:{
                                    display : false,                    
                                },
                                title: {
                                    display: true,
                                    text: 'Average Object Age per Content Type(minutes)',
                                    fontFamily: "'Archivo', sans-serif"
                                }    
                            }
                        });
                    } 
            </script>

        </div>

    </body>
</html>