<?php 


$host = 'mysql:host=localhost;dbname=dialogue';
$login = 'root'; 
$password = 'root'; 
$options = array(

    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
);  

$pdo = new PDO($host, $login, $password, $options);

if(isset($_GET['color'])){

    $choix = $_GET['color'];

}elseif(isset($_COOKIE['COLOR'])){

    $choix = $_COOKIE['COLOR'];

}else{

    $choix = 'red';
}




if(isset($_GET['color2'])){

    $choix2 = $_GET['color2'];

}elseif(isset($_COOKIE['COLOR2'])){

    $choix2 = $_COOKIE['COLOR2'];

}else {

    $choix2 = 'grey'; 
}


$unan = 365 * 24 * 3600;
setcookie('COLOR', $choix, time() + $unan);
setcookie('COLOR2', $choix2, time() + $unan);

?>


    <!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire 2</title>
    <!-- futur dark mode -->
    <style>form , header{background-color: <?php echo $choix?>;}</style>
    <style>body{background-color: <?php echo $choix2?>;}</style>
</head>
<body>
    <?php

    $msg = '';
    $req = '';
if( isset($_POST['pseudo']) && isset($_POST['message']) )  {

    $pseudo = trim($_POST['pseudo']);
    $message = trim($_POST['message']);
    $taille_pseudo = iconv_strlen($pseudo);
    $taille_message = iconv_strlen($message);
    $erreur = 'non';
    

    if($taille_pseudo < 3 || $taille_pseudo > 16){

        $erreur = 'oui';
        echo '<p style="color: red;">Attention, votre nom doit avoir entre 3 et 16 caractères inclus</p>';
       
    }else{
        
        echo '<p style="color: green;">Taille du pseudo ok !</p>';
    }

    if(empty($taille_message)) {

        $erreur = 'oui';
        echo '<p style="color: red;">écris un truc mon grand🚨</p>';
       
    }

    if($erreur == 'non'){

        $enregistrement = $pdo->prepare("INSERT INTO structure (id_commentaire, pseudo, message, date_enregistrement) VALUES (NULL, :pseudo, :message, NOW() )");
        $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(':message', $message, PDO::PARAM_STR);
        $enregistrement->execute(); 
        $afficher = $pdo->query(" SELECT * FROM structure ");
    } 

}  

        $liste_messages = $pdo->query("SELECT pseudo, message, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%i:%s') AS date_fr FROM structure  ORDER BY date_enregistrement DESC");


    ?>          <div class="color_cookies">
                    <header><h1>Negocojo</h1></header>
                    <li><a href="?color=red">Red</a></li>
                    <li><a href="?color=yellow">Yellow</a></li>
                    <li><a href="?color=pink">Pink</a></li>
                    <li><a href="?color=green">Green</a></li>
                    <hr>
                    <li><a href="?color2=grey">Grey</a></li>
                    <li><a href="?color2=blue">Blue</a></li>
                    <li><a href="?color2=white">White</a></li>
                    <li><a href="?color2=orange">Orange</a></li>
                </div>
                

        
        </div>
        <div class="conteneur">
        <form method="post" enctype="multipart/form-data">
        <?php

        echo '<p>Il y a ' . $liste_messages->rowCount() . '  messages</p>';

        while ($com = $liste_messages->fetch(PDO::FETCH_ASSOC)) {
?>
    <div>
        <h5>Par : <?php echo $com['pseudo']; ?>, le <?php echo $com['date_fr'] ?></h5>
        <div class="card-body">
        <p><?php echo $com['message']; ?></p>
    </div>
    </div>

<?php
}

?>
                
            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo"><br><br>
            <label for="message">Message</label>
          
            <input type="text" name="message" id="email"><br><br>
            <input type="submit" name="valider" id="valider" value="Valider">
        </form>
    </div>
</body>
</html>