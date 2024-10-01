<?php class Status extends Controller {   
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
            $status = new StatusModel();           
           $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$status->listar_Status(NULL,NULL,"id_status<>'99' AND ({$filiais})",NULL,' Status.id DESC',"Status.id,Status.descricao, Status.tabela,Status.cor AS cor_Cor, Status.cor AS Cor "), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","library_books");
        }else{
           $this->view('error_permisao');
        }    
    }

    public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();         
        $comando="/".__CLASS__."/incluir/";  
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $status= new StatusModel();
            $filial = new FilialModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");     
            $nome_form="Cadastra Status";           
            if(!empty($id)){
                $status_dados=$status->listar_Status($join, "1", "id='$id'", $offset, $orderby);
                $status_dados= $status_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Tipo Documento";
            }                      
            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");
            $inputs.= $form->Input("hidden", "id", null, $id, $Required, null, $disable);
            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' id ASC',NULL),"nome_fantasia");
            $inputs.= $form->Input("text", "descricao", "col-md-2", $status_dados["descricao"], $Required, "Descrição", $disable);           
            $inputs.= $form->Input("text", "tabela", "col-md-2", $status_dados["tabela"], $Required, "Tabela", $disable);
             $inputs.= $form->Input("text", "cor", "col-md-2", $status_dados["cor"], $Required, "Cor", $disable);
            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar"); 
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","library_books");
        }else{
           $this->view('error_permisao');
        }   
    }

    

    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Status/incluir/';           
       if($acesso->acesso_valida($comando)==true){
            $status = new StatusModel();      
            $id=$status->cadastrar_status( 
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tabela'=>"0",
                        'tabela'=>$_POST['tabela'],
                        'descricao'=>$_POST['descricao'],
                         'cor'=>$_POST['cor'],
                        'id_status'=>$_POST['id_status'],
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Status/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }
    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Status/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];

            $status = new StatusModel();      
            $status->alterar_status(
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tabela'=>"0",
                        'tabela'=>$_POST['tabela'],
                        'descricao'=>$_POST['descricao'],
                        'cor'=>$_POST['cor'],
                        'id_status'=>$_POST['id_status'],
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Status/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }
    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Status/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $status = new StatusModel();      
            $status->excluir_status( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Status/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
} ?> 