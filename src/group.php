<?php

class group extends db {

    protected function getAllGroups() {
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

}

?>