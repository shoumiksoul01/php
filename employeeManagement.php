```php
<?php

declare(strict_types=1);

class Employee
{
    private int $id;
    private string $name;
    private ?Department $department = null;

    public function __construct(
        int $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function setDepartment(Department $department): void
    {
        $this->department = $department;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}

class Department
{
    private int $id;
    private string $name;
    private array $employees = [];

    public function __construct(
        int $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
        $employee->setDepartment($this);
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

