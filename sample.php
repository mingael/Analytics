<?php
include 'class/Analytics.php';

$ant = new Analytics();
$data = $ant->getData();
if(is_array($data)) {
    $res['res'] = 'OK';
} else {
    $res['res'] = 'NO';
}

$ant->log('! ! !');

echo json_encode($res);
?>