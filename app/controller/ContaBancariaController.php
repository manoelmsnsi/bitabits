<?php
class ContaBancaria extends Controller {   
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
            $conta_bancaria = new ContaBancariaModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            

            $form = new FormularioHelper();     
                $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$conta_bancaria->listar_ContaBancaria(
                    "INNER JOIN Filial ON Filial.id=ContaBancaria.id_filial
                        INNER JOIN Status ON Status.id=ContaBancaria.id_status",
                    NULL,
                    "ContaBancaria.id_status<>99 AND ({$filiais})",
                    NULL,
                    'ContaBancaria.id DESC',
                    "ContaBancaria.id,ContaBancaria.id AS caixa,
                        Filial.nome_fantasia AS Filial,
                        ContaBancaria.descricao AS Descricao,
                        ContaBancaria.banco AS Banco,
                        ContaBancaria.conta AS Conta,
                        ContaBancaria.saldo AS Saldo,
                        Status.cor AS cor_Status,
                        Status.Descricao AS Status",
                    NULL,$pesquisa
                ),"tabela1",
                array(
                    array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),
                    array("acao"=>"/Venda/Pdv/id/0/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),
                    array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")
                )
            );
            $form->card("Cadastrar Conta/Caixa",$inputs,"col-md-12",$comando,"POST","account_balance_wallet");
            }else{
            $this->view('error_permisao');
        }    
    }
    public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/ContaBancaria/admin_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $contabancaria = new ContaBancariaModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits - Conta Bancária", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");     
            if(!empty($id)){
                $contabancaria_dados = $contabancaria->listar_ContaBancaria($join, "1", "id=$id", $offset, $orderby,$from);
                $contabancaria_dados = $contabancaria_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
            }            

            $form = new FormularioHelper();
                $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
                $inputs.= $form->select("Filial","id_filial","col-md-2",$filial->listar_Filial(NULL,NULL,"id_status <> 99",NULL,'Filial.id DESC',NULL),"nome_fantasia",$contabancaria_dados["id_filial"]);
                $inputs.= $form->Input("text","descricao","col-md-3",$contabancaria_dados["descricao"],"required","Descrição");
                $inputs.= $form->Input("text","banco","col-md-2",$contabancaria_dados["banco"],"required","Banco");
                $inputs.= $form->Input("text","conta","col-md-2",$contabancaria_dados["conta"],"required","Conta");
                $inputs.= $form->Input("text","saldo","col-md-2",$contabancaria_dados["saldo"],"required","Saldo");
                $inputs.= $form->select("Status","id_status", "col-md-1", $status->listar_Status(NULL,NULL,"Status.id_status<>99 AND tabela='Geral'",NULL,'Status.id ASC',NULL),"descricao",$contabancaria_dados["id_status"]);
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                $form->card("Cadastrar Conta Bancária",$inputs,"col-md-12",$comando,"POST","account_balance_wallet");
            }else{
                $this->view('error_permisao');
            }
    }

    public function incluir(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/ContaBancaria/incluir/';

        if($acesso->acesso_valida($comando)==true){
            $contabancaria = new ContaBancariaModel();      
            $id=$contabancaria->cadastrar_ContaBancaria( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'banco'=>$_POST['banco'],
                    'conta'=>$_POST['conta'],
                    'saldo'=>$_POST['saldo'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ContaBancaria/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/ContaBancaria/alterar/';

        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $contabancaria = new ContaBancariaModel();      
            $contabancaria->alterar_ContaBancaria(
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'banco'=>$_POST['banco'],
                    'conta'=>$_POST['conta'],
                    'saldo'=>$_POST['saldo'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ContaBancaria/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function excluir(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/ContaBancaria/excluir/';

        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $contabancaria = new ContaBancariaModel();      
            $contabancaria->excluir_ContaBancaria( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ContaBancaria/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
}
?>