<?php class Usuario extends Controller {   
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
            $usuario = new UsuarioModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
    
            $form = new FormularioHelper("Usuários","col-md-12" ,null,null,"people");     
                echo $form->Listar(
                    "col-md-12", null, "/".__CLASS__."/form/",$icone,$usuario->listar_usuario(
                        "INNER JOIN Status ON Status.id=Usuario.id_status
                            INNER JOIN Filial ON Filial.id = Usuario.id_filial                        
                        ",
                        NULL,
                        "Usuario.id_status<>99 AND ({$filiais})",
                        NULL,
                        'Usuario.usuario DESC',
                        "Usuario.id,
                            Filial.nome_fantasia AS Filial,
                            Usuario.usuario AS Usuário,
                            Usuario.inicio_funcionamento AS 'Início do Funcionamento',
                            Usuario.fim_funcionamento AS 'Fim do Funcionamento',
                            Status.cor AS cor_Status,
                            Status.Descricao AS Status
                            ",null,$pesquisa),"tabela1",
                    array(
                        array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),
                        array("acao"=>"/Upload/form/tabela/Usuario/","classe"=>"btn-sm btn-rose","icone"=>"cloud_upload"),
                        array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")       )
                );
        }else{
            $this->view('error_permisao');
        }    
    }
/*      ANTIGO LISTAR
    public function admin_listar(){
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Usuario/admin_listar/';

        if($acesso->acesso_valida($comando)==true){ 
            $filiais=$acesso->acesso_filial("Usuario");
            $acesso = new SessionHelper();
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
               echo $menu->Menu($acesso->selectSession('userAcesso'));

            $usuario = new UsuarioModel();
            $listar_usuario = $usuario->listar_usuario(NULL,NULL,"id_status<>99 AND ({$filiais})",NULL,' Usuario.usuario DESC',NULL);
            $logs->cadastrar_logs($comando,'0');//Gera Logs
            $dados['listar_usuario'] = $listar_usuario;           
            $this->view('listar_usuario',$dados); 
        }else{
            $this->view('error_permisao');
        }
    }*/
/*      ANTIGO FORM
    public function form(){ 
        $this->acesso_restrito();
 
        $acesso = new SessionHelper();
        $listar_acesso=$acesso->selectSession('userAcesso');
        $user_dados=$acesso->selectSession('userData');
        $dados['listar_acesso']=$listar_acesso;
        $dados['user_dados']=$user_dados;

        $filial = new FilialModel();
        $listar_filial = $filial->listar_filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
        $dados['listar_filial'] = $listar_filial; 
 
        $pessoa = new PessoaModel();
        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='colaborador'",NULL,' Pessoa.id DESC',NULL);
        $dados['listar_colaborador'] = $listar_pessoa; 
 
        $status = new StatusModel();
        $listar_status = $status->listar_status(NULL,NULL,"id_status<>'99' AND (tabela='Geral' OR tabela='Usuario')",NULL,' Status.id ASC',NULL);
        $dados['listar_status'] = $listar_status;  $usuario = new UsuarioModel();
    
        $id = $this->getParams('id');
        $dados['id']=$id;
 
        if(!empty($id)){
            $acessos = new AcessoModel();
            $listar_acessos2 = $acessos->listar_acesso(
                "INNER JOIN Usuario ON Usuario.id = Acesso.id_usuario
                    INNER JOIN Programa ON Programa.id = Acesso.id_programa
                    INNER JOIN Grupo ON Grupo.id = Acesso.id_grupo
                    INNER JOIN Status ON Status.id = Acesso.id_status",
                NULL,
                "Acesso.id_status<>99",
                NULL,
                'Usuario.id DESC',
                "Usuario.usuario,
                    Grupo.descricao AS grupo_descricao,
                    Programa.descricao AS programa_descricao,
                    Status.descricao AS status_descricao,
                    Acesso.data_lancamento"
            );
        $dados['listar_acesso2'] = $listar_acessos2;
        $listar_usuario = $usuario->listar_usuario(NULL,NULL,"id=$id AND id_status<>99",NULL,'Usuario.id DESC',NULL);
        $dados['listar_usuario'] = $listar_usuario;
        } 
        $this->view('form_usuario',$dados);
    }*/

    public function form(){ 
        $this->acesso_restrito();

        $acesso = new AcessoHelper();         
        $comando="/".__CLASS__."/incluir/";  

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $acesso = new SessionHelper();
            $status= new StatusModel();
            $filial = new FilialModel();
            $pessoa = new PessoaModel();
            $usuario = new UsuarioModel(); 

            $id=$this->getParams("id");     

            $nome_form="Cadastra Usuario";

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            if(!empty($id)){
                $usuario_dados=$usuario->listar_usuario($join, "1", "id='$id'", $offset, $orderby);
                $usuario_dados= $usuario_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Usuario";
            }            
            echo $menu->Menu();

            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");
                echo $form->Input("hidden", "id", $Classe, $id);
                echo $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$usuario_dados["id_filial"]);
                echo $form->select("Colaborador","id_colaborador", "col-md-2",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Colaborador'", $offset, $orderby, $from),"nome",$usuario_dados["id_colaborador"]);
                echo $form->Input("text", "usuario", "col-md-3", $usuario_dados["usuario"], $Required, "Usuario", $disable);
                echo $form->Input("password", "senha", "col-md-3", $usuario_dados["senha"], $Required,"Senha", $disable);
                echo $form->Input("time", "inicio_funcionamento", "col-md-1", $usuario_dados["inicio_funcionamento"], $Required, "Abrir", $disable);
                echo $form->Input("time", "fim_funcionamento", "col-md-1", $usuario_dados["fim_funcionamento"], $Required,"Fechar", $disable);
                echo $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao,id",$usuario_dados["id_status"]);
                echo $form->Button("btn btn-md btn-rose ","Salvar");
        }else{
           $this->view('error_permisao');
        }   
    }

    public function incluir(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Usuario/incluir/';

        if($acesso->acesso_valida($comando)==true){
            $senha = md5($_POST['senha']);
            $usuario = new UsuarioModel();  
            $in=$_POST['inicio_funcionamento'];
            $fi=$_POST['fim_funcionamento'];
            if(empty($in)){ $in='07:00:00'; }            
            if(empty($fi)){$fi='18:00:00'; }
            
            $id=$usuario->cadastrar_usuario( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_colaborador'=>$_POST['id_colaborador'],
                    'inicio_funcionamento'=>$in,
                    'fim_funcionamento'=>$fi,
                    'usuario'=>$_POST['usuario'],
                    'senha'=>$senha,
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Usuario/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Usuario/alterar/';

        if($acesso->acesso_valida($comando)==true){
            $in=$_POST['inicio_funcionamento'];
            $fi=$_POST['fim_funcionamento'];
            $id = $_POST['id'];

            if(strlen($_POST['senha'])==32){
                $senha = ($_POST['senha']);
            }else{
                $senha = md5($_POST['senha']);
            }

            if(empty($in)){ $in='07:00:00'; }
            if(empty($fi)){ $fi='18:00:00'; }

            $usuario = new UsuarioModel();      
            $usuario->alterar_usuario(
                array(             
                    'id_filial'=>$_POST['id_filial'],
                    'id_colaborador'=>$_POST['id_colaborador'],
                    'inicio_funcionamento'=>$in,
                    'fim_funcionamento'=>$fi,
                    'usuario'=>$_POST['usuario'],
                    'senha'=>$senha,
                    'id_status'=>$_POST['id_status'],
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Usuario/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }
        
    public function excluir(){    
        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Usuario/excluir/';

        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $usuario = new UsuarioModel();      
            $usuario->excluir_usuario( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Usuario/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
} ?> 