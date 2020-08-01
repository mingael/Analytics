<?php
include 'class/Analytics.php';
include 'db.php';

$ant = new Analytics();
$data = $ant->getData();

debug('START');

// Database
$conn = @mysqli_connect($dbHost, $dbUser, $dbPassword, $dbBase);

if($conn) {
    // INSERT
    $sql = "insert into collect (save_date, ip) values ('".date('Y-m-d')."', '".$data['ip']."')";
    debug($sql);
    $result = @mysqli_query($conn, $sql);
} else {
    debug(mysqli_connect_errno());
    debug(mysqli_connect_error());
}


if(isset($result)) {
    $res['res'] = 'OK';

    //debug(print_r($data, true));
} else {
    $res['res'] = 'NO';
}

mysqli_close($conn);
debug('END');

echo json_encode($res);

function debug($str) {
    $fp = fopen('log/'.date('Ymd').'.log', 'a');
    fwrite($fp, '['.date('H:i:s').']'.$str."\n");
    fclose($fp);
}
?>