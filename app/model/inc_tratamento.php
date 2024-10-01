<?php
$mysqli = new mysqli("bb_uniclinica.mysql.dbaas.com.br", "bb_uniclinica", "Uni#DBUC25207","bb_uniclinica");

$mysqli->set_charset("utf8");
$acao = $_POST["acao"];

if($acao == "incluir"){
    $id_agenda = $_POST['id_agenda'];
    $id_cliente = $_POST['id_cliente'];
    $id_colaborador = $_POST['id_colaborador'];
    $id_filial = $_POST['id_filial'];
    $descricao = $_POST['descricao'];
    $valor_total = $_POST['valor_total'];
    $tabela = "Venda";
   
    $query = "SELECT id,id_cliente FROM Venda WHERE id_cliente='$id_cliente' AND id_status='1' ";
    $result=$mysqli->query($query);
    $c=  $result->num_rows;
    if($c<=0){
        $query = "INSERT INTO Venda (id_cliente,id_status,tipo,id_colaborador,id_filial)VALUE ('$id_cliente','1','Receita','$id_colaborador','$id_filial')";
        $mysqli->query($query);
    }
    $query = "SELECT id,id_cliente FROM Venda WHERE id_cliente='$id_cliente' AND id_status='1' LIMIT 1";
    $result=$mysqli->query($query);
    while ($row = $result->fetch_row()) {
      $id_tabela=$row[0];
    }
    $data=date('Y-m-d H:i:s');
    $query = "INSERT INTO Itens (descricao,valor_venda,tabela,id_tabela,id_status,data_lancamento,quantidade,id_produto) VALUES ('$descricao', '$valor_total','$tabela','$id_tabela','1','$data','1','0')";
    $mysqli->query($query);    
}
if($acao == "alterar"){}

if($acao == "status_realizado"){
    $tabela = "Venda";
    $id = $_POST['id'];
    $query = "UPDATE Itens SET id_status='3' WHERE  id='$id'";
    $mysqli->query($query);
}
if($acao == "status_nao_realizado"){
    $tabela = "Venda";
    $id = $_POST['id'];
    $query = "UPDATE Itens SET id_status='1' WHERE  id='$id'";
    $mysqli->query($query);
}

if($acao == "excluir"){
    $tabela = "Venda";
    $id = $_POST['id'];
    $query = "UPDATE Itens SET id_status='99' WHERE  id='$id'";
    $mysqli->query($query);
}
    



