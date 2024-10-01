<?php

class Logs extends Controller {   

    private  $auth,$db;

    public function init(){          

        $this->auth = new AutenticaHelper();

        $this->auth->setLoginControllerAction("Admin", "login")
             ->checkLogin("redirect");   
        $this->db = new AdminModel();

    }

    

    public function index_action(){

        $redirect = new RedirectHelper();

        $redirect->goToUrl("/Index/");     

    }

    

    public function admin_listar(){           

        $this->init();

        $acesso = new AcessoHelper();  

    

        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 

            $filiais=$acesso->acesso_filial(__CLASS__);

            $status= new StatusModel();

            $filial = new FilialModel();           

            $acesso = new SessionHelper();            

            $logs = new LogsModel();

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper();     

            $inputs.= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$logs->listar_logs("INNER JOIN Usuario ON Usuario.id = Logs.id_usuario INNER JOIN Filial ON Filial.id=Logs.id_filial INNER JOIN Status ON Status.id = Logs.id_status","25"," ({$filiais})",NULL,"Logs.id DESC","Logs.id,Filial.nome_fantasia AS Filial,Usuario.usuario AS Usuario,Logs.id_comando AS 'Id Comando',Logs.comando AS Comando,Logs.observacao AS Observação,Status.cor AS cor_Status,Logs.data_lancamento AS Lançamento,Status.Descricao AS Status",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","history");

            

        }else{

            $this->view('error_permisao');

        }     

    }

    

    public function form(){ 

        $this->init();

        $acesso = new AcessoHelper();          

        

        $classe = explode("/", $_SERVER['HTTP_REFERER'] ); 

        $comando="/".__CLASS__."/incluir/";  

        echo "/".$classe[3]."/admin_listar/";

        if($acesso->acesso_valida("/".$classe[3]."/admin_listar/")==true){     

            $logs = new LogsModel(); 

            $acesso = new SessionHelper();
        
            $id=$this->getParams("id");     

            //$tabela=$this->getParams("tabela");    

            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

            $user=$acesso->selectSession('userData');

            $form = new FormularioHelper("Exclusão","col-md-12 " ,$comando,"POST","people");

            $inputs.= $form->Input("hidden", "id_tabela", $Classe, $id);

            $inputs.= $form->Input("hidden", "id_filial", $Classe, $user["id_filial"]);

            $inputs.= $form->Input("hidden", "ttabela", $Classe, $classe[3]); 

            $inputs.= $form->Input("hidden", "id_status", $Classe, "1"); 

            $inputs.= $form->Input("text", "observacao", "col-md-10", null, "required", "Motivo", $disable);

            $inputs.= $form->Button("btn btn-md btn-danger ","Excluir");

           $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","history"); 

        }else{

           $this->view('error_permisao');

        }

    }  

    

    public function form_alterar(){ 

        $this->init();

        $acesso = new AcessoHelper();        
        $classe=$this->getParams("classe");

            $acao=$this->getParams("acao");

        if($acesso->acesso_valida("/$classe/$acao/")==true){   
            $logs = new LogsModel(); 

            $acesso = new SessionHelper();
            $id=$this->getParams("id");    

            $id_tabela=$this->getParams("id_tabela");     

            $tabela=$this->getParams("tabela");     

          //  $classe=$this->getParams("classe");

          // $acao=$this->getParams("acao");


            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio); 

            echo $menu->Menu();

           

            $form = new FormularioHelper();
             
            $inputs.= $form->Input("hidden", "id", null, $id, $required,null);
            $inputs.= $form->Input("hidden", "id_tabela", null, $id_tabela, $required,null);
            $inputs.= $form->Input("hidden", "tabela", null, $tabela, $required,null);


            $inputs.= $form->Input("text", "observacao", "col-md-10", null, $Required, "Motivo", $disable);

            $inputs.= $form->Button("btn btn-md btn-danger ","Confirmar");
        
          
            $form->card("Excluir/Estorna",$inputs,"col-md-12","/$classe/$acao/","POST","sync_problem"); 

        }else{

           $this->view('error_permisao');

        }

    }

    

    public function visualizar(){  

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/Logs/visualizar/";       

        if($acesso->acesso_valida($comando)==true){ 

            $acesso = new SessionHelper();

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

            $id = $this->getParams("id");            

            $tabela = $this->getParams("tabela");

            $nome = $this->getParams("nome");

            $dados["nome"] =$nome;

            $comando = $this->getParams("comando");

            $dados["comando"] = $comando;

            $db = new Model;

            $db->_tabela = $tabela;

            $dados["tabela"] = $tabela;

    

            $listar_dados = $db->read(NULL,"id={$id}",NULL,NULL,NULL,NULL);

             

            $dados["listar_dados"] = $listar_dados;      

            $logs->incluir($comando, "0");

            

            $this->view("visualizar_logs",$dados);

        }else{

            $this->view("error_permisao");

        } 

    }  

    

     public function incluir() {



        $this->init();



        $acesso = new AcessoHelper();



        $logs = new LogsModel();

        $tabela=$_POST['ttabela'];

        $comando = "/$tabela/excluir/";

        $id_tabela=$_POST['id_tabela'];

        if ($acesso->acesso_valida("/".$tabela."/excluir/") == true) {
        $tabela1=$_POST['ttabela'];
        $tabela1=$tabela1."Model";
        $tabela_instancia = new $tabela1();
        $tabela1=$_POST['ttabela'];      
        $observacao=$_POST['observacao'];
        
        $tabela1="excluir_".$tabela1;        
        
                $tabela_instancia->$tabela1( 
                    array( 
                        'id_status' => '99',
                    ), "id=$id_tabela" 
            );

              //  Echo "Excluido com sucesso!!!";

            $logs = new logsModel();

            $logs->cadastrar_logs($comando, $id_tabela,$observacao); //Gera Logs       



           echo "<script>script:history.go(-2)</script>";



        } else {

           $this->view('error_permisao');

        }



    }

    

}

        