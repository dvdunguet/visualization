<?php
include_once ("../help/helper.php");
include_once ("../database/zabbix.php");

define("UNSIGNED",3);
define("FLOAT",0);

    $graphtype = get_graphs_detail($_GET['graphid']);
    $items = get_items($_GET['graphid']);
    $numberItems=count($items);

    $head = array();
    $datas = array();
    $clockHistory=array();
    $clockTrends=array();
    $haveTrends =true;
    $timeHistory=array();


    foreach ($items as $value) {
        $names = get_item_detail($value['itemid']);
        array_push($head, [$names['name'],$names['key_'],$value['drawtype'],$value['type'],$names['units']]);
        $str = '';
        if ($names['value_type'] == UNSIGNED) {
            $str = '_uint';
        }elseif ($names['value_type'] == FLOAT)
            $str = '';

        if($names['trends']==0){$haveTrends =false;}
        $history=convert_to_timestamp($names['history']);
        array_push($timeHistory,$history);

        $time = get_time_min_from_history($str,$value['itemid']);
        array_push($clockHistory,$time['MIN(clock)']);

        $time = get_time_min_from_trends($str,$value['itemid']);
        array_push($clockTrends,$time['MIN(clock)']);

    }
        $timeHistory=min($timeHistory);

        $minTime=min(array_diff(array_merge($clockHistory, $clockTrends),array(null)));

        $timeHistory=time()-$timeHistory;

    foreach ($items as $value) {
        $names = get_item_detail($value['itemid']);

        $str = '';
        if ($names['value_type'] == UNSIGNED) {
            $str = '_uint';
        }elseif ($names['value_type'] == FLOAT) {
            $str = '';
        }
            if($haveTrends == false || $_GET['startDate']<$timeHistory){
        $items = get_data_from_history($str,$value['itemid'],$_GET['startDate'],$_GET['endDate']);
            }else{
                get_data_from_trends($str,$value['itemid'],$_GET['startDate'],$_GET['endDate']);
            }
        array_push($datas, $items);
    }

    foreach ($head as $key =>$value){
        $value[1]=get_string_between($value[1],'[',']');
        $value[1]=explode(',',$value[1]);
        $tmp=explode(' ',$value[0]);
        foreach ($tmp as $vv){
            if (strpos($vv, '$') !== false) {
                $value[0]=str_replace($vv,$value[1][substr("$vv", 1, 1)-1],$value[0]);
            }
        }
        $head[$key]= [$value[0],$value[2],$value[3],$value[4]];
    }

    $itemvalue=array();
    foreach ($datas as $keys => $values){
        $temp=array();
        foreach ($values as $key => $value){
            $temp[$value['clock']]=$value['value'];
        }
        array_push($itemvalue,$temp);
    }
    $conn=null;
$data=array();

    foreach ($itemvalue as $k => $v){
        $sum= array_sum($v)/count($v);
        array_push($data,$sum);
    }

echo "  google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Name', 'Value']";
    $count=count($head);
    for($i=0;$i<$count;$i++){
        if(strcmp($head[$i][3],'B')==0 || strcmp($head[$i][3],'Bps')==0){
            $tmp=convert_data2($data[$i]);
        }else
        $tmp=convert_data1($data[$i]);
        echo ",\n['".$head[$i][0]."',{v:$data[$i],f:'$tmp".$head[$i][3]."'}]";
    }
echo "
        ]);

        var options = {";
if($graphtype['show_3d']==1)
echo            'is3D: true,';
if($graphtype['graphtype']==3){
    echo "slices: {\n";
    for($i=0;$i<$numberItems;$i++){
        echo "$i: {offset: 0.1},\n";
    }
    echo"            \n}\n";
}
echo "        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }";
?>


<!--    //    dateFrom = moment().subtract(7,'d').format('DD/MM/YYYY HH:mm');-->
    $(function () {
        $('#datetimepicker6').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            minDate: new Date(<?php if(isset($minTime)) echo $minTime*1000; else echo time()-604800?>),
            maxDate: new Date(),
            date:new Date(<?php echo $_GET['startDate']*1000 ?>),
        });
        $('#datetimepicker7').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            minDate: new Date(<?php if(isset($minTime)) echo $minTime*1000; else echo time()-604800?>),
            maxDate: new Date(),
            date:new Date(<?php echo $_GET['endDate']*1000 ?>),
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            $("#startDate").val(e.date.unix());

        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
            $("#endDate").val(e.date.unix());
        });

    });



