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
            $form = new FormularioHelper();     
                $inputs= $form->Listar(
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
           $form->card("Usuarios",$inputs,"col-md-12",$comando,"POST","people");
        }else{
            $this->view('error_permisao');
        }    
    }

    public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();         
       $comando="/".__CLASS__."/incluir/";  
     //  if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $acesso = new SessionHelper();
            $status= new StatusModel();
            $filial = new FilialModel();
            $pessoa = new PessoaModel();
            $usuario = new UsuarioModel(); 
            $userData=$acesso->selectSession("userData");
            if($userData["administrador"]=="SIM"){ $id= $this->getParams("id"); }else{ $id=$userData["id"]; } 
                 
            $nome_form="Cadastra Usuario";
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            if(!empty($id)){
                $usuario_dados=$usuario->listar_usuario($join, "1", "id='$id'", $offset, $orderby);
                $usuario_dados= $usuario_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Usuario";
                $upload = new UploadModel();
                 $listar_upload = $upload->listar_upload ( NULL,NULL,"id_status<>'99'AND id_tabela={$id} AND Tabela='Usuario'",NULL,NULL,"descricao,id,src,id AS id_tabela");
                $grupo = new GrupoModel();
                $usuarios_acesso=$usuario->listar_usuario("  INNER JOIN Acesso ON Acesso.id_usuario=Usuario.id
                                                            INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Acesso.id_grupo 
                                                     ", null, "Acesso.id_usuario='$id'", $offset, $orderby, "Usuario.usuario,GrupoAcesso.id AS id_grupo", "Usuario.id", $pesquisa);
                                                                    
                $grupos_acesso=$grupo->listar_Grupo("INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Grupo.id 
                                                     INNER JOIN Programa ON Programa.id = GrupoAcesso.id_programa", null, "GrupoAcesso.id='".$usuarios_acesso[0]['id_grupo']."' AND GrupoAcesso.id_status<>'99'", $offset, $orderby, "GrupoAcesso.id,Grupo.descricao AS Grupo", $group, $pesquisa);
                $grupos_filiais=$grupo->listar_Grupo("INNER JOIN GrupoFilial ON GrupoFilial.id_grupo=Grupo.id 
                                                     INNER JOIN VinculaFilial ON VinculaFilial.id_grupo = Grupo.id ", null, "VinculaFilial.id_usuario='$id' AND GrupoFilial.id_status<>'99' AND Grupo.tabela='Filial'", $offset, $orderby, "Grupo.id,Grupo.descricao AS Grupo","Grupo.id", $pesquisa);

            }            
            echo $menu->Menu();
            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");          
              $inputs .= $form->Input("hidden", "id", $Classe, $id);
              $inputs .= $form->select("Filial","id_filial", " col-md-3", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$usuario_dados["id_filial"]);
              $inputs .= $form->select("Colaborador","id_colaborador", " col-md-3",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Colaborador'", $offset, $orderby, $from),"nome",$usuario_dados["id_colaborador"]);
              $inputs .= $form->Input("text", "usuario", " col-md-3", $usuario_dados["usuario"], $Required, "Usuario", $disable);
              $inputs .= $form->Input("password", "senha", "col-md-3", $usuario_dados["senha"], $Required,"Senha", $disable);
              $inputs .=$form->Input("time", "inicio_funcionamento", "col-md-3", $usuario_dados["inicio_funcionamento"], $Required, "Abrir", $disable);
              $inputs .= $form->Input("time", "fim_funcionamento", "col-md-3", $usuario_dados["fim_funcionamento"], $Required,"Fechar", $disable);
              $inputs .= $form->select("Administrador","administrador", " col-md-2",array(array("id"=>"SIM"),array("id"=>"NAO")),"id",$usuario_dados["administrador"]);
              $inputs .= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao,id",$usuario_dados["id_status"]);
              $inputs .=  $form->Button("btn btn-md btn-rose ","Salvar");           
              $Galeria= $form->Listar("col-md-12", null, "/Upload/form/id_tabela/$id/tabela/Usuario/id_filial/1/", $icone,$listar_upload, "tabela3", array(array("acao"=>"/Upload/form/id/$id/tabela/Usuario/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/tabela/Upload/classe/Upload/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")));
              $Acessos1= $form->Listar("col-md-12", null, "/GrupoAcesso/admin_listar/", $icone,$grupos_acesso, "tabela1", array(array("acao"=>"/GrupoAcesso/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao" => "/GrupoAcesso/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
              
              $Filiais= $form->Listar("col-md-12", null, "/GrupoFilial/admin_listar", $icone,$grupos_filiais, "tabela2", array(array("acao"=>"/GrupoFilial/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao" => "/GrupoFilial/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
                          
            $inputs .= $form->Abas($Tipo, "Pefil", "col-md-12", 
                array(array("id" => "Acessos", "icone" => "lock_open", "descricao" => "Acessos"),array("id"=>"Filiais","icone"=>"school","descricao"=>"Filiais"),
                 array("id" => "Galeria", "icone" => "insert_photo", "descricao" => "Galeria")),
                array(array("id" => "Acessos", "dados" => "$Acessos1","classe" => " active"),
                array("id" => "Galeria", "dados" => "$Galeria"),array("id"=>"Filiais", "dados"=>"$Filiais")));            
            $form->card("Usuarios",$inputs,"col-md-12",$comando,"POST","people");
   //     }else{

     //      $this->view('error_permisao');

      //  }   

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
                    'administrador'=>$_POST['administrador'],
                    'usuario'=>$_POST['usuario'],
                    'senha'=>$senha,
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Usuario/form/id/$id/");    
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
     'administrador'=>$_POST['administrador'],
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