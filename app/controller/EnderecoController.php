<?php class Endereco extends Controller {   

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

            $endereco = new EnderecoModel();

            

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

                $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

                    echo $menu->Menu();            

    

                $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     

                    $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$endereco->listar_Endereco(

                            NULL,NULL,"id_status<>99  AND ({$filiais})",NULL,' Endereco.id DESC',NULL,NULL,$pesquisa

                        ),

                        "tabela1",

                        array(

                            array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),

                            array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),

                            array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")

                        )

                    );

                $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");

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

        $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

        $id = $this->getParams('id');

        $comando="/".__CLASS__."/incluir/";

        $form_nome="Cadastro Endereço";



        if(!empty($id)){

            $endereco = new EnderecoModel();

            $endereco_dados = $endereco->listar_Endereco(NULL,NULL,"id=$id AND id_status<>99",NULL,'Endereco.id DESC');

            $endereco_dados=$endereco_dados[0];

            $comando="/".__CLASS__."/alterar/";

            $form_nome="Editar Endereço";

        }



        $form = new FormularioHelper($form_nome,"col-md-12 ",$comando."tabela/$tabela/id_tabela/$id_tabela/","POST","perm_contact_calendar");

            $inputs.= $form->Input("hidden", "id", null, $id, $required,null,$disabled);

            $inputs.= $form->select("Filial","id_filial", "col-md-3", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL),"nome_fantasia");

            $inputs.= $form->Input("hidden", "id_tabela", null, $id_tabela, $required,null);                

            $inputs.= $form->Input("hidden", "tabelaa", null, $tabela, $required,null);                

            $inputs.= $form->Input("hidden", "data_lancamento", null, $endereco_dados["data_lancamento"],$required,null);                

            $inputs.= $form->Input("text", "pais", "col-md-3", $endereco_dados["pais"], "required","País",$disabled);

            $inputs.= $form->Input("text", "estado", "col-md-3", $endereco_dados["estado"], "required","Estado",$disabled);

            $inputs.= $form->Input("text", "cep", "col-md-3", $endereco_dados["cep"], "required","CEP",$disabled);

            $inputs.= $form->Input("text", "cidade", "col-md-3", $endereco_dados["cidade"], "required","Cidade",$disabled);

            $inputs.= $form->Input("text", "logradouro", "col-md-3", $endereco_dados["logradouro"], "required","Logradouro",$disabled);

            $inputs.= $form->Input("text", "numero", "col-md-2", $endereco_dados["numero"], "required","Número",$disabled);

            $inputs.= $form->Input("text", "bairro", "col-md-3", $endereco_dados["bairro"], "required","Bairro",$disabled);

            $inputs.= $form->Input("text", "complemento", "col-md-2", $endereco_dados["complemento"], "required","Complemento",$disabled);

            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

     

            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");

            $form->card($form_nome,$inputs,"col-md-12",$comando."tabela/$tabela/id_tabela/$id_tabela/","POST","people");

    }



    public function incluir(){    

        $this->acesso_restrito();



        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Endereco/incluir/';



        $id_tabela = $this->getParams('id_tabela');

        $tabela = $this->getParams('tabela');



        if($acesso->acesso_valida($comando)==true){

            $endereco = new EnderecoModel();      

            $id=$endereco->cadastrar_endereco(  

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'id_tabela'=>$id_tabela,

                    'id_status'=>$_POST['id_status'],

                    'tabela'=>$tabela,

                    'pais'=>$_POST['pais'],

                    'estado'=>$_POST['estado'],

                    'cep'=>$_POST['cep'],

                    'cidade'=>$_POST['cidade'],

                    'logradouro'=>$_POST['logradouro'],

                    'numero'=>$_POST['numero'],

                    'bairro'=>$_POST['bairro'],

                    'complemento'=>$_POST['complemento'],                  

                    'data_lancamento'=>date('Y-m-d H:i:s'),

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

        $comando='/Endereco/alterar/';



        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $tabela = $_POST['tabelaa'];

            $id_tabela = $_POST['id_tabela']; 

            $endereco = new EnderecoModel();      

            $endereco->alterar_endereco(

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'id_tabela'=>$id_tabela,

                    'id_status'=>$_POST['id_status'],

                    'pais'=>$_POST['pais'],

                    'estado'=>$_POST['estado'],

                    'cep'=>$_POST['cep'], 

                    'cidade'=>$_POST['cidade'], 

                    'logradouro'=>$_POST['logradouro'],

                    'numero'=>$_POST['numero'],

                    'bairro'=>$_POST['bairro'],

                    'complemento'=>$_POST['complemento'],   

                ),'id='.$id

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

        $comando='/Endereco/excluir/';



        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];
            $id_tabela = $_POST['id_tabela'];
            $tabela = $_POST['tabela'];

            $endereco = new EnderecoModel();      

            $endereco->excluir_endereco( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");     

        }else{

            $this->view('error_permisao');

        }

    } 

}

?>