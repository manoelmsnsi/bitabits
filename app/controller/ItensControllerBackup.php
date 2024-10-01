<?php class Itens extends Controller {   
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
        $comando='/Itens/admin_listar/';

        if($acesso->acesso_valida($comando)==true){ 
            $acesso = new SessionHelper();
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();

            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens(NULL,NULL,"id_status<>99",NULL,' Itens.id DESC',NULL);
            $logs->cadastrar_logs($comando,'0');//Gera Logs
            $dados['listar_itens'] = $listar_itens;           
            $this->view('listar_itens',$dados); 
        }else{
            $this->view('error_permisao');
        }
    } 
 public function form(){ 
        $this->acesso_restrito();
        
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        

        $id = $this->getParams('id');
        $dados['id']=$id;
        $tipo = $this->getParams('tipo');
        $dados['tipo']=$tipo; 
        $id_tabela = $this->getParams('id_tabela');
        $dados['id_tabela']=$id_tabela;    
        $tabela = $this->getParams('tabela');
        $dados['tabela']=$tabela;     

        $filial = new FilialModel();
                           $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
                           $dados['listar_filial'] = $listar_filial; 
        $produto = new ProdutoModel();
                        if($tabela=="Servico"){
                            $listar_produto = $produto->listar_Produto(NULL,NULL,"id_status<>99 AND tipo='Servico'",NULL,' Produto.id DESC',NULL);
                        }   else{
                            $listar_produto = $produto->listar_Produto(NULL,NULL,"id_status<>99 AND tipo='Produto'",NULL,' Produto.id DESC',NULL);
                        }
                           
                           $dados['listar_produto'] = $listar_produto; 
        $status = new StatusModel();
                           $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,' Status.id ASC',NULL);
                           $dados['listar_status'] = $listar_status;  $itens = new ItensModel();

        if(!empty($id)){
            $listar_itens = $itens->listar_itens(NULL,NULL,"id=$id AND id_status<>99",NULL,'Itens.id DESC');
            $dados['listar_itens'] = $listar_itens;
        } 
 

        $this->view('form_itens',$dados);
    }
 public function despesa_form(){ 
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        
        $filial = new FilialModel();
                           $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
                           $dados['listar_filial'] = $listar_filial; 
        $produto = new ProdutoModel();
                           $listar_produto = $produto->listar_Produto(NULL,NULL,"id_status<>99",NULL,' Produto.id DESC',NULL);
                           $dados['listar_produto'] = $listar_produto; 
        $status = new StatusModel();
                           $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,' Status.id ASC',NULL);
                           $dados['listar_status'] = $listar_status;  $itens = new ItensModel();

        $id = $this->getParams('id');
        $dados['id']=$id; 
        $id_tabela = $this->getParams('id_tabela');
        $dados['id_tabela']=$id_tabela;    
        $tabela = $this->getParams('tabela');
        $dados['tabela']=$tabela;     



        if(!empty($id)){
        $listar_itens = $itens->listar_itens(NULL,NULL,"id=$id AND id_status<>99",NULL,'Itens.id DESC');
           $dados['listar_itens'] = $listar_itens;
        } 


        $this->view('form_itens_despesa',$dados);
    }
 public function despesa_incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/incluir/';
         $id_venda = $this->getParams('id_tabela');
        if($acesso->acesso_valida($comando)==true){

            $itens = new ItensModel();      
            $id=$itens->cadastrar_itens( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_tabela'=>$_POST['id_tabela'],
                    'tabela'=>$_POST['tabela'],
                    'id_produto'=>$_POST['id_produto'],
                    'descricao'=>$_POST['descricao'],
                    'quantidade'=>$_POST['quantidade'],
                    'valor_venda'=>$_POST['valor_venda'],
                    'valor_desconto'=>$_POST['valor_desconto'],
                    'valor_compra'=>$_POST['valor_compra'],
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Despesa/form/id/'.$id_venda);    
        }else{
            $this->view('error_permisao');
        }
    }
     public function despesa_excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/excluir/';
        $id_venda = $this->getParams('id_tabela');
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $itens = new ItensModel();      
            $itens->excluir_itens( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Despesa/form/id/'.$id_venda);   
        }else{
            $this->view('error_permisao');
        }
    } 
    
 public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/incluir/';
        $id_venda = $this->getParams('id_tabela'); 
        $tipo = $this->getParams('tipo');
        $iten_produto=explode(',',$_POST['id_produto']);
        $id_iten=$iten_produto[0];
        $quantidade=$_POST['quantidade'];
        $produto = new ProdutoModel();
        $quantidade_estoque=$produto->listar_Produto($join, '1', "id=$id_iten", $offset, $orderby, "quantidade");
        $quantidade_estoque=$quantidade_estoque[0]["quantidade"]-$quantidade;
        $produto->alterar_Produto(array(
                "quantidade"=>$quantidade_estoque,
        ), "id=$id_iten");
   if($_POST['tabela']=="Venda"){
       $valor_iten=$iten_produto[1];
   }else{
       $valor_iten=$_POST['valor_venda'];
   }
        
        if($acesso->acesso_valida($comando)==true){ 

            $itens = new ItensModel();      
            $id=$itens->cadastrar_itens( 
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_tabela'=>$_POST['id_tabela'],
                    'descricao'=>$_POST['descricao'],
                    'tabela'=>$_POST['tabela'],
                    'id_produto'=>$id_iten,
                    'quantidade'=>$quantidade,
                    'valor_venda'=>$valor_iten,
                    'id_status'=>$_POST['id_status'],
                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Venda/form/id/'.$id_venda."/tipo/".$tipo);    
        }else{
            $this->view('error_permisao');
        }
    }
 public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];

            $itens = new ItensModel();      
            $itens->alterar_itens(
                array(
                     'id_filial'=>$_POST['id_filial'],
                     'id_tabela'=>$_POST['id_tabela'],
                     'tabela'=>$_POST['tabela'],
                     'pais'=>$_POST['pais'],
                     'id_produto'=>$_POST['id_produto'],
                     'descricao'=>$_POST['descricao'],
                     'quantidade'=>$_POST['quantidade'],
                     'valor_venda'=>$_POST['valor_venda'],
                     'valor_desconto'=>$_POST['valor_desconto'],
                     'valor_compra'=>$_POST['valor_compra'],
                     'id_status'=>$_POST['id_status'],
                     'data_lancamento'=>  date("Y-m-d H:i:s"),

                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Itens/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }

    }
 public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/excluir/';
        $id_venda = $this->getParams('id_tabela');
        if($acesso->acesso_valida($comando)==true){
            
            $quantidade= $this->getParams("quantidade");
            $id_iten= $this->getParams("id_iten");
            $produto = new ProdutoModel();
            $quantidade_estoque=$produto->listar_Produto($join, '1', "id=$id_iten", $offset, $orderby, "quantidade");
            $quantidade_estoque=$quantidade_estoque[0]["quantidade"]+$quantidade;
            $produto->alterar_Produto(array(
                    "quantidade"=>$quantidade_estoque,
            ), "id=$id_iten"); 
            
            $id = $this->getParams('id');
            $itens = new ItensModel();      
            $itens->excluir_itens( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Venda/form/id/'.$id_venda);   
        }else{
            $this->view('error_permisao');
        }
    } 
    public function incluir_pdv(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/incluir/';
        $id_venda = $this->getParams('id_tabela');
        $tipo = $this->getParams('tipo'); 
        $caixa=$this->getParams('caixa'); 
        $iten_produto=explode(',',$_POST['id_produto']);
        $id_iten=$iten_produto[0];
   
        $valor_iten=$iten_produto[1];
        if($acesso->acesso_valida($comando)==true){ 

            $itens = new ItensModel();      
            $id=$itens->cadastrar_itens( 
                array(
                    'id_filial'=>3,
                    'id_tabela'=>$id_venda,
                    'tabela'=>$tipo,
                    'id_produto'=>$id_iten,
                    'quantidade'=>$_POST['quantidade'],
                    'valor_venda'=>$valor_iten,
                    'id_status'=>'1',
                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Venda/pdv/id/'.$id_venda."/tipo/".$tipo."/caixa/$caixa/    ");    
        }else{
            $this->view('error_permisao');
        }
    }
    
 public function excluir_pdv(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Itens/excluir/';
        $id_venda = $this->getParams('id_tabela');
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $itens = new ItensModel();      
            $itens->excluir_itens( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Venda/pdv/id/'.$id_venda);   
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 