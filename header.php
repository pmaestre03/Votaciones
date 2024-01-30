
<body class="header">
    <link rel="stylesheet" href="Utilidades/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <header class="header">
        
        <?php
        session_start();

        if (isset($_SESSION['usuario'])) {
            // Si el usuario está logueado
            echo "<button class='button' id='signupButton'>SIGNUP</button>";
            //redireccion boton signup
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#signupButton").on("click", function() {';
            echo '        window.location.href = "register.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo "<h1>VotaPAX</h1>";
            echo '<div class="button-container">';

            echo '<button class="button button-secund" id="homeButton">HOME</button>';
            //redireccion boton home
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#homeButton").on("click", function() {';
            echo '        window.location.href = "index.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo '<button class="button button-secund" id="dashboardButton">DASHBOARD</button>';
            //redireccion boton home
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#dashboardButton").on("click", function() {';
            echo '        window.location.href = "dashboard.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo '<button class="button button-login" id="logoutButton">Hola, ' . $_SESSION['usuario'] . ' <a href="logout.php">Logout</a></button>';
            //redireccion boton logout
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#logoutButton").on("click", function() {';
            echo '        window.location.href = "logout.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo '</div>';
        } else {
            // Si el usuario no está logueado
            echo "<button class='button' id='signupButton'>SIGNUP</button>";
            //redireccion boton signup
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#signupButton").on("click", function() {';
            echo '        window.location.href = "register.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo "<h1>VotaPAX</h1>";
            echo '<div class="button-container">';

            echo '<button class="button button-secund" id="homeButton">HOME</button>';
            //redireccion boton home
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#homeButton").on("click", function() {';
            echo '        window.location.href = "index.php";';
            echo '    });';
            echo '});';
            echo '</script>';

            echo '<button class="button button-login" id="loginButton">LOGIN</button>';
            //redireccion boton login
            echo '<script>';
            echo '$(document).ready(function() {';
            echo '    $("#loginButton").on("click", function() {';
            echo '        window.location.href = "login.php";';
            echo '    });';
            echo '});';
            echo '</script>';
            
            echo '</div>';
        }
        ?>

    </header>

