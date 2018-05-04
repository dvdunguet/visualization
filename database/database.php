<?php
//file config
$servername = "localhost";
$username = "zabbix";
$password = "zabbix";
$dbname = "zabbix";

// Biến lưu trữ kết nối
$conn = null;

// Hàm kết nối
function db_connect(){
    global $conn,$servername,$username,$password,$dbname;
    if (!$conn){
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

// Hàm ngắt kết nối
function db_close(){
    global $conn;
    if ($conn){
        $conn=null;
    }
}

// Hàm lấy danh sách, kết quả trả về danh sách các record trong một mảng
function db_get_list($sql){
    db_connect();
    global $conn;
    $data  = array();
    $result = $conn->prepare($sql);
    $result->execute();
    $data=$result->fetchAll();
    return $data;
}

// Hàm lấy chi tiết, dùng select theo ID vì nó trả về 1 record
function db_get_row($sql){
    db_connect();
    global $conn;
    $data  = array();
    $result = $conn->prepare($sql);
    $result->execute();
    $data=$result->fetch();
    return $data;
}

// Hàm tạo câu truy vấn có thêm điều kiện Where
function db_create_sql($sql, $filter = array())
{
    // Chuỗi where
    $where = '';

    // Lặp qua biến $filter và bổ sung vào $where
    foreach ($filter as $field => $value){
        if ($value != ''){
            $value = addslashes($value);
            $where .= "AND $field = '$value' ";
        }
    }

    // Remove chữ AND ở đầu
    $where = trim($where, 'AND');
    // Remove ký tự , ở cuối
//    $where = trim($where, ', ');

    // Nếu có điều kiện where thì nối chuỗi
    if ($where){
        $where = ' WHERE '.$where;
    }

    // Return về câu truy vấn
    return str_replace('{where}', $where, $sql);
}







