<?php

class viewgroup extends group {

    public function showAllGroups() {
        $datas = $this->getAllGroups();
        foreach ($datas as $data) {
            echo $data['GROUP_CODE'];
            echo $data['GROUP_NAME'];
        }
    }

    

    

}

?>