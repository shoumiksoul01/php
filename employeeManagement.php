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

class Company
{
    private string $name;
    private array $employees = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function hireEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }

    public function assignDepartment(
        Employee $employee,
        Department $department
    ): bool {

        $found = false;

        foreach ($this->employees as $emp) {
            if ($emp === $employee) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }

        $department->addEmployee($employee);

        return true;
    }

    public function showEmployees(): void
    {
        foreach ($this->employees as $employee) {

            echo "ID: " .
                $employee->getId() .
                " | Name: " .
                $employee->getName();

            $department = $employee->getDepartment();

            if ($department !== null) {
                echo " | Department: " .
                    $department->getName();
            }

            echo "\n";
        }
    }
}

$company = new Company("Tech Corp");

$employee1 = new Employee(
    101,
    "Shoumik"
);

$employee2 = new Employee(
    102,
    "Mitu"
);

$department1 = new Department(
    1,
    "Software Engineering"
);

$department2 = new Department(
    2,
    "Human Resources"
);

$company->hireEmployee($employee1);
$company->hireEmployee($employee2);

$company->assignDepartment(
    $employee1,
    $department1
);

$company->assignDepartment(
    $employee2,
    $department2
);

$company->showEmployees();

?>
```

