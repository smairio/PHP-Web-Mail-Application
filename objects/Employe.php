<?php

class Employee
{
    private $cnx;
    public $employeeID;
    public $employeeName;
    public $email;
    public $departmentID;

    public function __construct($cnx, $employeeName = null, $email = null, $departmentID = null, $employeeID = null)
    {
        $this->cnx = $cnx;
        $this->employeeID = $employeeID;
        $this->employeeName = $employeeName;
        $this->email = $email;
        $this->departmentID = $departmentID;
    }


    public function getEmployeeID()
    {
        return $this->employeeID;
    }

    public function setEmployeeID($employeeID)
    {
        $this->employeeID = $employeeID;
    }

    public function getEmployeeName()
    {
        return $this->employeeName;
    }

    public function setEmployeeName($employeeName)
    {
        $this->employeeName = $employeeName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDepartmentID()
    {
        return $this->departmentID;
    }

    public function setDepartmentID($departmentID)
    {
        $this->departmentID = $departmentID;
    }

    public function create()
    {
        $query = "INSERT INTO Employee (EmployeeName, Email, DepartmentID) VALUES (?, ?, ?)";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->employeeName, $this->email, $this->departmentID]);
        return $stmt->rowCount();
    }

    public function update()
    {
        $query = "UPDATE Employee SET EmployeeName = ?, Email = ?, DepartmentID = ? WHERE EmployeeID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->employeeName, $this->email, $this->departmentID, $this->employeeID]);
        return $stmt->rowCount();
    }

    // Delete method
    public function delete()
    {
        $query = "DELETE FROM Employee WHERE EmployeeID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->employeeID]);
        return $stmt->rowCount();
    }

    public function get()
    {
        $query = "SELECT * FROM Employee WHERE EmployeeID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->employeeID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll($cnx)
    {
        $query = "SELECT * FROM Employee";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($cnx, $employee_id)
    {
        $query = "SELECT * FROM Employee WHERE EmployeeID = ?";
        $stmt = $cnx->prepare($query);
        $stmt->execute([$employee_id]);
        $employee_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee_data) {
            return new Employee($cnx, $employee_data['EmployeeName'], $employee_data['Email'], $employee_data['DepartmentID'], $employee_data['EmployeeID']);
        }

        return null;
    }

    public static function deleteEmployees($cnx, $employeeIDs)
    {
        if (!is_array($employeeIDs)) {
            $employeeIDs = [$employeeIDs];
        }

        $deletedCount = 0;
        foreach ($employeeIDs as $id) {
            $query = "DELETE FROM Employee WHERE EmployeeID = ?";
            $stmt = $cnx->prepare($query);
            $stmt->execute([$id]);
            $deletedCount += $stmt->rowCount();
        }
        return $deletedCount;
    }

    public static function getByEmail($cnx, $email)
    {
        $query = "SELECT * FROM Employee WHERE Email = ?";
        $stmt = $cnx->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>
