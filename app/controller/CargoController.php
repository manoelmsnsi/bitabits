<?php class Cargo extends Controller {   

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

            $cargo = new CargoModel();

            
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper();     

            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$cargo->listar_Cargo(NULL,NULL,"id_status<>99  AND ({$filiais})",NULL,' Cargo.id DESC',NULL,NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
           $form->card(__CLASS__, $inputs, "col-md-12", $comando, "POST", "people");

        }else{

            $this->view('error_permisao');

        }    

    }
public function form() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/Cargo/admin_listar/")==true){
        $status= new StatusModel();
        $filial = new FilialModel();
        $cargo = new CargoModel();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $id=$this->getParams("id");     
      
        if(!empty($id)){
           $cargo_dados=$cargo->listar_Cargo($join, "1", "id=$id", $offset, $orderby);
           $cargo_dados= $cargo_dados[0]; 
            $comando="/".__CLASS__."/alterar/";
        }            
        $form = new FormularioHelper("Inserção de Cargos","col-md-12", $comando, "POST", "people");
            $inputs.= $form->Input("hidden", "id", null, $id, $required,null);
            $inputs.= $form->Input("hidden", "id_tabela", null, $this->getParams('id_tabela'), $required,null);   
            $inputs.= $form->Input("hidden", "tabela", null, $this->getParams('tabela'), $required,null);   
            $inputs.= $form->select("Filial","id_filial", "col-md-3 ", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");
            $inputs.= $form->Input("text", "descricao", "col-md-9", $cargo_dados["descricao"], "required","Descrição do Cargo");
            $inputs.= $form->Input("date", "data_posse", "col-md-4", $cargo_dados["data_posse"], "required","Data da Posse");
            $inputs.= $form->Input("date", "data_saida", "col-md-4", $cargo_dados["data_saida"], "required","Data da Saída");
            $inputs.= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Input("text", "observacao", "col-md-12", $cargo_dados["observacao"], "required","Observação");         
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card("Cadastro de " .__CLASS__, $inputs, "col-md-12", $comando, "POST", "list");

            }else{

               $this->view('error_permisao');

           }                   

}

 public function incluir(){      
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Cargo/incluir/';
        
        $tabela = $_POST['tabela'];
        $id_tabela = $_POST['id_tabela'];
            
        if($acesso->acesso_valida($comando)==true){

            $cargo = new CargoModel();      
            $id=$cargo->cadastrar_cargo( 
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tabela'=>$id_tabela,
                        'id_status'=>$_POST['id_status'],
                        'tabela'=>$_POST['tabela'],
                        'descricao'=>$_POST['descricao'],
                        'observacao'=>$_POST['observacao'],
                        'data_posse'=>$_POST['data_posse'],
                        'data_saida'=>$_POST['data_saida'],                        
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
        $comando='/Cargo/alterar/';
        
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];            
            $tabela = $_POST['tabela'];
            $id_tabela = $_POST['id_tabela'];

            $cargo = new CargoModel();      
            $cargo->alterar_cargo(
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tabela'=>$_POST['id_tabela'],
                        'id_status'=>$_POST['id_status'],
                        'tabela'=>$_POST['tabela'],
                        'descricao'=>$_POST['descricao'],
                        'observacao'=>$_POST['observacao'],
                        'data_posse'=>$_POST['data_posse'],
                        'data_saida'=>$_POST['data_saida'],                        
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id/tabela/$tabela");     
        }else{
            $this->view('error_permisao');
        }

    }
    
 public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Cargo/excluir/';
        
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $id_tabela = $this->getParams('id_tabela');
            $tabela = $this->getParams('tabela');
            
            $cargo = new CargoModel();      
            $cargo->excluir_Cargo( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");     
        }else{
            $this->view('error_permisao');
        }
    } 
} ?> 