<?php
include_once ("../help/helper.php");
include_once ("../database/zabbix.php");

define("UNSIGNED",3);
define("FLOAT",0);

    $graphtype = get_graphs_detail($_GET['graphid']);
//    print_r($graphtype);
echo "function legendFormatter(data) {
  
var g = data.dygraph;

  // TODO(danvk): deprecate this option in place of {legend: 'never'}
  // XXX should this logic be in the formatter?
  if (g.getOption('showLabelsOnHighlight') !== true) return '';

  var sepLines = g.getOption('labelsSeparateLines');
  var html;

  if (typeof data.x === 'undefined') {
    // TODO: this check is duplicated in generateLegendHTML. Put it in one place.
    if (g.getOption('legend') != 'always') {
      return '';
    }
    
    html = '<br>';
    html+='<table>';
    for (var i = 0; i < data.series.length; i++) {
      var series = data.series[i];
      if (!series.isVisible) continue;

      if (html !== '') html += sepLines ? '<br/>' : ' ';
      html+='<tr>';
      html += \"<td><span style='font-weight: bold; color: \" + series.color + \";'>\" + series.dashHTML + \" \" + series.labelHTML + \"</span></td>\";
      html+='</tr>';
    }
    html+='</table>'
    
    return html;
  }

  html = data.xHTML ;
  html+='<table>';
  for (var i = 0; i < data.series.length; i++) {
    var series = data.series[i];
    if (!series.isVisible) continue;
    if (sepLines) html += '<br>';
    html+='<tr>';
    var cls = series.isHighlighted ? ' class=\"highlight\"' : '';
    html += \"<td><span\" + cls + \"> <b><span style='color: \" + series.color + \";'>\" + series.labelHTML + \"</span></b>:&#160;\" + series.yHTML + \"</span></td>\";
    html += '</tr>';
  }
  html+='</table>';
  return html;
}";
    echo "g2 = new Dygraph(
        document.getElementById(\"graphdiv2\"),\n[\n";

    $items = get_items($_GET['graphid']);

    $head = array();
    $datas = array();

    foreach ($items as $value) {
        $names = get_item_detail($value['itemid']);
        array_push($head, [$names['name'],$names['key_'],$value['drawtype'],$value['type'],$names['units']]);
        $str = '';
        if ($names['value_type'] == UNSIGNED) {
            $str = '_uint';
        }elseif ($names['value_type'] == FLOAT)
            $str = '';

        if($_GET['datatype']==0)
            $items=get_data_from_history($str,$value['itemid']);
        elseif ($_GET['datatype']==1)
            $items=get_data_from_trends($str,$value['itemid']);

        if($_GET['datatype']==0)
        $time=convert_to_timestamp($names['delay']);
        elseif ($_GET['datatype']==1)
            $time=3600;
        $count=0;
        $timestamp;
        foreach ($items as $key => $value){
            if($count!=0){
                if($value['clock']-$timestamp>2*$time)
                    array_push($items,array("clock" => $timestamp+$time , "value" => "NaN"));
            }
            $timestamp=$value['clock'];
            $count++;
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
//        echo "$value[0] $value[1] $value[2] $value[3] $value[4]";
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
    db_close();

    $int =max(array_keys($itemvalue));
    $arr_1=array();
    $arr_2=array();

    for($k=0;$k<=$int;$k++){
        $arr_1[]='null';
//        $arr_2[]='NaN';
    }

    $count=0;
//    print_r($itemvalue);
    $result = [];
    for($i=0;$i<=$int;$i++){
        if($i==0){
            $arr=array();
            for($j=0;$j<$int;$j++){
                $arr[]='null';
            }
            foreach($itemvalue[$i] as $key => $value){
                $key=$key-($key%60);
                $temp=array();
                $temp[]=$value;
                $result[$key] = array_merge($temp,$arr);
            }
        }
        else{
            $arr=array();
            for($j=0;$j<$i;$j++){
                $arr[]='null';
            }
            foreach($itemvalue[$i] as $key => $value){
                $key=$key-($key%60);
                if(isset($result[$key]))
                    $result[$key][$i]=$value;
                else{
                    $result[$key]=$arr_1;
                    $result[$key][$i]=$value;
                }
            }
        }
    }
    ksort($result);
//    $timestamp;
//    $count=0;
//    foreach ($result as $key => $value){
//        if($count!=0 && $key-$timestamp>1200){
//            $result[$timestamp+1]=$arr_2;
//        }
//        $timestamp=$key;
//        $count++;
//    }
//    ksort($result);
    foreach ($result as $key => $value){
        echo '[';
//        echo $key;
        echo 'new Date("'.date('Y-m-d H:i:s',$key).'")';
//        print_r($value);
        foreach ($value as $k => $v){
            echo ','.$v;
        }
        echo "],\n";
    }
    echo        "]\n,{\n";
    echo "legend: \"always\",\n";
    echo "legendFormatter: legendFormatter,\n";
    if(strcmp($head[0][3],'B')==0 || strcmp($head[0][3],'Bps')==0)
    echo "labelsKMG2: true,\n";
    else
        echo "labelsKMB: true,\n";
    echo "labels:[\"x\",";
    foreach ($head as $value){
        echo "\"".$value[0];
        if($value[3] != null)
            echo "($value[3])";
        echo "\",";
    }
    echo "],\n";
    if($graphtype['graphtype']==0){
        echo "series: {\n";
        foreach ($head as $value){
            echo "'".$value[0]."':{\n";
            if($value[1]==0){
                echo 'connectSeparatedPoints: true';
            }elseif ($value[1]==1||$value[1]==5){
                echo 'connectSeparatedPoints: true,
                      fillGraph: true';
            }elseif ($value[1]==2){
                echo 'connectSeparatedPoints: true,
                      strokeWidth: 3';
            }elseif ($value[1]==3){
                    echo 'connectSeparatedPoints:false';
            }elseif ($value[1]==4){
                echo 'connectSeparatedPoints: true,
                      strokePattern: [10, 2, 5, 2]';

            }

            echo "\n},\n";
        }
        echo "\n},\n";
        echo "\n
            }          // options
        );"."\n";
    }elseif ($graphtype['graphtype']==1){
        echo '  connectSeparatedPoints: true,
        stackedGraph: true
        }          // options
        );'."\n";
    }

?>

