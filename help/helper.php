<?php
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function convert_to_timestamp($time){
    if(strpos($time,'s')){
        $time=str_replace("s","",$time);
    }elseif (strpos($time,'m')){
        $time=str_replace("m","",$time)*60;
    }elseif (strpos($time,'h')){
        $time=str_replace("h","",$time)*3600;
    }elseif (strpos($time,'d')){
        $time=str_replace("d","",$time)*86400;
    }elseif (strpos($time,'w')){
        $time=str_replace("w","",$time)*604800;
    }
    return $time;
}
function convert_data1($number){
    if(($number)>1000){
        if(($number)>1000000){
            if(($number)>1000000000){
                $number=$number/1000000000 .'B';
            }else $number=$number/1000000 .'M';
        }else $number=$number/1000 .'K';
    }
    return $number;
}

function convert_data2($number){
    if(($number)>1024){
        if(($number)>(1024*1024)){
            if(($number)>(1024*1024*1024)){
                 if(($number)>(1024*1024*1024*1024)){
                     if(($number)>(1024*1024*1024*1024*1024)){
                         if(($number)>(1024*1024*1024*1024*1024*1024)){
                             if(($number)>(1024*1024*1024*1024*1024*1024*1024)){
                                 if(($number)>(1024*1024*1024*1024*1024*1024*1024*1024)){
                                     $number=number_format((float)$number/(1024*1024*1024*1024*1024*1024*1024*1024), 2, '.', '') .'Y';
                                 }else $number=number_format((float)$number/(1024*1024*1024*1024*1024*1024*1024), 2, '.', '') .'Z';
                             }else $number=number_format((float)$number/(1024*1024*1024*1024*1024*1024), 2, '.', '') .'E';
                         }else $number=number_format((float)$number/(1024*1024*1024*1024*1024), 2, '.', '') .'P';
                     }else $number=number_format((float)$number/(1024*1024*1024*1024), 2, '.', '') .'T';
                }else $number=number_format((float)$number/(1024*1024*1024), 2, '.', '') .'G';
            }else $number=number_format((float)$number/(1024*1024), 2, '.', '') .'M';
        }else $number=number_format((float)$number/1024, 2, '.', '') .'K';
    }
    return $number;
}
?>
