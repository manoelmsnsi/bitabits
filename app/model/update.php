<?php

//update.php
$connect = new PDO('mysql:host=manoelfeio.mysql.dbaas.com.br;dbname=manoelfeio', 'manoelfeio', 'FeioManoel#252');

if(isset($_POST["id"]))
{
 $query = "
 UPDATE Agenda 
 SET  data_inicio=:start_event, data_fim=:end_event 
 WHERE id=:id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id'   => $_POST['id']
  )
 );
}

?>