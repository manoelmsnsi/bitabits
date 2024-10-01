<?php class Familia extends Controller {   
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
            $familia = new FamiliaoModel();            
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();
            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$familia->listar_Familia(NULL,NULL,"id_status<>99  AND ({$filiais})",NULL,' Familia.id DESC',NULL,NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot"); 
        }else{
            $this->view('error_permisao');
        }    
    }
    public function form() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
      //  if($acesso->acesso_valida("/Familia/form/")==true){
        $status= new StatusModel();
        $filial = new FilialModel();
        $familia = new FamiliaModel();  
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $id=$this->getParams("id");     
        if(!empty($id)){
            $familia_dados=$familia->listar_Familia($join, "1", "id=$id", $offset, $orderby);
            $familia_dados= $familia_dados[0]; 
            $comando="/".__CLASS__."/alterar/";
        }                    
         $form = new FormularioHelper("Cadastro de Familiares","col-md-12" ,$comando,"POST","people");
            $inputs.= $form->Input("hidden", "id", null, $id, $required,null);
            $inputs.= $form->Input("hidden", "id_tabela", null, $this->getParams('id_tabela'), $required,null);          
            $inputs.= $form->Input("hidden", "tabela", null, $this->getParams('tabela'), $required,null);            
            $inputs.= $form->select("Filial","id_filial", "col-md-3", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");            
            $inputs.= $form->select("Tipo","tipo", "col-md-2",array(array("id"=>"Cônjuge","descricao"=>"Cônjuge"),array("id"=>"Filho(a)","descricao"=>"Filho(a)"),array("id"=>"Pai","descricao"=>"Pai"),array("id"=>"Mãe","descricao"=>"Mãe"),array("id"=>"Sobrinho(a)","descricao"=>"Sobrinho(a)"),array("id"=>"Avô(ó)","descricao"=>"Avô(ó)"),array("id"=>"Tio/Tia","descricao"=>"Tio/Tia"),array("id"=>"Irmã(o)","descricao"=>"Irmã(o)")),"descricao");
            $inputs.= $form->Input("text", "nome", "col-md-7", $familia_dados["nome"], $required,"Nome");
            $inputs.= $form->Input("text", "contato", "col-md-3", $familia_dados["contato"], $required,"Contato");
            $inputs.= $form->Input("date", "data_nascimento", "col-md-3", $familia_dados["data_nascimento"], $required,"Data de Nascimento");           
            $inputs.= $form->select("Status","id_status", "col-md-3", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");            
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        //    }else{
        //       $this->view('error_permisao');
         //  }       
    }
    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Familia/incluir/';        
        $id_tabela = $_POST['id_tabela'];
        $tabela = $_POST['tabela'];                    
        if($acesso->acesso_valida($comando)==true){
            $familia = new FamiliaModel();      
            $id=$familia->cadastrar_Familia( 
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_status'=>$_POST['id_status'],
                        'id_tabela'=>$_POST['id_tabela'],
                        'tabela'=>$_POST['tabela'],
                        'tipo'=>$_POST['tipo'],
                        'nome'=>$_POST['nome'],
                        'contato'=>$_POST['contato'],
                        'data_nascimento'=>$_POST['data_nascimento'],
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );              
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");    
        }else{
            $this->view('error_permisao');
        }
    }
 public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Familia/alterar/';        
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $tabela = $_POST['tabela'];
            $id_tabela = $_POST['id_tabela'];            
            $familia = new FamiliaModel();      
            $familia->alterar_Familia(
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_status'=>$_POST['id_status'],
                        'id_tabela'=>$_POST['id_tabela'],
                        'tabela'=>$_POST['tabela'],
                        'tipo'=>$_POST['tipo'],
                        'nome'=>$_POST['nome'],
                        'contato'=>$_POST['contato'],
                        'data_nascimento'=>$_POST['data_nascimento'],

                ),"id=$id"
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
           $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");    
        }else{
            $this->view('error_permisao');
        }

    }
 public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Familia/excluir/';
        if($acesso->acesso_valida($comando)==true){
           $id = $_POST['id'];
            $id_tabela = $_POST['id_tabela'];
            $tabela = $_POST['tabela'];

            $familia = new FamiliaModel();      
            $familia->excluir_Familia( array( 'id_status'=>'99' ),'id='.$id );           
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");     
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 