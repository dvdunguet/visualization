<?php
include_once ("help/session.php");
include_once ("help/helper.php");
include_once ("database/zabbix.php");

if(isset($_GET['groupid'])){
    session_set('groupid',$_GET['groupid']);

}
if( isset($_GET['hostid'])){
    session_set('hostid',$_GET['hostid']);
}
$sql_host1=$sql_host2='';
if(isset($_GET['groupid']) && $_GET['groupid'] != 0){
    $sql_host1=', hosts_groups hg ';
    $sql_host2='hg.groupid='.$_GET['groupid'].' and hg.hostid=h.hostid AND';
}

$groups =get_groups();
$hosts = get_hosts($sql_host1,$sql_host2);

$hostInGroup=false;

foreach ($hosts as $key => $value){
    if($value['hostid'] == session_get('hostid')){
        $hostInGroup=true;
    }
}

if($hostInGroup==false){ session_delete('hostid'); }

if(session_isset('hostid') && session_get('hostid')!=0){
    $dataitems=get_items_by_hostid(session_get('hostid'));
}

if(isset($dataitems)){
    $items=array();
    foreach ($dataitems as $key => $value){
        $value['key_']=get_string_between($value['key_'],'[',']');
        $value['key_']=explode(',',$value['key_']);
        $tmp=explode(' ',$value['name']);
        foreach ($tmp as $vv){
            if (strpos($vv, '$') !== false) {
                $value['name']=str_replace($vv,$value['key_'][substr("$vv", 1, 1)-1],$value['name']);
            }
        }
        $items[$key]['itemid']=$value['itemid'];
        $items[$key]['name']=$value['name'];
    }
}


db_close();

include_once("views/report.view.php");

?>