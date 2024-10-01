<?php
class Upload extends Controller {     
    private  $auth,$db;   
    public function acesso_restrito(){    
        $this->auth = new AutenticaHelper();    
        $this->auth->setLoginControllerAction("Index","")  
        ->checkLogin("redirect");
        $this->db = new AdminModel();
    }
    public function index_action(){
      $redirect = new RedirectHelper();
      $redirect->goToUrl("/Index/");
    }  
      
    public function form() {               
        $this->acesso_restrito();       
        $acesso = new AcessoHelper();
        $comando="/".__CLASS__."/incluir/";
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
            $logs = new LogsModel();             $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();  
            $status= new StatusModel();
            $id=$this->getParams("id");
            $id_tabela = $this->getParams("id_tabela");
            $id_filial = $this->getParams("id_filial");
            $tabela = $this->getParams("tabela");          
            $form = new FormularioHelper();
            $inputs .= $form->select("Tipo", "tipo", "col-md-4 ", array(array("id"=>"IMG","descricao"=>"Imagem"),array("id"=>"DOC","descricao"=>"Documento"),array("id"=>"BACKGROUND","descricao"=>"Plano de Fundo")),"descricao");
            $inputs .= $form->Input("text", "descricao", "col-md-6", NULL, $Required,"Descrição", $disable);
            $inputs .= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs .= $form->upload("offset-5 col-md-12");
            $inputs .= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card(__CLASS__,$inputs,"col-md-12",$comando."id_tabela/$id_tabela/tabela/$tabela/id_filial/$id_filial/","POST","up");
        }else{       
            $this->view('error_permisao');   
        }        
    }   
                  
    public function galeria(){         
    $id_tabela = $this->getParams("id_tabela");  
      if($id_tabela<>NULL){     
        $upload = new UploadModel(); 
        $listar_upload = $upload->listar_upload ( NULL,NULL,"id_status<>'99'AND id_tabela={$id_tabela}",NULL,NULL);
        $dados["listar_upload"] = $listar_upload;    
        $this->view("site_galeria",$dados);       
        }else{    
          $redirect = new RedirectHelper();        
          $redirect->goToUrl("/Noticia/listar/");   
        }          
    }    

    
    public function visualizar(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/visualizar/"; 

        

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
            $filiais=$acesso->acesso_filial(__CLASS__);
            $acesso = new SessionHelper();          
            $userData = $acesso->selectSession("userData");
            $serverData = $acesso->selectSession("serverData");
            $id_filial=$userData["id_filial"];
            $id= $userData["id"];            
            $serverData = $serverData[0]['cpf'];
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();   
            $form = new FormularioHelper();
            $chamado = new ChamadoModel();
            $upload = new UploadModel();
            $upload_liberacao = new ModelLiberacao();
            $upload_liberacao->_tabela="Upload";
            
            
            
            $lista1= $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/0/tabela/Upload/id_filial/$id_filial/",$icone,$upload->listar_Upload($join, $limit,"Tabela='Upload' AND ($filiais) AND id_tabela='0'" , $offset, $orderby, "descricao AS 'Descrição',src AS 'srcImagem Documento',RIGHT(src,3) AS Tipo,data_lancamento AS 'Data Lançamento'"),"tabela1");    
            $lista_arquivo_privado= $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/$id/tabela/Upload/id_filial/$id_filial/id_usuario/$id",$icone,$upload->listar_Upload($join, $limit,"Tabela='Upload' AND id_tabela='$id'" , $offset, $orderby, "descricao AS 'Descrição',src AS 'srcImagem Documento',RIGHT(src,3) AS Tipo,data_lancamento AS 'Data Lançamento'"),"tabela2");    
            $financeiro_sistema= $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/$id/tabela/Upload/id_filial/$id_filial/id_usuario/$id",$icone,$upload_liberacao->read("INNER JOIN Pessoa ON Pessoa.id=Upload.id_tabela INNER JOIN Status ON Status.id=Upload.id_status", "Upload.tipo='Boleto Financeiro' AND Pessoa.cpf='$serverData'", $limit, $offset, $orderby, "Pessoa.cpf AS 'CPF/CNPJ',Pessoa.nome AS 'Nome',Upload.tipo,Upload.descricao,Status.cor as cor_Status,Status.descricao AS Status,Upload.src AS srcBoleto", $group, $pesquisa),"tabela3");    
         
            $logs->cadastrar_logs($comando, '0'); //Gera Logs
            $inputs.= $form->Abas($Tipo, "Files", "col-md-12", 
                array(
                    array("id" => "Filial", "icone" => "contacts", "descricao" => "Filial"), 
                    array("id" => "arquivo", "icone" => "image", "descricao" => "Meus Arquivos","classe" => " active"),
                    array("id" => "financeiro", "icone" => "image", "descricao" => "Financeiro Sistema")
                    ),
                array(
                    array("id" => "Filial", "dados" => "$lista1"),
                    array("id" => "arquivo", "dados" => "$lista_arquivo_privado","classe" => " active"),
                    array("id" => "financeiro", "dados" => "$financeiro_sistema")
                    )); 
          
            $form->card("Arquivos",$inputs,"col-md-12",null,"POST","donut_small");

        }else{

            $this->view('error_permisao');

        }

    }
 
    public function incluir(){       
        $this->acesso_restrito();     
        $acesso = new AcessoHelper();        
        $logs = new LogsModel();        
        $comando="/Upload/incluir/";                 
        if($acesso->acesso_valida("$comando")==true){
            $id_tabela = $this->getParams("id_tabela");  
            $tabela = $this->getParams("tabela");   
            $id_filial = $this->getParams("id_filial");               
            $tipo =$_POST["tipo"];          
            $nome=$this->getParams("nome");            
            $data = date("dmYHis");     
            $subDominio = explode(".", $_SERVER['HTTP_HOST']);
            $subDominio=$subDominio[0];
            $arquivo = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : FALSE;                
            $upload = new UploadHelper();       
            $upload_mod= new UploadModel();          
            for ($controle = 0; $controle < count($arquivo["name"]);$controle++){          
                $src = $upload->setFile($arquivo,"c{$controle}_sb{$subDominio}_f{$id_filial}_t{$tabela}_it{$id_tabela}_d{$data}",$controle)  
                ->setPath("/$subDominio/web-files/$subDominio/filial$id_filial/")    
                ->upload();   
                $id=$upload_mod->cadastrar_Upload(array(            
                "tipo" => $_POST["tipo"],      
                "id_filial" => $id_filial,           
                "src" => $src,              
                "descricao" => $_POST["descricao"],                 
                "tabela" => $tabela,         
                "id_tabela" => $id_tabela,              
                "data_lancamento" =>date("Y-m-d H:i:s"),            
                "id_status" => "1"));          
              }              
              $logs->cadastrar_logs($comando,$id);   
            }else{        
                $this->view("error_permisao");     
            }
            echo "<script>script:history.go(-2)</script>";   
    } 
                             
    public function excluir(){        
        $this->acesso_restrito();     
        $acesso = new AcessoHelper();      
        $logs = new LogsModel();    
        $comando='/Upload/excluir/';   
        if($acesso->acesso_valida($comando)==true){     
          $id = $_POST['id'];
            $id_tabela = $_POST['id_tabela'];
            $tabela = $_POST['tabela'];       
          $upload = new UploadModel();              
          $upload->excluir_Upload( array( 'id_status'=>'99' ),"id=$id" );             
          $logs->cadastrar_logs($comando,$id);//Gera Logs           
          $redirect = new RedirectHelper();                           
        }else{         
          $this->view('error_permisao');       

        }     
      echo "<script>script:history.go(-2)</script>";    
    }  
}