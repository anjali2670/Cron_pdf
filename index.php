<?php
session_start();
require("db.php");
$sql = "SELECT distinct host_name FROM view_ipdus ";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <title>UIDAI MANESAR IPDU's POWER CONSUMPTION REPORT</title>
    <style>
        .fetch-btn {
            margin: 14px 6px 16px 5px;
            background: linear-gradient(to right, #3b4a6b, #0d0d0d);
            color: white;
            padding: 17px 18px;
            border-radius: 4px;
            border: 0;
            display: inline-block;
        }

        #printAndSaveButton {
            margin: 8px 5px 0px -4px;
            background: linear-gradient(to right, #3b4a6b, #0d0d0d);
            color: white;
            padding: 16px 16px;
            border-radius: 4px;
            border: 0;
        }

        h3 {
            text-align: center;
            color: #0d0d0d;
        }

        #generate_report {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px;
        }
    </style>
    <script src="jquery.js"></script>
</head>
<body>
    <script>
        $(document).ready(function() {
            //all select  
            // $('#btn').click(function() {
            //     $('select#reportperiodDropdown > option').prop('selected', 'selected');
            // });
            //
            $(".loader").hide();
            $('#generate_report').submit(function(e) {
               e.preventDefault();
                // $(".loader").show();
                var hostname = $('#host_name').val();
                var report = $('#reportperiodDropdown').val();

                if (hostname == '' || report == '') {
                    $("#message").html("Please select all fields!");
                    $(".loader").hide();
                } else {
                    $("#message").html("");


                    $.ajax({
                        url: 'get_table_data.php',
                        type: 'POST',
                        data: {
                            host_name: $('#host_name').val(),
                            timeperiod: $('#reportperiodDropdown').val(),
                        },
                        success: function(response) {
                            var responseData = JSON.parse(response);
                            console.log(responseData);
                            $('#all_data').empty();
                            //$('#all_data').css('display', 'flex');
                            var html = '';
                            // var hostName1 = '';
                            var i = 0;
                            var mod;
                            $.each(responseData.data, function(hostName, hostData) {
                                i++;
                                // hostName1 = hostName1 + ', ' + hostName;
                                var totalkwh = totalkva = 0;
                                mod = i % 2;
                                if (mod == 1) {
                                    html += '<div class="row" style="width:100%; display:flex;">';

                                }
                                var tableWidth = (hostname.length === 1) ? '100%' : '50%';
                                html += '<table border="1" style="width: ' + tableWidth + '; margin: 10px; text-align:center"><thead><tr><th colspan="3" style="padding: 8px; background-color:black; color:white;">' + hostName + '</th></tr><tr><th style="padding: 8px; background-color:black; color:white; width:20%; ">Outlet_No</th><th style="background-color:black; color:white; width:40%;">kWh</th><th style="background-color:black; color:white;width:40%;">kVA</th></tr></thead><tbody>';

                                $.each(hostData, function(index, row) {
                                    var formattedKwh = parseFloat(row.kWh).toFixed(2);
                                    var formattedKva = parseFloat(row.kVA).toFixed(2);
                                    // totalKva += parseFloat(row.kVA);
                                    // numRows++;

                                    html += '<tr>' +
                                        '<td style="padding: 8px; background-color:#EDEADE; color:black;">' + row.Outlet_No + '</td>' +
                                        '<td style="padding: 8px; background-color:#BDF5BD;">' + formattedKwh + '</td>' +
                                        '<td style="padding: 8px; background-color:#BDF5BD;">' + formattedKva + '</td>' +
                                        '</tr>';
                                    totalkwh += parseFloat(formattedKwh);
                                    totalkva += parseFloat(formattedKva);
                                    
                                });
                                html += '<tr>' +
                                    '<td style="padding: 8px; background-color:black; color:white;">Total Sum</td>' +
                                    '<td style="padding: 8px; background-color:black; color:white;">' + totalkwh.toFixed(2) + '</td>' +
                                    '<td style="padding: 8px; background-color:black; color:white;">' + totalkva.toFixed(2) + '</td>' +
                                    '</tr>';
                               
                                html += '</tbody></table>';
                                if (mod == 0) {
                                    html += '</div>';
                                }

                                // html +=html;
                            });

                            
                            if (mod == 1) {
                                html += '</div>';
                            }


                            var totalBoxHtml = '<table border="1" style="text-align:center; margin-left:60%; "><thead><tr><th style="background-color:black; color:white; width:40%; padding:5px;">Total kWh</th><th style="background-color:black; color:white;width:40%; padding:5px;">Total kVA</th></tr></thead><tbody>';
                            totalBoxHtml += '<tr>' +
                                '<td style="background-color:#BDF5BD; color:black; padding:20px; font-weight: bold; ">' + responseData.totalkwh + '</td>' +
                                '<td style="background-color:#BDF5BD; color:black; padding:20px; font-weight: bold;">' + responseData.totalkva + '</td>' +
                                '</tr>';
                            totalBoxHtml += '</tbody></table>';

                            var h2Html = html3 = '';
                            h2Html += '<h3 style="color:black; background-color:#EDEADE; width:50%; margin-left:350px; padding:5px;">UIDAI MANESAR IPDU\'s POWER CONSUMPTION REPORT</h3>';
                            $('#titilename').html(h2Html);

                            // var h = hostName1.substr(2, hostName1.length - 1);

                            // var html3 = 'UIDAI' + '<br>' + h;

                            // $("#h2title").html(html3);

                            $('#total').html(totalBoxHtml);
                            $('#all_data').append(html);

                            // var timeperiodHTML = '<p style="color: grey; margin-right: 200px;>' + $('#reportperiodDropdown').val() + '</p>';
                            $('#time').html(responseData.from +' to '+responseData.to);

                        },

                        error: function() {
                            alert('Error fetching data.');
                            $(".loader").hide();
                        }
                    });

                }
            });
        });
    </script>
    <?php
    // print_r($_SESSION);
    ?>
    <h3>UIDAI MANESAR IPDU's POWER CONSUMPTION REPORT
    </h3>
    <form method="post" id="generate_report">
        <div class="date-select">
            TimePeriod:
            <select id="reportperiodDropdown" name="timeperiod" style="width:200px; height:50px" class="form-control">
                <option value="today">Today</option>
                <option value="last24hours">Daily</option>
                <option value="thisweek">Weekly</option>
                <option value="thismonth">Monthly</option>
            </select>
        </div>
        <p style="margin:10px;">IPDU's Model:</p>
        <div class="host-select">
            <select id="host_name" name="host_name" style="width: 200px;height:50px" class="form-control" multiple>;
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    echo '<option value="' . $row['host_name'] . '">' . $row['host_name'] . '</option>';
                }
                ?>
                <!-- <option value="">All</option> -->
            </select>
            <script>
                document.getElementById('host_name').addEventListener('change', function() {
                    var select = this;
                    var options = select.options;
                    var allOption = options[options.length - 1];

                    if (allOption.selected) {
                        for (var i = 0; i < options.length - 1; i++) {
                            options[i].selected = true;
                        }
                    } else {
                        allOption.selected = false;
                    }
                });
            </script>
        </div>
        <div>
            <!-- <input type="hidden" id="btnsubmintcount" name="btnsubmintcount" value="0"> -->
            <input class="fetch-btn" type="submit" value="Go" name="btn" id="btn">
            <button id="printAndSaveButton" onclick="printAndSaveAsPDF()">Save as PDF</button>
            <div id="message" style="color:red;"></div>
        </div>
    </form>

    <div class="loader1">
        <!-- <div>
            <h3 style="color:black; background-color:#EDEADE; width:50%; margin-left:350px;" id="h2title"></h3>
        </div> -->
        <div id="titilename"></div>
        <p id="time" style="color:grey; text-align:center;"></p>
        <div id="total" style="margin-left:800px;">
        </div>
        <div id="all_data"></div>

    </div>
    <script>
        function printAndSaveAsPDF() { 
            window.location = 'index_pdf.php';
        }
    </script>
</body>

</html>