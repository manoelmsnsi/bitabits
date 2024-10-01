<?php class Marca extends Controller {   
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
            $marca = new MarcaModel();
          
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     
            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$marca->listar_Marca("INNER JOIN Filial ON Filial.id=Marca.id_filial INNER JOIN Status ON Status.id=Marca.id_status ",NULL,"Marca.id_status<>'99' AND ({$filiais})",NULL,' Marca.id DESC',"Marca.id,Filial.nome_fantasia AS Filial,Marca.descricao  AS Marca,Status.cor AS cor_Status,Status.Descricao AS Status",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }    
    }

    public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();            
        $comando='/'.__CLASS__.'/incluir/';         
        if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){
           
        $filial = new FilialModel();
        $status = new StatusModel();
        $marca = new MarcaModel();
        $id = $this->getParams('id');
        $dados['id']=$id;
        $nome_form='Cadastra Marca';
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        if(!empty($id)){ 
            $marca_dados=$marca->listar_marca($JOIN, '1', "id=$id", $offset, $orderby);
            $marca_dados = $marca_dados[0]; 
            $comando='/'.__CLASS__.'/alterar/';
            $nome_form='Alterar Marca';
        } 
        $form = new FormularioHelper($nome_form,'col-md-12' ,$comando,'POST','people');
            $inputs.= $form->Input('hidden', 'id', $CSS, $id);
            $inputs.= $form->select('Filial','id_filial',"col-md-2",$filial->listar_Filial(NULL,NULL,"id_status<>'99'",NULL,NULL,NULL,null,null),'nome_fantasia',$marca_dados['id_status']);
            $inputs.= $form->Input("text", 'descricao', "col-md-6", $marca_dados["descricao"], $Required, 'descricao', $disable);
            $inputs.= $form->select('Status',"id_status",'col-md-2',$status->listar_Status(NULL,NULL,"id_status<>'99'",NULL,NULL,NULL,null,null),'descricao',$marca_dados['id_status']);
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");     
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }
    }

    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Marca/incluir/';           
        if($acesso->acesso_valida($comando)==true){
           $marca = new MarcaModel();      
            $id=$marca->cadastrar_marca( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Marca/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Marca/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $marca = new MarcaModel();      
            $marca->alterar_marca(
                array(
                   'id_filial'=>$_POST['id_filial'],
                    'descricao'=>$_POST['descricao'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Marca/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Marca/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $marca = new MarcaModel();      
            $marca->excluir_marca( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Marca/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 