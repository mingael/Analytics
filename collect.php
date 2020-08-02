<?php
include 'class/Analytics.php';
include 'class/Table.php';
include 'function.php';
include 'db.php';

debug('START');

$ant = new Analytics();
$data = $ant->getData();

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbBase);
if($conn) {
    $table = new Table($conn, 'collect');
    
    // Collect
    $INS['save_date'] = date('Y-m-d');
    $INS['ip'] = $data['ip'];

    $sql = $table->getInsertQuery($INS, Array('idx'));
    debug('collect: '.$sql);
    if(mysqli_query($conn, $sql)) {
        $idx = mysqli_insert_id($conn);
        debug('idx '.$idx);
    
        // Browser
        unset($INS);
        $INS['collect_idx'] = $idx;
        $INS['app_time'] = $data['appInfo']['time'];
        $INS['app_type'] = 'WEb';
        $INS['code_name'] = $data['browser']['codeName'];
        $INS['name'] = $data['browser']['name'];
        $INS['version'] = $data['browser']['version'];
        $INS['platform'] = $data['browser']['platform'];
        $INS['product'] = $data['browser']['product'];
        $INS['user_agent'] = $data['browser']['userAgent'];
            
        $table->setTable('collect_browser');
        $sql = $table->getInsertQuery($INS, Array('idx'));
        debug('browser: '.$sql);
        $result = mysqli_query($conn, $sql);
    
        // Location
        unset($INS);
        $INS['collect_idx'] = $idx;
        $INS['app_time'] = $data['appInfo']['time'];
        $INS['language_type'] = $data['language']['langType'];
        $INS['language'] = $data['language']['lang'];
        $INS['protocol'] = $data['location']['protocol'];
        $INS['host'] = $data['location']['host'];
        $INS['url'] = $data['location']['url'];
        $INS['param'] = $data['location']['param'];
    
        $table->setTable('collect_location');
        $sql = $table->getInsertQuery($INS, null);
        debug('location: '.$sql);
        $result = mysqli_query($conn, $sql);
    }

} else {
    $result = false;
    debug(mysqli_connect_errno());
    debug(mysqli_connect_error());
}


if($result) {
    $res['res'] = 'OK';
} else {
    $res['res'] = 'NO';
}

mysqli_close($conn);
debug('END');

echo json_encode($res);
?>