<?php class TipoPagamento extends Controller {   

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

            $tipo_pagamento = new TipoPagamentoModel();           

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper("Forma de Pagamento","col-md-12" ,null,null,"people");     

            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$tipo_pagamento->listar_TipoPagamento("INNER JOIN Filial ON Filial.id = TipoPagamento.id_filial INNER JOIN Status ON Status.id = TipoPagamento.id_status",NULL,"TipoPagamento.id_status<>99 AND ({$filiais})",NULL,' TipoPagamento.id DESC',"TipoPagamento.id,Filial.nome_fantasia AS Filial,TipoPagamento.descricao AS Descrição,TipoPagamento.entrada AS Entrada,TipoPagamento.quantidade AS Quantidade,TipoPagamento.juros AS 'Juros Dia',((TipoPagamento.juros*100)*30) AS 'Juros Mes',Status.cor AS cor_Status,Status.descricao AS Status",null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

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

            $acesso = new SessionHelper();

            $status= new StatusModel();

            $filial = new FilialModel();

            $tipo_pagamento= new TipoPagamentoModel(); 

            $acesso = new SessionHelper();

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

            $id=$this->getParams("id");    

            $nome_form="Cadastra Tipo Pagamento";    



            if(!empty($id)){

                $tipo_pagamento_dados=$tipo_pagamento->listar_TipoPagamento($join, "1", "id='$id'", $offset, $orderby);

                $tipo_pagamento_dados= $tipo_pagamento_dados[0]; 

                $comando="/".__CLASS__."/alterar/";

                $nome_form="Alterar Tipo Pagamento";

            } 

            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");

            $inputs.= $form->Input("hidden", "id", null, $id, $Required, null, $disable);

            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' id ASC',NULL),"nome_fantasia");

            $inputs.= $form->Input("text", "descricao", "col-md-10", $tipo_pagamento_dados["descricao"], $Required, "Descrição", $disable);

            $inputs.= $form->select("Entrada","entrada", "col-md-4", array(array("id"=>"1","descricao"=>"SIM"),array("id"=>"0","descricao"=>"NÃO")),"descricao",$tipo_pagamento_dados["entrada"]);

            $inputs.= $form->Input("number", "quantidade", "col-md-2", $tipo_pagamento_dados["quantidade"], $Required, "Quantidade", $disable);
            $inputs.= $form->Input("text", "juros", "col-md-2", $tipo_pagamento_dados["juros"], $Required, "Juros", $disable);

            $inputs.= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

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

        $comando='/TipoPagamento/incluir/';           

        if($acesso->acesso_valida($comando)==true){

            $tipopagamento = new TipoPagamentoModel();      

            $id=$tipopagamento->cadastrar_tipopagamento( 

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'descricao'=>$_POST['descricao'],

                    'entrada'=>$_POST['entrada'],
                    'juros'=>(($_POST['juros']/100)/30),
                    'quantidade'=>$_POST['quantidade'],

                    'id_status'=>$_POST['id_status'],

                   'data_lancamento'=>  date("Y-m-d H:i:s"),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/TipoPagamento/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }



 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/TipoPagamento/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

           $tipopagamento = new TipoPagamentoModel();      

            $tipopagamento->alterar_tipopagamento(

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'descricao'=>$_POST['descricao'],

                    'entrada'=>$_POST['entrada'],
   'juros'=>(($_POST['juros']/100)/30),
                    'quantidade'=>$_POST['quantidade'],

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/TipoPagamento/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }

 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/TipoPagamento/excluir/';

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $tipopagamento = new TipoPagamentoModel();      

            $tipopagamento->excluir_tipopagamento( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/TipoPagamento/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

 } ?> 