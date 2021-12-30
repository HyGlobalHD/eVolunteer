<?php

class suggestions extends db
{

    protected function getAllSuggestions()
    {
        $sql = "SELECT * FROM suggestions";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getRecordCount()
    {
        $sql = "SELECT * FROM suggestions";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            return $numRows;
        } else {
            return 0;
        }
    }

    /**
     * @param Integer $limit
     * @param Integer $offset
     * @param String $order ASC, DESC
     */
    public function getTopSuggestionsLimitOrder($offset, $limit, $order)
    {
        /**
         * page that use this function:
         * homepage, top_suggestions
         * result needed: SUGGESTIONS_TITLE, SUGGESTIONS_CREATED_DATE, VOTECOUNT, SUGGESTIONS_ID, USER_NRIC
         * note to do: remove * query, and list it one by one
         */
        $order = strtoupper($order);
        $sql = "SELECT  suggestions.*, (SELECT  COUNT(*) FROM vote WHERE   vote.SUGGESTIONS_ID = suggestions.SUGGESTIONS_ID) AS voteCount FROM suggestions LEFT JOIN volunteer_program ON suggestions.SUGGESTIONS_ID <> volunteer_program.SUGGESTIONS_ID AND volunteer_program.SUGGESTIONS_ID IS NOT NULL ORDER BY voteCount " . $order . " LIMIT " . $offset . ", " . $limit;
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    
    public function getUserTopSuggestionsLimitOrder($user_nric, $offset, $limit, $order)
    {
        $order = strtoupper($order);
        $sql = "SELECT  suggestions.*, (SELECT  COUNT(*) FROM vote WHERE   vote.SUGGESTIONS_ID = suggestions.SUGGESTIONS_ID) AS voteCount FROM suggestions LEFT JOIN volunteer_program ON suggestions.SUGGESTIONS_ID <> volunteer_program.SUGGESTIONS_ID AND volunteer_program.SUGGESTIONS_ID IS NOT NULL WHERE suggestions.USER_NRIC = ? ORDER BY voteCount " . $order . " LIMIT " . $offset . ", " . $limit;
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        /*
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        } */
    }

    
    public function getUserRecentSuggestionsLimitOrder($user_nric, $offset, $limit, $order) {
        $order = strtoupper($order);
        $sql = "select suggestions.* FROM suggestions WHERE suggestions.SUGGESTIONS_ID not in (select volunteer_program.SUGGESTIONS_ID FROM volunteer_program WHERE volunteer_program.SUGGESTIONS_ID IS NOT NULL) AND suggestions.USER_NRIC = ? ORDER BY suggestions.SUGGESTIONS_CREATED_DATE " . $order . " LIMIT " . $offset . "," . $limit;
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        /*
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        } */
    }

    /**
     * @param Integer $limit
     * @param Integer $offset
     * @param String $order ASC, DESC
     */
    public function getRecentSuggestionsLimitOrder($offset, $limit, $order)
    { // get suggestions based on the limit / order
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        /**
         * SELECT  suggestions.* FROM suggestions LEFT JOIN volunteer_program ON suggestions.SUGGESTIONS_ID <> volunteer_program.SUGGESTIONS_ID AND volunteer_program.SUGGESTIONS_ID IS NOT NULL ORDER BY suggestions.SUGGESTIONS_CREATED_DATE DESC LIMIT 0, 3
         * select suggestions.*
from suggestions
where suggestions.SUGGESTIONS_ID not in (select volunteer_program.SUGGESTIONS_ID from volunteer_program WHERE volunteer_program.SUGGESTIONS_ID IS NOT NULL) ORDER BY suggestions.SUGGESTIONS_CREATED_DATE DESC LIMIT 0,2
         */
        $order = strtoupper($order);
        $sql = "select suggestions.* FROM suggestions WHERE suggestions.SUGGESTIONS_ID not in (select volunteer_program.SUGGESTIONS_ID FROM volunteer_program WHERE volunteer_program.SUGGESTIONS_ID IS NOT NULL) ORDER BY suggestions.SUGGESTIONS_CREATED_DATE " . $order . " LIMIT " . $offset . "," . $limit;
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // check suggestions exist
    public function checkSuggestionExist($suggestions_id)
    {
        $sql = "SELECT suggestions.SUGGESTIONS_ID FROM suggestions WHERE SUGGESTIONS_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $suggestions_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getSuggestionsDetails($suggestions_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM suggestions WHERE SUGGESTIONS_ID = '$suggestions_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // search suggestions
    public function searchSuggestions($search)
    {
        // search sql from suggestions table and user table
        $sql = "SELECT suggestions.*, user.USER_USERNAME FROM suggestions LEFT JOIN user ON suggestions.USER_NRIC = user.USER_NRIC WHERE suggestions.SUGGESTIONS_TITLE LIKE '%$search%' OR user.USER_USERNAME LIKE '%$search%'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // update suggestions
    public function updateSuggestions($suggestions_id, $suggestions_title, $suggestions_details)
    {
        $sql = "UPDATE suggestions SET SUGGESTIONS_TITLE = ?, SUGGESTIONS_DETAILS = ? WHERE SUGGESTIONS_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("sss", $suggestions_title, $suggestions_details, $suggestions_id);
        $result = $stmt->execute();
        return $result;
        $stmt->close();
    }

    // delete suggestions
    public function deleteSuggestions($suggestions_id)
    {
        $sql = "DELETE FROM suggestions WHERE SUGGESTIONS_ID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $suggestions_id);
        $result = $stmt->execute();
        return $result;
        $stmt->close();
    }

    // create suggestions
    public function createSuggestions($suggestions_title, $suggestions_details, $user_nric)
    {
        $sql = "INSERT INTO suggestions(SUGGESTIONS_ID, SUGGESTIONS_TITLE, SUGGESTIONS_DETAILS, SUGGESTIONS_CREATED_DATE, USER_NRIC) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $sid = uniqid('', true);
        while ($this->checkSGExists($sid)) {
            $sid = uniqid('', true);
        }
        $stmt->bind_param("ssss", $sid, $suggestions_title, $suggestions_details, $user_nric);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($result) {
            return $sid;
        } else {
            return null;
        }
    }

    public function checkSGExists($suggestions_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM suggestions_comment WHERE SC_ID = '$suggestions_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getVote($suggestions_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT COUNT(*) c FROM vote WHERE SUGGESTIONS_ID = '$suggestions_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['c'];
        }
    }


    // if vp_id exists -> then filter it out from suggestions ???
    public function checkVP($suggestions_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM volunteer_program WHERE SUGGESTIONS_ID = '$suggestions_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function checkVPID($suggestions_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT VP_ID r FROM volunteer_program WHERE SUGGESTIONS_ID = '$suggestions_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['r'];
        }
    }

    public function getVolunteerProgramLimitOrder($offset, $limit, $order)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $order = strtoupper($order);
        $sql = "SELECT * FROM volunteer_program ORDER BY VP_PICKED_DATE " . $order . " LIMIT " . $offset . ", " . $limit;
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getSuggestionsCommentLimitOrder($suggestions_id, $offset, $limit, $order)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        //SELECT * FROM suggestions_comment WHERE SUGGESTIONS_ID = 'test' ORDER BY COMMENT_DATE_TIME DESC LIMIT 0, 10
        $order = strtoupper($order);
        $sql = "SELECT * FROM suggestions_comment WHERE SUGGESTIONS_ID = '$suggestions_id' ORDER BY COMMENT_DATE_TIME " . $order . " LIMIT " . $offset . ", " . $limit;
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getCommentRecordCount()
    {
        $sql = "SELECT * FROM suggestions_comment";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            return $numRows;
        } else {
            return 0;
        }
    }

    public function checkSCExist($sc_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM suggestions_comment WHERE SC_ID = '$sc_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function getUserCommentCount($suggestions_id, $user_nric)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT COUNT(SC_ID) c FROM suggestions_comment WHERE SUGGESTIONS_ID = '$suggestions_id' AND USER_NRIC = '$user_nric'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['c'];
        }
    }

    public function getSuggestionsCommentDetails($sc_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM suggestions_comment WHERE SC_ID = '$sc_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function postSuggestionComment($suggestions_id, $user_nric, $comment)
    {
        $insert = "INSERT INTO suggestions_comment(SC_ID, SUGGESTIONS_ID, USER_NRIC, COMMENT, COMMENT_DATE_TIME) VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $conn = $this->connect();
        $stmt = $conn->prepare($insert);
        $uid = uniqid();
        while ($this->checkSCExist($uid)) {
            $uid = uniqid();
        }
        $stmt->bind_param("ssss", $uid, $suggestions_id, $user_nric, $comment);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function updateSuggestionComment($sc_id, $comment)
    {
        $update = "UPDATE suggestions_comment SET COMMENT = ?, COMMENT_DATE_TIME = CURRENT_TIMESTAMP WHERE SC_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ss", $comment, $sc_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function deleteSuggestionComment($sc_id)
    {
        $delete = "DELETE FROM suggestions_comment WHERE SC_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("s", $sc_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getUserVote($user_nric, $suggestions_id)
    {
        $sql = "SELECT * FROM vote WHERE USER_NRIC = ? AND SUGGESTIONS_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_nric, $suggestions_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        $stmt->close();
        $conn->close();
    }

    public function createUserVote($user_nric, $suggestions_id)
    {
        $insert = "INSERT INTO vote(USER_NRIC, SUGGESTIONS_ID, VOTE_DATE) VALUES(?, ?, CURRENT_TIMESTAMP)";
        $conn = $this->connect();
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("ss", $user_nric, $suggestions_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function deleteUserVote($user_nric, $suggestions_id)
    {
        $delete = "DELETE FROM vote WHERE USER_NRIC = ? AND SUGGESTIONS_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("ss", $user_nric, $suggestions_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }


    /**
     * type 0 = alert-primary // use for information
     * type 1 = alert-success // use to show success
     * type 2 = alert-warning  // use to show warning
     * type 3 = alert-danger // use to show error
     */
    function msgbox($type, $msg)
    {
        $output = "";
        switch ($type) {
            case 0:
                $output = "<div class='alert alert-primary d-flex align-items-center alert-dismissible fade show' role='alert'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Info:'><use xlink:href='#info-fill'/></svg><div>$msg</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                break;
            case 1:
                $output = "<div class='alert alert-success  d-flex align-items-center alert-dismissible fade show' role='alert'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg><div>$msg</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                break;
            case 2:
                $output = "<div class='alert alert-warning   d-flex align-items-center alert-dismissible fade show' role='alert'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Warning:'><use xlink:href='#exclamation-triangle-fill'/></svg><div>$msg</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                break;
            case 3:
                $output = "<div class='alert alert-danger   d-flex align-items-center alert-dismissible fade show' role='alert'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Warning:'><use xlink:href='#exclamation-triangle-fill'/></svg><div>$msg</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                break;
            default:
                $output = "";
                break;
        }
        return $output;
    }

    public function checkVPExist($vp_id)
    {
        $sql = "SELECT * FROM volunteer_program WHERE VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        $stmt->close();
        $conn->close();
    }

    public function checkSuggestionsExist($suggestions_id)
    {
        $sql = "SELECT SUGGESTIONS_ID FROM suggestions WHERE SUGGESTIONS_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $suggestions_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        $stmt->close();
        $conn->close();
    }

    public function createVolunteerProgram($vp_title, $vp_details, $vp_start_date, $vp_end_program, $vp_minimum_participant, $user_nric, $suggestions_id)
    {
        $insert = "INSERT INTO volunteer_program(VP_ID, VP_TITLE, VP_DETAILS, VP_START_DATE, VP_END_PROGRAM, VP_MINIMUM_PARTICIPANT, USER_NRIC, SUGGESTIONS_ID, VP_PICKED_DATE) VALUES(?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $conn = $this->connect();
        $stmt = $conn->prepare($insert);
        $vp_id = uniqid('', true);
        while ($this->checkVPExist($vp_id)) {
            $vp_id = uniqid('', true);
        }
        // note minimum participant is integer value
        $stmt->bind_param("sssssiss", $vp_id, $vp_title, $vp_details, $vp_start_date, $vp_end_program, $vp_minimum_participant, $user_nric, $suggestions_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($result) {
            return $vp_id;
        } else {
            return null;
        }
    }

    // search volunteer program with user table

    public function searchVolunteerProgram($search)
    {
        // search sql from suggestions table and user table

        $sql = "SELECT volunteer_program.*, user.USER_USERNAME FROM volunteer_program LEFT JOIN user ON volunteer_program.USER_NRIC = user.USER_NRIC WHERE volunteer_program.VP_TITLE LIKE '%$search%' OR user.USER_USERNAME LIKE '%$search%'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // update volunteer program
    public function updateVolunteerProgram($vp_id, $vp_title, $vp_details, $vp_start_date, $vp_end_program, $vp_minimum_participant)
    {
        $update = "UPDATE volunteer_program SET VP_TITLE = ?, VP_DETAILS = ?, VP_START_DATE = ?, VP_END_PROGRAM = ?, VP_MINIMUM_PARTICIPANT = ? WHERE VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ssssis", $vp_title, $vp_details, $vp_start_date, $vp_end_program, $vp_minimum_participant, $vp_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }
    // delete volunteer program
    public function deleteVolunteerProgram($vp_id)
    {
        $delete = "DELETE FROM volunteer_program WHERE VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("s", $vp_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getVolunteerProgramDetails($vp_id)
    {
        $sql = "SELECT * FROM volunteer_program WHERE VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
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

    public function getVolunteerCommentDetails($vc_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM vp_comment WHERE VC_ID = '$vc_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getVolunteerCommentLimitOrder($vp_id, $offset, $limit, $order)
    {
        $order = strtoupper($order);
        $sql = "SELECT * FROM vp_comment WHERE VP_ID = '$vp_id' ORDER BY COMMENT_DATE_TIME " . $order . " LIMIT " . $offset . ", " . $limit;
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    // update volunteer program
    public function updateVolunteerComment($vc_id, $vc_comment)
    {
        $update = "UPDATE vp_comment SET COMMENT = ? WHERE VC_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ss", $vc_comment, $vc_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // delete volunteer comment
    public function deleteVolunteerComment($vc_id)
    {
        $delete = "DELETE FROM vp_comment WHERE VC_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("s", $vc_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }
    // get volunteer comment details
    public function getVolunteerComment($vc_id)
    {
        $sql = "SELECT * FROM vp_comment WHERE VC_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vc_id);
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


    public function getUserParticipate($user_nric, $vp_id)
    {
        $sql = "SELECT * FROM participate WHERE USER_NRIC = ? AND VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_nric, $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        $stmt->close();
        $conn->close();
    }

    public function checkCurrentDateTimeVP($vp_id)
    {
        $sql = "SELECT VP_ID FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() BETWEEN VP_START_DATE AND VP_END_PROGRAM";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkPastDateTimeVP($vp_id)
    {
        $sql = "SELECT VP_ID FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() > VP_START_DATE AND CURRENT_DATE() > VP_END_PROGRAM";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkOngoingVP($vp_id)
    {
        $sql = "SELECT VP_ID FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() BETWEEN VP_START_DATE AND VP_END_PROGRAM";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkUpcomingVP($vp_id) {
        $sql = "SELECT VP_ID FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() < VP_START_DATE";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // return volunteer program details
    public function getOngoingVP($vp_id)
    {
        $sql = "SELECT * FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() BETWEEN VP_START_DATE AND VP_END_PROGRAM";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result) {
            $numRows = $result->num_rows;
            if($numRows > 0) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function getUpcomingVP($vp_id) {
        $sql = "SELECT * FROM volunteer_program WHERE VP_ID = ? AND CURRENT_DATE() < VP_START_DATE";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $vp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result) {
            $numRows = $result->num_rows;
            if($numRows > 0) {
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    public function checkConfictingDateVP($vp_id1, $vp_id2) {
        $sql = "SELECT VP_ID FROM volunteer_program WHERE VP_ID = ? AND VP_START_DATE BETWEEN (SELECT VP_START_DATE FROM volunteer_program WHERE VP_ID = ?) AND (SELECT VP_END_PROGRAM FROM volunteer_program WHERE VP_ID = ?)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $vp_id1, $vp_id2, $vp_id2);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function createUserParticipate($user_nric, $vp_id)
    {
        if ($this->getUserParticipate($user_nric, $vp_id)) {
            return false;
        } else {
            $insert = "INSERT INTO participate(USER_NRIC, VP_ID, PARTICIPATE_DATE, PARTICIPATE_NOTIFIED) VALUES(?, ?, CURRENT_TIMESTAMP, 0)";

            // check if user have participant in other volunteer program
            if ($this->checkParticipate($user_nric)) {
                $rvp_id = $this->getUserVPParticipate($user_nric);
                if (!(is_null($rvp_id))) {
                    foreach ($rvp_id as $rvp_ids) {
                        //if ($this->checkCurrentDateTimeVP($rvp_ids['VP_ID'])) {
                            // not allow to participate in other volunteer program
                            //return false;
                        //}
                        if($this->checkConfictingDateVP($vp_id, $rvp_ids['VP_ID'])) {
                            // not allow to participate in other volunteer program
                            return false;
                        }
                    }
                }
            }
            
            if($this->checkPastDateTimeVP($vp_id)){
                return false;
            }
            if($this->checkOngoingVP($vp_id)){
                return false;
            }
            // allow to participate in other volunteer program
            $conn = $this->connect();
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("ss", $user_nric, $vp_id);
            $result = $stmt->execute();
            $stmt->close();
            $conn->close();
            return $result;
        }
    }

    public function deleteUserParticipate($user_nric, $vp_id)
    {
        $delete = "DELETE FROM participate WHERE USER_NRIC = ? AND VP_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("ss", $user_nric, $vp_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getParticipateCount($vp_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT COUNT(*) c FROM participate WHERE VP_ID = '$vp_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['c'];
        }
    }

    public function getUserVPCommentCount($vp_id, $user_nric)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT COUNT(SC_ID) c FROM suggestions_comment WHERE VP_ID = '$vp_id' AND USER_NRIC = '$user_nric'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['c'];
        }
    }

    public function checkVCExist($vc_id)
    {
        // note doesn't involved in user input, hence doesn't need to use prepared statemen
        $sql = "SELECT * FROM vp_comment WHERE SC_ID = '$vc_id'";
        $result = $this->connect()->query($sql);
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function postVolunteerComment($vp_id, $user_nric, $comment)
    {
        $insert = "INSERT INTO vp_comment(VC_ID, VP_ID, USER_NRIC, COMMENT, COMMENT_DATE_TIME) VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $conn = $this->connect();
        $stmt = $conn->prepare($insert);
        $uid = uniqid();
        while ($this->checkVCExist($uid)) {
            $uid = uniqid();
        }
        $stmt->bind_param("ssss", $uid, $vp_id, $user_nric, $comment);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // check if user has already participated in a volunteer project
    public function checkParticipate($user_nric)
    {
        $sql = "SELECT VP_ID FROM participate WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        }
        $stmt->close();
        $conn->close();
    }

    // get all volunteer project that user has participated
    public function getUserVPParticipate($user_nric)
    {
        $sql = "SELECT * FROM participate WHERE USER_NRIC = ?";
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
}
