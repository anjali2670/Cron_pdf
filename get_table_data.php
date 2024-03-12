<?php
session_start();
require("db.php");
if (isset($_POST['host_name'])) {
    $hostname = $_POST['host_name'];
    $timeperiod = $_POST['timeperiod'];

    $where = '';
    if (!empty($timeperiod)) {
        $_SESSION['timeperiod'] = $timeperiod;
        date_default_timezone_set('Asia/Kolkata');
        if ($timeperiod == 'today') {
            $from = date("Y-m-d 00:00:00 T", strtotime("today"));
            $to = date("Y-m-d H:i:s T"); 
        } elseif ($timeperiod == 'last24hours') {
            $from = date("Y-m-d 00:00:00 T", strtotime("-1 day"));
            $to = date("Y-m-d 23:59:59 T ", strtotime("-1 day"));
        } elseif ($timeperiod == 'thisweek') {
            $from = week_start_date(date('W') - 1 , date('Y')) . "00:00:00 IST";
            $to = date('Y-m-d ', strtotime('+6 days', strtotime($from))) . " 23:59:59 IST";
        } elseif ($timeperiod == 'thismonth') {
            $from = date("Y-m-d 00:00:00 T", strtotime("first day of last month"));
            $to = date("Y-m-d 23:59:59 T ", strtotime("last day of last month"));
        }

        $where .= " AND `date` BETWEEN '$from' AND '$to'";
    }


    $data2 = $data3 = array();
    $totalkva = $totalkwh = 0;
    $numRows = 0;
    foreach ($hostname as $host) {

        $sql = "SELECT
        tmp.socket_no AS Outlet_No,
        tmp.kwh AS kWh,
        tmp1.kva AS kVA
    FROM
        (
            SELECT
                MAX(output + 0) - MIN(output + 0) AS kwh,
                host_name,
                CASE
                    WHEN service_description = 'IPDU-OL1-Active-Energy' THEN 'OL-01'
                    WHEN service_description = 'IPDU-OL2-Active-Energy' THEN 'OL-02'
                    WHEN service_description = 'IPDU-OL3-Active-Energy' THEN 'OL-03'
                    WHEN service_description = 'IPDU-OL4-Active-Energy' THEN 'OL-04'
                    WHEN service_description = 'IPDU-OL5-Active-Energy' THEN 'OL-05'
                    WHEN service_description = 'IPDU-OL6-Active-Energy' THEN 'OL-06'
                    WHEN service_description = 'IPDU-OL7-Active-Energy' THEN 'OL-07'
                    WHEN service_description = 'IPDU-OL8-Active-Energy' THEN 'OL-08'
                    WHEN service_description = 'IPDU-OL9-Active-Energy' THEN 'OL-09'
                    WHEN service_description = 'IPDU-OL10-Active-Energy' THEN 'OL-10'
                    WHEN service_description = 'IPDU-OL11-Active-Energy' THEN 'OL-11'
                    WHEN service_description = 'IPDU-OL12-Active-Energy' THEN 'OL-12'
                    WHEN service_description = 'IPDU-OL13-Active-Energy' THEN 'OL-13'
                    WHEN service_description = 'IPDU-OL14-Active-Energy' THEN 'OL-14'
                    WHEN service_description = 'IPDU-OL15-Active-Energy' THEN 'OL-15'
                    WHEN service_description = 'IPDU-OL16-Active-Energy' THEN 'OL-16'
                    WHEN service_description = 'IPDU-OL17-Active-Energy' THEN 'OL-17'
                    WHEN service_description = 'IPDU-OL18-Active-Energy' THEN 'OL-18'
                    WHEN service_description = 'IPDU-OL19-Active-Energy' THEN 'OL-19'
                    WHEN service_description = 'IPDU-OL20-Active-Energy' THEN 'OL-20'
                    WHEN service_description = 'IPDU-OL21-Active-Energy' THEN 'OL-21'
                    WHEN service_description = 'IPDU-OL22-Active-Energy' THEN 'OL-22'
                    WHEN service_description = 'IPDU-OL23-Active-Energy' THEN 'OL-23'
                    WHEN service_description = 'IPDU-OL24-Active-Energy' THEN 'OL-24'
                    ELSE ''
                END AS socket_no
            FROM
                view_ipdus
            WHERE
                service_description LIKE 'IPDU-OL%-Active-Energy'
                AND host_name = '$host' $where
            GROUP BY
                host_name,
                socket_no
        ) AS tmp
        JOIN (
            SELECT
                ROUND(AVG(output + 0), 1) AS kva,
                host_name,
                CASE
                    WHEN service_description = 'IPDU-OL1-Apparent-Power' THEN 'OL-01'
                    WHEN service_description = 'IPDU-OL2-Apparent-Power' THEN 'OL-02'
                    WHEN service_description = 'IPDU-OL3-Apparent-Power' THEN 'OL-03'
                    WHEN service_description = 'IPDU-OL4-Apparent-Power' THEN 'OL-04'
                    WHEN service_description = 'IPDU-OL5-Apparent-Power' THEN 'OL-05'
                    WHEN service_description = 'IPDU-OL6-Apparent-Power' THEN 'OL-06'
                    WHEN service_description = 'IPDU-OL7-Apparent-Power' THEN 'OL-07'
                    WHEN service_description = 'IPDU-OL8-Apparent-Power' THEN 'OL-08'
                    WHEN service_description = 'IPDU-OL9-Apparent-Power' THEN 'OL-09'
                    WHEN service_description = 'IPDU-OL10-Apparent-Power' THEN 'OL-10'
                    WHEN service_description = 'IPDU-OL11-Apparent-Power' THEN 'OL-11'
                    WHEN service_description = 'IPDU-OL12-Apparent-Power' THEN 'OL-12'
                    WHEN service_description = 'IPDU-OL13-Apparent-Power' THEN 'OL-13'
                    WHEN service_description = 'IPDU-OL14-Apparent-Power' THEN 'OL-14'
                    WHEN service_description = 'IPDU-OL15-Apparent-Power' THEN 'OL-15'
                    WHEN service_description = 'IPDU-OL16-Apparent-Power' THEN 'OL-16'
                    WHEN service_description = 'IPDU-OL17-Apparent-Power' THEN 'OL-17'
                    WHEN service_description = 'IPDU-OL18-Apparent-Power' THEN 'OL-18'
                    WHEN service_description = 'IPDU-OL19-Apparent-Power' THEN 'OL-19'
                    WHEN service_description = 'IPDU-OL20-Apparent-Power' THEN 'OL-20'
                    WHEN service_description = 'IPDU-OL21-Apparent-Power' THEN 'OL-21'
                    WHEN service_description = 'IPDU-OL22-Apparent-Power' THEN 'OL-22'
                    WHEN service_description = 'IPDU-OL23-Apparent-Power' THEN 'OL-23'
                    WHEN service_description = 'IPDU-OL24-Apparent-Power' THEN 'OL-24'
                    ELSE ''
                END AS socket_no
            FROM
                view_ipdus
            WHERE
                service_description LIKE 'IPDU-OL%-Apparent-Power'
                AND host_name = '$host' $where
            GROUP BY
                host_name,
                socket_no
        ) AS tmp1 ON tmp.socket_no = tmp1.socket_no
    ORDER BY
        Outlet_No";
         //echo $sql;

        $result = $conn->query($sql);
        
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
                $totalkva = $totalkva + $row['kVA'];
                $totalkwh = $totalkwh + $row['kWh'];
            }
            
        }
        $data2[$host] = $data;
    }
    $data3['data'] = $data2;
     $data3['totalkwh'] = number_format($totalkwh, 2);
    $data3['totalkva'] = number_format($totalkva, 2);
    $data3['to'] = $to;
    $data3['from'] = $from;
    
    //     echo "<pre>";
    //    print_r($data3);
    $conn->close();

    // Store the data in the session
    $_SESSION['get_table_data'] = $data2;
    $_SESSION['totalkva'] = $totalkva;
    $_SESSION['totalkwh'] = $totalkwh;
    $_SESSION['host_name'] = $hostname;
   
    // $_SESSION['timeperiod']=$timeperiod;
    $_SESSION['to'] = $to;
    $_SESSION['from'] = $from;
    echo json_encode($data3);
}


function week_start_date($week, $year, $format = 'Ymd', $date = FALSE)
{

    if ($date) {
        $week = date("W", strtotime($date));
        $year = date("o", strtotime($date));
    }

    $week = sprintf("%02s", $week);

    $desiredMonday = date("Y-m-d ", strtotime("$year-W$week-1"));

    return $desiredMonday;
}
?>