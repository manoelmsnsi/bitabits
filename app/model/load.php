<?php

//load.php
//$host="bb_uniclinica.mysql.dbaas.com.br";
//        $host_nome="bb_uniclinica"; 
//        $host_pass="Uni#DBUC25207";
        
$host="manoelfeio.mysql.dbaas.com.br";
        $host_nome="manoelfeio";
        $host_pass="FeioManoel#252";

$connect = new PDO("mysql:host=$host;dbname=$host_nome;charset=utf8","$host_nome","$host_pass");

$data = array();
$user_id=$_GET["user_id"];

//$query = "SELECT * FROM events ORDER BY id";
$query = "SELECT Agenda.id,Cliente.id as id_cliente, Cliente.nome as nome_cliente,Agenda.data_inicio,Agenda.data_fim
    FROM Agenda  INNER JOIN Status ON Status.id = Agenda.id_status 
                                INNER JOIN Colaborador ON Colaborador.id = Agenda.id_colaborador 
                                INNER JOIN Cliente ON Cliente.id = Agenda.id_cliente 
                                WHERE Agenda.id_status='1' AND Agenda.id_colaborador='$user_id'";

$statement = $connect->prepare($query);
//echo $query;
$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'id_cliente'   => $row["id_cliente"],
  'title'   => $row["nome_cliente"],
  'start'   => $row["data_inicio"],
  'end'   => $row["data_fim"]
 );
}

echo json_encode($data);

?>
