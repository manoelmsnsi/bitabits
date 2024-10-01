<?php
class ChamadoGrupoInterno extends Controller {
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
        if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
            $filiais=$acesso->acesso_filial(__CLASS__);
            $status= new StatusModel();
            $filial = new FilialModel();           
            $acesso = new SessionHelper();           
            $chamado_grupo = new ChamadoGrupoInternoModel();
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
               $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$chamado_grupo->listar_ChamadoGrupo(NULL, NULL, "id_status<>99  AND ({$filiais})", NULL, ' ChamadoGrupo.id DESC', NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));              
               $form->card(__CLASS__, $inputs, "col-md-12", $comando, "POST", "group");
        }else{
            $this->view('error_permisao');
        }    
    }
 
    public function form() {
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);     
        echo $menu->Menu();    
        $colaborador = new ModelLiberacao();
        $colaborador->_tabela="Pessoa";
       // $listar_colaborador = $colaborador->read($join, "id_status<>'99' AND Pessoa.tipo='Colaborador'", $limit, $offset, $orderby, $camposfrom, $group, $pesquisa);
        $colaborador = new PessoaModel();
        $listar_colaborador = $colaborador->listar_Pessoa(NULL, NULL, "id_status<>99 AND Pessoa.tipo='Colaborador'", NULL, ' Pessoa.id DESC', NULL);      
        $status = new StatusModel();
        $listar_status = $status->listar_Status(NULL, NULL, "id_status<>99", NULL, ' Status.id DESC', NULL);    
        $id = $this->getParams('id');
        $id_chamado = $this->getParams('id_chamado');       
        if (!empty($id)) {
            $chamadogrupo = new ChamadoGrupoInternoModel();
            $listar_chamadogrupo = $chamadogrupo->listar_chamadogrupo(NULL, NULL, "id=$id AND id_status<>'99'", NULL, 'ChamadoGrupo.id DESC');
       }
        $form = new FormularioHelper();
        $inputs.= $form->Input("hidden", "id_chamado", null, $id, $required,null);
        $inputs .= $form->select("Colaborador","id_colaborador", "col-md-4",$colaborador->listar_Pessoa(NULL, NULL, "id_status<>99 AND Pessoa.tipo='Colaborador'", NULL, ' Pessoa.id DESC', NULL),"nome",$agenda_dados["id_colaborador"]); 
        $inputs .= $form->Button("btn btn-md btn-rose ","Salvar");
        echo $form->card(__CLASS__, $inputs, "col-md-12", '/ChamadoGrupoInterno/incluir/', "POST", "group");
    }
    public function incluir() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/ChamadoGrupo/incluir/';
        $id_chamado=$_POST['id_chamado'];
        if ($acesso->acesso_valida($comando) == true) {
            $chamado =new ChamadoInternoModel();
            $chamadogrupo = new ChamadoGrupoInternoModel();
            $id = $chamadogrupo->cadastrar_chamadogrupo(
                    array(
                        'id_colaborador' => $_POST['id_colaborador'],
                        'id_chamado' => $id_chamado,
                        'id_status' => "1",
                        'data_lancamento' => date('Y-m-d H:i:s'),
                    )
            );
            $chamado->alterar_Chamado(array("id_status"=>"104"), "id=$id_chamado");
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ChamadoInterno/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
    public function alterar() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/ChamadoGrupo/alterar/';
        if ($acesso->acesso_valida($comando) == true) {
            $id = $_POST['id'];
            $chamadogrupo = new ChamadoGrupoInternoModel();
            $chamadogrupo->alterar_chamadogrupo(
            array(
                'id_colaborador' => $_POST['id_colaborador'],
                'id_chamado' => $_POST['id_chamado'],
                'id_status' => $_POST['id_status'],
                'data_lancamento' => date('Y-m-d H:i:s'),
                ), 'id=' . $id
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ChamadoGrupoInterno/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
    public function excluir() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/ChamadoGrupo/excluir/';
        if ($acesso->acesso_valida($comando) == true) {
            $id = $this->getParams('id');
            $chamadogrupo = new ChamadoGrupoInternoModel();
            $chamadogrupo->excluir_chamadogrupo(array('id_status' => '99'), 'id=' . $id);
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ChamadoGrupoInterno/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
}
?> 