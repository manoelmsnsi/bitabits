e<?php

class Contato extends Controller {   

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

            $contato = new ContatoModel();

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper();     

            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$contato->listar_Contato(NULL,NULL,"id_status<>99  AND ({$filiais})",NULL,' Contato.id DESC',NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","donut_small");

        }else{

            $this->view('error_permisao');

        }    

    }



    

     public function form(){    

        $this->acesso_restrito();



        $acesso = new SessionHelper();

        $listar_acesso=$acesso->selectSession('userAcesso');

        $user_dados=$acesso->selectSession('userData');



        $filial = new FilialModel();          

        $status = new StatusModel();        

        $id_tabela= $this->getParams('id_tabela');

        $tabela = $this->getParams('tabela');          

        $acesso = new SessionHelper();

        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

        $id = $this->getParams('id');

        $comando="/".__CLASS__."/incluir/";

        $form_nome="Cadastro Contato";



        if(!empty($id)){

            $contato = new ContatoModel();

            $contato_dado = $contato->listar_contato(NULL,NULL,"id=$id AND id_status<>99",NULL,'Contato.id DESC');

            $contato_dado=$contato_dado[0];

            $comando="/".__CLASS__."/alterar/";

            $form_nome="Editar Contato";

        } 



        $form = new FormularioHelper($form_nome,"col-md-12 ",$comando."tabela/$tabela/id_tabela/$id_tabela/","POST","perm_contact_calendar");

            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL),"nome_fantasia");

            $inputs.= $form->Input("hidden", "id", null, $id, $required,null,$disabled);      

            $inputs.= $form->select("Descrição","descricao", "col-md-4",array(array("id"=>"Celular","descricao"=>"Celular"),array("id"=>"Fixo","descricao"=>"Fixo"),array("id"=>"Trabalho","descricao"=>"Trabalho"),array("id"=>"E-mail","descricao"=>"E-mail")),"descricao", $contato_dado["descricao"]);            

            $inputs.= $form->Input("text", "contato", "col-md-4", $contato_dado["contato"], $required,"Contato",$disabled);

            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

            $inputs.=$form->Button("btn btn-md btn-rose ","Salvar");

            $form->card($form_nome,$inputs,"col-md-12",$comando."tabela/$tabela/id_tabela/$id_tabela/","POST","donut_small");

    }



    public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Contato/incluir/';

        $id_tabela = $this->getParams('id_tabela');

        $tabela = $this->getParams('tabela');         

        if($acesso->acesso_valida($comando)==true){

            $contato = new ContatoModel();      

            $id=$contato->cadastrar_contato( 

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'id_tabela'=>$id_tabela,

                    'tabela'=>$tabela,

                    'descricao'=>$_POST['descricao'],

                    'contato'=>$_POST['contato'],

                    'id_status'=>$_POST['id_status'],

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

        $comando='/Contato/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $id_tabela = $this->getParams('id_tabela');

            $tabela = $this->getParams('tabela');



            $contato = new ContatoModel();      

            $contato->alterar_contato(

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'descricao'=>$_POST['descricao'],

                    'contato'=>$_POST['contato'],

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela/");    

        }else{

            $this->view('error_permisao');

        }

    }



    public function excluir(){    

        $this->acesso_restrito();



        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Contato/excluir/';



        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $id_tabela = $_POST['id_tabela'];

            $tabela = $_POST['tabela'];



            $contato = new ContatoModel();      

            $contato->excluir_contato( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs



            $redirect = new RedirectHelper();

            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");      

        }else{

            $this->view('error_permisao');

        }

    } 

}

?>