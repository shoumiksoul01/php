<?php
class user{
    private $data =[];
    public function __set($properties, $values){
        if($properties == "age" && $values< 0){
            die ("Invalid age");
        }
        $this->data[$properties]=  $values;

    }
    public function __get($properties){
        if(isset($this->data[$properties])){
            return $this->data[$properties];
        }
    }

}

?>