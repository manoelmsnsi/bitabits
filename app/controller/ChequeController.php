<?php class Cheque extends Controller {   

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
            $cheque = new ChequeModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits - Controle de Cheques", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
                $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$cheque->listar_Cheque(
                    "INNER JOIN Venda ON Venda.id = Cheque.id_venda
                        INNER JOIN Pessoa ON Pessoa.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Cheque.id_status",
                        NULL,
                        "Cheque.id_status<>99",
                        NULL,
                        'Cheque.id DESC',
                            "Cheque.id,
                            Pessoa.nome AS 'Nome Cliente',
                            Cheque.emissor AS Emissor,
                            Cheque.id_venda,
                            Cheque.valor AS Valor,    
                            Status.cor AS cor_Status,
                            Status.descricao AS 'Status',
                            Cheque.agencia AS 'Agência',                            
                            Cheque.numero_cheque AS 'Nº Cheque',
                            Cheque.data_lancamento AS 'Data Lançamento'",NULL,$pesquisa
                   ),"tabela1",
                    array(
                        array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),
                        array("acao"=>"/Venda/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),
                        array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")
                    )
                );
            $form->card(__CLASS__, $inputs, "col-md-12", $comando, "POST", "account_balance_wallet");
        }else{
            $this->view('error_permisao');
        }    
    }



    public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
       if($acesso->acesso_valida("/Cheque/admin_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $cheque = new ChequeModel();
            $venda = new VendaModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits - Cadastro de Cheques", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");     
            if(!empty($id)){
               $cheque_dados=$cheque->listar_Cheque($join, "1", "id=$id", $offset, $orderby);
                $cheque_dados= $cheque_dados[0]; 
               $comando="/".__CLASS__."/alterar/";
            }            
            $form = new FormularioHelper("Cadastrar Cheques","col-md-12" ,$comando,"POST","account_balance_wallet","false");
                $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
                $inputs.= $form->select("ID Venda","id_venda", "col-md-4", $venda->listar_Venda("INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento INNER JOIN Pessoa ON Pessoa.id = Venda.id_cliente",NULL,"Venda.id_status <> 99 AND TipoDocumento.descricao='Cheque'",NULL,' Venda.id DESC',"Venda.id,Pessoa.nome"),"id,nome",$cheque_dados["id_venda"]);
                $inputs.= $form->Input("text", "banco", "col-md-4", $cheque_dados["banco"], "required","Banco");
                $inputs.= $form->Input("text", "agencia", "col-md-3", $cheque_dados["agencia"], "required","Agência");
                $inputs.= $form->Input("text", "numero_cheque", "col-md-3", $cheque_dados["numero_cheque"], "required", "Número do Cheque");
                $inputs.= $form->Input("text", "emissor", "col-md-3", $cheque_dados["emissor"], "required", "Emissor");
                $inputs.= $form->Input("number", "valor", "col-md-2", $cheque_dados["valor"], "required step=0.01", "Valor");
                $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Cheque'",NULL,' Status.id ASC',NULL),"descricao",$cheque_dados["id_status"]);
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","list");
            }else{

                $this->view('error_permisao');

            }

    }



    public function incluir(){   

        $this->acesso_restrito();



        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Cheque/incluir/';



        if($acesso->acesso_valida($comando)==true){

            $cheque = new ChequeModel();      

            $id=$cheque->cadastrar_cheque( 

                array(

                    'id_venda'=>$_POST['id_venda'],

                    'banco'=>$_POST['banco'],

                    'agencia'=>$_POST['agencia'],

                    'numero_cheque'=>$_POST['numero_cheque'],

                    'emissor'=>$_POST['emissor'],

                    'valor'=>$_POST['valor'],

                    'baixa'=>date('Y-m-d H:i:s'),

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>date('Y-m-d H:i:s'),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Cheque/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }



    public function alterar(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando='/Cheque/alterar/';



        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $cheque = new ChequeModel();      

            $cheque->alterar_cheque(

                array(

                    'id_venda'=>$_POST['id_venda'],

                    'banco'=>$_POST['banco'],

                    'agencia'=>$_POST['agencia'],

                    'numero_cheque'=>$_POST['numero_cheque'],

                    'emissor'=>$_POST['emissor'],

                    'valor'=>$_POST['valor'],

                    'baixa'=>date('Y-m-d H:i:s'),

                    'id_status'=>$_POST['id_status'],

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Cheque/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }



    public function excluir(){   

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Cheque/excluir/';



        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $cheque = new ChequeModel();      

            $cheque->excluir_cheque( array( 'id_status'=>'99' ),'id='.$id );  



            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Cheque/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

 } ?>