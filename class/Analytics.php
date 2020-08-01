<?php
class Analytics {
    public $data;

    public function __construct() {
        $contents = file_get_contents('php://input');
        if(!empty($contents)) {
            $this->data = json_decode($contents);
        } else {
            $this->data = '';
        }
    }

    public function getData() {
        return $this->data;
    }
}
?>