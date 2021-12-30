<?php

class users extends db {

    protected function getAllUsers() {
        $sql = "SELECT * FROM user";
        $result = $this->connect()->query($sql);
        $numRows = $result->num_rows;
        if($numRows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function checkUserExist($nric) {
        $sql = "SELECT USER_NRIC FROM user WHERE USER_NRIC = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $stmt->bind_result($nric);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }



    public function getUserDetails($nric) { // omitted pass
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

    public function getUserUsername($nric) {
        $data = $this->getUserDetails($nric);
        if(!(is_null($data))) {
            foreach ($data as $userdetails) {
                $username = $userdetails['USER_USERNAME'];
            }
            return $username;
        } else {
            return null;
        }
    }

    // check if username already taken
    public function checkUsernameExist($username) {
        $sql = "SELECT USER_USERNAME FROM user WHERE USER_USERNAME = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if email already taken
    public function checkEmailExist($email) {
        $sql = "SELECT USER_EMAIL FROM user WHERE USER_EMAIL = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if phone number already taken
    public function checkPhoneExist($phoneno) {
        $sql = "SELECT USER_PHONE_NO FROM user WHERE USER_PHONE_NO = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $phoneno);
        $stmt->execute();
        $stmt->bind_result($phoneno);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    // check if nric already taken
    public function checkNricExist($nric) {
        $sql = "SELECT USER_NRIC FROM user WHERE USER_NRIC = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $stmt->bind_result($nric);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if($rnum > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getUserNRIC($username) {
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

    protected function createUser($nric, $username, $password, $fullname, $email, $phoneno, $logincount, $userstatus, $groupcode) {
        $userExist = $this->checkUserExist($nric);
        if($userExist) {
            return false;
        } else {
            $insert = "INSERT INTO user(USER_NRIC, USER_USERNAME, USER_PASSWORD, USER_FULL_NAME, USER_EMAIL, USER_PHONE_NO, USER_CREATED_DATE, USER_LOGIN_COUNT, USER_STATUS, GROUP_CODE) VALUES(?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)";
        }
        

    }
    

    



}
