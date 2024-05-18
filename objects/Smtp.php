<?php

class SMTP
{
    private $cnx;
    public $id;
    public $host;
    public $port;
    public $username;
    public $password;
    public $encryption;

    public function __construct($cnx, $host = null, $port = null, $username = null, $password = null, $encryption = null, $id = null)
    {
        $this->cnx = $cnx;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = $encryption;
        $this->id = $id;
    }

    // Getters and setters for SMTP properties

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEncryption()
    {
        return $this->encryption;
    }

    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
    }

    public function create()
    {
        $query = "INSERT INTO smtp (host, port, username, password, encryption) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->host, $this->port, $this->username, $this->password, $this->encryption]);
        return $stmt->rowCount();
    }

    public function update()
    {
        $query = "UPDATE smtp SET host = ?, port = ?, username = ?, password = ?, encryption = ? WHERE id = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->host, $this->port, $this->username, $this->password, $this->encryption, $this->id]);
        return $stmt->rowCount();
    }

    public function delete()
    {
        $query = "DELETE FROM smtp WHERE id = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->rowCount();
    }

    public function get()
    {
        $query = "SELECT * FROM smtp WHERE id = ?";
        $stmt = $this->cnx->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll($cnx)
    {
        $query = "SELECT * FROM smtp";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($cnx, $id)
    {
        $query = "SELECT * FROM smtp WHERE id = ?";
        $stmt = $cnx->prepare($query);
        $stmt->execute([$id]);
        $smtp_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($smtp_data) {
            return new SMTP($cnx, $smtp_data['host'], $smtp_data['port'], $smtp_data['username'], $smtp_data['password'], $smtp_data['encryption'], $smtp_data['id']);
        }

        return null;
    }

    public static function deleteSMTPs($cnx, $ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $deletedCount = 0;
        foreach ($ids as $id) {
            $query = "DELETE FROM smtp WHERE id = ?";
            $stmt = $cnx->prepare($query);
            $stmt->execute([$id]);
            $deletedCount += $stmt->rowCount();
        }
        return $deletedCount;
    }
}
?>
