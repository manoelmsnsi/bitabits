<?php class Contrato extends Controller {   
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
            $contrato = new ContratoModel();           
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     
            $inputs.= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$contrato->listar_Contrato("INNER JOIN Pessoa ON Pessoa.id=Contrato.id_cliente INNER JOIN Status ON Status.id = Contrato.id_status",NULL,"Contrato.id_status<>99",NULL,' Contrato.id DESC',"Contrato.id,nome,cpf,Contrato.data_vencimento,Contrato.valor_total,Contrato.tipo,Contrato.parcela,Contrato.id_status,Status.cor AS cor_Status,Status.Descricao AS Status",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card("Construtor",$inputs,"col-md-12",$comando,"POST","donut_small");
        }else{
            $this->view('error_permisao');
        }    
    }
    
    public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();         
        $comando="/".__CLASS__."/incluir/";  

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $status= new StatusModel();
            $filial = new FilialModel();
            $contrato = new ContratoModel();
            $pessoa = new PessoaModel();
            $id=$this->getParams("id");     
            $nome_form="Cadastrar Contrato";  
            if(!empty($id)){
                $contrato_dados =$contrato->listar_Contrato($join, "1", "id='$id'", $offset, $orderby);
                $contrato_dados = $contrato_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Contrato";
            }             

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $form = new FormularioHelper($nome_form, $Class, $comando, "POST", "people", "false");
            $inputs.= $form->Input("hidden", "id", $Classe, $id);
            $inputs.= $form->Input("date", "data_vencimento", "col-md-2", $contrato_dados["data_vencimento"], $Required, "Data Vencimento", $disable);
            $inputs.= $form->Input("text", "valor_total", "col-md-2", $contrato_dados["valor_total"], $Required, "Valor Total", $disable);
            $inputs.= $form->Input("text", "tipo", "col-md-2", $contrato_dados["tipo"], $Required, "Tipo", $disable);
            $inputs.= $form->Input("text", "valor_parcela", "col-md-2", $contrato_dados["valor_parcela"], $Required, "Valor Parcela", $disable);
            $inputs.= $form->Input("text", "parcela", "col-md-2", $contrato_dados["parcela"], $Required, "Parcela", $disable);
            $inputs.= $form->select("Cliente", "id_cliente", "col-md-2", $pessoa->listar_Pessoa(NULL, NULL, "id_status<>99 ", NULL, ' Pessoa.id ASC',null), "nome",$contrato_dados["id_cliente"]);
            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao",$contrato_dados["id_status"]);
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","donut_small");                      
        }else{
           $this->view('error_permisao');
        }   
    }

    public function incluir(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Contrato/incluir/';
           if($acesso->acesso_valida($comando)==true){
               $contrato = new ContratoModel();      
               $id=$contrato->cadastrar_contrato( 
                   array(
                        'data_vencimento'=>$_POST['data_vencimento'],
                        'valor_total'=>$_POST['valor_total'],
                        'tipo'=>$_POST['tipo'],
                        'valor_parcela'=>$_POST['valor_parcela'],
                        'parcela'=>$_POST['parcela'],
                        'id_cliente'=>$_POST['id_cliente'],
                        'id_status'=>$_POST['id_status'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                   )
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Contrato/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }
       }
    public function alterar(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Contrato/alterar/';
           if($acesso->acesso_valida($comando)==true){
               $id = $_POST['id'];
               $contrato = new ContratoModel();      
               $contrato->alterar_contrato(
                   array(
                        'data_vencimento'=>$_POST['data_vencimento'],
                        'valor_total'=>$_POST['valor_total'],
                        'tipo'=>$_POST['tipo'],
                        'valor_parcela'=>$_POST['valor_parcela'],
                        'parcela'=>$_POST['parcela'],
                        'id_cliente'=>$_POST['id_cliente'],
                        'id_status'=>$_POST['id_status'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                   ),'id='.$id
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Contrato/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }
       }
    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Contrato/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $contrato = new ContratoModel();      
            $contrato->excluir_contrato( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Contrato/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 