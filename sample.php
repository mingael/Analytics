<?php
include 'class/Analytics.php';
include 'db.php';

$ant = new Analytics();
$data = $ant->getData();

$ant->log(print_r($_SERVER, true));

// Database
$conn = mysql_connect($dbHost, $dbName, $dbPw);

// INSERT
$sql = 'insert into collect (save_date, ip) values (\''.date('Y-m-d').'\', \''.$data['ip'].'\')';
mysql_query($conn, $sql);


if(is_array($data)) {
    $res['res'] = 'OK';

    $ant->log(print_r($data, true));
} else {
    $res['res'] = 'NO';
}

$ant->log('?');

echo json_encode($res);
?>