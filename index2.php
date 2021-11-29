<?php
require("conf.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login2.php');
    exit();
}

require("functions2.php");
$sort = "kaubanimi";
$search_term = "";
if(isset($_REQUEST["sort"])) {
    $sort = $_REQUEST["sort"];
}
if(isset($_REQUEST["search_term"])) {
    $search_term = $_REQUEST["search_term"];
}

if(isset($_REQUEST["kaubagruppi_lisamine"])) {
    if (empty($_REQUEST['kaubagrupp'])) {
        echo '<script>alert("Viga andmete sisestamisel")</script>';
    }
    else {
        addCounty($_REQUEST["kaubagrupp"]);
        header("Location: index2.php");
        exit();
    }

}

if(isset($_REQUEST["kauba_lisamine"])) {
    if (empty($_REQUEST['kaubanimi']) or empty($_REQUEST['hind']) or empty($_REQUEST['kaubagrupp_id'])) {
        echo '<script>alert("Viga andmete sisestamisel")</script>';
    }
    else {
        addPerson($_REQUEST["kaubanimi"], $_REQUEST["hind"], $_REQUEST["kaubagrupp_id"]);
        header("Location: index2.php");
        exit();
    }
}
if(isset($_REQUEST["delete"])) {
    deletePerson($_REQUEST["delete"]);
}
if(isset($_REQUEST["save"])) {
    savePerson($_REQUEST["changed_id"], $_REQUEST["kaubanimi"], $_REQUEST["hind"], $_REQUEST["kaubagrupp_id"]);
}
$kaup = countyData($sort, $search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
    <title>Kaubad ja kaubagruppid</title>
</head>
<body>
<header class="header">
    <p><?php echo $_SESSION['login']; ?> on sisse logitud</p>

    <form action="logout2.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
    <div class="container">
        <h1>Tabelid | Kaubad ja kaubagruppid</h1>
    </div>
</header>
<main class="main">
    <div class="container">
        <form action="index2.php">
            <input type="text" name="search_term" placeholder="Otsi...">
        </form>
    </div>
    <?php if(isset($_REQUEST["edit"])): ?>
        <?php foreach($kaup as $toode): ?>
            <?php if($toode->id == intval($_REQUEST["edit"])): ?>
                <div class="container">
                    <form action="index2.php">
                        <input type="hidden" name="changed_id" value="<?=$toode->id ?>"/>
                        <input type="text" name="kaubanimi" value="<?=$toode->kaubanimi?>">
                        <input type="text" name="hind" value="<?=$toode->hind?>">
                        <?php echo createSelect("SELECT id, kaubagrupp FROM kaubagrupid", "kaubagrupp_id"); ?>
                        <a title="Katkesta muutmine" class="cancelBtn" href="index2.php" name="cancel">X</a>
                        <input type="submit" name="save" value="&#10004;">
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th>Id</th>
                <th><a href="index.php?sort=kaubanimi">Kaubanimi</a></th>
                <th><a href="index.php?sort=hind">Hind</a></th>
                <th><a href="index.php?sort=kaubagrupp">Kaubagrupp</a></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($kaup as $toode): ?>
                <tr>
                    <td><strong><?=$toode->id ?></strong></td>
                    <td><?=$toode->kaubanimi ?></td>
                    <td><?=$toode->hind ?></td>
                    <td><?=$toode->kaubagrupp ?></td>
                    <td>
                        <a title="Kustuta kaup" class="deleteBtn" href="index2.php?delete=<?=$toode->id?>"
                           onclick="return confirm('Oled kindel, et soovid kustutada?');">X</a>
                        <a title="Muuda kaup" class="editBtn" href="index2.php?edit=<?=$toode->id?>">&#9998;</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        if($_SESSION['login'] == "admin" ) {
            echo "<form action='index2.php'>
            <h2>Kaubagrupi lisamine:</h2>
            <dl>
                <dt>Kaubagrupi nimi:</dt>
                <dd><input type='text' name='kaubagrupp' placeholder='Sisesta kaubagruppi nimi...'></dd>

                <input type='submit' name='kaubagruppi_lisamine' value='Lisa kaubagrupp'>
            </dl>
        </form>";
        }
        ?>



        <form action="index2.php">
            <h2>Kauba lisamine:</h2>
            <dl>
                <dt>Kauba nimi:</dt>
                <dd><input type="text" name="kaubanimi" placeholder="Sisesta kauba nimi..."></dd>
                <dt>Hind:</dt>
                <dd><input type="text" name="hind" placeholder="Sisesta kauba hind..."></dd>
                <dt>Kaubagrupp</dt>
                <dd><?php
                    echo createSelect("SELECT id, kaubagrupp FROM kaubagrupid", "kaubagrupp_id");
                    ?></dd>
                <input type="submit" name="kauba_lisamine" value="Lisa kaup">
            </dl>
        </form>
    </div>
</main>
</body>
</html>
