//Parser JS

    var url;
    var status;
    var statusText;
    var startedDateTime = "";
    var serverIP = "";

    var modal = document.getElementsByClassName("modal");

    var noentries = 0; 

    let entries = [];
   
    var local_entries = "";

    var date = new Date();
    var last_upload = "";

    var isp = "";

    document.querySelector("#submitFile").addEventListener('click', function() {
        //Εμφάνιση Pop up με μήνυμα για αναμονή
        modal[0].style.display = "none";
        modal[1].style.display = "block";
        let file = document.querySelector("#file_to_Upload").files[0];

        //Άνοιγμα har me file reader
        let reader = new FileReader();
        reader.addEventListener('load', function(e) {
            let har = e.target.result;

            //parsed as json
            var data = JSON.parse(har);
            //console.log(data);
            //Για κάθε index του har
            $(data.log.entries).each(function(index, value){
                startedDateTime = value.startedDateTime.replace(/T/g, " ");
                entries[0] = startedDateTime;
                entries[1] = value.timings.wait;
                entries[2] = value.serverIPAddress;
                //μερικές ip είναι undefined λόγω adblock
                if(typeof entries[2] !== 'undefined'){
                    //Έλεγχος αν IP v6 για αφαίρεση [] επειδή το site βγάζει σφάλμα
                    if(entries[2].charAt(0) == '['){
                        entries[2] = entries[2].slice(0, -1);//]
                        entries[2] = entries[2].substring(1);//[ 
                    }
                }else{//αν είναι κενη => 0
                    entries[2] = "0";
                }   

                entries[3] = value.request.method;

                //από το url μόνο το domain
                url = value.request.url;
                let domain = (new URL(url));
                domain = domain.hostname;
                entries[4] = domain;

                //request headers αν και οι περισσότεροι επιστρεφουν κενό
                $(value.request.headers).each(function(hindex, hvalue){
                    if(hvalue.name === "content-type"){
                        entries[18] = hvalue.value;
                    }
           
                    if(hvalue.name === "cache-control"){
                        entries[19] = hvalue.value;
                    }
             
                    if(hvalue.name === "pragma"){
                        entries[20] = hvalue.value;
                    }
            
                    if(hvalue.name === "expires"){
                        entries[21] = hvalue.value;
                    }
    
                    if(hvalue.name === "age"){
                        entries[22] = hvalue.value;
                    }

                    if(hvalue.name === "last-modified"){
                        entries[23] = hvalue.value;
                    }

                    if(hvalue.name === "host"){
                        entries[24] = hvalue.value;
                    }
                });

                entries[5] = value.response.status;
                entries[6] = value.response.statusText;

                $(value.response.headers).each(function(hindex, hvalue){
                    
                    if(hvalue.name === "content-type"){
                        entries[11] = hvalue.value;
                    }

                    if(hvalue.name === "cache-control"){
                        entries[12] = hvalue.value;
                    }
           
                    if(hvalue.name === "pragma"){
                        entries[13] = hvalue.value;
                    }
            
                    if(hvalue.name === "expires"){
                        //Τα blocked Ip επιστρέφουν κενό expires ή lastmod
                        //αν είναι κενό skip
                        //αν όχι αλλαγή της ημερομηνίας σε μορφή τέτοια ώστε να είναι δυνατές οι πράξεις στην sql
                        if(typeof hvalue.name !== 'undefined' && hvalue.name !== "0" && hvalue.name !== ""){
                            var expires_date = new Date(hvalue.value);
                            var d = expires_date.toISOString().replace(/T/g, " ");
                            entries[14] = d;
                        }else{
                            entries[14] = hvalue.value;
                        }
                        
                    }
    
                    if(hvalue.name === "age"){
                        entries[15] = hvalue.value;
                    }

                    if(hvalue.name === "last-modified"){
                        //το ίδιο με το expires
                        if(typeof hvalue.name !== 'undefined' && hvalue.name !== "0" && hvalue.name !== ""){
                            var lastmod = new Date(hvalue.value);
                            var d = lastmod.toISOString().replace(/T/g, " ");
                            entries[16] = d;
                        }else{
                            entries[16] = hvalue.value;
                        }
                    }

                    if(hvalue.name === "host"){
                        entries[17] = hvalue.value;
                    }

                });

                if(entries[2] === "0"){
                    entries[7] = " ";
                    entries[8] = " ";
                    entries[9] = " ";
                }else{
                    $.ajax({
                        dataType: "json",
                        url: "https://api.astroip.co/"+entries[2]+"/?api_key=6440d61e-912a-46b3-b2b6-731dac9d9ed3" ,//orestis API
                        async: false,//σύγρονα γτ πρέπει να πάρω απάντηση από site
                        success: function(data){
                            entries[7] = data.asn.organization;
                            entries[8] = data.geo.latitude;
                            entries[9] = data.geo.longitude;
                        }
                    });
                }

                $.ajax({
                    dataType: "json",
                    url: "https://api.astroip.co/?api_key=6440d61e-912a-46b3-b2b6-731dac9d9ed3",
                    async: false,//σύγρονα γτ πρέπει να πάρω απάντηση από site
                    success: function(data){
                        entries[10] = data.ip;
                        entries[25] = data.geo.latitude;
                        entries[26] = data.geo.longitude;
                        console.log("OK");
                        parse();
                    }
                });

                

                function parse(){
                    //αν ο χρήστης επιλέξει save to server -> DB
                    if (document.getElementById("saveToServer").checked){
                        $.ajax({ 
                            url: "php/putDB.php",
                            type: "POST",
                            async: false,//σύγρονα γτ πρέπει να πάρω απάντηση από site
                            data: {
                                    entries : entries,
                                    noentries : noentries,
                                },
                            success: function() { 
                                //console.log("OK");
                            } 
                        }); 
                    }
                    //Αν τοπικά φτιάξε το αρχείο και κατέβασε
                    if(document.getElementById("saveLocally").checked){
                        var str = "Entry " + noentries + 
                                    "\n\tstartedDateTime: " +  entries[0] + 
                                    "\n\tTimings\n\t\twait: " + entries[1] + 
                                    "\n\tserverIPAddress: " + entries[2] + 
                                    "\n\tRequest\n\t\tmethod: " + entries[3] + 
                                                    "\n\t\turl: " + entries[4] +
                                                    "\n\t\tHeaders" + 
                                                        "\n\t\t\tcontent-type: " + entries[18] +
                                                        "\n\t\t\tcache-control: " + entries[19] + 
                                                        "\n\t\t\tpragma: " + entries[20] + 
                                                        "\n\t\t\texpires: " + entries[21] + 
                                                        "\n\t\t\tage: " + entries[22] +
                                                        "\n\t\t\tlast-modified: " + entries[23] + 
                                                        "\n\t\t\thost: " + entries[24] + 
                                    "\n\tResponse\n\t\tstatus: " + entries[5] + 
                                    "\n\t\tstatusText: " + entries[6] + 
                                    "\n\t\tHeaders" + 
                                        "\n\t\t\tcontent-type: " + entries[11] +
                                        "\n\t\t\tcache-control: " + entries[12] + 
                                        "\n\t\t\tpragma: " + entries[13] + 
                                        "\n\t\t\texpires: " + entries[14] + 
                                        "\n\t\t\tage: " + entries[15] +
                                        "\n\t\t\tlast-modified: " + entries[16] + 
                                        "\n\t\t\thost: " + entries[17] + "\n";

                        local_entries = local_entries + str;
                    }

                    noentries++;
                }

            });
            
            //console.log(noentries);
            //console.log(local_entries);

            if (document.getElementById("saveToServer").checked){
                alert("Upload to database was successful!");
                modal[1].style.display = "none";
                setTimeout(function () { window.location.reload(); }, 2000);
            }else if (document.getElementById("saveLocally").checked){
                alert("Your file is ready to download");
                modal[1].style.display = "none";
                function download(content, fileName, contentType) {
                    var a = document.createElement("a");
                    var file = new Blob([content], {type: contentType});
                    a.href = URL.createObjectURL(file);
                    a.download = fileName;
                    a.click();
                }
                download(local_entries, 'parsedFile.txt', 'application/json');
            }else{
                modal[1].style.display = "none";
                alert("Make a choice!");
            }

        });

        //Δημιουρία ημερομηνίας για το τελευταίο ανέβασμα
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        if(hours <=9){
            hours = "0"+hours;
        }
        if(minutes <=9){
            minutes = "0"+minutes;
        }
        if(seconds <=9){
            seconds = "0"+seconds;
        }

        last_upload = JSON.stringify(date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear() + " " +
                    hours + ":" + minutes + ":" + seconds);
                    
        $.ajax({ 
            url: "php/save_date.php",
            type: "POST",
            async: false, 
            data: {
                    last_upload : last_upload,
                },
                success: function() { 
                //console.log(last_upload); 
            } 
        });

        reader.readAsText(file);
        noentries = 0;
    });