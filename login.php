<!DOCTYPE html>
<html>

<head>
    <title>Login </title>
    <meta name="author" content="Antonio Di Palma, Paolo Franco">
    <link rel="stylesheet" type="text/css" href="login.css">
    <link rel="icon" href="./images/icona.jpg">
</head>

<body>
    <?php
    include 'header.php';

    $cf = $password = "";
    extract($_POST);
    if ($cf != "" && $password != "") {
        require 'connect db.php';
        $db = pg_connect($connection_string);
        pg_prepare("cerca user utenti", "select * from utenti where CF=$1;");
        pg_prepare("cerca user admin", "select * from amministratori where CF=$1;");
        $res_1 = pg_execute("cerca user utenti", array($cf));
        $res_1 = pg_fetch_assoc($res_1);
        $res_2 = pg_execute("cerca user admin", array($cf));
        $res_2 = pg_fetch_assoc($res_2);


        if ($res_1 == false && $res_2 == false) {
    ?>
            <script>
                alert("Nome utente o password errati!")
            </script>
        <?php
        } elseif (isset($res_1['password']) && password_verify($password, $res_1['password'])) {
            session_start();
            setcookie("tipoUtente", "user");
            setcookie("CF", $res_1['cf']);
            header("Location: index.php");
        } elseif (isset($res_2['password']) && password_verify($password, $res_2['password'])) {
            session_start();
            setcookie("tipoUtente", "admin");
            setcookie("CF", $res_2['cf']);
            header("Location: index.php");
        } else {
        ?>
            <script>
                alert("Nome utente o password errati!")
            </script>
    <?php
        }
    }
    ?>

    <div class="container">
        <div class="form-container">
            <div class="image">
            </div>
            <div class="panels-container">
                <div class="signin">
                    <form id="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <h2 id="register">Effettua il login</h2>
                        <div class="input-field">
                            <input type="text" id="cf" name="cf" placeholder="Codice Fiscale" value="<?php echo $cf; ?>"></h3>
                        </div>
                        <div class="input-field">
                            <input type="password" id="password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="loginbtn"> Login </button>
                        <div class="container-signup">
                            <p>Non sei registrato? <a href="register.php" id="signin">Iscriviti</a>.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.html'; ?>

    <script type="text/javascript">
        document.getElementById("form").addEventListener('submit', function(e) {
            e.preventDefault();
            cf = document.getElementById("cf").value;
            passw = document.getElementById("password").value;

            if (cf == "" || passw == "") {
                alert("Compila tutti i campi del form!");
                return false;
            }

            if (cf.length != 16) {
                alert("Codice fiscale in un formato errato!");
                //console.log(cf.length)
                return false;
            }
            //console.log("Inviati");
            document.forms.form.submit()
        });
    </script>
</body>

</html>