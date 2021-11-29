<?php
require("conf.php");
global $connection;
session_start();
/*if (!isset($_SESSION['tuvastamine'])) {
    header('Location: index.php');
    exit();
}
*/

if (!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=htmlspecialchars(trim($_POST['login']));
    $pass=htmlspecialchars(trim($_POST['pass']));

    $sool='tavalinetext';
    $krypt=crypt($pass, $sool);
    //kontroll, et andmebaasis on selline kasutaja
    $paring="SELECT * FROM kasutajad WHERE nimi='$login' AND parool='$krypt'";
    $yhendus=mysqli_query($connection, $paring );
    if(mysqli_num_rows($yhendus)==1){
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['tuvastamine']='tere';
        header('Location: index2.php');
    }
    else {
        echo '<script>alert("Kasuta või parool on valed")</script>';
        //echo "Kasutaja või parool on valed";
    }


    /*if($login=='admin' && $pass=='admin'){
        $_SESSION['tuvastamine']='tere';
        header('Location: index.php');
    }*/
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
    <title>Kaubad ja kaubagruppid</title>
</head>
<body>
<h1>Login vorm</h1>
<table>
    <form action="" method="post">
        <tr>
            <td>Kasutaja nimi:</td>
            <td>
                <input type="text" name="login" placeholder="nimi">
            </td>
        </tr>
        <tr>
            <td>Salasõna:</td>
            <td>
                <input type="password" name="pass">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Logi sisse">
            </td>
        </tr>
    </form>
</table>
</body>
</html>

<?php
//Ülesanne
//1. lisa parooli pikkuse kontroll (strlen).
//2. Admin - kasutaja parooliga admin saab maakonnad/kaubagruppide HALDUS (select, insert, delete).
//3. Tava - kasutaja parooliga 123456 saab inimesed/kaubad lisada, muuta, kustutada, inimeste  otsing.
?>