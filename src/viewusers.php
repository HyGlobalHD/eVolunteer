<?php

class viewusers extends users {

    public function showAllUsers() {
        $datas = $this->getAllUsers();
        foreach ($datas as $data) {
            echo $data['USER_NRIC'];
            echo $data['USER_PASSWORD'];
        }
    }

    

}

?>