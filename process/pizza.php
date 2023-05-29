
<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

// RESGATE DOS DADOS, MONTAGEM DOS PEDIDOS
if ($method === "GET") {

$bordasQuery = $conn->query("SELECT * FROM bordas;");

$bordas = $bordasQuery->fetchAll();

$massasQuery = $conn->query("SELECT * FROM massas;");

$massas = $massasQuery->fetchAll();

$saboresQuery = $conn->query("SELECT * FROM sabores;");

$sabores = $saboresQuery->fetchAll();


// CRIAÇÃO DO PEDIDO 
} else if ($method === "POST") {

 $data = $_POST;

 $borda = $data["borda"];
 $massa = $data["massa"];
 $sabores = $data["sabores"];

 
 // validação do pedido
 if (count($sabores) > 3) {

   $_SESSION["msg"] = "Selecione no máximo três sabores!";
   $_SESSION["status"] = "warning";

 } else {

 // salvando borda e massa na pizza
 $stmt = $conn->prepare("INSERT INTO pizzas (borda_id, massa_id) VALUES (:borda, :massa)");

 // filtrando input
 $stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
 $stmt->bindParam(":massa", $massa, PDO::PARAM_INT);

 $stmt->execute();
 
 // resgantando último id da última pizza
 $pizzaId = $conn->lastInsertId();

 $stmt = $conn->prepare("INSERT INTO pizza_sabor (pizza_id, sabor_id) VALUES (:pizza, :sabor)");

 // repetição até terminar de slvar todos os sabores
 foreach($sabores as $sabor) {

 // filtrando os inputes 
 $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
 $stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);
 
 $stmt->execute();

 }
 // crindo pedido de pizza
 $stmt = $conn->prepare("INSERT INTO pedidos (pizza_id, status_id) VALUES (:pizza, :status)");
 
 // status sempre sem inicia com 1, é quem e a produção

 $statusId = 1;

 // filtram inputs
 $stmt->bindParam(":pizza", $pizzaId);
 $stmt->bindParam(":status", $statusId);

 $stmt->execute();

 // exibir mesagem de sucesso
 
 $_SESSION["msg"] = "Pedido realizado com sucesso!";
 $_SESSION["status"] = "success";


 }

 // retorna para página inicial
 header("Location:..");
}

?>