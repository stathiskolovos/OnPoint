<?php
session_start();
 
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
        <link rel="stylesheet" type="text/css" href="css/adminPage.css">
        <link rel="stylesheet" type="text/css" href="css/adminTTL.css">
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

        <div class="ttl_graph_box">

            <!-- Cachability -->
            <div class="graph1_box">

                <div class="radar">
                    <div id="canvas_container1">
                        <canvas id="cachability_chart"></canvas>
                    </div>
                </div>

                <div class="radar_cb">

                    <input type="checkbox" id="all_checkboxes" value="">
                    <label for="all_checkboxes">Select all</label><br>

                    <div class="content_types">
                        <div class="cb_titles">
                            <h>Content Types</h>
                        </div>
                        <input type="checkbox" id="all_ct" value="">
                        <label for="all_ct">Check All Content Types</label><br>

                        <script>
                            var content_types = [];
                            //???????????????????? cb ???????????????? ?????? data ?????? ???????? ???????????????????? ?? ajax
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

                    
                    <div class="isp">
                        <div class="cb_titles">
                            <h>ISP</h>
                        </div>
                        <input type="checkbox" id="all_isp" value=""> 
                        <label for="all_isp">Check All ISPs</label><br>

                        <script>
                            var isp = [];
                            //???????????????????? cb ???????????????? ?????? data ?????? ???????? ???????????????????? ?? ajax
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

                    <button id="apply_filters" type="button" class="btn btn-info btn-lg">Apply Filters</button>

                </div>

                <!-- Check all boxes -->
                <script>
                    $("#all_ct").click(function(){
                        $('.content_types > input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $("#all_isp").click(function(){
                        $('.isp > input:checkbox').not(this).prop('checked', this.checked);
                    }); 
                    $("#all_checkboxes").click(function(){
                        $('input:checkbox').not(this).prop('checked', this.checked);
                    });       
                </script>

            </div>


            <div class="graph2_box">

                <div class="ttl_chart">
                    <div id="canvas_container2">
                        <canvas id="ttl_chart" height=230></canvas>
                    </div>
                </div>

            </div>



        </div>

        <!-- Cache ability chart -->

        <script>
            var content_types = [];
            var isp = [];
            var myarr = [];

            var myChart;

            //???????????? ?????????? data ?????????? ???????????????? ?? ?????????????? ?????? ?????????????? apply filters ???????????????????? ????????
            createGraphs();

            function createGraphs(){
                //?????????????? ?????? ???? ???????? cb ?????????? checked ?????? ?????????????????? ???? value ???????? ???? ??????????????
                //???? ?????????????? ???? ajax ???? sql ?????? ?????? ???????????????? ?????? ?????????????? ???? ???????????????????? where
                var ct_boxes = document.querySelectorAll('.content_types > input[type=checkbox]:checked');
                var isp_boxes = document.querySelectorAll('.isp > input[type=checkbox]:checked');

                for(var i=0; i<ct_boxes.length; i++){
                    content_types.push(ct_boxes[i].value);
                }
                for(var i=0; i<isp_boxes.length; i++){
                    isp.push(isp_boxes[i].value);
                }

                content_types = JSON.stringify(content_types).replace(/\[/g, "(").replace(/\]/g, ")");
                isp = JSON.stringify(isp).replace(/\[/g, "(").replace(/\]/g, ")");

                $.ajax({ 
                    url: "php/fill_cache_graph.php",
                    type: "POST",
                    data: {
                            content_types : content_types,
                            isp : isp
                        },
                        success: function(data) {
                            //???? ???????????????? 
                            myarr = data[0];

                            console.log(myarr);
                            //???????? ???????????? ?????????????????? ?????? ???????????????????????? ???????? ???????????? ?????? ???? ??????????????
                            var chart_data = myarr.split(" ");
                            //???? ???? ?????????????????? ???????????????? ?????? ???????????? ?????????? ????????
                            chart_data.pop();
                           
                            var ctx = document.getElementById('cachability_chart').getContext('2d');
                            myChart = new Chart(ctx, {
                                type: 'radar',
                                data: {
                                    labels: ["Public","Private","no-Cache","no-Store"],
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
                                            text: 'Cacheability Directives Percentages(%)',
                                            fontFamily: "'Archivo', sans-serif"
                                        }    
                                }
                            });


                            $.ajax({ 
                                url: "php/fill_ttl_graph.php",
                                type: "POST",
                                data: {
                                        content_types : content_types,
                                        isp : isp
                                    },
                                    success: function(data) { 
                                        //???? ????????????????
                                        var max_age;
                                        var get_max = [];
                                        var ttl = [];

                                        console.log(data);

                                        //???????????????? ?????????????? ?????? ?????? ?????????????????? ?????? ?? ?????????????? ?????????? ?????? ??????????????
                                        content_types = [];
                                        isp = [];

                                        //?????? ???? ?????????? ?????? ???????????? ?????? ???????????????????? ?? ????????
                                        for(var i=0; i < data.length; i++){
                                            //???????? ???????????? ?????????? ?????? ???? ???????????? ???? ?????????????? ???? max age
                                            get_max = data[i][0].split(",");
                                            //???? ?????????? ?????????? ???????? ????????????
                                            var str = " max-age";
                                            //???? ?????????? ??????????
                                            var str1 = "max-age";
                                            //?????????? ?? ?????????????? ?????????????? ?????? ???? ???????? ?????? ??????????????
                                            ttl[i] = [];//2D 0 = ???????? , 1 = ????????????????????
                                            for(var j=0; j < get_max.length; j++){
                                                if(get_max[j].substring(0,7).localeCompare(str1) == 0){
                                                    max_age = get_max[j].substring(8,get_max[j].length);
                                                    ttl[i][0] = parseFloat(max_age);//?????? ???????? ?????? max age ?????? float
                                                    ttl[i][1] = parseFloat(data[i][3]);//?????? ??????????????????????
                                                    break;
                                                }else if(get_max[j].substring(0,8).localeCompare(str) == 0){
                                                    max_age = get_max[j].substring(9,get_max[j].length);
                                                    ttl[i][0] = parseFloat(max_age);
                                                    ttl[i][1] = parseFloat(data[i][3]);
                                                    break;
                                                }
                                            }

                                            if(ttl[i][0] == null){//???? ?????? ?????????????? max age
                                                if(data[i][1] > 0){//???? expires > 0 
                                                    ttl[i][0] = parseFloat(data[i][1]);//???????? expired(diff sql)
                                                    ttl[i][1] = parseFloat(data[i][3]);
                                                }else{//???? ?????? 
                                                    ttl[i][0] = parseFloat(data[i][2]);//lastmod(diff sql)
                                                    ttl[i][1] = parseFloat(data[i][3]);
                                                }
                                            }

                                        }

                                        // console.log(data);
                                        // console.log(ttl);


                                        //Create graph buckets #10
                                        var max;
                                        var min;
                                        var scope;
                                        var buckets = [];
                                        var fill_buckets = [];

                                        max = Math.max.apply(Math, ttl.map(v => v[0]));
                                        min = Math.min.apply(Math, ttl.map(v => v[0]));
                                       
                                        // console.log(max, min);
                                        // console.log(ttl);

                                        //?????????? bucket ????????????????
                                        scope = Math.round(((max-min)/10),2);

                                        //????????????????????????
                                        for(var i=0; i<=10; i++){
                                            buckets.push(i*scope);
                                            fill_buckets[i] = 0;
                                        }

                                        //???????????????? ???? bucket ???????????????? ?????????? - ???????????? bucket
                                        for (var i=0; i<ttl.length; i++){
                                            for(var j=0; j<10; j++){
                                                if(ttl[i][0] >= buckets[j] && ttl[i][0] <= buckets[j+1]){
                                                    fill_buckets[j] = fill_buckets[j] + ttl[j][1];
                                                    break;
                                                }
                                            }
                                        }

                                        // console.log(buckets);
                                        // console.log(fill_buckets);
                                        // console.log(fill_buckets);

                                        var ctx = document.getElementById("ttl_chart").getContext('2d');
                                        var myChart = new Chart(ctx, {
                                            type: 'bar',
                                        data: {
                                            labels: buckets,
                                            datasets: [{
                                            label: 'TTL Histogram',
                                            data: fill_buckets,
                                            backgroundColor: '#F08080',
                                            }]
                                        },
                                        options: {
                                            scales: {
                                            xAxes: [{
                                                display: false,
                                                barPercentage: 1.3,
                                                ticks: {
                                                max: 3,
                                                }
                                            }, {
                                                display: true,
                                                ticks: {
                                                autoSkip: false,
                                                max: 4,
                                                }
                                            }],
                                            yAxes: [{
                                                ticks: {
                                                beginAtZero: true
                                                }
                                            }]
                                            }
                                        }
                                        });
                                    }      
                            });    
                                        
                        }      
                });

            }

            //???????????????????? ?????? ???????????????????? canvas
            $('#apply_filters').on('click', function(){
                $('#canvas_container1').empty().append('<canvas id="cachability_chart"></canvas>');
                $('#canvas_container2').empty().append('<canvas id="ttl_chart" height=230></canvas>');
                createGraphs();
                        
            })

        </script>
        
    </body>
</html>