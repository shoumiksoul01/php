<?php

class UserModel{
    private array $dirty=[];
    private array $data =[];
    public function __set(string $name, mixed $value): void{
        if(($this->data[$name]?? null !==$value)){
            $this->dirty[]=$name;

        }
        $this->data[$name]= $value;
    }

    public function __get(string $name): mixed{
        return $this->data[$name]
               ?? throw new \RuntimeException("Property '$name' not found");

    }
    public function getDirty(): array { 
        return $this->dirty;
    }
}
$user = new UserModel();
$user->name  = 'Shoumik';
$user->email = 'sheikhshoumik64@gmail.com';
echo $user->name;            
print_r($user->getDirty()); 

?>