<?php
class Analytics {
    public $data;

    public function __construct() {
        $contents = file_get_contents('php://input');
        if(!empty($contents)) {
            $this->data = json_decode($contents);
        }
    }

    public function getData() {
        return $this->data;
    }

    public function log($str) {
        $fp = fopen('log/'.date('Ymd').'.log', 'a');
        fwrite($fp, '['.date('H:i:s').']'.$str."\n");
        fclose($fp);
    }
}
?>