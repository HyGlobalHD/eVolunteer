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

    public function getUserDetails($nric) {
        $sql = "SELECT * FROM user WHERE USER_NRIC = '$nric'";
        $result = $this->connect()->query($sql);
        $numRows = $result->num_rows;
        if($numRows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getUserUsername($nric) {
        $data = $this->getUserDetails($nric);
        foreach ($data as $datas) {
            return $datas['USER_USERNAME'];
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

?>