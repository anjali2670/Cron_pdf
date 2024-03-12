<?php
session_start();
require_once("TCPDF/tcpdf.php");

// Create new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 8);

// Add a page
$pdf->AddPage();
$html = '';

if (isset($_SESSION['get_table_data'])) {
    
    $html .= '<table cellpadding="5" style="text-align:center;">';
    $html .= '<tr><th style="background-color:#EDEADE; color:black; padding: 5px;" colspan="2"><h2>UIDAI MANESAR IPDU\'s POWER CONSUMPTION REPORT</h2></th></tr>';
    $html .= '</table></div>';
    
    $html .= '<table style="text-align:center;">';
    $html .= '<tr><th style="padding: 5px;" colspan="2"><p style="color: grey; margin-right: 200px;">' . $_SESSION['from'] . ' to ' . $_SESSION['to'] . '</p></th></tr>';
    $html .= '</table></div>';
    // $html .= '<p style="color: grey; margin-right: 200px;">' . $_SESSION['from'] . ' to ' . $_SESSION['to'] . '</p>';
   

     $totalTableHTML = '<div style="float: right; display:flex;"><table cellpadding="1" cellspacing="1" style="text-align:center;"><tr><td style="width:50%;" ></td><td style="width:50%;">';
    $totalTableHTML .= '<table border="1" style="text-align:center; margin-bottom:20px; width:100%; padding:5px;"><thead><tr><th style="background-color:#696969; color:white;">Total kWh</th><th style="background-color:#696969; color:white;">Total kVA</th></tr></thead><tbody>';
    $totalTableHTML .= '<tr>' .
    
        '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' . number_format($_SESSION['totalkwh'], 2) . '</td>' .
        '<td style="background-color:#BDF5BD; color:black;  font-weight: bold; ">' . number_format($_SESSION['totalkva'],2) . '</td>' .
        '</tr>';
    $totalTableHTML .= '</tbody></table></td></tr></table></div></div>';
    $html .= $totalTableHTML;

    
    // $averageKVA=0;
    $totalKwh = $totalKva = 0;
    $hostTablesHTML = '';
    $i = 0;
    $mod = '';
    $html .= '<table cellpadding="1" cellspacing="1" style="text-align:center;">';
    foreach ($_SESSION['get_table_data'] as $hostName => $hostData) {
        $i++;
        $mod = $i % 2;
        if ($mod == 1) {
            $html .= '<tr>';
        }
       
       // $tableWidth = (strlen($hostName) === 1) ? '100%' : '50%';
        $hostTotalKwh = $hostTotalKva = 0;

       // $html .='<div style="display:flex;">';
        $html .= '<td><table border="1" style="padding:8px;"><thead><tr><th colspan="3" style="background-color:white; color:black; text-align:center;">' . $hostName . '</th></tr><tr><th style="padding: 8px; background-color:#696969; color:white; text-align:center;">Outlet_No</th><th style="background-color:#696969; color:white; text-align:center;">kWh</th><th style="background-color:#696969; color:white; text-align:center;">kVA</th></tr></thead><tbody>';

        foreach ($hostData as $row) {
            $formattedKwh = number_format($row['kWh'], 2);
            $formattedKva = number_format($row['kVA'], 2); 
            $html .= '<tr style="padding: 8px;">' .
                '<td style="padding: 8px; text-align:center; background-color:#fffdd0; color:black;">' . $row['Outlet_No'] . '</td>' .
                '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $formattedKwh . '</td>' .
                '<td style="padding: 8px; text-align:center; background-color:#BDF5BD;">' . $formattedKva . '</td>' .
                '</tr>';
            $hostTotalKwh += floatval($formattedKwh);
            $hostTotalKva += floatval($formattedKva);
        }
               
        $html .= '<tr style="background-color:#696969; color:white;">' .
            '<td style="padding: 8px; text-align:center; ">Total Sum</td>' .
            '<td style="padding: 8px; text-align:center;">' . number_format($hostTotalKwh, 2). '</td>' .
            '<td style="padding: 8px; text-align:center;">' . number_format($hostTotalKva, 2). '</td>' .
            '</tr>' ;
        $html .= '</tbody></table></td>';
        if ($mod == 0) {
            $html .= '</tr>';
              $html .= '<div style="height:200px;">&nbsp;</div>';
        }
       
        // $html .= '<div style="height:200px;">&nbsp;</div>
        // <div style="height:200px;">&nbsp;</div>';
        
    }
    if ($mod == 1) {
        $html .= '</tr>';
    }
    $html .= '</table>';
     //echo $html;
    $pdf->writeHTML($html);

    $pdf->Output('IPDU.pdf', 'I');
}