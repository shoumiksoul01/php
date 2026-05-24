<?php

class cacheproxy{
    public function __construct(
        private array $store =[]
    ){}

    public function __set(string $k, mixed $v):void{
        $this->store[$k]= $v;
    }
    public function __get(string $k):mixed{
        return $this->store[$k] ?? null;
    }
    public function __isset(string $k):bool{
        return array_key_exists($k, $this->store) && $this->store[$k]!==null;
    }
    public function __unset(string $k):void{
        unset($this->store[$k]);
    }
}

$cache = new cacheproxy();
$cache->token = 'abc123';

var_dump(isset($cache->token)); 
var_dump(empty($cache->missing)); 
unset($cache->token);
var_dump(isset($cache->token));

?>
