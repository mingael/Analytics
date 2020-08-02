<?php
class Table {
    public $conn = null;
    public $table = '';

    function __construct($conn, $table) {
        if(!empty($conn) && !empty($table)) {
            $this->conn = $conn;
            $this->table = $table;
        } else {
            return 'No Value';
        }
    }

    function setTable($table) {
        $this->table = $table;
    }

    function getColumns() {
        $idx = 0;
    
        $sql = "SELECT column_name, data_type, character_maximum_length
                FROM information_schema.columns
                WHERE TABLE_NAME='".$this->table."'
                ORDER BY ordinal_position";
        $res = mysqli_query($this->conn, $sql);
        while($v = mysqli_fetch_array($res)) {
            $arr[$idx]['name'] = $v['column_name'];
            $arr[$idx]['type'] = $v['data_type'];
            $arr[$idx]['length'] = !empty($v['character_maximum_length']) ? $v['character_maximum_length'] : 0;
    
            $idx++;
        }
    
        if(isset($arr)) {
            return $arr;
        } else {
            return null;
        }
    }

    /**
     * 컬럼 데이터 확인
     * 
     * @param value = 데이터
     * @param type =  컬럼 형식
     * @param length =  컬럼 길이
     */
    function checkColumn($value, $type, $length=0) {
        if($type === 'varchar' || $type === 'char') {
            if(strlen($value) > $length) {
                $value = substr($value, 0, $length);
            }
            $value = "'".$value."'";
        } else if($type === 'int' || $type === 'bit' || substr($type, -3) === 'int') {
            $value = preg_replace('/[^0-9]/', '', $value);
        } else if($type === 'float') {

        } else if($type === 'date') {
            if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value)) {
                $value = "'".$value."'";
            } else {
                if(strpos('-', $value) !== false) {
                    $tmp = explode('-', $value);
                    if(count($tmp) == 3) {
                        $value = sprintf('%04d', $tmp[0]);
                        $value.= '-'.sprintf('%02d', $tmp[1]);
                        $value.= '-'.sprintf('%02d', $tmp[2]);

                        $value = "'".$value."'";
                    } else {
                        $value = null;
                    }
                } else {
                    if(strlen($value) === 8) {
                        $value = substr($value, 0, 4).'-'.substr($value, 4, 2).'-'.substr($value, -2);
                        $value = "'".$value."'";
                    } else {
                        $value = null;
                    }
                }
            }
        } else if($type === 'text') {
            $value = "'".$value."'";
        }
        return $value;
    }
    
    /**
     * Insert Query 생성
     * 
     * @param data = 데이터
     * @param except =  제외시킬 컬럼
     */
    function getInsertQuery($data, $except=null) {
        $columns = $this->getColumns();
        if(empty($columns)) {
            return 'There are no columns';
        }
        
        foreach($columns as $idx => $val) {
            if($except!=null && in_array($val['name'], $except)) {
                continue;
            }
            if(isset($data[$val['name']])) {
                $column[$idx] = $val['name'];
                $value[$idx] = $this->checkColumn($data[$val['name']], $val['type'], $val['length']);
            }
        }

        if(isset($value)) {
            $sql = "insert into ".$this->table." (".implode(',', $column).") values (".implode(',', $value).")";
        } else {
            $sql = '';
        }

        return $sql;
    }
}
?>