<?php class Intranet extends Controller {   
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
       // if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $intranet = new IntranetModel();   
            $listar_intranet = $intranet->listar_intranet("INNER JOIN Status ON Status.id=Intranet.id_status ", $limit, $where, $offset, $orderby, "Intranet.id,Intranet.titulo,Intranet.noticia,Intranet.modulo,Status.cor,Status.descricao AS Status,Intranet.funcionalidade"); 
            $menu = new MenuHelper("Biabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);    
            echo $menu->Menu();  
            
            $form = new FormularioHelper(null,"col-md-12" ,null,null,null,false);
            $inputs= $form->Listar("col-md-12", "Alertas", "/Intranet/form/", "warning", $listar_intranet, "tabela1", array(array("acao"=>"/Intranet/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
      // }
    
    } 
    public function visualizar(){
           
       } 


    public function form(){ 
                
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
       // if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
        $status= new StatusModel();
        $intranet= new IntranetModel();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        
        $id=$this->getParams("id");     
      
        if(!empty($id)){
            $intranet_dados=$intranet->listar_intranet($join, "1", "id='$id'", $offset, $orderby);
            $intranet_dados= $intranet_dados[0]; 
            $comando="/".__CLASS__."/alterar/";
        }            
         
        $tipo = $this->getParams('tipo');         
            $form = new FormularioHelper("Cadastro Noticia/Comunicado/Alerta","col-md-12" ,$comando,"POST","people");         
                          
            $inputs.= $form->Input("text", "titulo", "col-md-6", $intranet_dados["titulo"], "required","Titulo");
            $inputs.= $form->Input("text", "modulo", "col-md-2", $intranet_dados["modulo"], $required,"Modulo");
            $inputs.= $form->Input("text", "funcionalidade", "col-md-2", $intranet_dados["funcionalidade"], $required,"Funcionalidade");
            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Intranet'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Input("text", "noticia", "col-md-12", $intranet_dados["noticia"], $required,"Notícia");            
            
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        //    }else{
      //         $this->view('error_permisao');
        //   }
   
    }

    public function incluir(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Intranet/incluir/';

           if($acesso->acesso_valida($comando)==true){
               $intranet = new IntranetModel();      
             
               $id=$intranet->cadastrar_intranet( 
                   array(
                       'titulo'=>$_POST['titulo'],
                       'noticia'=>$_POST['noticia'],
                       'modulo'=>$_POST['modulo'],
                       'id_status'=>$_POST['id_status'],
                       'funcionalidade'=>$_POST['funcionalidade'],                   
                      
                       'data_lancamento'=>  date("Y-m-d H:i:s"),
                   )
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl("/Intranet/admin_listar");   
           
           }else{
               $this->view('error_permisao');
           }
       }
    public function alterar(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/'.__CLASS__.'/alterar/';
              echo"ok $id";
           if($acesso->acesso_valida($comando)==true){
               $id = $_POST['id'];
            
               $intranet = new IntranetModel();   
               $id=$intranet->cadastrar_intranet( 
                   array(
                       'titulo'=>$_POST['titulo'],
                       'noticia'=>$_POST['noticia'],
                       'modulo'=>$_POST['modulo'],
                       'id_status'=>$_POST['id_status'],
                       'funcionalidade'=>$_POST['funcionalidade']
                   ),"id=$id"
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl("/Intranet/admin_listar");    
           }else{
               $this->view('error_permisao');
           }

       }
    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Pessoa/excluir/';
        
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            
            $pessoa = new PessoaModel();      
            $pessoa->alterar_Pessoa( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Pessoa/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 }