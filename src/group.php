<?php

class group extends db {

    public function getAllGroups() {
        $sql = "SELECT * FROM `group`";
        $result = $this->connect()->query($sql);
        if($result) {
            $numRows = $result->num_rows;
            if($numRows > 0) {
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }


    public function getGroupDetails($groupcode) { // omitted pass
        $sql = "SELECT * FROM `group` WHERE GROUP_CODE = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $groupcode);
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

    // get group_name by group_code
    public function getGroupName($group_code) {
        $data = $this->getGroupDetails($group_code);
        if(!(is_null($data))) {
            foreach ($data as $userdetails) {
                $username = $userdetails['GROUP_NAME'];
            }
            return $username;
        } else {
            return null;
        }
    }

    // get group application based on user_nric
    public function getGroupApplication($user_nric) {
        $sql = "SELECT * FROM group_application WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
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

    // create group application
    public function createGroupApplication($user_nric, $group_code) {
        $sql = "INSERT INTO group_application (USER_NRIC, GROUP_CODE, APP_DATE, APP_STATUS, APP_ACCEPT_BY) VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $app_status = "P";
        $app_accept_by = "";
        $stmt->bind_param("ssss", $user_nric, $group_code, $app_status, $app_accept_by);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    // update group application
    public function updateGroupApplication($user_nric, $group_code, $app_status, $app_accept_by) {
        $sql = "UPDATE group_application SET APP_STATUS = ?, APP_ACCEPT_BY = ?, GROUP_CODE = ?, APP_DATE = CURRENT_TIMESTAMP WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $app_status, $app_accept_by, $group_code, $user_nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    // delete group application
    public function deleteGroupApplication($user_nric) {
        $sql = "DELETE FROM group_application WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public function getGroupApplicationOrderLimit($offset, $limit, $order) {
        $order = strtoupper($order);
        $sql = "SELECT * FROM group_application ORDER BY APP_STATUS ASC, APP_DATE $order LIMIT $offset, $limit";
        $result = $this->connect()->query($sql);
        if($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getGroupAppCount() {
        $sql = "SELECT COUNT(*) c FROM group_application";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['c'];
        }
    }

    public function updateUserGroup($nric, $groupcode) {
        $sql = "UPDATE user SET GROUP_CODE = ? WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $groupcode, $nric);
        return $stmt->execute();
        $stmt->close();
        $conn->close();
    }

}

?>