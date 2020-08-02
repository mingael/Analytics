<?php
class Analytics {
    public $data;

    public function __construct() {
        $contents = file_get_contents('php://input');
        if(!empty($contents)) {
            $data = (Array) json_decode($contents);
            foreach($data as $key => $val) {
                $data[$key] = (Array) $val;
            }
            $data['ip'] = $_SERVER['REMOTE_ADDR'];

            $this->data = $data;
        } else {
            $this->data = '';
        }
    }

    public function getData() {
        return $this->data;
    }
}
?>