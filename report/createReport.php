<?php
require('fpdf.php');
$items = $_POST['chk'];
$startDate=$_POST['startDate'];
$endDate=$_POST['endDate'];
$hostname=$_POST['hostname'];

$url='http://localhost/visualization/report/';

$pdf = new FPDF();
$i=0;
foreach ($items as $value){
    $url="$url"."createImage.php?itemid=$value&startDate=$startDate&endDate=$endDate#.jpg";
    if($i%2==0) {
        $pdf->AddPage();
        $pdf->Image("$url",5,30,200,100);
    }else
    $pdf->Image("$url",5,180,200,100);
    $i++;
}
$pdf->Output("report_$hostname.pdf",'D')
?>
