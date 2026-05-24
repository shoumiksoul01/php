<?php
class UserModel
{
    private array $data = [];
    private array $dirty = [];

    public function __set(string $name, mixed $value): void
    {
        if (($this->data[$name] ?? null) !== $value) {
            $this->dirty[] = $name;
        }
        $this->data[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->data[$name]
            ?? throw new \RuntimeException("Property '$name' not found");
    }

    public function getDirty(): array
    {
        return $this->dirty;
    }
}

$user = new UserModel();
$user->name  = 'Alice';
$user->email = 'alice@example.com';
echo $user->name;            // Alice
print_r($user->getDirty());  // ['name', 'email']
