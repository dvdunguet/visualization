<?php
include_once ("database.php");

function get_groups(){
    $sql='SELECT  g.groupid, g.name FROM groups g where EXISTS (SELECT NULL FROM hosts h,items i,graphs_items gi,graphs gr,hosts_groups hg WHERE hg.hostid=h.hostid AND h.status IN (\'0\',\'1\') AND hg.hostid=i.hostid AND i.itemid=gi.itemid AND gi.graphid=gr.graphid AND gr.flags IN (\'0\',\'4\') AND g.groupid=hg.groupid)';
    $result=db_get_list($sql);
    return $result;
}

function get_hosts($sql_host1,$sql_host2){
    $sql="SELECT h.hostid , h.name, h.status FROM hosts h $sql_host1 WHERE h.flags IN (0,4) AND $sql_host2 h.status IN (0,1) AND EXISTS (SELECT NULL FROM items i,graphs_items gi,graphs g WHERE i.hostid=h.hostid AND i.itemid=gi.itemid AND gi.graphid=g.graphid AND g.flags IN (0,4))";
    $result=db_get_list($sql);
    return $result;
}

function get_graphs_detail($graphid){
    $sql="SELECT graphtype,width,height,show_3d FROM graphs WHERE graphid=".$graphid.";";
    $result=db_get_row($sql);
    return $result;
}

function get_graphs($sql_graph1,$sql_graph2){
    $sql="SELECT g.graphid, g.name FROM graphs g, graphs_items gi, items i $sql_graph1 , hosts h WHERE $sql_graph2 gi.graphid=g.graphid AND  i.itemid=gi.itemid AND g.graphid=gi.graphid AND h.hostid=i.hostid AND h.status<>3 AND g.flags IN ('0','4') GROUP BY g.graphid ORDER BY g.name";
    $result=db_get_list($sql);
    return $result;
}

function get_items($graphid){
    $sql=" SELECT itemid,drawtype,type FROM graphs_items WHERE graphs_items.graphid=".$graphid.";";
    $result=db_get_list($sql);
    return $result;
}

function get_item_detail($itemid){
    $sql='SELECT name,key_,value_type,delay,trends,history,units FROM items WHERE items.itemid='.$itemid.';';
    $result=db_get_row($sql);
    return $result;
}
function get_data_from_history($str,$itemid,$startDate,$endDate){
    $tmp='';
    if(isset($startDate)&&isset($endDate)){
        $tmp="AND (clock BETWEEN $startDate AND $endDate)";
    }
    $sql="SELECT clock,value FROM history$str WHERE itemid=$itemid $tmp ORDER BY clock;";
    $result=db_get_list($sql);
    return $result;
}



function get_data_from_trends($str,$itemid,$startDate,$endDate){
    $tmp='';
    if(isset($startDate)&&isset($endDate)){
        $tmp="AND (clock BETWEEN $startDate AND $endDate)";
    }
    $sql="SELECT clock,value_avg as value FROM trends$str WHERE itemid=$itemid $tmp ORDER BY clock;";
    $result=db_get_list($sql);
    return $result;
    echo $sql;
}
function get_time_min_from_history($str,$itemid){
    $sql="SELECT MIN(clock) FROM trends$str WHERE itemid=" . $itemid . ';';
    $result=db_get_row($sql);
    return $result;
}
function get_time_min_from_trends($str,$itemid){
    $sql="SELECT MIN(clock) FROM history$str WHERE itemid=" . $itemid . ';';
    $result=db_get_row($sql);
    return $result;
}

?>