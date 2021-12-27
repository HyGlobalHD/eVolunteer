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
}

?>