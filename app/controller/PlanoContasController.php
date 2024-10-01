<?php class PlanoContas extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
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
            $plano_contas = new PlanoContasModel();            
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits - Plano de Contas", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$plano_contas->listar_PlanoContas("
                   INNER JOIN Status ON Status.id=PlanoContas.id_status 
                    INNER JOIN PlanoContas AS pl ON pl.id=PlanoContas.id_pai
                    INNER JOIN Filial ON Filial.id=PlanoContas.id_filial",
                    NULL,
                    "PlanoContas.id_status<>99 AND ({$filiais})",
                    NULL,
                    'PlanoContas.id DESC',"PlanoContas.id,Filial.nome_fantasia AS Filial,PlanoContas.descricao AS Descrição,pl.descricao AS Pai,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa),
                    "tabela1",
                    array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
                    $form->card("Planos de Contas",$inputs,"col-md-12",$comando,"POST","list");
        }else{
            $this->view('error_permisao');
        }    
    }
     public function form(){               
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
        $status= new StatusModel();
        $filial = new FilialModel();
        $plano_contas = new PlanoContasModel();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("Bit a Bits - Plano de Contas", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        
        $id=$this->getParams("id");                        
        if(!empty($id)){
            $plano_contas_dados=$plano_contas->listar_PlanoContas($join, "1", "id='$id'", $offset, $orderby);
            $plano_contas_dados= $plano_contas_dados[0]; 
            $comando="/".__CLASS__."/alterar/";
        }           
        $form = new FormularioHelper();        
        $inputs= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");
        $inputs.= $form->select("Pai/Filho/Fluxo","id_pai", "col-md-2",$plano_contas->listar_PlanoContas($join, $limit, $where, $offset, $orderby, $from),"descricao",$plano_contas_dados["id_pai"]);
        $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
        $inputs.= $form->Input("text", "descricao", "col-md-6", $plano_contas_dados["descricao"], "required","Descrição");
        $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
        $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");    
        $form->card("Cadastro de Plano de Contas",$inputs,"col-md-12",$comando,"POST","list");
        }else{
           $this->view('error_permisao');
        } 
    }
    
    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/PlanoContas/incluir/';            
       if($acesso->acesso_valida($comando)==true){
            $plano_contas = new PlanoContasModel();      
            $id=$plano_contas->cadastrar_PlanoContas( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_pai'=>$_POST['id_pai'],
                    'descricao'=>$_POST['descricao'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s")
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/PlanoContas/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/PlanoContas/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $plano_contas = new PlanoContasModel();      
            $plano_contas->alterar_PlanoContas(
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_pai'=>$_POST['id_pai'],
                    'descricao'=>$_POST['descricao'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s")
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/PlanoContas/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/PlanoContas/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $plano_contas = new PlanoContasModel();      
            $plano_contas->excluir_PlanoContas( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/PlanoContas/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 