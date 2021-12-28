
rules:
a user can post suggestions once every 15 mins

algorithms for showing top suggestions/recent ->
check if the result/data is null -> if true then shows the data in nonexisting
check if suggestions already been picked from volunteer_program if true, avoid it


TODO today:
- alert modal using bootstrap -= change the current use of text popup
- make message function to show popup // e.g delete use header to location with get param like 1/3/5/7 etc...
- fix the search query


                $_SESSION["loggedin"] = true;
                $_SESSION["nric"] = $nric;
                $_SESSION["groupcode"] = $groupcode;


note: for now I'll allow for one user to only participate one volunteer program
note: remove group_matrix and web_program table due to ununse

note: todo: make new ERD based on above
