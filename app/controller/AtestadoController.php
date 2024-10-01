<?php

class Atestado extends Controller {

  private  $auth,
  $db;

  public function acesso_restrito() {
    $this->auth = new AutenticaHelper();
    $this->auth->setLoginControllerAction('Index', '')
    ->checkLogin('redirect');
    $this->db = new AdminModel();

  }

  public function form1() {

    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();

    $comando = "/".__CLASS__."/incluir/";
    //if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
    $status = new StatusModel();
    $filial = new FilialModel();

    $atestado = new AtestadoModel();
    $acesso = new SessionHelper();
    $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();

    $id_cliente = $this->getParams("id_cliente");
    $dados["id_cliente"] = $id_cliente;

    if (!empty($id)) {
      //  $atestado_dados=$atestado->listar_Atestado($join, "1", "id='$id'", $offset, $orderby);
      $atestado_dados = $atestado_dados[0];
      $comando = "/".__CLASS__."/alterar/";
    }
    //
    $this->view("form_atestado", $dados);
    // }else{
    //        $this->view('error_permisao');
    //   }

  }
public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();        
        $comando="/".__CLASS__."/incluir/";  
    //    if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $status= new StatusModel();
            $filial = new FilialModel();          
            $pessoa = new PessoaModel();
            $atestado = new AtestadoModel();           
            $acesso = new SessionHelper();
            $id=$this->getParams("id");     
            $nome_form="Cadastrar Atestado";  
            if(!empty($id)){
                $atestado_dados =$atestado->listar_Atestado($join, "1", "id='$id'", $offset, $orderby);
                $atestado_dados = $atestado_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Atestado";
            }             
            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio);      
            echo $menu->Menu();
            $form = new FormularioHelper();
                $inputs.= $form->Input("hidden", "id", $Classe, $id);
                $inputs.= $form->Input("hidden", "id_cliente", $Classe,  $this->getParams("id_cliente"));          
                $inputs.= $form->select("Colaborador","id_colaborador", "col-md-5",$pessoa->listar_Pessoa(NULL,NULL,"Pessoa.id_status<>99 AND Pessoa.tipo='Colaborador'",NULL,' Pessoa.id DESC',"Pessoa.nome,Pessoa.id"),"nome",$atestado_dados["id_colaborador"]);
                $inputs.= $form->Input("date", "data_atestado", "col-md-2", $atestado_dados["data_atestado"], $Required, "Data", $disable);
                $inputs.= $form->select("Status","id_status", "col-md-5", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
                $inputs.= $form->Text("text", "texto", "col-md-12", $atestado_dados["texto"], $Required, "Atestado", $disable);
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                
                $form->card($nome_form, $inputs, "col-md-12", $comando, "POST", "list");
      //  }else{
     //      $this->view('error_permisao');
    //    }   
    }

  public function incluir() {

    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Modelo/incluir/';


    // if($acesso->acesso_valida($comando)==true){
    $atestado = new AtestadoModel();
    $id_cliente = $_POST['id_cliente'];
   // echo $id_cliente;
    $id = $atestado->cadastrar_Atestado(
      array(

        'id_cliente' => $_POST['id_cliente'],
        'id_colaborador' => $_POST['id_colaborador'],
        'texto' => $_POST['texto'],
        'data_atestado' => $_POST['data_atestado'],
        'data_lancamento' => date("Y-m-d H:i:s"),
        'id_status' => "1",
      )

    );
    echo "ID:".$id;
    $logs->cadastrar_logs($comando, $id); //Gera Logs
    $redirect = new RedirectHelper();
    $redirect->goToUrl("/Pessoa/visualizar/tabela/Cliente/id/$id_cliente");
    //   }else{
    //      $this->view('error_permisao');
    ////
    //   }

  }

  public function alterar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Modelo/alterar/';

    if ($acesso->acesso_valida($comando) == true) {
      $id = $_POST['id'];

      $modelo = new ModeloModel();
      $modelo->alterar_modelo(
        array(
          'id_filial' => $_POST['id_filial'],
          'descricao' => $_POST['descricao'],
          'id_status' => $_POST['id_status'],
          'data_lancamento' => date("Y-m-d H:i:s"),
        ), 'id='.$id
      );
      $logs->cadastrar_logs($comando, $id); //Gera Logs
      $redirect = new RedirectHelper();
      $redirect->goToUrl('/Modelo/admin_listar/');
    } else {

      $this->view('error_permisao');
    }
  }

  public function excluir() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Modelo/excluir/';

    if ($acesso->acesso_valida($comando) == true) {
      $id = $this->getParams('id');

      $modelo = new ModeloModel();
      $modelo->excluir_modelo(array('id_status' => '99'), 'id='.$id);
      $logs->cadastrar_logs($comando, $id); //Gera Logs

      $redirect = new RedirectHelper();
      $redirect->goToUrl('/Modelo/admin_listar/');
    } else {
      $this->view('error_permisao');
    }
  }
} ?>