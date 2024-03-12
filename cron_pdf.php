<?php
require_once("TCPDF/tcpdf.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reportdata";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$timeperiod ='thisweek';
$where ='';
$to = '';
$from = '';
if($timeperiod=='thisweek'){
date_default_timezone_set('Asia/Kolkata');
$from = week_start_date(date('W') - 1, date('Y')) . "00:00:00 IST"." ";
$to = date(" ".'Y-m-d ', strtotime('+6 days', strtotime($from))) . " 23:59:59 IST";
$where .= " AND `date` BETWEEN '$from' AND '$to'";

$html = $html2 = '';
$overallTotalKwh = 0;
$overallTotalKva = 0;
$html .= '<table cellpadding="5" style="text-align:center;">';
$html .= '<tr><th style="background-color:#EDEADE; color:black; padding: 5px;" colspan="2"><h2>UIDAI MANESAR IPDU\'s POWER CONSUMPTION REPORT</h2></th></tr>';
$html .= '</table></div>';

$html .= '<table style="text-align:center;">';
$html .= '<tr><th style="padding: 5px;" colspan="2"><p style="color: grey; margin-right: 200px;">' . $from . 'to' . $to . '</p></th></tr>';
$html .= '</table></div>';


$html2 .= '<table cellpadding="1" cellspacing="1" style="text-align:center;">';

$sql = "SELECT distinct  host_name FROM view_ipdus";
$result = mysqli_query($conn, $sql);

$html2 .= '<tr>';

$i = 0;
$overallTotalKwh = 0;
$overallTotalKva = 0;

while ($row1 = mysqli_fetch_assoc($result)) {
    $i++;
    $host_name = $row1['host_name'];
    $totalKwh = 0;
    $totalKva = 0;

    $sql = "SELECT tmp.socket_no AS Outlet_No, tmp.kwh AS kWh, tmp1.kva AS kVA 
     FROM 
         (SELECT MAX(output + 0) - MIN(output + 0) AS kwh, 
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
          FROM view_ipdus 
          WHERE service_description LIKE 'IPDU-OL%-Active-Energy' 
                AND host_name = '$host_name' $where
          GROUP BY host_name, socket_no 
         ) AS tmp 
     JOIN 
         (SELECT ROUND(AVG(output + 0), 1) AS kva, 
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
          FROM view_ipdus 
          WHERE service_description LIKE 'IPDU-OL%-Apparent-Power' 
                AND host_name = '$host_name' $where
          GROUP BY host_name, socket_no
         ) AS tmp1 ON tmp.socket_no = tmp1.socket_no 
     ORDER BY Outlet_No";
    $resultData = $conn->query($sql);

    // Generate table header
    if ($i % 2 == 1) {
        $html2 .= '<tr>';
    }
    $html2 .= '<td><table border="1" style="padding:8px;"><thead><tr><th colspan="3" style="background-color:white; color:black; text-align:center;">' . $host_name . '</th></tr><tr><th style="padding: 8px; background-color:#696969; color:white; text-align:center;">Outlet_No</th><th style="background-color:#696969; color:white; text-align:center;">kWh</th><th style="background-color:#696969; color:white; text-align:center;">kVA</th></tr></thead><tbody>';

    while ($row = $resultData->fetch_assoc()) {
        $kWh = number_format($row["kWh"], 2);
        $kVa = number_format($row["kVA"], 2);
        $totalKwh += $kWh;
        $totalKva += $kVa;

        $html2 .= '<tr>' .
            '<td style="padding: 8px; text-align:center; background-color:#fffdd0; color:black;">' . $row["Outlet_No"] . '</td>' .
            '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $kWh . '</td>' .
            '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $kVa . '</td>' .
            '</tr>';
    }

    $html2 .= '<tr style="padding: 8px; text-align:center; background-color:#black; color:white;">' .
        '<td style="padding: 8px; text-align:center; ">Total</td>' .
        '<td style="padding: 8px; text-align:center; ">' .number_format($totalKwh,2) . '</td>' .
        '<td style="padding: 8px; text-align:center; ">' .number_format($totalKva,2) . '</td>' .
        '</tr>';
    $html2 .= '</tbody></table></td>';
    $overallTotalKwh += $totalKwh;
    $overallTotalKva += $totalKva;
    if ($i % 2 == 0) {
        $html2 .= '</tr>';
        $html2 .= '<div style="height:200px;">&nbsp;</div>';
    }
}

if ($i % 2 == 1) {
    $html2 .= '<td></td></tr>';
}

$html2 .= '</table>';


$totalTableHTML = '<div style="float: right; display:flex;"><table cellpadding="1" cellspacing="1" style="text-align:center;"><tr><td style="width:50%;" ></td><td style="width:50%;">';
$totalTableHTML .= '<table border="1" style="text-align:center; margin-bottom:20px; width:100%; padding:5px;"><thead><tr><th style="background-color:#696969; color:white;">Total kWh</th><th style="background-color:#696969; color:white;">Total kVA</th></tr></thead><tbody>';
$totalTableHTML .= '<tr>' .
    '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' .number_format($overallTotalKwh,2) . '</td>' .
    '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' . number_format($overallTotalKva,2). '</td>' .
    '</tr>';
$totalTableHTML .= '</tbody></table></td></tr></table></div></div>';

$html .=  $totalTableHTML;
$html .= $html2;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 8);
$pdf->AddPage();
$pdf->writeHTML($html);
//echo $html;
$from1 = week_start_date(date('W') - 1, date('Y'));
$to1 = date('Y-m-d ', strtotime('+6 days', strtotime($from)));
$pdf->Output("D:/ANJALI/htdocs/grafana/pdf/{$from1}-{$to1}.pdf", 'F');
mysqli_close($conn);
}else{

    date_default_timezone_set('Asia/Kolkata');
   $from = date("Y-m-d 00:00:00 T"." ", strtotime("first day of last month"));
   $to = date(" "."Y-m-d 23:59:59 T ", strtotime("last day of last month"));
   $where .= " AND `date` BETWEEN '$from' AND '$to'";

$html = $html2 = '';
$overallTotalKwh = 0;
$overallTotalKva = 0;
$html .= '<table cellpadding="5" style="text-align:center;">';
$html .= '<tr><th style="background-color:#EDEADE; color:black; padding: 5px;" colspan="2"><h2>UIDAI MANESAR IPDU\'s POWER CONSUMPTION REPORT</h2></th></tr>';
$html .= '</table></div>';

$html .= '<table style="text-align:center;">';
$html .= '<tr><th style="padding: 5px;" colspan="2"><p style="color: grey; margin-right: 200px;">' . $from . 'to' . $to . '</p></th></tr>';
$html .= '</table></div>';


$html2 .= '<table cellpadding="1" cellspacing="1" style="text-align:center;">';

$sql = "SELECT distinct  host_name FROM view_ipdus";
$result = mysqli_query($conn, $sql);

$html2 .= '<tr>';

$i = 0;
$overallTotalKwh = 0;
$overallTotalKva = 0;

while ($row1 = mysqli_fetch_assoc($result)) {
    $i++;
    $host_name = $row1['host_name'];
    $totalKwh = 0;
    $totalKva = 0;

    $sql = "SELECT tmp.socket_no AS Outlet_No, tmp.kwh AS kWh, tmp1.kva AS kVA 
     FROM 
         (SELECT MAX(output + 0) - MIN(output + 0) AS kwh, 
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
          FROM view_ipdus 
          WHERE service_description LIKE 'IPDU-OL%-Active-Energy' 
                AND host_name = '$host_name' $where
          GROUP BY host_name, socket_no 
         ) AS tmp 
     JOIN 
         (SELECT ROUND(AVG(output + 0), 1) AS kva, 
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
          FROM view_ipdus 
          WHERE service_description LIKE 'IPDU-OL%-Apparent-Power' 
                AND host_name = '$host_name' $where
          GROUP BY host_name, socket_no
         ) AS tmp1 ON tmp.socket_no = tmp1.socket_no 
     ORDER BY Outlet_No";
    $resultData = $conn->query($sql);

    // Generate table header
    if ($i % 2 == 1) {
        $html2 .= '<tr>';
    }
    $html2 .= '<td><table border="1" style="padding:8px;"><thead><tr><th colspan="3" style="background-color:white; color:black; text-align:center;">' . $host_name . '</th></tr><tr><th style="padding: 8px; background-color:#696969; color:white; text-align:center;">Outlet_No</th><th style="background-color:#696969; color:white; text-align:center;">kWh</th><th style="background-color:#696969; color:white; text-align:center;">kVA</th></tr></thead><tbody>';

    while ($row = $resultData->fetch_assoc()) {
        $kWh = number_format($row["kWh"], 2);
        $kVa = number_format($row["kVA"], 2);
        $totalKwh += $kWh;
        $totalKva += $kVa;

        $html2 .= '<tr>' .
            '<td style="padding: 8px; text-align:center; background-color:#fffdd0; color:black;">' . $row["Outlet_No"] . '</td>' .
            '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $kWh . '</td>' .
            '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $kVa . '</td>' .
            '</tr>';
    }

    $html2 .= '<tr style="padding: 8px; text-align:center; background-color:#black; color:white;">' .
        '<td style="padding: 8px; text-align:center; ">Total</td>' .
        '<td style="padding: 8px; text-align:center; ">' .number_format($totalKwh,2) . '</td>' .
        '<td style="padding: 8px; text-align:center; ">' .number_format($totalKva,2) . '</td>' .
        '</tr>';
    $html2 .= '</tbody></table></td>';
    $overallTotalKwh += $totalKwh;
    $overallTotalKva += $totalKva;
    if ($i % 2 == 0) {
        $html2 .= '</tr>';
        $html2 .= '<div style="height:200px;">&nbsp;</div>';
    }
}

if ($i % 2 == 1) {
    $html2 .= '<td></td></tr>';
}

$html2 .= '</table>';


$totalTableHTML = '<div style="float: right; display:flex;"><table cellpadding="1" cellspacing="1" style="text-align:center;"><tr><td style="width:50%;" ></td><td style="width:50%;">';
$totalTableHTML .= '<table border="1" style="text-align:center; margin-bottom:20px; width:100%; padding:5px;"><thead><tr><th style="background-color:#696969; color:white;">Total kWh</th><th style="background-color:#696969; color:white;">Total kVA</th></tr></thead><tbody>';
$totalTableHTML .= '<tr>' .
    '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' .number_format($overallTotalKwh,2) . '</td>' .
    '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' . number_format($overallTotalKva,2). '</td>' .
    '</tr>';
$totalTableHTML .= '</tbody></table></td></tr></table></div></div>';

$html .=  $totalTableHTML;
$html .= $html2;



$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 8);
$pdf->AddPage();
$pdf->writeHTML($html);
//echo $html;
$from2 = date("Y-m-d", strtotime("first day of last month"));
$to2 = date("Y-m-d", strtotime("last day of last month"));
$pdf->Output("D:/ANJALI/htdocs/grafana/pdf/{$from2}-{$to2}.pdf", 'F');
mysqli_close($conn);

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
