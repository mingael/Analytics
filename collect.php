<?php
include 'class/Analytics.php';
include 'class/Table.php';
include 'function.php';
include 'db.php';

session_start();
debug('START');
debug(print_r($_SESSION, true));

$ant = new Analytics();
$data = $ant->getData();

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbBase);
if($conn) {
    $table = new Table($conn, 'collect');
    $today = date('Y-m-d');
    $time = time();

    if(!empty($_SESSION['collect_idx'])) {
        $sqlChk = "SELECT idx
                FROM collect
                WHERE idx=".$_SESSION['collect_idx']." and save_date='".$today."' and ip='".$data['ip']."'";
        $resChk = mysqli_query($conn, $sqlChk);
        $vChk = mysqli_fetch_array($resChk);
        if(isset($vChk['idx'])) {
            $idx = $_SESSION['collect_idx'];   
        } else {
            unset($_SESSION['collect_idx']);
        }
    }

    if(!isset($idx)) {
        // Collect
        $INS['save_date'] = $today;
        $INS['ip'] = $data['ip'];
        $INS['app_time'] = $time;
    
        $sql = $table->getInsertQuery($INS, Array('idx'));
        debug('collect: '.$sql);
        mysqli_query($conn, $sql);

        // Collect Index
        $idx = mysqli_insert_id($conn);
    
        // session
        $_SESSION['collect_idx'] = $idx;
    }

    if(!empty($idx)) {    
        // Browser
        unset($INS);
        $INS['collect_idx'] = $idx;
        $INS['save_time'] = $time;
        $INS['type'] = $data['browser']['type'];
        $INS['code_name'] = $data['browser']['codeName'];
        $INS['name'] = $data['browser']['name'];
        $INS['version'] = $data['browser']['version'];
        $INS['platform'] = $data['browser']['platform'];
        $INS['product'] = $data['browser']['product'];
        $INS['device'] = $data['browser']['device'];
        $INS['user_agent'] = $data['browser']['userAgent'];
        debug('idx '.$idx);
            
        $table->setTable('collect_browser');
        $sql = $table->getInsertQuery($INS, Array('idx'));
        debug('browser: '.$sql);
        $result = mysqli_query($conn, $sql);
    
        // Location
        unset($INS);
        $INS['collect_idx'] = $idx;
        $INS['save_time'] = $time;
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
    } else {
        debug('?');
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
// session_destroy();
echo json_encode($res);
?>