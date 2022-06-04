<!DOCTYPE html>
<html>

<head>
    <title>Registrazione</title>
    <meta name="author" content="Antonio Di Palma, Paolo Franco">
    <link rel="stylesheet" type="text/css" href="register.css">
    <link rel="icon" href="./images/icona.jpg">

</head>

<body>
    <?php
    include 'header.php';
    $cf = $password = $psw_repeat = $name = $surname = $sex = $birthdate = $address = "";
    extract($_POST);
    if ($cf != "" && $password != "" && $psw_repeat != "" && $name != "" && $surname != "" && $sex != "" && $birthdate != "" && $address != "") {
        require 'connect db.php';
        $db = pg_connect($connection_string);
        try {
            pg_prepare("InsertUtente", "insert into utenti(cf, nome, cognome, nascita, iscrizione, residenza, sesso, password) values($1,$2,$3,$4,$5,$6,$7,$8)");
            pg_execute("InsertUtente", array($cf, $name, $surname, $birthdate, date('Y-m-d H:i:s'), $address, $sex, password_hash($password, PASSWORD_DEFAULT)));
            header("Location: login.php");
        } catch (Exception $e) {
            echo '<script>alert("Utente già registrato!")</script>';
        }
    }
    ?>

    <div class="container">
        <div class="form-container">
            <div class="image">
            </div>
            <div class="panels-container">
                <div class="signup">
                    <form id="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <h2 id="register">Registrazione</h2>
                        <p>Compilare il form per registrarsi</p><br>
                        <div class="input-field">
                            <input type="text" id="cf" name="cf" placeholder="Codice Fiscale" value="<?php echo $cf; ?>"><br>
                        </div>
                        <div class="input-field">
                            <input type="password" id="password" name="password" placeholder="Password (almeno 8 caratteri)" value="<?php echo $password; ?>"><br>
                        </div>
                        <div class="input-field">
                            <input type="password" id="psw_repeat" name="psw_repeat" placeholder="Ripeti password" value="<?php echo $psw_repeat; ?>"><br>
                        </div>
                        <div class="input-field">
                            <input type="text" id="name" name="name" placeholder="Nome" value="<?php echo $name; ?>"><br>
                        </div>
                        <div class="input-field">
                            <input type="text" id="surname" name="surname" placeholder="Cognome" value="<?php echo $surname; ?>"><br>
                        </div>
                        <div class="input-field">
                            <input type="text" id="address" name="address" placeholder="Indirizzo" value="<?php echo $address; ?>"><br>
                        </div>
                        <h3>Sesso: M <input type="radio" id="sexm" name="sex" value="M"> F <input type="radio" id="sexf" name="sex" value="F"><br></h3>
                        <h3>Data di nascita: <input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>"></h3><br>
                        <button type="submit" class="registerbtn">Registrati</button>
                        <div class="container-signin">
                            <p>Hai già un account? <a href="login.php">Effettua il login</a>.</p>
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
            psw_repeat = document.getElementById("psw_repeat").value;
            name = document.getElementById("name").value;
            surname = document.getElementById("surname").value;
            sexm = document.getElementById("sexm").value;
            sexf = document.getElementById("sexf").value;
            birthdate = document.getElementById("birthdate").value;
            address = document.getElementById("address").value;

            if (cf == "" || passw == "" || psw_repeat == "" || name == "" || surname == "" || (sexm == "" && sexf == "") || birthdate == "" || address == "") {
                alert("Compila tutti i campi del form!");
                return false;
            }

            if (cf.length != 16) {
                alert("Codice fiscale in un formato errato!");
                return false;
            }

            if (passw != psw_repeat) {
                alert("Le due password non combaciano, riprova!");
                return false;
            }

            if (!passw.match("(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}")) {
                alert("La password deve avere almeno 8 caratteri e deve contenere almeno una maiuscola,una minuscola e un numero!")
                return false;
            }
            document.forms.form.submit()
        });
    </script>
</body>

</html>