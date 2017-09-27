<?php

namespace Vibe\Database;

/**
* Database class.
*/
class Database
{
    private $db;


    /**
    * Connect to database
    * @param $dsn String. The dsn to the database-file
    * @return void
    */
    public function connect($dsn)
    {
        try {
            $this->db = new \PDO(...($dsn));
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Failed to connect to the database using DSN:<br>$dsn<br>";
        }
    }

    /**
    * Create user and insert it into the database
    * @param $username String. User's username.
    * @param $email String. User's email adress.
    * @param $password String. User's password.
    * @return void
    */
    public function createUser($username, $email, $password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(username, email, password) VALUES ('$username', '$email', '$hashPassword')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    /**
    * Delete user from database
    * @param $username String. User's username.
    * @return void
    */
    public function deleteUser($username)
    {
        $sql = "DELETE FROM users WHERE username='$username'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    /**
    * Gets the hashed password from the database
    * @param $username String. The username to get password from.
    * @return return String. The hashed password
    */
    public function getHash($username)
    {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE username='$username'");
        $stmt->execute();
        
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $res["password"];
    }

    /**
    * Change the users password
    * @param $username String. The username to get password from.
    * @return return String. The hashed password
    */
    public function changePassword($username, $newPassword)
    {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password='$newHash' WHERE username='$username'");
        $stmt->execute();
    }

    /**
    * Change the user information
    * @param $username String. The username to get password from.
    * @return return String. The hashed password
    */
    public function changeUser($username, $email, $firstname, $lastname)
    {
        $stmt = $this->db->prepare("UPDATE users SET email='$email', firstname='$firstname', lastname='$lastname' WHERE username='$username'");
        $stmt->execute();
    }

    /**
    * Check if username exists in user table
    * @param $username String. Username in user table.
    * @return true if user exists or false if not.
    */
    public function exists($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username='$username'");
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);

        return !$res ? false : true;
    }

    /**
    * Return user data from database
    * @param $username String. Username in user table.
    * @return Object $res with user information.
    */
    public function getUser($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username='$username'");
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $res;
    }

    /**
    * Return all users from database
    * @return Object $res with users information.
    */
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users LIMIT 10");
        $stmt->execute();
        $res = $stmt->fetchAll();

        return $res;
    }

    /**
    * Return users matching search query
    * @return Object $res with users information.
    */
    public function searchUsers($query, $limit = 3, $page = 1, $orderby = "username", $order = "ASC")
    {
        $offset = (($page - 1) * $limit);
        $searchQuery = "%" . $query . "%";
        $sql = "SELECT * FROM users WHERE username LIKE ? OR firstname LIKE ? OR lastname LIKE ? ORDER BY $orderby $order LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $param = [$searchQuery, $searchQuery, $searchQuery];
        $stmt->execute($param);
        $res = $stmt->fetchAll();

        return $res;
    }

    /**
    * Return number of users in database
    * @return Int $res number of user objects.
    */
    public function getMax()
    {
        $sql = "SELECT COUNT(id) AS max FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();

        return $res[0]->max;
    }
}
