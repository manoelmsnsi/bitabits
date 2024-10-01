<?php class TipoDocumento extends Controller {   
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
            $tipo_documento = new TipoDocumentoModel();           
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper("Forma de Pagamento","col-md-12" ,null,null,"people");      
            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$tipo_documento->listar_TipoDocumento("INNER JOIN Filial ON Filial.id = TipoDocumento.id_filial INNER JOIN Status ON Status.id = TipoDocumento.id_status",NULL,"TipoDocumento.id_status<>99 AND ({$filiais})",NULL,' TipoDocumento.id DESC',"TipoDocumento.id,Filial.nome_fantasia AS Filial,TipoDocumento.descricao AS Descrição,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
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
            $tipo_documento= new TipoDocumentoModel(); 
            $acesso = new SessionHelper();
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");     
            $nome_form="Cadastra Tipo Documento" ;            
            if(!empty($id)){
                $tipo_documento_dados=$tipo_documento->listar_TipoDocumento($join, "1", "id='$id'", $offset, $orderby);
                $tipo_documento_dados= $tipo_documento_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Tipo Documento";
            }                  
            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");
            $inputs .= $form->Input("hidden", "id", null, $id, $Required, null, $disable);
            $inputs .= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' id ASC',NULL),"nome_fantasia");
            $inputs .= $form->Input("text", "descricao", "col-md-6", $tipo_documento_dados["descricao"], $Required, "Descrição", $disable);           
            $inputs .= $form->Input("text", "tipo", "col-md-2", $tipo_documento_dados["tipo"], $Required, "Tipo", $disable);
            $inputs .= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs .= $form->Button("btn btn-md btn-rose ","Salvar"); 
            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","library_books");
        }else{
           $this->view('error_permisao');
        }   
    }
    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/TipoDocumento/incluir/';           
        if($acesso->acesso_valida($comando)==true){
            $tipodocumento = new TipoDocumentoModel();      
            $id=$tipodocumento->cadastrar_tipodocumento( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'tipo'=>$_POST['tipo'],
                    'id_status'=>$_POST['id_status'],
                   'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/TipoDocumento/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }
    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/TipoDocumento/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $tipodocumento = new TipoDocumentoModel();      
            $tipodocumento->alterar_tipodocumento(
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'tipo'=>$_POST['tipo'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/TipoDocumento/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }
    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/TipoDocumento/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $tipodocumento = new TipoDocumentoModel();      
            $tipodocumento->excluir_tipodocumento( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/TipoDocumento/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 