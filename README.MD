
rules:
a user can post suggestions once every 15 mins

algorithms for showing top suggestions/recent ->
check if the result/data is null -> if true then shows the data in nonexisting
check if suggestions already been picked from volunteer_program if true, avoid it


TODO today:
- [x] alert modal using bootstrap -= change the current use of text popup
- [x] make message function to show popup // e.g delete use header to location with get param like 1/3/5/7 etc...
- [x] fix the search query
- [ ] volunteer program
- [ ] user profile
- [ ] check username to make it only unique // one only
- [ ] send alert to user if the participate estimated date start is nearly
- [ ] possible to email notification 
- [ ] check and make sure to clean unuse code
- [ ] todo: disabled changes on volunteer program start date/end date, once it started.
- [ ] todo: only allow use to create/update the start date in about 2/3 day after the current picked date

- [ ] todo: fix delete button not showing confirm popup.
- [ ] let organizer see the list of the participants.
- [ ] send notification of program going to start.


                $_SESSION["loggedin"] = true;
                $_SESSION["nric"] = $nric;
                $_SESSION["groupcode"] = $groupcode;


note: for now I'll allow for one user to only participate one volunteer program
note: remove group_matrix and web_program table due to ununse

note: todo: make new ERD based on above


groupcode = USER, ADM, ORG
USER - NORMAL USER WHO CAN CREATE SUGGESTIONS// READ SUGGESTIONS // VOTE SUGGESTIONS // DELETE THEIR OWN SUGGESTIONS // COMMENT ON SUGGESTIONS // EDIT THEIR OWN SUGGESTIONS // UPDATE THEIR PROFILE // READ ACHIEVEMENT // APPLY FOR GROUPCODE // CAN PARTICIPATE IN VOLUNTEER PROGRAM

ORG - HAVE WHAT USER HAVE // CAN PICK SUGGESTIONS AS VOLUNTEER PROGRAM // CAN EDIT/DELETE THEIR VOLUNTEER PROGRAM PICK // 

ADM - CAN DO ANYTHING LIKE USER AND ORG // CAN CREATE/UPDATE/DELETE SUGGESTIONS VOLUNTEER PROGRAM USER COMMENT
