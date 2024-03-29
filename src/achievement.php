<?php

class achievement extends db
{

    public function getAllAchievements()
    {
        $sql = "SELECT * FROM achievement";
        $result = $this->connect()->query($sql);
        $numRows = $result->num_rows;
        if ($numRows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAchievementDetails($achievement_id)
    {
        // ACHIEVEMENT_ID	ACHIEVEMENT_NAME	ACHIEVEMENT_DESCRIPTION	ACHIEVEMENT_CREATED_DATE USER_NRIC
        $sql = "SELECT * FROM achievement WHERE ACHIEVEMENT_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $achievement_id);
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

    // create achievement
    public function createAchievement($achievement_id, $achievement_name, $achievement_description, $user_nric)
    {
        $sql = "INSERT INTO achievement (ACHIEVEMENT_ID, ACHIEVEMENT_NAME, ACHIEVEMENT_DESCRIPTION, ACHIEVEMENT_CREATED_DATE, USER_NRIC) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        while ($this->checkAchievementExist($achievement_id)) {
            $achievement_id = uniqid(); // alt
        }
        $stmt->bind_param("ssss", $achievement_id, $achievement_name, $achievement_description, $user_nric);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // check Achievement Exist
    public function checkAchievementExist($achievement_id)
    {
        $sql = "SELECT ACHIEVEMENT_ID FROM achievement WHERE ACHIEVEMENT_ID = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $achievement_id);
        $stmt->execute();
        $stmt->bind_result($achievement_id);
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

    // USER_NRIC	ACHIEVEMENT_ID	RECEIVED_DATE -> user_achievement
    public function getUserAchievement($user_nric)
    {
        $sql = "SELECT * FROM user_achievement WHERE USER_NRIC = ?";
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

    // check if user_nric have the achievement_id
    public function checkUserAchievement($user_nric, $achievement_id)
    {
        $sql = "SELECT ACHIEVEMENT_ID FROM user_achievement WHERE USER_NRIC = ? AND ACHIEVEMENT_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_nric, $achievement_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                $stmt->close();
                $conn->close();
                return true;
            }
        }
        $stmt->close();
        $conn->close();
        return false;
    }

    // insert user_achievement
    public function createUserAchievement($user_nric, $achievement_id)
    {
        $sql = "INSERT INTO user_achievement (USER_NRIC, ACHIEVEMENT_ID, RECEIVED_DATE) VALUES (?, ?, CURRENT_TIMESTAMP)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_nric, $achievement_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // remove user_achievement
    public function removeUserAchievement($user_nric, $achievement_id)
    {
        $sql = "DELETE FROM user_achievement WHERE USER_NRIC = ? AND ACHIEVEMENT_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_nric, $achievement_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    // remove achievement
    public function removeAchievement($achievement_id)
    {
        $sql = "DELETE FROM achievement WHERE ACHIEVEMENT_ID = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $achievement_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function achievementValidator($user_nric)
    {
        // CHECK EVERYTHING HERE
        // login achievement
        $sql = "SELECT USER_LOGIN_COUNT FROM user WHERE USER_NRIC = ?";
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
                $user_login_count = $data[0]['USER_LOGIN_COUNT'];
                if ($user_login_count == 10) {
                    $this->createUserAchievement($user_nric, 'login_1');
                } else if ($user_login_count == 30) {
                    $this->createUserAchievement($user_nric, 'login_2');
                } else if ($user_login_count == 50) {
                    $this->createUserAchievement($user_nric, 'login_3');
                }
                // for future plan
                /* else if ($user_login_count == 100) {
                    $this->createUserAchievement($user_nric, 'login_4');
                } else if ($user_login_count == 250) {
                    $this->createUserAchievement($user_nric, 'login_5');
                } else if ($user_login_count == 1000) {
                    $this->createUserAchievement($user_nric, 'login_6');
                } */
            }
        }
        // suggestions total achievement
        $sql = "SELECT COUNT(*) AS TOTAL_SUGGESTIONS FROM suggestions WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data2[] = $row;
                }
                $total_suggestions = $data2[0]['TOTAL_SUGGESTIONS'];
                if ($total_suggestions == 10) {
                    $this->createUserAchievement($user_nric, 'suggestion_1');
                } else if ($total_suggestions == 30) {
                    $this->createUserAchievement($user_nric, 'suggestion_2');
                } else if ($total_suggestions == 50) {
                    $this->createUserAchievement($user_nric, 'suggestion_3');
                }
                // for future plan
                /* else if ($total_suggestions == 100) {
                    $this->createUserAchievement($user_nric, 'suggestion_4');
                } else if ($total_suggestions == 250) {
                    $this->createUserAchievement($user_nric, 'suggestion_5');
                } else if ($total_suggestions == 1000) {
                    $this->createUserAchievement($user_nric, 'suggestion_6');
                } */
            }
        }

        // total voted from vote achievement
        $sql = "SELECT COUNT(*) AS TOTAL_VOTES FROM vote WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data3[] = $row;
                }
                $total_votes = $data3[0]['TOTAL_VOTES'];
                if ($total_votes == 10) {
                    $this->createUserAchievement($user_nric, 'vote_1');
                } else if ($total_votes == 30) {
                    $this->createUserAchievement($user_nric, 'vote_2');
                } else if ($total_votes == 50) {
                    $this->createUserAchievement($user_nric, 'vote_3');
                }
                // for future plan
                /* else if ($total_votes == 100) {
                    $this->createUserAchievement($user_nric, 'vote_4');
                } else if ($total_votes == 250) {
                    $this->createUserAchievement($user_nric, 'vote_5');
                } else if ($total_votes == 1000) {
                    $this->createUserAchievement($user_nric, 'vote_6');
                } */
            }
        }
        // total participated in volunteer achievement
        $sql = "SELECT COUNT(*) AS TOTAL_PARTICIPATIONS FROM participate WHERE USER_NRIC = ?";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_nric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                while ($row =  $result->fetch_array(MYSQLI_ASSOC)) {
                    $data4[] = $row;
                }
                $total_participations = $data4[0]['TOTAL_PARTICIPATIONS'];
                if ($total_participations == 10) {
                    $this->createUserAchievement($user_nric, 'volunteer_1');
                } else if ($total_participations == 30) {
                    $this->createUserAchievement($user_nric, 'volunteer_2');
                } else if ($total_participations == 50) {
                    $this->createUserAchievement($user_nric, 'volunteer_3');
                }
                // for future plan
                /* else if ($total_participations == 100) {
                    $this->createUserAchievement($user_nric, 'volunteer_4');
                } else if ($total_participations == 250) {
                    $this->createUserAchievement($user_nric, 'volunteer_5');
                } else if ($total_participations == 1000) {
                    $this->createUserAchievement($user_nric, 'volunteer_6');
                } */
            }
        }
    }
}
