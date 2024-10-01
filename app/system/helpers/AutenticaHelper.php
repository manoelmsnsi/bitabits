<?php

class AutenticaHelper {
    protected   $sessionHelper,$redirectorHelper, $tableNome, $userCollumn,$passColumn,$statusColumn="id_status",$db,
                $user, $pass, $loginController="Index",$logoutController = "Index", $loginAction="Index",$logoutAction="Index";

   
    public function __construct(){
        $this->db = new Model();
        $this->sessionHelper  = new SessionHelper();
        $this->redirectorHelper  = new RedirectHelper();
        return $this;
    }  

    public function setTableName( $val){
        $this->tableNome = $val;
        return $this;
    }

    public function setUserCollumn( $val){
        $this->userCollumn = $val;
        return $this;
    }

    public function setPassColumn( $val){
        $this->passColumn = $val;
        return $this;

    }

    public function setUser( $val){
        $this->user = $val;
        return $this;
    }

    

    public function setPass( $val){
        $this->pass = $val;
        return $this;
    }

    public function setLoginControllerAction( $controller,$action ){
        $this->loginController = $controller;
        $this->loginAction = $action;
        return $this;
    }

    public function setLogoutControllerAction( $controller,$action ){
        $this->logoutController = $controller;
        $this->logoutAction = $action;
        return $this;
    }

   

    public function login(){ 
        $logs = new LogsModel();
        $comando="/Autenticar/LOGIN/";
           
        //$db = new Model();
        $this->db->_tabela = $this->tableNome;
        $md5_senha = md5($this->pass);
        $where = "Upload.tabela='Usuario' AND ".$this->userCollumn."='".$this->user."' AND ".  $this->passColumn."='".$md5_senha."' AND Usuario.".$this->statusColumn."='1'";
        $sql =  $this->db->read("INNER JOIN Upload ON Upload.id_tabela=Usuario.id",$where,'1',NULL,NULL,"Usuario.id,Usuario.id_filial,Usuario.inicio_funcionamento,Usuario.fim_funcionamento, Usuario.usuario,Usuario.id_colaborador,src,Usuario.administrador",null);
      
        if(count($sql) > 0){
        $id=$sql[0]["id"];
            $this->sessionHelper->createSession("userAuth", true)
                                ->createSession("userData", $sql[0]);   
            $logs->cadastrar_logs($comando,"0");
            
            if(($sql[0]["inicio_funcionamento"]<=date("H:m:s"))AND($sql[0]["fim_funcionamento"]>=date("H:m:s"))){                
                $this->acesso($sql[0]["id"]);            
                $this->filial($sql[0]["id"]);
            }else{
                $redirect = new RedirectHelper(); 
                $redirect->goToUrl("/Errors/error_404/erro/Sistema fora do horario de funcionamento!/");
            }
        }else{
           $redirect = new RedirectHelper(); 
           $redirect->goToUrl("/Errors/error_login/erro/AT001/error2/UP00");  
        }
      // $this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction); 
       return $this;
    }
            

    public function acesso($id){       
      
        $this->db->_tabela = "Acesso";     
        $where ="((id_usuario={$id} AND Acesso.id_status='1') OR (id_usuario={$id} AND tipo='' )AND Acesso.id_status='1' AND Programa.id_status='1')";        
        $sql_0 =       $this->db->read("INNER JOIN Programa ON Programa.id = Acesso.id_programa",$where,NULL,NULL,"Programa.ordem ASC","Acesso.id AS id_acesso,Programa.icone,Programa.cor,Programa.id as id_programa,Programa.tipo,Programa.id_pai,Programa.descricao,Programa.comando,Acesso.id_usuario",null);
   
        $this->db->_tabela = "GrupoAcesso";       
        $where ="((id_usuario={$id} AND GrupoAcesso.id_status='1') OR (id_usuario={$id} AND tipo='' ))AND GrupoAcesso.id_status='1' AND Programa.id_status='1'";
        $sql_1 =       $this->db->read("INNER JOIN Acesso ON GrupoAcesso.id_grupo = Acesso.id_grupo INNER JOIN Programa ON Programa.id = GrupoAcesso.id_programa",$where,NULL,NULL,"Programa.ordem ASC","GrupoAcesso.id AS id_grupo,Programa.icone,Programa.cor,Programa.id as id_programa,Programa.tipo,Programa.id_pai,Programa.descricao,Programa.comando,Acesso.id_usuario",null);
      
        if(count($sql_0) > 0 OR count($sql_1) > 0){ 
            $resut1=array_merge($sql_0,$sql_1);    
         
            $acesso=$this->uniqueSession($resut1, "comando");         
            $this->sessionHelper->createSession("userAcesso", $acesso);  
            
        }else{
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Errors/error_404/erro/Usuario sem acesso!/"); 
        }
        
        return $this;
    }
    
    
         public function uniqueSession($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}
        public function filial($id){   

        $this->db->_tabela = "VinculaFilial";     
        $where ="((id_usuario={$id} AND VinculaFilial.id_status='1') OR (id_usuario={$id}  )AND VinculaFilial.id_status='1' AND Filial.id_status='1')";        
        $sql_2 =       $this->db->read("INNER JOIN Filial ON Filial.id = VinculaFilial.id_filial",$where,NULL,NULL,"Filial.id ASC","Filial.id AS id_filial,Filial.nome_fantasia",null);
   
        $this->db->_tabela = "GrupoFilial";       
        $where ="((VinculaFilial.id_usuario={$id} AND GrupoFilial.id_status='1') OR (VinculaFilial.id_usuario={$id} ))AND GrupoFilial.id_status='1' AND Filial.id_status='1'";
        $sql_3 =       $this->db->read("INNER JOIN VinculaFilial ON GrupoFilial.id_grupo = VinculaFilial.id_grupo INNER JOIN Filial ON Filial.id = GrupoFilial.id_filial",$where,NULL,NULL,"Filial.id ASC","GrupoFilial.id AS id_filial,Filial.nome_fantasia",null);
         $this->db->_tabela = "VinculaFilial";
        $where ="((VinculaFilial.id_usuario={$id} AND GrupoFilial.id_status='1') OR (VinculaFilial.id_usuario={$id} ))AND GrupoFilial.id_status='1' AND Filial.id_status='1' AND Contato.id_status='1'AND Contato.descricao='E-mail'";
        $sql_4 =       $this->db->read("INNER JOIN GrupoFilial ON GrupoFilial.id_grupo = VinculaFilial.id_grupo INNER JOIN Filial ON Filial.id = GrupoFilial.id_filial INNER JOIN Contato ON Contato.id_tabela=VinculaFilial.id_filial",$where,NULL,NULL,"Filial.id ASC","Contato.descricao,Contato.contato",null);
       
        if(count($sql_2) > 0 OR count($sql_3) > 0){
            
            $resut2=array_merge($sql_2,$sql_3);    
 
            $filial= $this->uniqueSession($resut2, "id_filial");   
            $contatoFilial= $this->uniqueSession($sql_4, "contato");   
            $this->sessionHelper->createSession("userFilial", $filial);        
            $this->sessionHelper->createSession("contatoFilial", $contatoFilial);        
            $this->liberacao($filial); 
        }else{  
            $redirect = new RedirectHelper(); 
            $redirect->goToUrl("/Errors/error_404/erro/Usuario não vinculado a uma filial!/");
        }       
        
        return $this;
    }
    
    public function liberacao($filial){ 
        $controle=true;
        $db1 = new ModelLiberacao();
        $this->db->_tabela = "Filial";
        $db1->_tabela = "Pessoa";         
       // print_r($filial);
        foreach ($filial as $id_filial):
            
            $id=$id_filial["id_filial"];        
            $sql_filial=$this->db->read(NULL,"id='$id'",NULL,null,' id DESC',"cnpj");
            $cnpj = $sql_filial[0]["cnpj"];
            $sql_4 = $db1->read("INNER JOIN Contrato ON Pessoa.id = Contrato.id_cliente INNER JOIN Status ON Status.id=Contrato.id_status","Contrato.id_status='1' AND Pessoa.cpf='$cnpj' ",NULL,NULL,' Contrato.id DESC',"(SELECT contato FROM Contato WHERE descricao='E-mail' AND Contato.id_tabela=Pessoa.id limit 1) AS email,Contrato.data_vencimento,Contrato.valor_total,Contrato.valor_parcela,Pessoa.nome,Pessoa.id AS id_cliente,Pessoa.cpf,Status.descricao",null,null);       
            if(count($sql_4) > 0){
            //  echo" ok";
                //print_r($sql_4);
                if($this->sessionHelper->checkSession("serverData")){
                  $this->sessionHelper->addItensSession("serverData", $sql_4);
                }else{
                  $this->sessionHelper->createSession("serverData", $sql_4);
                }              
            }else{
                $controle=false;                
            }            
        endforeach;
        if($controle==true){
           $this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction); 
        }else{
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Errors/error_bloqueio/erro/BQ001/"); 
        }
        
        return $this;     
        
    }
        
   public function  logout(){
        $this->sessionHelper->deleteSession("userAuth")
                            ->deleteSession("userData")
                            ->deleteSession("serverData")
                            ->deleteSession("userFilial")
                            ->deleteSession("userAcesso");
        $this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction);
        return $this;
    }
    
    public function checklogin( $action ){
        switch ($action){
            case  "boolean":
                if(!$this->sessionHelper->checkSession("userAuth"))
                    return false;
                else
                   return true;
                break;
            case  "redirect":
                if(!$this->sessionHelper->checkSession("userAuth"))
                    if($this->redirectorHelper->getCurrentController() != $this->loginController || $this->redirectorHelper->getCurrentAction() != $this->loginAction)
                    $this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction);                           
                break;
            case  "stop":
                exit;
                break;
        }
    }

   
            
    public function userData( $key){
        $s = $this->sessionHelper->selectSession("userData");
        return $s[$key];
    }
    
}
            
