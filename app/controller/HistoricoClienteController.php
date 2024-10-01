<?php

class HistoricoCliente extends Controller {

    private $auth, $db;

    public function acesso_restrito() {
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index', '')
                ->checkLogin('redirect');
        $this->db = new AdminModel();
    }

    public function admin_listar(){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
            $filiais=$acesso->acesso_filial(__CLASS__);
            $status= new StatusModel();
            $filial = new FilialModel();           
            $acesso = new SessionHelper();           
            $hitsorico_pessoa = new HistoricoClienteModel();
            
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
           echo $menu->Menu();            
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     
            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$hitsorico_pessoa->listar_HistoricoCliente(NULL, NULL, "id_status<>99 AND {$filiais}", NULL, ' HistoricoCliente.id DESC', NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/".__CLASS__."/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }    
    }
    public function incluir() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/HistoricoCliente/incluir/';

        if ($acesso->acesso_valida($comando) == true) {

            $historicocliente = new HistoricoClienteModel();
            $id = $historicocliente->cadastrar_historicocliente(
                    array(
                        'id_filial' => $_POST['id_filial'],
                        'observacao' => $_POST['observacao'],
                        'id_tabela' => $_POST['id_tabela'],
                        'tabela' => $_POST['tabela'],
                        'id_status' => $_POST['id_status'],
                        'data_lancamento' => date("Y-m-d H:i:s"),
                    )
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/HistoricoCliente/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }

    public function alterar() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/HistoricoCliente/alterar/';
        if ($acesso->acesso_valida($comando) == true) {
            $id = $_POST['id'];

            $historicocliente = new HistoricoClienteModel();
            $historicocliente->alterar_historicocliente(
                    array(
                        'id_filial' => $_POST['id_filial'],
                        'observacao' => $_POST['observacao'],
                        'id_tabela' => $_POST['id_tabela'],
                        'tabela' => $_POST['tabela'],
                        'id_status' => $_POST['id_status'],
                        'data_lancamento' => date("Y-m-d H:i:s"),
                    ), 'id=' . $id
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/HistoricoCliente/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }

    public function excluir() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/HistoricoCliente/excluir/';
        if ($acesso->acesso_valida($comando) == true) {
            $id = $this->getParams('id');
            $historicocliente = new HistoricoClienteModel();
            $historicocliente->excluir_historicocliente(array('id_status' => '99'), 'id=' . $id);
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/HistoricoCliente/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }

}

?> 