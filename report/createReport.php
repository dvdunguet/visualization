<?php
require('fpdf.php');
$items = $_POST['chk'];
$startDate=$_POST['startDate'];
$endDate=$_POST['endDate'];
$hostname=$_POST['hostname'];

$url='http://localhost/visualization/report/';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Report of $hostname",0,0,'C');
$i=0;
foreach ($items as $value){
    $url="$url"."createImage.php?itemid=$value&startDate=$startDate&endDate=$endDate#.jpg";
    if($i==0){
        $pdf->Image("$url",5,40,200,100);
    }elseif($i%2==0) {
        $pdf->AddPage();
        $pdf->Image("$url",5,40,200,100);
    }else
    $pdf->Image("$url",5,150,200,100);
    $i++;
}
$pdf->Output("report_$hostname.pdf",'I');

?>
