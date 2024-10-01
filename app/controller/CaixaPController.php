<?php

  class CaixaP extends Controller {
    
  private  $auth, $db;
  public function acesso_restrito() {
    $this->auth = new AutenticaHelper();
    $this->auth->setLoginControllerAction('Index', '')
         ->checkLogin('redirect');
    $this->db = new AdminModel();
  }

  public function admin_listar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/".__CLASS__."/incluir/";
    if ($acesso->acesso_valida("/Caixa/admin_listar/") == true) {
      $filiais = $acesso->acesso_filial("CaixaPai");
      $status = new StatusModel();
      $filial = new FilialModel(); 
      $acesso = new SessionHelper();
      $caixa = new CaixaPModel();
      if (empty($_POST["pesquisa"])) { $pesquisa = null;} else {$pesquisa = $_POST["pesquisa"]; }
      
      $menu = new MenuHelper("Bit a Bits - Abertura e Fechamento de Caixa", $Class, $AcaoForm, $MetodoDeEnvio);
      echo $menu->Menu();

      $form = new FormularioHelper();
      $listar_aberto = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,
        $caixa->listar_caixa("INNER JOIN Status ON Status.id=CaixaPai.id_status INNER JOIN ContaBancaria ON ContaBancaria.id= CaixaPai.id_caixa ",
          NULL,
          " CaixaPai.id_status<>'99' AND CaixaPai.id_status='10'  AND ({$filiais})",
          NULL, ' CaixaPai.id ASC',
          "CaixaPai.id,CaixaPai.abertura AS Abertura,CaixaPai.valor_abertura AS 'Valor de Abertura',CaixaPai.fechamento AS Fechamento,CaixaPai.valor_fechamento AS 'Valor de Fechamento',Status.cor AS cor_Status,ContaBancaria.descricao AS Caixa,Status.descricao AS Status",
          NULL,
          $pesquisa),
        "tabela1",
        array(array("acao" => "/".__CLASS__."/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
          array("acao" => "/".__CLASS__."/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
          array("acao" => "/Logs/form/", "classe" => "btn-sm btn-danger", "icone" => "close")));

      $listar_fechado = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,
        $caixa->listar_caixa("INNER JOIN Status ON Status.id=CaixaPai.id_status INNER JOIN ContaBancaria ON ContaBancaria.id= CaixaPai.id_caixa ",
          NULL,
          " CaixaPai.id_status<>'99' AND CaixaPai.id_status='11' AND ({$filiais})",
          NULL,
          ' CaixaPai.id ASC',
          "CaixaPai.id,CaixaPai.abertura AS Abertura,CaixaPai.valor_abertura AS 'Valor de Abertura',CaixaPai.fechamento AS Fechamento,CaixaPai.valor_fechamento AS 'Valor de Fechamento',Status.cor AS cor_Status,ContaBancaria.descricao AS Caixa,Status.descricao AS Status",
          NULL,
          $pesquisa),
        "tabela2",
        array(array("acao" => "/".__CLASS__."/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
          array("acao" => "/".__CLASS__."/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
          array("acao" => "/Logs/form/", "classe" => "btn-sm btn-danger", "icone" => "close")));

        $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12",
        array(array("id" => "Aberto", "icone" => "attach_money", "descricao" => "Aberto"), 
        array("id" => "Fechado", "icone" => "money_off", "descricao" => "Fechado")),
        array(array("id" => "Aberto", "dados" => "$listar_aberto", "classe" => " active"),
        array("id" => "Fechado", "dados" => "$listar_fechado")));
        $form->card("Abrir/Fechar Caixa", $inputs, "col-md-12", $comando, "POST", "monetization_on");

    } else {
      $this->view('error_permisao');
    }
  }




  public function visualizar() {
    $this->acesso_restrito();
    $acesso = new SessionHelper();
    $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    $comando = '/Caixa/visualizar/';
    $id_caixa = $this->getParams("id");
    $dados["id_caixa"] = $id_caixa;
    $caixa = new CaixaPModel();
    $dados['listar_caixa'] = $caixa->listar_Caixa("INNER JOIN Status ON Status.id=CaixaPai.id_status INNER JOIN ContaBancaria ON ContaBancaria.id= CaixaPai.id_caixa", NULL, "CaixaPai.id_status<>99 AND CaixaPai.id={$id_caixa}", NULL, ' CaixaPai.id DESC', "Status.descricao AS descricao_status,CaixaPai.abertura,CaixaPai.fechamento,CaixaPai.valor_abertura,ContaBancaria.descricao AS descricao_caixa,CaixaPai.valor_fechamento,ContaBancaria.id_filial");
    $caixa_itens = new CaixaModel();
    $dados['listar_caixa_itens'] = $caixa_itens->listar_Caixa(" INNER JOIN Contas ON Contas.id=Caixa.id_tabela INNER JOIN Venda ON Venda.id = Contas.id_tabela INNER JOIN Pessoa ON (Pessoa.id= Venda.id_cliente)OR(Pessoa.id= Venda.id_fornecedor) ", NULL, "Caixa.id_status<>99 AND Caixa.id_caixa={$id_caixa}", NULL, ' Caixa.id DESC', "Venda.id AS id_venda,Caixa.id,Caixa.id_tabela,Pessoa.nome,Caixa.tipo,Caixa.data_lancamento,Contas.valor_pago");
    $dados['listar_fechamento'] = $caixa_itens->listar_Caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela
        INNER JOIN Venda ON Venda.id= Contas.id_tabela
        INNER JOIN TipoDocumento  ON TipoDocumento.id = Venda.id_tipo_documento", NULL, "Caixa.id_status<>99 AND Caixa.id_caixa={$id_caixa} ", NULL, ' Caixa.id DESC', "Caixa.id,Caixa.id_tabela,Caixa.tipo,Caixa.data_lancamento,SUM(Contas.valor_pago) AS valor_grupo,TipoDocumento.descricao AS descricao_documento, COUNT(Venda.id_tipo_documento) AS doc ", "Venda.id_tipo_documento,Caixa.tipo");

    $id = $this->getParams('id');
    $dados['id'] = $id;
    $this->view('form_visualizar_caixa', $dados);

}




  public function form() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/CaixaP/abrir_caixa/";
    if ($acesso->acesso_valida("/Caixa/admin_listar/") == true) {
      $status = new StatusModel();
      $filial = new FilialModel();
      $conta_bancaria = new ContaBancariaModel();
      $acesso = new SessionHelper();
      $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
      echo $menu->Menu();
      $id = $this->getParams("id");
      if (!empty($id)) {
        $dados_caixa = $conta_bancaria->listar_ContaBancaria(null,null,"id='$id'",null);
        $comando = "/CaixaP/alterar/";
      }
        $form = new FormularioHelper();
        $inputs.= $form->Input('hidden', 'id', $CSS, $id);
        $inputs.= $form->select("Filial", "id_filial", "col-md-3 ", $filial->listar_Filial(NULL, NULL, "Filial.id_status<>'99'", NULL, ' Filial.id DESC', NULL), "nome_fantasia");
        $inputs.= $form->select("Caixa", "id_caixa", "col-md-3 ", $conta_bancaria->listar_ContaBancaria(NULL, NULL, "id_status<>'99'", NULL, ' id DESC', NULL), "descricao");
        $inputs.= $form->Input("text", "valor_abertura", "col-md-4", $dados_caixa["valor_abertura"], null, "Valor Inicial", $disable);
        $inputs.= $form->Button("btn btn-md btn-rose ", "Salvar");
        $form->card("Abrir Caixa", $inputs, "col-md-12", $comando, "POST", "monetization_on");
    } else {
      $this->view('error_permisao');
    } 
  }



  public function abrir_caixa() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Caixa/incluir/';
    if ($acesso->acesso_valida($comando) == true) {
      $caixa = new CaixaPModel();
      $id = $caixa->cadastrar_caixa(
        array(
          'id_filial' => $_POST['id_filial'],
          'id_status' => "10",
          'id_caixa' => $_POST['id_caixa'],
          'abertura' => date("Y-m-d H:i:s"),
          'valor_abertura' => $_POST['valor_abertura'],
          'data_lancamento' => date("Y-m-d H:i:s"),
        )
      );
      $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
      $redirect->goToUrl("/CaixaP/visualizar/id/{$id}");
    } else {
      $this->view('error_permisao');
    }
  }



  public function fechar_caixa() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Caixa/incluir/';
    if ($acesso->acesso_valida($comando) == true) {
      $id = $this->getParams("id");
      $caixa = new CaixaPModel();
      $caixa->alterar_Caixa(
        array(
          'id_status' => "11",
          'fechamento' => date("Y-m-d H:i:s"),
          'valor_fechamento' => $_POST['valor_fechamento'],
        ), "id={$id}"
      );
      $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
      $redirect->goToUrl('/CaixaP/admin_listar/');
    } else {
      $this->view('error_permisao');
    }
  }



  public function estorna() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Caixa/estorna/';
    if ($acesso->acesso_valida($comando) == true) {
      $id = $_POST['id'];
      $id_tabela = $_POST['id_tabela'];
      $observacao = $_POST['observacao'];
      $caixa = new CaixaModel();
      $caixa->alterar_caixa(
        array(
          'id_status' => "99",
        ), 'id='.$id
      );
      $contas = new ContasModel();
      $contas->alterar_contas(
        array(
          'id_status' => "1",
        ), 'id='.$id_tabela
      );
      $logs->cadastrar_logs($comando, $id, $observacao); //Gera Logs
      echo "<script>script:history.go(-2)</script>";
    } else {
      $this->view('error_permisao');
    }
  }



  public function alterar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Caixa/alterar/';
    if ($acesso->acesso_valida($comando) == true) {
      $id = $_POST['id'];
      $caixa = new CaixaPModel();
      $caixa->alterar_Caixa(
        array(
            'id_filial' => $_POST['id_filial'],
            'id_status' => "10",
            'id_caixa' => $_POST['id_caixa'],
            'valor_abertura' => $_POST['valor_abertura'],
          ), "id=$id"
        );
        $logs->cadastrar_logs($comando, $id); //Gera Logs
        $redirect = new RedirectHelper();
        $redirect->goToUrl('/CaixaP/admin_listar/');
      } else {
        $this->view('error_permisao');
      }
    }



    public function excluir() {
      $this->acesso_restrito();
      $acesso = new AcessoHelper();
      $logs = new LogsModel();
      $comando = '/Caixa/excluir/';
      if ($acesso->acesso_valida($comando) == true) {
        $id = $this->getParams('id');
        $caixa = new CaixaModel();
        $caixa->excluir_caixa(array('id_status' => '99'), 'id='.$id);
        $logs->cadastrar_logs($comando, $id); //Gera Logs
        $redirect = new RedirectHelper();
        $redirect->goToUrl('/Caixa/admin_listar/');
      } else {
        $this->view('error_permisao');
      }
    }
  } ?>