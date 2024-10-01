<?php class Caixa extends Controller {

  private  $auth,

  $db;

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

    if ($acesso->acesso_valida("/".__CLASS__."/admin_listar/") == true) {

      $filiais = $acesso->acesso_filial(__CLASS__);

      $status = new StatusModel();

      $filial = new FilialModel();

      $acesso = new SessionHelper();

      $caixa = new CaixaModel();

      $menu = new MenuHelper("Bit a Bits - Fluxo de Caixa", $Class, $AcaoForm, $MetodoDeEnvio);

      echo $menu->Menu();

      $form = new FormularioHelper();

      $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone, $caixa->listar_caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela ", "25", " Caixa.id_status<>'99'  AND ({$filiais})", NULL, ' Caixa.id DESC', "Caixa.id,Caixa.tipo AS Tipo,Contas.valor_pago AS Valor,Caixa.data_lancamento AS Lançamento"), "tabela1", null);

      $form->card("Fluxo de Caixa", $inputs, "col-md-12", $comando, "POST", "timeline");

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

    $id_tabela = $this->getParams("id");

    $tabela = $this->getParams("tabela");

    $conta = new ContasModel();

    $listar_conta = $conta->listar_contas(NULL, NULL, "id_status<>99 AND id_tabela='$id_tabela'AND tabela='$tabela'", NULL, ' Filial.id DESC', NULL);

    foreach ($listar_conta as $listar_conta1):

      $id_tabela = $listar_conta1[0]["id_tabela"];

    endforeach;

        $filial = new FilialModel();

        $listar_filial = $filial->listar_Filial(NULL, NULL, "id_status<>99", NULL, ' Filial.id DESC', NULL);

        $dados['listar_filial'] = $listar_filial;

        $tipo_documento = new TipoDocumentoModel();

        $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL, NULL, "id_status<>99", NULL, ' TipoDocumento.id DESC', NULL);

        $dados['listar_tipo_documento'] = $listar_tipo_documento;

        $tipo_pagamento = new TipoPagamentoModel();

        $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL, NULL, "id_status<>99", NULL, ' TipoPagamento.id DESC', NULL);

        $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento;

        $cliente = new ClienteModel();

        $listar_cliente = $cliente->listar_Cliente(NULL, NULL, "id_status<>99", NULL, ' Cliente.id DESC', NULL);

        $dados['listar_cliente'] = $listar_cliente;

        $conta_bancaria = new ContaBancariaModel();

        $listar_conta_bancaria = $conta_bancaria->listar_ContaBancaria(NULL, NULL, "id_status<>99", NULL, ' ContaBancaria.id DESC', NULL);

        $dados['listar_conta_bancaria'] = $listar_conta_bancaria;

        $colaborador = new ColaboradorModel();

        $listar_colaborador = $colaborador->listar_Colaborador(NULL, NULL, "id_status<>99", NULL, ' Colaborador.id DESC', NULL);

        $dados['listar_colaborador'] = $listar_colaborador;

        $status = new StatusModel();

        $listar_status = $status->listar_Status(NULL, NULL, "id_status<>99", NULL, ' Status.id DESC', NULL);

        $dados['listar_status'] = $listar_status; $venda = new VendaModel();

        $id = $this->getParams('id');

        $dados['id'] = $id;

        if (!empty($id)) {

          $itens = new ItensModel();

          $listar_itens = $itens->listar_Itens(NULL, NULL, "id_status<>99 AND id_tabela='{$id}' AND tabela='Venda'", NULL, ' Itens.id DESC', NULL);

          $dados['listar_itens'] = $listar_itens;

          $listar_venda = $venda->listar_venda(NULL, NULL, "id=$id AND id_status='4'", NULL, 'Venda.id DESC');

          $dados['listar_venda'] = $listar_venda;

        }

      $this->view('form_visualizar_venda', $dados);

  }

  public function incluir() {

  $this->acesso_restrito();

    $acesso = new AcessoHelper();

    $logs = new LogsModel();

    $comando = '/Caixa/incluir/';

    if ($acesso->acesso_valida($comando) == true) {

      $caixa = new CaixaModel();

      $id = $caixa->cadastrar_caixa(

        array(

            'id_filial' => $_POST['id_filial'],

            'id_tabela' => $_POST['id_tabela'],

            'descricao' => $_POST['descricao'],

            'tipo' => $_POST['tipo'],

            'id_status' => $_POST['id_status'],

            'data_lancamento' => date("Y-m-d H:i:s"),

          )

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

    $id_tabela = $_POST['id_tabela'];//$this->getParams('id_tabela');

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



  $logs->cadastrar_logs($comando, $id); //Gera Logs

      $redirect = new RedirectHelper();

    $redirect->goToUrl('/CaixaP/admin_listar/');

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

      $caixa = new CaixaModel();

      $caixa->alterar_caixa(

        array(

          'id_filial' => $_POST['id_filial'],

          'id_tabela' => $_POST['id_tabela'],

          'descricao' => $_POST['descricao'],

          'tipo' => $_POST['tipo'],

          'id_status' => $_POST['id_status'],

          'data_lancamento' => date("Y-m-d H:i:s"),

        ), 'id='.$id

      );

      $logs->cadastrar_logs($comando, $id); //Gera Logs

      $redirect = new RedirectHelper();

      $redirect->goToUrl('/Caixa/admin_listar/');

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

    }else{

      $this->view('error_permisao');

    }

    }

} ?>