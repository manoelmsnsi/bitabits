<?php
class Acesso extends Controller {
  private $auth,$db;

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
        $acessos = new AcessoModel();
        if (empty($_POST["pesquisa"])) {
        $pesquisa = null;
        } else {
        $pesquisa = $_POST["pesquisa"];
        }
        $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);
        echo $menu->Menu();
        $form = new FormularioHelper();
        $inputs.= $form->Listar( "col-md-6", "Acesso", "/".__CLASS__."/form/", $icone, $acessos->listar_acesso(
            "INNER JOIN Status ON Status.id=Acesso.id_status
            INNER JOIN Usuario ON Usuario.id = Acesso.id_usuario
            INNER JOIN Programa ON Programa.id=Acesso.id_programa",
            NULL,
            "Acesso.id_status<>'99' AND Acesso.id_programa<>'0' AND ({$filiais})",
            NULL,
            'Acesso.id DESC',
            "Acesso.id,
            Acesso.id_filial AS Filial,
            Usuario.usuario AS Usuário,
            Programa.descricao AS Programa,
            Status.cor AS cor_Status,
            Status.Descricao AS Status
            ", NULL, $pesquisa

        ), "tabela2",
        array(
            array("acao" => "/".__CLASS__."/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
            array("acao" => "/".__CLASS__."/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
            array("acao" => "/Logs/form/", "classe" => "btn-sm btn-danger", "icone" => "close")
        )
      );
          $inputs.= $form->Listar("col-md-6", "Grupo Acesso", "/".__CLASS__."/form/", $icone, $acessos->listar_acesso(
          "INNER JOIN Status ON Status.id=Acesso.id_status
                            INNER JOIN Usuario ON Usuario.id = Acesso.id_usuario
                            INNER JOIN Grupo ON Grupo.id=Acesso.id_grupo",
          NULL,
          "Acesso.id_status<>99 AND ({$filiais})",
          NULL,
          'Acesso.id DESC',
          "Grupo.id,
       
            Usuario.usuario AS Usuário,
            Grupo.descricao AS Grupo,
            Status.cor AS cor_Status,
            Status.Descricao AS Status
            ", NULL, $pesquisa
        ), "tabela1",
        array(
          array("acao" => "/".__CLASS__."/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
          array("acao" => "/GrupoAcesso/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
          array("acao" => "/Logs/form/", "classe" => "btn-sm btn-danger", "icone" => "close")
        )
      );
      $form->card("Gestao de Acesso",$inputs,"col-md-12",$comando,"POST","list");
    } else {
      $this->view('error_permisao');
    }
  }

  public function form() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/".__CLASS__."/incluir/";
    if ($acesso->acesso_valida("/".__CLASS__."/admin_listar/") == true) {
      $acesso = new SessionHelper();
      $status = new StatusModel();
      $filial = new FilialModel();
      $grupo = new GrupoModel();
      $programa = new ProgramaModel();
      $usuario = new UsuarioModel();
      $acesso = new SessionHelper();
      $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
      echo $menu->Menu();
      $id = $this->getParams("id");
      $id_grupo = $this->getParams("id_grupo");
      if (!empty($id)) {
          $comando = "/".__CLASS__."/alterar/";
      }
      $form = new FormularioHelper();
      $inputs.= $form->select("Filial", "id_filial", "col-md-2", $filial->listar_Filial(NULL, NULL, "Filial.id_status<>'99'", NULL, ' Filial.id DESC', NULL), "nome_fantasia");
      $inputs.= $form->select("Usuario", "id_usuario", "col-md-3", $usuario->listar_usuario(NULL, NULL, "Usuario.id_status<>'99' ", NULL, ' Usuario.id DESC', NULL), "usuario");
      $inputs.= $form->select("Grupo", "id_grupo", "col-md-2", $grupo->listar_Grupo(NULL, NULL, "Grupo.id_status<>'99' AND tabela='Acesso'", NULL, ' Grupo.id DESC', NULL), "descricao",$id_grupo);
      $inputs.= $form->select("Programa", "id_programa", "col-md-3", $programa->listar_programa(NULL, NULL, "Programa.id_status<>'99'", NULL, ' Programa.id DESC', NULL), "descricao");
      $inputs.= $form->select("Status", "id_status", "col-md-2", $status->listar_Status(NULL, NULL, "id_status<>99 AND tabela='Geral'", NULL, ' Status.id ASC', NULL), "descricao");
      $inputs.= $form->Button("btn btn-md btn-rose ", "Salvar");
      $form->card("Cadastro de Acesso",$inputs,"col-md-12",$comando,"POST","store");
    } else {
      $this->view('error_permisao');
    }
  }


  public function incluir() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Acesso/incluir/';
    if ($acesso->acesso_valida($comando) == true) {
      $acesso = new AcessoModel();
      $id = $acesso->cadastrar_acesso(
        array(
          'id_filial' => $_POST['id_filial'],
          'id_usuario' => $_POST['id_usuario'],
          'id_programa' => $_POST['id_programa'],
          'id_grupo' => $_POST['id_grupo'],
          'id_status' => $_POST['id_status'],
          'data_lancamento' => date("Y-m-d H:i:s"),
        )
      );

    $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
      $redirect->goToUrl('/Acesso/admin_listar/');
    } else {
      $this->view('error_permisao');
    }
  }

  public function alterar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Acesso/alterar/';
    if ($acesso->acesso_valida($comando) == true) {
      $id = $_POST['id'];
      $acesso = new AcessoModel();
      $acesso->alterar_acesso(
        array(
          'id_filial' => $_POST['id_filial'],
          'id_usuario' => $_POST['id_usuario'],
          'id_programa' => $_POST['id_programa'],
          'id_status' => $_POST['id_status'],
          'data_lancamento' => date("Y-m-d H:i:s"),
        ), 'id='.$id
      );
      $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
      $redirect->goToUrl('/Acesso/admin_listar/');
    } else {
      $this->view('error_permisao');
    }
  }
  public function excluir() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Acesso/excluir/';
    if ($acesso->acesso_valida($comando) == true) {
      $id = $_POST['id'];
   
      $acesso = new AcessoModel();
      $acesso->excluir_acesso(array('id_status' => '99'), 'id='.$id);
      $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
     $redirect->goToUrl('/Acesso/admin_listar/');
    } else {
      $this->view('error_permisao');
    }
  }
}

?>