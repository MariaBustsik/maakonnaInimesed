<?php
require ('conf.php');


function countyData($sort_by = "kaubanimi", $search_term = "") {
    global $connection;
    $sort_list = array("kaubanimi", "hind", "kaubagrupp");
    if(!in_array($sort_by, $sort_list)) {
        return "Seda tulpa ei saa sorteerida";
    }
    $request = $connection->prepare("SELECT kaubad.id, kaubanimi, hind, kaubagrupid.kaubagrupp
    FROM kaubad, kaubagrupid 
    WHERE kaubad.kaubagrupp_id = kaubagrupid.id 
    AND (kaubanimi LIKE '%$search_term%' OR hind LIKE '%$search_term%' OR kaubagrupp LIKE '%$search_term%')
    ORDER BY $sort_by");
    $request->bind_result($id, $kaubanimi, $hind, $kaubagrupp);
    $request->execute();
    $data = array();
    while($request->fetch()) {
        $toode = new stdClass();
        $toode->id = $id;
        $toode->kaubanimi = htmlspecialchars($kaubanimi);
        $toode->hind = htmlspecialchars($hind);
        $toode->kaubagrupp = $kaubagrupp;
        array_push($data, $toode);
    }
    return $data;
}

function createSelect($query, $name) {
    global $connection;
    $query = $connection->prepare($query);
    $query->bind_result($id, $data);
    $query->execute();
    $result = "<select name='$name'>";
    while($query->fetch()) {
        $result .= "<option value='$id'>$data</option>";
    }
    $result .= "</select>";
    return $result;
}

function addCounty($county_name) {
    global $connection;
    $query = $connection->prepare("INSERT INTO kaubagrupid (kaubagrupp)
    VALUES (?)");
    $query->bind_param("s", $county_name);
    $query->execute();
}

function addPerson($first_name, $last_name, $county_id) {
    global $connection;
    $query = $connection->prepare("INSERT INTO kaubad (kaubanimi, hind, kaubagrupp_id)
    VALUES (?, ?, ?)");
    $query->bind_param("ssd", $first_name, $last_name, $county_id);
    $query->execute();
}

function deletePerson($toode_id) {
    global $connection;
    $query = $connection->prepare("DELETE FROM kaubad WHERE id=?");
    $query->bind_param("i", $toode_id);
    $query->execute();
}

function savePerson($toode_id, $first_name, $last_name, $county_id) {
    global $connection;
    $query = $connection->prepare("UPDATE kaubad
    SET kaubanimi=?, hind=?, kaubagrupp_id=?
    WHERE kaubad.id=?");
    $query->bind_param("ssii", $first_name, $last_name, $county_id, $toode_id);
    $query->execute();

}
?>
