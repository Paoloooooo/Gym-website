<!DOCTYPE html>
<html lang='it'>

<head>
    <title>Nuovo corso</title>
    <meta charset="utf-8">
    <meta name="author" content="Gruppo 18">
    <link rel="stylesheet" href="nuovoCorso.css">
    <meta name="author" content="Antonio Di Palma, Paolo Franco">
    <link rel="icon" href="./images/icona.jpg">

</head>

<body>
    <?php
    include 'header.php';
    $image = $nome = $descrizione = $numero = "";
    extract($_POST);
    if (isset($_FILES['image'])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // effettua controlli sul file caricato
        if ($nome != "") {
            //controlla se il file è un immagine
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = true;
            } else {
                echo "<script>alert(\"Il file caricato non è un immagine\")</script>";
                $uploadOk = false;
            }
        }
    }

    if (isset($uploadOk) && $uploadOk) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

            $image = $target_file;
            if ($image != "" && $nome != "" && $descrizione != "" && $numero != "") {
                include 'connect db.php';
                $db = pg_connect($connection_string);
                pg_prepare("nuovo corso", "insert into corsi(nome,posti_tot,image,descrizione) values($1,$2,$3,$4)");
                $res = pg_execute("nuovo corso", array($nome, $numero, $image, $descrizione));
                if ($res == false) {
                    echo "<script>alert(\"Inserimento non riuscito\")</script>";
                } else {
                    header("Location: corsi.php");
                }
            }
        } else {
            echo "<script>alert(\"Si è verificato un problema nel caricamento. Riprova\")</script>";
        }
    }





    if (isset($_COOKIE['tipoUtente']) && $_COOKIE['tipoUtente'] == 'admin') {
    ?>

        <div class="container">
            <div class="form-container">
                <h2 id="title">Inserisci un nuovo corso:</h2>
                <form method="POST" id="nuovo" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="input-field">
                        <input required type="text" id="nome" name="nome" placeholder="Nome del corso" value="<?php echo $nome; ?>">
                    </div>
                    <div class="desc-field">
                        <textarea required name="descrizione" id="descrizione" cols="30" rows="10" placeholder="Descrizione del corso (MAX 400 caratteri)" value="<?php echo $descrizione; ?>"></textarea>
                    </div>
                    <div class="numb-picker">
                        <p>Numero di posti: <input required type="number" id="numero" name="numero" value="<?php echo $numero; ?>"></p>
                    </div>
                    <div class="file-picker">
                        <input required type="file" id="image" name="image" value="Immagine del corso (solo jpeg)">
                    </div>
                    <input class="send" type="submit">
                </form>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="riempimento">
            <h1 style="text-align:center;">Questa pagina è riservata</h1>
        </div>
    <?php
    }
    ?>
    <script>
        console.log(document.forms);
        document.getElementById("nuovo").addEventListener('submit', function(e) {
            e.preventDefault();
            descrizione = document.getElementById("descrizione").value;
            num = document.getElementById("numero").value;
            image = document.getElementById("image");

            if (descrizione.length > 400) {
                alert("Hai superato il limite di caratteri, hai usato " + descrizione.length + " caratteri su un massimo di 400");
                return false;
            }
            if (num < 0) {
                alert("Il numero di posti deve essere positivo");
                return false;
            }
            document.forms.nuovo.submit();
        });
    </script>
    <?php
    include 'footer.html';
    ?>
</body>

</html>