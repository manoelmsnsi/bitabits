<?php class Venda extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    } 

    public function pdv(){ 
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $listar_acesso=$acesso->selectSession('userAcesso');
        $user_dados=$acesso->selectSession('userData');
        $dados['listar_acesso']=$listar_acesso;
        $dados['user_dados']=$user_dados;
       // $filiais=$acesso->acesso_filial("Venda");
        $dados['id_filial']=$user_dados["id_filial"];
       // print_r($user_dados);
        $tipo = $this->getParams("tipo");
        $caixa = $this->getParams("caixa");
        $dados["caixa"]=$caixa;
        $dados["tipo"]=$tipo;     
        $id = $this->getParams('id');
        $dados['id']=$id;
        if(!empty($id)){          
            $venda = new VendaModel();
            $listar_venda = $venda->listar_venda(NULL,NULL,"id=$id AND id_status='1'",NULL,'Venda.id DESC');
            $dados['listar_venda'] = $listar_venda;           
            $produto = new ProdutoModel();
            $listar_produto = $produto->listar_Produto(NULL,NULL,"id_status<>99",NULL,' Produto.id DESC',NULL);
            $dados['listar_produto'] = $listar_produto; 
            $pessoa = new PessoaModel();
            $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Cliente'",NULL,' Pessoa.id DESC',NULL);
            $dados['listar_cliente'] = $listar_pessoa;          
            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Venda'",NULL,' Itens.id DESC',"Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.id_produto,Itens.valor_venda");
            $dados['listar_itens'] = $listar_itens;               
            $tipo_documento = new TipoDocumentoModel();
            $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status<>99",NULL,' TipoDocumento.id ASC',NULL);
            $dados['listar_tipo_documento'] = $listar_tipo_documento; 
            $tipo_pagamento = new TipoPagamentoModel();
            $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99",NULL,' TipoPagamento.id ASC',NULL);
            $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento;            
//            $contabancaria = new ContaBancariaModel();
//            $listar_contabancaria = $contabancaria->listar_ContaBancaria(nULL,NULL,"ContaBancaria.id_status<>99 AND id={$caixa}",NULL,' ContaBancaria.id DESC',null);
//            $dados['listar_contabancaria'] = $listar_contabancaria; 
//            print_r($listar_contabancaria);
            $this->view('form_pdv',$dados);
            }else{
                $venda = new VendaModel();    
                $id=$venda->cadastrar_venda( 
                   array( 
                        'tipo'=>"Venda",
                        'id_conta_bancaria'=>"$caixa",
                        'id_colaborador'=>$user_dados["id"],
                        'id_status'=>"1",
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                    )
               ); 
                $redirect = new RedirectHelper;
                echo "teste".$caixa;
                $redirect->goToUrl('/Venda/pdv/id/'.$id."/tipo/Receita/caixa/$caixa/");  
            }
        //$this->view('form_pdv',$dados);       
    }   
    public function form(){ 
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $dados["userData"]=$acesso->selectSession("userData");
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $tipo = $this->getParams("tipo");
        $dados["tipo"]=$tipo;
        $filial = new FilialModel();
        $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
        $dados['listar_filial'] = $listar_filial;                  

        $tipo_documento = new TipoDocumentoModel();
        $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status<>99",NULL,' TipoDocumento.id ASC',NULL);
        $dados['listar_tipo_documento'] = $listar_tipo_documento; 

        $tipo_pagamento = new TipoPagamentoModel();
        $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99",NULL,' TipoPagamento.id ASC',NULL);
        $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento; 

        $plano_contas = new PlanoContasModel();
        $listar_plano_conta = $plano_contas->listar_PlanoContas(NULL,NULL,"id_status<>99",NULL,' PlanoContas.id DESC',NULL);
        $dados['listar_plano_conta'] = $listar_plano_conta;           

        if($tipo=="Receita"){
            $pessoa = new PessoaModel();
            $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Cliente'",NULL,' Pessoa.id DESC',NULL);
            $dados['listar_cliente'] = $listar_pessoa; 

            }else{

            $pessoa = new PessoaModel();
            $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND (Pessoa.tipo='Fornecedor' OR Pessoa.tipo='Colaborador')",NULL,' Pessoa.id DESC',NULL);
            $dados['listar_cliente'] = $listar_pessoa;  
        }       
        $conta_bancaria = new ContaBancariaModel();
        $listar_conta_bancaria = $conta_bancaria->listar_ContaBancaria(NULL,NULL,"id_status<>99",NULL,' ContaBancaria.id DESC',NULL);
        $dados['listar_conta_bancaria'] = $listar_conta_bancaria; 

        $pessoa = new PessoaModel();
        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='colaborador'",NULL,' Pessoa.id DESC',NULL);
        $dados['listar_colaborador'] = $listar_pessoa; 

        $status = new StatusModel();
        $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99 AND (Status.tabela='Venda' OR Status.tabela='Geral')",NULL,' Status.id ASC',NULL);
        $dados['listar_status'] = $listar_status;  

        $id = $this->getParams('id');
        $dados['id']=$id;

        if(!empty($id)){
            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Produto'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");
            $dados['listar_itens'] = $listar_itens;      
            
            $listar_servico = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Servico'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_servico'] = $listar_servico;            
            
            $listar_outros = $itens->listar_Itens(NULL,NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Outros'",NULL,' Itens.id DESC',"id,descricao,Itens.id,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_outros'] = $listar_outros; 
            
            $venda = new VendaModel();
            $listar_venda = $venda->listar_venda(NULL,NULL,"id=$id AND id_status='1'",NULL,'Venda.id DESC');
            $dados['listar_venda'] = $listar_venda;
        } 
    
        $this->view('form_venda',$dados);
 
    }      

//    public function form (){
//        $this->acesso_restrito();
//        $acesso = new AcessoHelper();
//        $logs = new LogsModel();
//        $comando="/".__CLASS__."/incluir/";         
//
//        if($acesso->acesso_valida("/Venda/orcamento_listar/")==true){
//            $acesso = new SessionHelper();
//
//            $status= new StatusModel();
//            $filial = new FilialModel();
//            $plano_contas = new PlanoContasModel();
//            $tipo_pagamento = new TipoPagamentoModel();
//            $tipo_documento = new TipoDocumentoModel();
//            $conta_bancaria = new ContaBancariaModel();
//            $pessoa = new PessoaModel();
//            
//            $menu = new MenuHelper("Bit a Bits - Orçamento", $Class, $AcaoForm, $MetodoDeEnvio);        
//                echo $menu->Menu();
//                
//            $tipo = $this->getParams("tipo");     
//            $dados["tipo"] = $tipo;
//                    
//            if($tipo=="Receita"){
//                $listar_pessoa_dados = $pessoa->listar_Pessoa($join, "1", "id=$id AND Pessoa.tipo='Cliente", $offset, $orderby);
//                $listar_pessoa_dados = $listar_pessoa_dados[0];
//            }else{
//                $listar_pessoa = $pessoa->listar_Pessoa($join, "1", "id=$id AND Pessoa.tipo='Fornecedor", $offset, $orderby);
//                $listar_pessoa_dados = $listar_pessoa_dados[0];
//            }
//            
//            $id=$this->getParams("id");     
//
//            if(!empty($id)){
//                $venda_dados = $venda->listar_Venda($join, "1", "id=$id", $offset, $orderby);
//                $venda_dados = $venda_dados[0]; 
//                $comando="/".__CLASS__."/alterar/";
//            }            
//
//            $form = new FormularioHelper("Alterar Orçamento","col-md-12" ,$comando,"POST","account_balance_wallet","false");
//                //echo $form->Input("hidden", "id", null, $id, $required,null);                
////                echo $form->Input("hidden", "tipo", null, $tipo, $required,null);                
//                echo $form->select("Filial","id_filial","col-md-4",$filial->listar_Filial(NULL,NULL,"id_status <> 99",NULL,' Filial.id DESC',NULL),"nome_fantasia",$contas_dados["id_filial"]);
//
//               
//                echo $form->Button("btn btn-md btn-rose ","Salvar"); 
//        }else{
//            $this->view('error_permisao');
//        }
//    }
    
    public function visualizar(){  
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $tipo = $this->getParams("tipo");
        $dados["tipo"]=$tipo;
                    $filial = new FilialModel();
                    $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
                    $dados['listar_filial'] = $listar_filial;                     
                    $tipo_documento = new TipoDocumentoModel();
                    $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status<>99",NULL,' TipoDocumento.id DESC',NULL);
                    $dados['listar_tipo_documento'] = $listar_tipo_documento;                     
                    $tipo_pagamento = new TipoPagamentoModel();
                    $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99",NULL,' TipoPagamento.id DESC',NULL);
                    $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento;                     
                    $plano_contas = new PlanoContasModel();
                    $listar_plano_conta = $plano_contas->listar_PlanoContas(NULL,NULL,"id_status<>99",NULL,' PlanoContas.id DESC',NULL);
                    $dados['listar_plano_conta'] = $listar_plano_conta;           
                    if($tipo=="Receita"){
                        $pessoa = new PessoaModel();
                        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Cliente'",NULL,' Pessoa.id DESC',NULL);
                        $dados['listar_cliente'] = $listar_pessoa; 
                    }else{
                        $pessoa = new PessoaModel();
                        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Fornecedor'",NULL,' Pessoa.id DESC',NULL);
                        $dados['listar_cliente'] = $listar_pessoa;  
                    }                    
                    $conta_bancaria = new ContaBancariaModel();
                    $listar_conta_bancaria = $conta_bancaria->listar_ContaBancaria(NULL,NULL,"id_status<>99",NULL,' ContaBancaria.id DESC',NULL);
                    $dados['listar_conta_bancaria'] = $listar_conta_bancaria; 
                    $pessoa = new PessoaModel();
                    $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='colaborador'",NULL,' Pessoa.id DESC',NULL);
                    $dados['listar_colaborador'] = $listar_pessoa; 
                    
                    $status = new StatusModel();
                    $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99 AND (Status.tabela='Venda' OR Status.tabela='Geral')",NULL,' Status.id ASC',NULL);
                    $dados['listar_status'] = $listar_status;  
        $id = $this->getParams('id_venda');       
        $dados['id']=$id;
        if(empty($id)){
            $id = $this->getParams('id');
            $dados['id']=$id;
        }
        if(!empty($id)){
            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Venda'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");
            $dados['listar_itens'] = $listar_itens;                  
            $listar_servico = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Servico'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_servico'] = $listar_servico;            
            $listar_outros = $itens->listar_Itens(NULL,NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Outros'",NULL,' Itens.id DESC',"id,descricao,Itens.id,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_outros'] = $listar_outros;           
            $venda = new VendaModel();
            $listar_venda = $venda->listar_venda(NULL,NULL,"id=$id ",NULL,'Venda.id DESC');
            $dados['listar_venda'] = $listar_venda;
        } 
        $this->view('form_visualizar_venda',$dados);
    }
 
    public function incluir(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Venda/incluir/';
           if($acesso->acesso_valida($comando)==true){
            $tipo = $this->getParams("tipo");
            $id_cliente=0;
            $id_fornecedor=0;
            if($tipo=="Receita"){ 
              $id_cliente=$_POST['id_pessoa'];               
            }else{ 
              $id_fornecedor=$_POST['id_pessoa'];             
            }
            if(empty($_POST['id_pessoa'])){
              $id_filial = $this->getParams("id_filial");
              $id_tipo_documento = "1";//
              $id_tipo_pagamento = "1";//1 
              $id_conta_bancaria = "0";
              $id_colaborador = $this->getParams("id_colaborador");
              $id_plano_contas = "1";
              $codigo = $this->getParams("id");
              $id_fornecedor = $this->getParams("id_fornecedor");
              $id_cliente = $this->getParams("id_cliente");
            }else{
              $id_filial = $_POST["id_filial"];//                
              $id_tipo_documento = $_POST["id_tipo_documento"];
              $id_tipo_pagamento = $_POST["id_tipo_pagamento"];
              $id_conta_bancaria = $_POST["id_conta_bancaria"];
              $id_colaborador = $_POST["id_colaborador"];
              $id_plano_contas = $_POST["id_plano_contas"];  
              $codigo = $_POST["codigo"];
//              $id_fornecedor = $_POST["id_fornecedor"];
//              $id_cliente = $_POST["id_pessoa"];
                }
            

               $venda = new VendaModel();      

               $id=$venda->cadastrar_venda( 
                   array(                                               
                        'id_filial'=>$id_filial,
                        'id_tipo_documento'=>$id_tipo_documento,
                        'id_tipo_pagamento'=>$id_tipo_pagamento,
                        'id_cliente'=>$id_cliente,
                        'id_conta_bancaria'=>$id_conta_bancaria,
                        'id_colaborador'=>$id_colaborador,
                        'id_plano_contas'=>$id_plano_contas,
                        'codigo'=>$codigo,  
                        'id_fornecedor'=>$id_fornecedor,
                           'tipo'=>$tipo,
                        'id_status'=>"1",
                        'data_lancamento'=>  date("Y-m-d H:i:s")
                       )

               );   
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Venda/form/id/'.$id."/tipo/".$tipo);    
           }else{
               $this->view('error_permisao');
           }
       }
    public function alterar(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Venda/alterar/';
           if($acesso->acesso_valida($comando)==true){
               $id = $_POST['id'];
               $venda = new VendaModel();      
               $venda->alterar_venda(
                   array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tipo_documento'=>$_POST['id_tipo_documento'],
                        'id_tipo_pagamento'=>$_POST['id_tipo_pagamento'],
                        'id_cliente'=>$_POST['id_pessoa'],
                       'id_conta_bancaria'=>$_POST['id_conta_bancaria'],
                        'id_plano_contas'=>$_POST['id_plano_contas'],
                        'id_colaborador'=>$_POST['id_colaborador'],
                        'codigo'=>$_POST['codigo'],
                        'tipo'=>$_POST['tipo'],
                        'id_status'=>$_POST['id_status'],

                   ),'id='.$id
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Venda/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }

       }
    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Venda/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
           if(empty($id)){  $id = $_POST['id']; }
           $venda = new VendaModel();       
            $venda->excluir_venda( array( 'id_status'=>'99' ),"id=$id" );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Contas/receita_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 
           

           