<?php

class Department
{
    private $cnx;
    public $departmentID;
    public $departmentName;

    public function __construct($cnx, $departmentName = null, $departmentID = null)
    {
        $this->cnx = $cnx;
        $this->departmentName = $departmentName;
        $this->departmentID = $departmentID;
    }


    public function getDepartmentID()
    {
        return $this->departmentID;
    }

    public function setDepartmentID($departmentID)
    {
        $this->departmentID = $departmentID;
    }

    // Getters and setters for department name
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;
    }

    // Create method
    public function create()
    {
        $query = "INSERT INTO Department (DepartmentName) VALUES (?)";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->departmentName]);
        return $stmt->rowCount();
    }

    // Update method
    public function update()
    {
        $query = "UPDATE Department SET DepartmentName = ? WHERE DepartmentID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->departmentName, $this->departmentID]);
        return $stmt->rowCount();
    }

    // Delete method
    public function delete()
    {
        $query = "DELETE FROM Department WHERE DepartmentID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->departmentID]);
        return $stmt->rowCount();
    }

    // Get method
    public function get()
    {
        $query = "SELECT * FROM Department WHERE DepartmentID = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->departmentID]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }



    public static function getAll($cnx)
    {
        $query = "SELECT * FROM Department";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        
        $departments = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $department = new Department($cnx);
            $department->departmentID = $row['DepartmentID'];
            $department->departmentName = $row['DepartmentName'];
            $departments[] = $department;
        }
        
        return $departments;
    }

    
    // Delete departments by array of IDs
    public static function deleteDepartments($cnx, $departmentIDs)
    {
        if (!is_array($departmentIDs)) {
            $departmentIDs = [$departmentIDs];
        }
    
        $deletedCount = 0;
        foreach ($departmentIDs as $id) {
            $query = "DELETE FROM Department WHERE DepartmentID = ?";
            $stmt = $cnx->prepare($query);
            $z = [(int)$id];
            $stmt->execute($z);
            $deletedCount += $stmt->rowCount();
        }
        return $deletedCount;
    }

    public static function getById($cnx, $department_id)
    {
        $query = "SELECT * FROM Department WHERE DepartmentID = ?";
        $stmt = $cnx->prepare($query);
        $stmt->execute([$department_id]);
        $department_data = $stmt->fetch(PDO::FETCH_OBJ);

        if ($department_data) {
            return new Department($cnx, $department_data['DepartmentName'], $department_data['DepartmentID']);
        }

        return null;
    }
}

?>
