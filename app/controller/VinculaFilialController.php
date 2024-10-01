<?php
  class VinculaFilial extends Controller {   
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
            $filiais=$acesso->acesso_filial("VinculaFilial");
            $status= new StatusModel();
            $filial = new FilialModel();           
            $acesso = new SessionHelper();           
            $vincula_filial = new VinculaFilialModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");   
            $form = new FormularioHelper();     
                $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$vincula_filial->listar_VinculaFilial(
                    "INNER JOIN Usuario ON Usuario.id = VinculaFilial.id_usuario
                     INNER JOIN Grupo ON Grupo.id = VinculaFilial.id_grupo
                     INNER JOIN Filial ON Filial.id = VinculaFilial.id_filial   
                    ",
                    NULL,
                    "VinculaFilial.id_status<>99 AND ({$filiais})",
                    NULL,
                    'VinculaFilial.id DESC',
                    "VinculaFilial.id,
                        Grupo.descricao AS Grupo,
                        Filial.nome_fantasia AS Filial,
                        Usuario.usuario AS Usuario"
                    ),
                    "tabela1",
                    array(
                        array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),
                        array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
          $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","list");
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
            $grupo = new GrupoModel();
            $usuario = new UsuarioModel(); 
            $acessos = new VinculaFilialModel();
            $id=$this->getParams("id");     
            $listar_acesso=$acesso->selectSession('userAcesso');
            $user_dados=$acesso->selectSession('userData');
            $dados['listar_acesso']=$listar_acesso;
            $dados['user_dados']=$user_dados;
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            if(!empty($id)){
              $acessos_dados=$acessos->listar_VinculaFilial($join, "1", "id='$id'", $offset, $orderby);
                $acessos_dados= $acessos_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
            }            
            echo $menu->Menu();
            $form = new FormularioHelper();
                $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");
                $inputs.= $form->select("Grupo","id_grupo", "col-md-2",$grupo->listar_Grupo($join, $limit, "id_status<>'99' And tabela='Filial'", $offset, $orderby, $from),"descricao");
                $inputs.= $form->select("Usuario","id_usuario", "col-md-5",$usuario->listar_usuario(NULL,NULL,"id_status<>99 ",NULL,' id ASC',NULL),"usuario");
                $inputs.= $form->select("Status","id_status", "col-md-3", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card("Cadastro Vincula/Filial",$inputs,"col-md-12",$comando,"POST","list");
            
        }else{
           $this->view('error_permisao');
        }   
    }



    public function incluir(){  
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/VinculaFilial/incluir/';   
        if($acesso->acesso_valida($comando)==true){
            $acesso = new VinculaFilialModel();      
            $id=$acesso->cadastrar_VinculaFilial( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_usuario'=>$_POST['id_usuario'],
                    'id_grupo'=>$_POST['id_grupo'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/VinculaFilial/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/VinculaFilial/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $acesso = new VinculaFilialModel();      
            $acesso->alterar_Vincula_Filial(
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_usuario'=>$_POST['id_usuario'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/VinculaFilial/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/VinculaFilial/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            if(empty($id)){ $id = $_POST["id"]; }
            
            $acesso = new VinculaFilialModel();      
            $acesso->excluir_VinculaFilial( array( 'id_status'=>'99' ),"id=$id "); 
           // echo $id;
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/VinculaFilial/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 

}?>  