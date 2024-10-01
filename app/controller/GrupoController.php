<?php



class Grupo extends Controller {

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
            $grupo = new GrupoModel();
           
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$grupo->listar_Grupo("INNER JOIN Filial ON Filial.id=Grupo.id_filial INNER JOIN Status ON Status.id=Grupo.id_status ",NULL,"Grupo.id_status='1' ",NULL,' Grupo.id DESC',"Grupo.id,Filial.nome_fantasia AS Filial,Grupo.tabela AS Tabela,Grupo.descricao AS Descrição,Status.cor AS cor_Status,Status.descricao AS Status",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }    
    }

    public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();    
        $comando='/'.__CLASS__.'/incluir/';
        if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();  
                
            $filial = new FilialModel();
            $status = new StatusModel();            
            $grupo = new GrupoModel();
            $id = $this->getParams('id');

            $dados['id']=$id;
            $nome_form='Cadastra Grupo';
            
            if(!empty($id)){
                $grupo_dados=$grupo->listar_grupo($JOIN, '1', "id=$id", $offset, $orderby);
                $grupo_dados = $grupo_dados[0]; 
                $comando='/'.__CLASS__.'/alterar/';
                $nome_form='Alterar Grupo';
            } 
             $form = new FormularioHelper();
                $inputs.= $form->Input('hidden', 'id', $CSS, $id);
                $inputs.= $form->select('Filial','id_filial','col-md-2',$filial->listar_Filial(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome_fantasia',$grupo_dados['id_filial']);
                $inputs.= $form->Input("text", 'descricao', "col-md-3", $grupo_dados["descricao"], $Required, 'descricao', $disable);
                $inputs.= $form->select('Status','id_status','col-md-3',$status->listar_Status(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$grupo_dados['id_status']);
                $inputs.= $form->select('Referencia','tabela','col-md-2',array(array("id"=>"Filial"),array("id"=>"Acesso"),array("id"=>"Produto")),'id',$grupo_dados['id_status']);
                $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');    
                $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }
    }


    public function incluir() {

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando = '/Grupo/incluir/';

        if ($acesso->acesso_valida($comando) == true) {

            $grupo = new GrupoModel();

            $id = $grupo->cadastrar_grupo(

                array(

                    'id_filial' => $_POST['id_filial'],

                    'tabela' => $_POST['tabela'],

                    'descricao' => $_POST['descricao'],

                    'id_status' => $_POST['id_status'],

                    'data_lancamento' => date("Y-m-d H:i:s"),

                )

            );

            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Grupo/admin_listar/');

        } else {

            $this->view('error_permisao');

        }

    }



    public function alterar() {

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando = '/Grupo/alterar/';

        if ($acesso->acesso_valida($comando) == true) {

            $id = $_POST['id'];

            $grupo = new GrupoModel();

            $grupo->alterar_grupo(

                array(

                    'id_filial' => $_POST['id_filial'],

                    'tabela' => $_POST['tabela'],

                    'descricao' => $_POST['descricao'],

                    'id_status' => $_POST['id_status'],

                    'data_lancamento' => date("Y-m-d H:i:s"),

                ), 'id=' . $id

            );

            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Grupo/admin_listar/');

        } else {

            $this->view('error_permisao');

        }

    }



    public function excluir() {

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando = '/Grupo/excluir/';

        if ($acesso->acesso_valida($comando) == true) {

            $id = $this->getParams('id');

            $grupo = new GrupoModel();

            $grupo->excluir_grupo(array('id_status' => '99'), 'id=' . $id);

            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Grupo/admin_listar/');

        } else {

            $this->view('error_permisao');

        }

    }

} ?> 