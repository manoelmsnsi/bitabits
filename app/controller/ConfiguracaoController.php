<?php class Configuracao extends Controller {   
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
        $comando='/Configuracao/admin_listar/';
        //if($acesso->acesso_valida($comando)==true){ 
        $filiais=$acesso->acesso_filial("Upload");
        $acesso = new SessionHelper();
        if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();

        $imagens = new UploadModel();
        $listar_imagens = $imagens->listar_Upload($join, $limit, "(tabela='Login' OR tabela='Sistema') AND Upload.id_status<>'99'  AND ({$filiais})", $offset, $orderby, "id,tipo,src,id_status,tabela");
        $listar_menu = $imagens->listar_Upload($join, $limit, "tabela='Menu'", $offset, $orderby, "id,tipo,src,id_status,tabela");

        $logs->cadastrar_logs($comando,'0');//Gera Logs
        $dados['listar_imagens'] = $listar_imagens;           
        $dados['listar_menu'] = $listar_menu;           
        $this->view('listar_configuracao',$dados); 
     //   }else{
     //       $this->view('error_permisao');
     //   }
    } 

    public function form(){ 
        $this->acesso_restrito();

        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
    
        $filial = new FilialModel();
        $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
        $dados['listar_filial'] = $listar_filial; 

        $status = new StatusModel();
        $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99 AND Status.tabela='Geral'",NULL,' Status.id DESC',NULL);
        $dados['listar_status'] = $listar_status;  $contabancaria = new ContaBancariaModel();

        $id = $this->getParams('id');
        $dados['id']=$id;
 
        if(!empty($id)){
            $listar_contabancaria = $contabancaria->listar_contabancaria(NULL,NULL,"id=$id AND id_status<>99",NULL,'ContaBancaria.id DESC');
            $dados['listar_contabancaria'] = $listar_contabancaria;
        } 
        $this->view('form_contabancaria',$dados);
    }

    public function incluir(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/ContaBancaria/incluir/';

        if($acesso->acesso_valida($comando)==true){
            $contabancaria = new ContaBancariaModel();      
            $id=$contabancaria->cadastrar_contabancaria( 
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
            $contabancaria->alterar_contabancaria(
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
        $comando='/Configuracao/excluir/';

        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $upload = new UploadModel();      
            $upload->excluir_Upload( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Configuracao/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
}
?> 