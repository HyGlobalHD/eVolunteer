<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='icon' href='favicon.png' type='image/png' />
    <title>eVolunteer - About</title>
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="css/signup.css" rel="stylesheet">
    <style>
        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .form-control-dark {
            color: #fff;
            background-color: var(--bs-dark);
            border-color: var(--bs-gray);
        }

        .form-control-dark:focus {
            color: #fff;
            background-color: var(--bs-dark);
            border-color: #fff;
            box-shadow: 0 0 0 .25rem rgba(255, 255, 255, .25);
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .text-small {
            font-size: 85%;
        }

        .dropdown-toggle {
            outline: 0;
        }

        body {
            display: block;
        }
    </style>
</head>

<div class="p-3 bg-white text-dark">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul></ul>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="index.php" class="nav-link px-2 text-secondary">Home</a></li> <!-- text-secondary -->
            <li><a href="#" class="nav-link px-2 text-dark"><strong>About</strong></a></li>
        </ul>

        <div class="text-end">
            <a href="user_login.php" class="btn btn-outline-primary me-2">Login</a>
            <a href="user_register.php" class="btn btn-primary" style="text-decoration: none;">Sign-up</a>
        </div>
    </header>
</div>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>


<body>

    <main class="">


        <div class="text-center">

            <h4 style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer</span></h4>
            <h5>About</h5>

            <p>Developed by UiTM Student, Amirul Adli Fahmi bin Azam</p>

            <p>The purpose of eVolunteer is to make the need of user much easier in finding/suggesting a volunteer program.</p>

            <p>eVolunteer offers a lot of features, such as the ability to use markdown(A Markup Language) format for posting.</p>


            <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy; 2021</span></p>
        </div>


    </main>

    <br><br><br><br>
    <br>
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
        </footer>
    </div>


    <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/ur.js"></script>
</body>


</html>