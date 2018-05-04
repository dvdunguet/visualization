<?php
include_once ("help/session.php");
include_once ("database/zabbix.php");

if(isset($_GET['groupid'])){
    session_set('groupid',$_GET['groupid']);

}
if( isset($_GET['hostid'])){
    session_set('hostid',$_GET['hostid']);
}
if( isset($_GET['graphid'])){
    session_set('graphid',$_GET['graphid']);
}
$sql_host1=$sql_host2='';
if(isset($_GET['groupid']) && $_GET['groupid'] != 0){
    $sql_host1=', hosts_groups hg ';
    $sql_host2='hg.groupid='.$_GET['groupid'].' and hg.hostid=h.hostid AND';
}

if(!isset($_SESSION['datatype'])){
    $_SESSION['datatype']=0;
}

if( isset($_GET['datatype'])){
    $_SESSION['datatype']=$_GET['datatype'];
}

if( isset($_GET['startDate'])){
    $_SESSION['startDate']=$_GET['startDate'];
}elseif (!isset($_SESSION['startDate'])){
    $_SESSION['startDate']=time();
}

if( isset($_GET['endDate'])){
    $_SESSION['endDate']=$_GET['endDate'];
}elseif (!isset($_SESSION['endDate'])){
    $_SESSION['endDate']=time()-604800;
}

$groups =get_groups();
$hosts = get_hosts($sql_host1,$sql_host2);

if(isset($_SESSION['graphid'])){
    $graphtype = get_graphs_detail($_SESSION['graphid']);
}

$hostInGroup=false;

foreach ($hosts as $key => $value){
    if($value['hostid'] == session_get('hostid')){
        $hostInGroup=true;
    }
}


if($hostInGroup==false){ session_delete('hostid'); }

$sql_graph1=$sql_graph2='';

if(session_isset('hostid') && session_get('hostid')!=0){
    $sql_graph2='i.hostid='.session_get('hostid').' AND';
}elseif(session_isset('groupid') && session_get('groupid')!=0){
    $sql_graph1=', hosts_groups hg';
    $sql_graph2='hg.groupid='.session_get('groupid').' AND hg.hostid=i.hostid AND';
}


$graphs = get_graphs($sql_graph1,$sql_graph2);

$graphsInHost=false;

foreach ($graphs as $key => $value){
    if($value['graphid'] == $_SESSION['graphid']){
        $graphsInHost=true;
    }
}
if($graphsInHost==false){ unset($_SESSION['graphid']); }

db_close();

include_once ("charts.views.php");

?>