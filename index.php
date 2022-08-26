<?php
$servername = "localhost";
$database = "db";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexão Falhou: " . $conn->connect_error);
}
if (!empty($_GET['url'])) {
    $sql = "SELECT id, link_original, link_curto, data_criacao FROM encurta_link WHERE link_curto = '" . $_GET['url'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
        }
    } else {
        echo "ENDEREÇO NÃO ENCONTRADO.";
    }
}
if ($_POST) {
    $sql = "INSERT INTO encurta_link (link_original, link_curto, data_criacao) VALUES ('" . $_POST['original'] . "', '" . $_POST['curto'] . "', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "<b>SALVO COM SUCESSO URL = </b>" . $_POST['curto'];
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desenvolvimento Back-End</title>
</head>

<body>
    <form action="index.php" method="post">
        <label for="">Link para Encurtar</label>
        <input type="text" id="original" name="original" placeholder="Link para Encurtar" onblur="encurtarLink()" autocomplete="off">
        <input type="text" id="curto" name="curto" autocomplete="off" hidden>
        <button type="submit" id="btn" disabled>Encurtar</button>
    </form>
    <br>
    <hr>
    <label for="">Filtros</label>
    <input type="number" placeholder="Retornar por Id" id="id" autocomplete="off">
    <input type="date" placeholder="Retornar por Data" id="data" autocomplete="off">
    <input type="text" placeholder="Retornar link original" id="ret_original" autocomplete="off">
    <button id="btn_filtro" onclick="filtro()">Buscar</button>
    <button id="btn_filtro" onclick="window.location.href = 'http://localhost/dbe/index.php'">Limpar</button>

    <br>
    <hr>
    <?php

    $sql = "SELECT id, link_original, link_curto, data_criacao FROM encurta_link WHERE 1=1";
    if (!empty($_GET['id'])) {
        $sql = $sql . " AND id =" . $_GET['id'];
    }
    if (!empty($_GET['data'])) {
        $sql = $sql . " AND data_criacao BETWEEN '" . $_GET['data'] ." 00:00:00' AND '" . $_GET['data'] ." 23:59:59'";
    }
    if (!empty($_GET['ret_original'])) {
        $sql = $sql . " AND link_curto ='" . $_GET['ret_original']."'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"] . " || Original: " . $row["link_original"] . "  || Encurtado: " . $row["link_curto"] . "  || Data Criação: " . $row["data_criacao"] . "<br>";
        }
    } else {
        echo "ENDEREÇO NÃO ENCONTRADO.";
    }
    $conn->close();


    ?>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }


    function encurtarLink() {
        let link = $('#original').val();
        link = uuidv4();
        if ($('#original').val() != '') {
            $('#curto').val('http://localhost/dbe/index.php?url=' + link);
            $('#btn').prop("disabled", false)
        } else {
            $('#btn').prop("disabled", true)
            $('#curto').val('');

        }
    }

    function filtro() {
        let id = $('#id').val();
        let data = $('#data').val();
        let original = $('#ret_original').val();

        window.location.href = 'http://localhost/dbe/index.php?id=' + id + '&data=' + data + '&ret_original=' + encodeURIComponent(original);

    }
</script>

</html>
