<?php class Historico extends Controller {  
private $auth, $db;   
public function acesso_restrito() {  
    $this->auth = new AutenticaHelper(); 
    $this->auth->setLoginControllerAction('Index', '')             
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
        $hitsorico_pessoa = new HistoricoModel();  
        $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);      
        echo $menu->Menu();                      
        $form = new FormularioHelper();            
        $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$hitsorico_pessoa->listar_Historico(NULL, NULL, "id_status<>99 AND {$filiais}", NULL, ' Historico.id DESC', NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));        
        $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");  
    }else{    
        $this->view('error_permisao');      
    }   
} 
public function form(){               
  $this->acesso_restrito();             
  $acesso = new AcessoHelper();   
  $logs = new LogsModel();    
  $comando="/".__CLASS__."/incluir/";  
 // if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
    $status= new StatusModel();      
    $filial = new FilialModel();        
    $pessoa = new PessoaModel();   
    $acesso = new SessionHelper();
    $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);                
    echo $menu->Menu();                                
    $id_filial = $this->getParams("id_filial");       
    $id_cliente = $this->getParams("id_cliente");       
    $id_tabela = $this->getParams("id_tabela");      
    $tabela = $this->getParams("tabela");           
    $form = new FormularioHelper();       
      $inputs.= $form->Input("hidden", "id_filial", "col-md-12",$id_filial, "required");  
      $inputs.= $form->Input("hidden", "id_cliente", "col-md-12",$id_cliente, "required");  
      $inputs.= $form->Input("hidden", "id_tabela", "col-md-12",$id_tabela, "required");  
      $inputs.= $form->Input("hidden", "tabela", "col-md-12",$tabela, "required");       
      $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral' OR tabela='Cobranca'",NULL,' Status.id ASC',NULL),"descricao");     
      $inputs.= $form->Input("text", "observacao", "col-md-10",null, "required","Observação");          
      $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");     
    $form->card("Cobrança",$inputs,"col-md-12",$comando,"POST","people");  
//    }else{          
  //    $this->view('error_permisao');        
    //}  
}  

public function incluir() {  
  $this->acesso_restrito();    
  $acesso = new AcessoHelper(); 
  $logs = new LogsModel(); 
  $comando = '/Historico/incluir/';    
 // if ($acesso->acesso_valida($comando) == true) {      
    $id = $_POST['id_cliente'];      
    $tabela=  $_POST['tabela'];  
    $historico = new HistoricoModel(); 
    $historico->cadastrar_Historico(      
      array(                      
      'id_filial' => $_POST['id_filial'],            
      'observacao' => $_POST['observacao'],        
      'id_tabela' => $_POST['id_tabela'],          
      'tabela' => $tabela,                   
      'id_status' => $_POST['id_status'],       
      'data_lancamento' => date("Y-m-d H:i:s"),  
      ));          
    $logs->cadastrar_logs($comando, $id); //Gera Logs     
    $redirect = new RedirectHelper();    
    $redirect->goToUrl("/$tabela/admin_listar/id/$id/");   
  //} else {      
 //   $this->view('error_permisao');   
 // }    
  
}       

public function alterar() {   
    $this->acesso_restrito();     
    $acesso = new AcessoHelper();   
    $logs = new LogsModel();       
    $comando = '/Historico/alterar/';    
    if ($acesso->acesso_valida($comando) == true) { 
        $id = $_POST['id'];          
        $historico = new HistoricoModel();       
        $historico->alterar_historico(             
        array(                      
        'id_filial' => $_POST['id_filial'],
        'observacao' => $_POST['observacao'],                  
        'id_tabela' => $_POST['id_tabela'],                  
        'tabela' => $_POST['tabela'],                     
        'id_status' => $_POST['id_status'],                 
        'data_lancamento' => date("Y-m-d H:i:s"),           
        ), 'id=' . $id          
    );         
    $logs->cadastrar_logs($comando, $id); //Gera Logs
    $redirect = new RedirectHelper();       
    $redirect->goToUrl('/Historico/admin_listar/');   
    } else {      
        $this->view('error_permisao');     
    }   
}   
public function excluir() {   
$this->acesso_restrito();   
$acesso = new AcessoHelper(); 
$logs = new LogsModel();     
$comando = '/Historico/excluir/';  
if ($acesso->acesso_valida($comando) == true) {  
$id = $_POST['id'];
            $id_tabela = $_POST['id_tabela'];
            $tabela = $_POST['tabela'];   
$historico = new HistoricoModel();   
$historico->excluir_historico(array('id_status' => '99'), 'id=' . $id); 
$logs->cadastrar_logs($comando, $id); //Gera Logs            
$redirect = new RedirectHelper();         
$redirect->goToUrl('/Historico/admin_listar/'); 
} else {       
$this->view('error_permisao');      
}    }}
?> 