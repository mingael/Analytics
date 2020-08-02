<?php
function debug($str) {
    $fp = fopen('log/'.date('Ymd').'.log', 'a');
    fwrite($fp, '['.date('H:i:s').']'.$str."\n");
    fclose($fp);
}
?>