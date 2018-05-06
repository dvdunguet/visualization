<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');
require_once ('jpgraph/jpgraph_date.php');

require_once ('../database/zabbix.php');
require_once ('../help/helper.php');

$itemid = $_GET['itemid'];
$startDate=$_GET['startDate'];
$endDate=$_GET['endDate'];
$str='';

$item=get_item_detail($itemid);
if($item['value_type']==0){
    $str='';
}elseif ($item['value_type']==3){
    $str='_uint';

}
$item['key_']=get_string_between($item['key_'],'[',']');
$item['key_']=explode(',',$item['key_']);
$tmp=explode(' ',$item['name']);
foreach ($tmp as $vv){
    if (strpos($vv, '$') !== false) {
        $item['name']=str_replace($vv,$item['key_'][substr("$vv", 1, 1)-1],$item['name']);
    }
}

$items=get_data_from_history("$str","$itemid","$startDate","$endDate");

if(!empty($items)){
    $datas=array();
    foreach ($items as $value){
        $datas[$value['clock']]=$value['value'];
    }
    ksort($datas);
    $time=0;
    foreach ($datas as $key => $value){
        if($time!=0) {
            if($key-$time>600){
                $datas[$time+1]='';
            };
        }
        $time=$key;
    }
    ksort($datas);
    $data = array();
    $xdata = array();
    foreach ($datas as $key => $value){
        $data[]= $value;
        $xdata[]=$key;
        ////    print_r($value);
    }
    //print_r($data);
    // Create the new graph
    $graph = new Graph(1000,400);

    // Slightly larger than normal margins at the bottom to have room for
    // the x-axis labels
    $graph->SetMargin(60,10,20,130);

    // Fix the Y-scale to go between [0,100] and use date for the x-axis
    $graph->SetScale('datlin');
    $graph->title->Set($item['name'].'('.$item['units'].')');

    // Set the angle for the labels to 90 degrees
    $graph->xaxis->SetLabelAngle(90);

    $line = new LinePlot($data,$xdata);
    //$line->SetLegend('Year 2005');
    //$line->SetFillColor('lightblue@0.5');
    $graph->Add($line);
    $graph->img->SetImgFormat('jpeg');
    $graph->Stroke();
}else{

    $data = array(0);
    $xdata = array(0);

    // Create the new graph
    $graph = new Graph(800,400);

    // Slightly larger than normal margins at the bottom to have room for
    // the x-axis labels
    $graph->SetMargin(40,40,30,130);

    // Fix the Y-scale to go between [0,100] and use date for the x-axis
    $graph->SetScale('datlin',0,100);
    $graph->title->Set($item['name']);

    // Set the angle for the labels to 90 degrees
    $graph->xaxis->SetLabelAngle(90);

    $line = new LinePlot($data,$xdata);
    //$line->SetLegend('Year 2005');
    //$line->SetFillColor('lightblue@0.5');
    $graph->Add($line);
    $graph->img->SetImgFormat('jpeg');
    $graph->Stroke();
}
?>