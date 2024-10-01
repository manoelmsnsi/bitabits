<?php class Escolaridade extends Controller {   
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
            $escolaridade = new EscolaridadeModel();          
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper();     
            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$escolaridade->listar_Escolaridade(NULL,NULL,"id_status<>99  AND ({$filiais})",NULL,' Escolaridade.id DESC',NULL,NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
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
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();                  
            $filial = new FilialModel();
            $status = new StatusModel();
            $escolaridade = new EscolaridadeModel();
            $id = $this->getParams('id');
            $id_tabela = $this->getParams('id_tabela');
            $tabela = $this->getParams('tabela');
            $nome_form='Cadastra Escolaridade';

            if(!empty($id)){
                $escolaridade_dados=$escolaridade->listar_escolaridade($JOIN, '1', "id=$id", $offset, $orderby);
                $escolaridade_dados = $escolaridade_dados[0]; 
                $comando='/'.__CLASS__.'/alterar/';
                $nome_form='Alterar Escolaridade';
            } 
            $form = new FormularioHelper();
            $inputs.= $form->Input('hidden', 'id', $CSS, $id);
            $inputs.= $form->Input('hidden', 'id_tabela', $CSS, $id_tabela);
            $inputs.= $form->Input('hidden', 'tabela', $CSS, $tabela);
            $inputs.= $form->select('Filial','id_filial','col-md-2',$filial->listar_Filial(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome_fantasia',$escolaridade_dados['id_filial']);
            $inputs.= $form->select('Tipo','tipo','col-md-2',array(array("id"=>"Ensino Fundamental"),array("id"=>"Ensino Médio"),array("id"=>"Ensino Superior Completo"),array("id"=>"Ensino Superior Incompleto"),array("id"=>"Ensino Superior Incompleto Cursando"),array("id"=>"Curso Teológico"),array("id"=>"Curso Técnico")),'id',$escolaridade_dados['tipo']);
            $inputs.= $form->Input("text", 'escola', "col-md-8", $escolaridade_dados["escola"], $Required, 'Escola', $disable);
            $inputs.= $form->Input("date", 'prev_termino', "col-md-4", $escolaridade_dados["prev_termino"], $Required, 'Previsão de Termino', $disable);
            $inputs.= $form->Input("date", 'ano_conclusao', "col-md-4", $escolaridade_dados["ano_conclusao"], $Required, 'Ano da Conclusão', $disable);
            $inputs.= $form->select('Status','id_status','col-md-4',$status->listar_Status(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$escolaridade_dados['id_status']);
            $inputs.= $form->Input("text", 'observacoes', "col-md-10", $escolaridade_dados["observacoes"], $Required, 'Observações', $disable);
          
            $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');;
            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","ballot");
        }else{
            $this->view('error_permisao');
        }
    }

    

public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Escolaridade/incluir/';     
        $tabela = $_POST['tabela'];
        $id_tabela = $_POST['id_tabela'];            
        if($acesso->acesso_valida($comando)==true){
            $escolaridade = new EscolaridadeModel();      
            $id=$escolaridade->cadastrar_escolaridade( 
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_status'=>$_POST['id_status'],
                        'id_tabela'=>$_POST['id_tabela'],
                        'tabela'=>$_POST['tabela'],
                        'tipo'=>$_POST['tipo'],
                        'escola'=>$_POST['escola'],
                        'ano_conclusao'=>$_POST['ano_conclusao'],
                        'prev_termino'=>$_POST['prev_termino'],
                        'observacoes'=>$_POST['observacoes'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");    
        }else{
            $this->view('error_permisao');
        }
    }

 public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Escolaridade/alterar/';       
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];            
            $tabela = $_POST['tabela'];
            $id_tabela = $_POST['id_tabela'];
            $escolaridade = new EscolaridadeModel();      
            $escolaridade->alterar_escolaridade(
                array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_status'=>$_POST['id_status'],
                        'id_tabela'=>$_POST['id_tabela'],
                        'tabela'=>$_POST['tabela'],
                        'tipo'=>$_POST['tipo'],
                        'escola'=>$_POST['escola'],
                        'ano_conclusao'=>$_POST['ano_conclusao'],
                        'prev_termino'=>$_POST['prev_termino'],
                        'observacoes'=>$_POST['observacoes'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");     
        }else{
            $this->view('error_permisao');
        }
    }
    
 public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Escolaridade/excluir/';        
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $id_tabela = $_POST['id_tabela'];
            $tabela = $_POST['tabela'];          
            $escolaridade = new EscolaridadeModel();      
            $escolaridade->excluir_Escolaridade( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs           
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Pessoa/visualizar/id/$id_tabela/tabela/$tabela");   
        }else{
            $this->view('error_permisao');
        }
    } 

 } ?> 