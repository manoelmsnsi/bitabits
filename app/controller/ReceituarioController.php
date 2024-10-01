<?php 

class Receituario extends Controller {   

    private  $auth,$db;

    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 

    }     


    
public function form(){ 
       $this->acesso_restrito();
        $acesso = new AcessoHelper();         
        $comando="/".__CLASS__."/incluir/";  
    //    if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $status= new StatusModel();
            $filial = new FilialModel();         
            $pessoa = new PessoaModel();
            $receituario = new ReceituarioModel();
            $acesso = new SessionHelper();
            $id=$this->getParams("id");     
            $nome_form="Cadastrar Receituario";  
            if(!empty($id)){
                $receituario_dados =$receituario->listar_Receituario($join, "1", "id='$id'", $offset, $orderby);
                $receituario_dados = $receituario_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Receituario";
            }             
            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio);      
            echo $menu->Menu();
            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");
            $inputs.= $form->Input("hidden", "id", $Classe, $id);
            $inputs.= $form->Input("hidden", "id_cliente", $Classe,  $this->getParams("id_cliente"));
            //echo $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$atestado_dados["filial"]);
            $inputs.= $form->select("Colaborador","id_colaborador", "col-md-5",$pessoa->listar_Pessoa(NULL,NULL,"Pessoa.id_status<>99 AND Pessoa.tipo='Colaborador'",NULL,' Pessoa.id DESC',"Pessoa.nome,Pessoa.id"),"nome",$receituario_dados["id_colaborador"]);
            $inputs.= $form->Input("date", "data_receita", "col-md-2", $receituario_dados["data_receita"], $Required, "Data", $disable);
            $inputs.= $form->select("Status","id_status", "col-md-5", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Text("text", "texto", "col-md-12", $receituario_dados["texto"], $Required, "Atestado", $disable);
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","people");
            
    //  }else{
    //      $this->view('error_permisao');
    //    }   
    }


 public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Receituario/incluir/';

       // if($acesso->acesso_valida($comando)==true){
       $id_cliente=$_POST['id_cliente'];
            $receituario = new ReceituarioModel();
            $id=$receituario->cadastrar_Receituario( 
                array(

                    'id_cliente'=>$_POST['id_cliente'],
                    'id_colaborador'=>$_POST['id_colaborador'],
                    'texto'=>$_POST['texto'],
                    'data_receita'=>$_POST['data_receita'],                    
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                    'id_status'=>  "1",
                )
            ); 
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
           $redirect->goToUrl("/Pessoa/visualizar/tabela/Cliente/id/$id_cliente");    
     //   }else{
      //      $this->view('error_permisao');
     //   }

    }

// public function alterar(){    
//        $this->acesso_restrito();
//        $acesso = new AcessoHelper(); 
//        $logs = new LogsModel();
//        $comando='/Receituario/alterar/';
//        
//        if($acesso->acesso_valida($comando)==true){
//            $id = $_POST['id'];
//            
//            $modelo = new ReceituarioModel();      
//            $modelo->alterar_modelo(
//                array(
//                        'id_filial'=>$_POST['id_filial'],
//                        'descricao'=>$_POST['descricao'],
//                        'id_status'=>$_POST['id_status'],
//                       'data_lancamento'=>  date("Y-m-d H:i:s"),
//                ),'id='.$id
//            );  
//            $logs->cadastrar_logs($comando,$id);//Gera Logs
//            $redirect = new RedirectHelper();
//            $redirect->goToUrl('/Receituario/admin_listar/');    
//        }else{
//
//            $this->view('error_permisao');
//        }
//    }

 public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Receituario/excluir/';

        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $modelo = new ReceituarioModel();      
            $modelo->excluir_modelo( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Receituario/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 