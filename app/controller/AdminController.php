<?php
class Admin extends Controller {
  private  $auth, $db;
  public function acesso_restrito() {
   $this->auth = new AutenticaHelper();
    $this->auth->setLoginControllerAction("Index", "")
    ->checkLogin("redirect");
    $this->db = new AdminModel();
  }

public function index_action() {
    //  $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
   // echo $menu->Menu();
    $redirect = new RedirectHelper();
    $redirect->goToControllerAction("Dash", "doutores");
  
    $this->backup_automatico(); 
}

  public function comando() { 
    $this->acesso_restrito();
     $logs = new LogsModel();
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    $model = new Model();
     $sql = $this->getParams("sql");
     if(!empty($_POST["comando"])){ $sql = $_POST["comando"]; }
    
    $form = new FormularioHelper(null, "col-md-12", "", "POST", null, "true");
     $inputs1.= '<textarea name="comando" class=" col-md-12" rows="10" cols="50">'. $sql .'</textarea>';
   $inputs1.= $form->Button("btn btn-md btn-rose col-md-12", "Executar");
    $form->card("SQL QUERY",$inputs1,"col-md-12","/Admin/comando/","POST","list");
    if (!empty($sql)) { 
       $comando = $model->comando($sql);
       $logs->cadastrar_logs("SQL", "0",$sql);
    };       
    $inputs.= $form->ListarId("col-md-12", "Resultado", $action, $icone, $comando, "tabela",  array(array("acao"=>"/Admin/form_comando/sql/$sql/","classe"=>"btn-sm btn-warning","icone"=>"edit")), $pesquisa);
    $form->card("RESULT QUERY",$inputs,"col-md-12","#","POST","list");
  }
   public function form_comando() { 
    $this->acesso_restrito();
     $logs = new LogsModel();
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    $model = new Model();
    $sql = $this->getParams("sql");
    $id = $this->getParams("id");
    $form = new FormularioHelper();
 
        if(empty(strpos($sql, 'FROM '))){ $tabela = strpos($sql, 'from '); }else{$tabela=strpos($sql, 'FROM '); }
        $tabela=( explode(" ", substr($sql, $tabela)));
        $tabela= $tabela[1];
        $inputs_db = $model->comando("SELECT * FROM $tabela WHERE id='$id'");
        $inputs_db=$inputs_db[0];
        $ex= $this->getParams("ex");
        //   $logs->cadastrar_logs("SQL", "0",$sql);    
        foreach ($inputs_db AS $chave=>$valor):
            $dados[$chave]="null";
            $inputs.=$form->Input($Tipo,$chave, "col-md-3",$valor, $Required, $chave, $disable, $id);       
        endforeach;
        
        //$form->card("EDIT $tabela ID: $id",$inputs,"col-md-12","/Admin/form_comando/sql/$sql/id/$id/ex/ex","POST","edit");

        if(!empty($ex)){
            
           $id=$_POST["id"];
            foreach ($dados AS $key=>$dd):
                $d=$_POST["$key"];
                $dados2[$key]= $d;
            endforeach;
 
            $model->_tabela=$tabela;
            $model->update($dados2, "id=$id");
            $inputs.= $form->Button("btn btn-md btn-info col-md-12", "Atualizar Tela"); 
            $form->card("EDIT $tabela ID: $id",$inputs,"col-md-12","/Admin/form_comando/sql/$sql/id/$id/","POST","edit");
        }else{
            $inputs.= $form->Button("btn btn-md btn-rose col-md-12", "Salvar"); 
            $form->card("EDIT $tabela ID: $id",$inputs,"col-md-12","/Admin/form_comando/sql/$sql/id/$id/ex/ex","POST","edit");
        }       
   
  }

  public function login() {
    $this->auth = new AutenticaHelper();
    if (!empty($this->getParams("acao"))) {
      $this->auth->setTableName("Usuario")
      ->setUserCollumn("usuario")
     ->setPassColumn("senha")
      ->setUser($_POST["usuario"])
      ->setPass($_POST["senha"])
      ->setLoginControllerAction("Admin", "Index")
      ->login();
    } else {
     $this->view("form_login"); 
    }
  }

  public function recuperar_senha() {
      
    $this->view("form_recuperar_senha");
  }
  public function localiza() {
   echo"ok";
    $this->view("LocalizacaoHelper");
  }

  public function senha_email() {

    $email= new EmailHelper();
      
    $usuario = new UsuarioModel();
    $user_usuario = $_POST["usuario"];
    $user_email = $_POST["email"];
    $user_cpf = $_POST["cpf"];
    $listar_usuario = $usuario->listar_usuario(
      "INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Usuario.id_colaborador
                INNER JOIN Contato ON Contato.id_tabela=Colaborador.id",
      "1",
      "Usuario.usuario='{$user_usuario}' AND Usuario.id_status='1'AND Colaborador.cpf='{$user_cpf}'AND Contato.descricao='E-mail'AND Contato.contato='{$user_email}'",
      $offset,
      $orderby,
      "Usuario.id, Colaborador.nome,Colaborador.cpf,Contato.contato,Usuario.usuario, Usuario.senha"
    );
       
    $nova_senha = md5($listar_usuario[0]["senha"]);
    if (count($listar_usuario) > 0) {
      $usuario->alterar_usuario(array(
        "senha" => $nova_senha,
      ), 'id='.$listar_usuario[0]["id"]);
//      echo $listar_usuario[0]["id"].$listar_usuario[0]["nome"].$listar_usuario[0]["contato"]."<br>".$listar_usuario[0]["senha"]."<br>".$nova_senha."<br>";
    $email->enviar($listar_usuario[0]["contato"], "suporte@bitabits.com.br", "Bit a Bits", "Recuperar Senha", "Ola <b>{$listar_usuario[0]["nome"]}</b>, sua senha foi redefinida, Usuario: <b>".$listar_usuario[0]["usuario"]."</b>, sua nova senha e! <b>". $listar_usuario[0]["senha"]."</b>, esta senha so sera valida por 1 acesso, apos acessar o sistema favor alterar.",null,null,"Enviado COm Sucesso!");
  //  $email->enviar("manoelmsnsi@gmail.com", "suporte@bitabits.com.br", "Bit a Bits", "Recuperar Senha", "Ola Manoel Messias, sua senha foi redefinida, voce recebera um email em breve com sua nova senha e! SENHA, esta senha so sera valida por 1 acesso, apos acessar o sistema favor alterar","../../backup/30-11-2020 11:11:54 db-backup-db_develop.sql","backup.sql");

  
    $redirect = new RedirectHelper();
      $redirect->goToControllerAction("Index", "site");
   } else {
      $this->view("form_recuperar_senha");
    }
  }

  public function bloquear() {
    $this->acesso_restrito();
    $acesso = new SessionHelper();
    $listar_acesso = $acesso->selectSession("userAcesso");
    $user_dados = $acesso->selectSession("userData");
    $dados["listar_acesso"] = $listar_acesso;
    $dados["user_dados"] = $user_dados;
    $this->view("form_bloquear", $dados);
  }

  public function desbloquear() {
    $this->acesso_restrito();
    if (!empty($this->getParams("acao"))) {
      $this->auth->setTableName("Usuario")
      ->setUserCollumn("usuario")
      ->setPassColumn("senha")
      ->setUser($_POST["usuario"])
      ->setPass($_POST["senha"])
      ->setLoginControllerAction("Admin", "index")
      ->login();
    }
    $this->view("form_admin");
  }

  public function backup() {
    $this->acesso_restrito();
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/Admin/backup/";
    if ($acesso->acesso_valida($comando) == true) {
        $backup = new BackupHelper();
        $model = new Model();  
        if(!empty($_POST["BACKUP"])){           
            $backup->backup($model->host, $model->host_nome, $model->host_pass,$model->host_nome, '*', '../../backup/');
            $logs->cadastrar_logs($comando, "0");
        }

        $form = new FormularioHelper();

       $inputs.= '  <div class=" col-md-12  ">
                            <div class="modal-header">
                              <h5 class="modal-title" id="myModalLabel"></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">close</i>
                              </button>
                            </div>
                            <div class="modal-body">
                             ';
                                $path = "../../backup/"; 
                                 $diretorio = dir($path);     
                                 $controle=1;
                                 while($arquivo = $diretorio -> read()){ 
                                                                        
                                        if($controle>2){ $inputs.=  '<div class="instruction">
                                                <div class="row">
                                                  <div class="col-md-8">
                                                    <strong>'.substr($arquivo,-14).'</strong>
                                                    <p class="description">Entre em Contato Com Suporte para Restaurar 
                                                      <a href='.$path.$arquivo.'>'.$arquivo.'</a>  .</p>
                                                  </div>
                                                  <div class="col-md-4">
                                                    <div class="picture">
                                                      Dia: '.substr($arquivo, 0,10).'
                                                    </div>
                                                  </div>
                                                </div>
                                        </div>';}
                                  $controle++; 
                                 }
                                $diretorio -> close(); 
                               
                        $inputs.= '
                                                       
                            </div>
                             <p class="description"><a href="">Em Caso de Problema entre em contato com o ADMINISTRADOR</a></p>
                            <div class="modal-footer justify-content-center">'
                           .  $form->Input("submit", "BACKUP", "btn btn-rose btn-sm", "BACKUP", $Required, $Label, $disable, $id).                               
                          '  </div>
                          </div>
                        </div>
                     
                    
                    
                  
                       
         ';
      $form->card("Gerenciamento de Backup",$inputs,"col-md-12","#","POST","list");
    } else {
        $this->view("error_permisao");
    }
   
    }
    
  public function backup_automatico() {
    
        $this->acesso_restrito();   
        $logs = new LogsModel();
        $backup = new BackupHelper();
        $email = new EmailHelper();
        $model = new Model();  
        $path = "../../backup/".date("Y")."/".date("m")."/";
        
        if(!is_dir($path)){
            mkdir($path);
        }
        
        $diretorio = dir($path);     
        $controle=1;
        while($arquivo = $diretorio -> read()){                                      
            $arrayArquivos[] = $path.str_replace(".", "", substr($arquivo, 0,-4));    
            
            echo $path.str_replace(".", "", substr($arquivo,0, -4))."<br>"; 
            $controle++;        
        }
        $diretorio -> close(); 
        ksort($arrayArquivos, SORT_STRING);
        $v=$path.date("d")." db-backup-".$model->host_nome;
       // print_r($arrayArquivos); 
        echo "<br>".$v."<br>";
      //  echo $path;
        if(in_array($v, $arrayArquivos)){
       
        }else{
            $arq=$backup->backup($model->host, $model->host_nome, $model->host_pass,$model->host_nome, '*', $path);
            $email->enviar("suporte@bitabits.com.br", "suporte@bitabits.com.br", "BACKUP", "BackUp - $model->host_nome", "BackUP - Sistema", "$arq", "backup.sql","ENVIADO COM SUCESSO!!!");
            $logs->cadastrar_logs($comando, "0"); 
        }  
         
    } 

  public function logout() {
    $this->acesso_restrito();
    $acesso = new SessionHelper();
    $logs = new LogsModel();
    $user_dados = $acesso->selectSession("userData");
    $comando = "/Autenticar/LOGOUT/";
    $logs->cadastrar_logs($comando, $user_dados["id"]);
    $this->auth->setLogoutControllerAction("Admin", "Index")
    ->logout();
  }
}