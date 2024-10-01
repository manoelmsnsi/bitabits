<?php 
    $acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
    $parametro = (isset($_GET['parametro'])) ? $_GET['parametro'] : '';
    $where = (isset($_GET['where'])) ? $_GET['where'] : '';
    $campo = (isset($_GET['campo'])) ? $_GET['campo'] : '';
    $tabela = (isset($_GET['tabela'])) ? $_GET['tabela'] : '';

// Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'autocomplete'):
	
	$db = new Model();
        $db->_tabela =$tabela;
        $stm = $db->read(NULL, $where, $limit, $offset, $orderby, $campo);
        $dados = $stm->fetchAll(PDO::FETCH_OBJ);

	$json = json_encode($dados);
	echo $json;
endif;

        

    

