<html>
<head>
    <title>Admin</title>
    <meta content="author" value="Gruppo 18">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php
        $cf=$nome=$cognome=$pass="";
        extract($_POST);
        if($cf!=""&&$pass!=""&&$cognome!=""&&$nome!=""){
            include 'connect db.php';
            $db=pg_connect($connection_string);
            pg_prepare("add admin","insert into amministratori(cf,password,nome,cognome) values($1,$2,$3,$4)");
            $res=pg_execute("add admin",array($cf,password_hash($pass, PASSWORD_DEFAULT),$nome,$cognome));
        };
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h3>cf:<input type="text" id="cf" name="cf"></h3>
        <h3>nome:<input type="text" id="nome" name="nome"></h3>
        <h3>cognome:<input type="text" id="cognome" name="cognome"></h3>
        <h3>password:<input type="password" id="pass" name="pass"></h3>
        <input type="submit">Registrati</input>
    </form>
    <script type="text/javascript">

    </script>
</body>
</html>