<?php
$tema = $_COOKIE['tema'] ?? "claro";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['tema'])) {
$redirecina=$_POST['tema'];
        if ($tema == "claro") {

            $tema = "escuro";
        } else {
            $tema = "claro";

        }

        setcookie("tema", $tema, time() + 36000, "/");
        
    header("Location:".$redirecina.".php");
    exit;
    }


}
if ($tema == "claro") {
    $arquivo = "css/claro/tema_claro.css";
} else {
    $arquivo = "css/escuro/tema_escuro.css";
}
?>