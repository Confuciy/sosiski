<?php
spl_autoload_register(function($class){
    include $class.'.php';
});

class Organization {

    protected $employees;

    public function addEmployee(Employee $employee) {
        $this->employees[] = $employee;
    }

    public function getNetSalaries() : float {
        $netSalary = 0;

        foreach ($this->employees as $employee) {
            $netSalary += $employee->getSalary();
        }

        return $netSalary;
    }
}

// Prepare the employees
$john = new Developer('John Doe', 12000);
$jane = new Designer('Jane', 10000);
$martin = new Designer('Martin', 5000);

// Add them to organization
$organization = new Organization();
$organization->addEmployee($john);
$organization->addEmployee($jane);
$organization->addEmployee($martin);

echo "Net salaries: " . $organization->getNetSalaries();
