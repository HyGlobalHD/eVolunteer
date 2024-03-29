<?php

class users extends db
{

    protected function getAllUsers()
    {
        $sql = "SELECT * FROM user";
        $result = $this->connect()->query($sql);
        $numRows = $result->num_rows;
        if ($numRows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function checkUserExist($nric)
    {
        $sql = "SELECT USER_NRIC FROM user WHERE USER_NRIC = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $stmt->bind_result($nric);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }



    public function getUserDetails($nric)
    { // omitted pass
        $sql = "SELECT * FROM user WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        $stmt->close();
        $conn->close();
    }

    public function getUserUsername($nric)
    {
        $data = $this->getUserDetails($nric);
        if (!(is_null($data))) {
            foreach ($data as $userdetails) {
                $username = $userdetails['USER_USERNAME'];
            }
            return $username;
        } else {
            return null;
        }
    }

    // check if username already taken
    public function checkUsernameExist($username)
    {
        $sql = "SELECT USER_USERNAME FROM user WHERE USER_USERNAME = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if email already taken
    public function checkEmailExist($email)
    {
        $sql = "SELECT USER_EMAIL FROM user WHERE USER_EMAIL = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if phone number already taken
    public function checkPhoneExist($phoneno)
    {
        $sql = "SELECT USER_PHONE_NO FROM user WHERE USER_PHONE_NO = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $phoneno);
        $stmt->execute();
        $stmt->bind_result($phoneno);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if nric already taken
    public function checkNricExist($nric)
    {
        $sql = "SELECT USER_NRIC FROM user WHERE USER_NRIC = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $stmt->bind_result($nric);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getUserNRIC($username)
    {
        // where user_username = ?
        $sql = "SELECT USER_NRIC FROM user WHERE USER_USERNAME = ? LIMIT 1";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // get user group_code
    public function getUserGroupCode($nric)
    {
        $data = $this->getUserDetails($nric);
        if (!(is_null($data))) {
            foreach ($data as $userdetails) {
                $username = $userdetails['GROUP_CODE'];
            }
            return $username;
        } else {
            return null;
        }
    }

    protected function createUser($nric, $username, $password, $fullname, $email, $phoneno, $logincount, $userstatus, $groupcode)
    {
        $userExist = $this->checkUserExist($nric);
        if ($userExist) {
            return false;
        } else {
            $insert = "INSERT INTO user(USER_NRIC, USER_USERNAME, USER_PASSWORD, USER_FULL_NAME, USER_EMAIL, USER_PHONE_NO, USER_CREATED_DATE, USER_LOGIN_COUNT, USER_STATUS, GROUP_CODE) VALUES(?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)";
        }
    }

    public function checkPassword($nric, $password)
    {
        $userdetails = $this->getUserDetails($nric);
        if (!(is_null($userdetails))) {
            foreach ($userdetails as $user) {
                $dbpassword = $user['USER_PASSWORD'];
            }
            if (password_verify($password, $dbpassword)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateEmail($nric, $email)
    {
        $sql = "UPDATE user SET USER_EMAIL = ? WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public function updatePhoneNo($nric, $phoneno)
    {
        $sql = "UPDATE user SET USER_PHONE_NO = ? WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $phoneno, $nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public function updatePassword($nric, $password)
    {
        $sql = "UPDATE user SET USER_PASSWORD = ? WHERE USER_NRIC = ?"; // todo : hash password
        $password = password_hash($password, PASSWORD_ARGON2I);
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public function getUserFullName($nric)
    {
        $sql = "SELECT USER_FULL_NAME FROM user WHERE USER_NRIC = ? LIMIT 1";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // get user status
    public function getUserStatus($nric)
    {
        $data = $this->getUserDetails($nric);
        if (!(is_null($data))) {
            foreach ($data as $userdetails) {
                $us = $userdetails['USER_STATUS'];
            }
            return $us;
        } else {
            return null;
        }
    }

    // update user status
    public function updateUserStatus($nric, $userstatus)
    {
        $sql = "UPDATE user SET USER_STATUS = ? WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userstatus, $nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
