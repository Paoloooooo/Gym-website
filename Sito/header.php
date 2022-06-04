<meta name="Author" content="Antonio Di Palma, Paolo Franco">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
<!-- Awesome Fonts -->
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<!-- Usato solo per le icone del menù -->
<link rel="stylesheet" href="header.css">

<?php
$curr = basename($_SERVER['PHP_SELF']);
$home = $corsi = $acc = $bach = "";
//echo $curr;
// da gestire iscriviti che diventa profilo al login
switch ($curr) {
    case ("index.php"):
        $home = "active";
        break;
    case ("corsi.php"):
        $corsi = "active";
        break;
    case ("nuovoCorso.php"):
        $corsi = "active";
        break;
    case ("bacheca.php"):
        $bach = "active";
        break;
    default:
        $acc = "active";
}
?>
<div id="header">
    <nav>
        <div class="open"><i class="fas fa-bars"></i></div>
        <a href="index.php" class="logo">top gym</a>
        <ul class="navigation">
            <li><a class="<?php echo $home; ?>" href="index.php">Home page</a></li>
            <li><a class="<?php echo $corsi; ?>" href="corsi.php">Corsi</a></li>
            <?php
            if (!isset($_COOKIE['tipoUtente'])) {
            ?>
                <li><a class="<?php echo $acc; ?>" href="login.php">Accedi</a></li>
            <?php
            } else {
            ?>
                <li><a class="<?php echo $acc; ?>" href="profilo.php">Profilo</a></li>
            <?php
            }
            ?>
            <li><a class="<?php echo $bach; ?>" href="bacheca.php">Bacheca</a></li>
            <div class="close"><i class="fas fa-times"></i></div>
        </ul>
    </nav>
</div>

<!-- Javascript Section -->
<script>
    var mainMenu = document.querySelector('.navigation')
    var openMenu = document.querySelector('.open')
    var closeMenu = document.querySelector('.close')

    openMenu.addEventListener('click', show)
    closeMenu.addEventListener('click', close)

    function show() {
        mainMenu.style.display = 'flex'
        mainMenu.style.right = '0'
    }

    function close() {
        mainMenu.style.right = '-60%'
    }
</script>