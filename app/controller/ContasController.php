<?php class Contas extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
       $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    } 
            
    public function receita_listar(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/".__CLASS__."/receita_listar/")==true){
            $filiais=$acesso->acesso_filial("Contas");
            $acesso = new SessionHelper();
            if(empty($_POST["pesquisa"])){ $pesquisa=null; $limit=25; }else{ $pesquisa=$_POST["pesquisa"]; $limit=$_POST["limit"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio); 
            
          echo $menu->Menu();
            $hoje= date("Y-m-d"); 
            $contas = new ContasModel();
            $listar_contas = $contas->listar_contas(
                    "INNER JOIN Filial ON Filial.id = Contas.id_filial
                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente",
                    "25",
                    "Contas.id_status='1' AND Contas.tipo='Receita' AND ({$filiais})",
                    NULL,
                    'Contas.data_vencimento ASC',
                    "Contas.id,
                       
                        PessoaCliente.nome AS Cliente,
                        PessoaCliente.cpf AS CPF,
                        Contas.valor_total AS 'Valor Total',
                        Contas.valor_parcela AS 'Valor Parcela',
                        Contas.juros AS Juros,
                        if(DATEDIFF ('$hoje',Contas.data_vencimento)>=1, Contas.valor_parcela+(Contas.valor_parcela*((Contas.juros)* DATEDIFF ('$hoje',Contas.data_vencimento))), Contas.valor_parcela) AS 'Valor Juros',
                        DATEDIFF ('$hoje',Contas.data_vencimento) AS atraso,
                        TipoDocumento.descricao AS Documento,                       
                        Contas.data_vencimento,
                        Contas.parcela AS Parcela
                       
                        ",null,$pesquisa); 
                    
            $orcamento_receita = new VendaModel();
            $listar_orcamento = $dados["listar_orcamento"] = $orcamento_receita->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_status='1' AND Venda.tipo='Receita' ",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        TipoDocumento.descricao AS Documento,
                        TipoPagamento.descricao AS Pagamento, 
                        ContaBancaria.descricao AS Caixa,
                        PessoaColaborador.nome AS Colaborador,
                        Status.cor AS cor_Status,
                        Status.descricao AS Status,
                        Venda.data_lancamento
                        ",null,$pesquisa);
                    
           $form = new FormularioHelper(null,"col-md-12" ,null,null,null);  
                   
           $listar_receita=$form->Listar("col-md-12", null, null, $icone, $listar_contas, "tabela1", array(array("acao"=>"/Contas/form/tipo/Venda/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Contas/form_receber/tipo/Receita/","classe"=>"btn-sm btn-rose","icone"=>"arrow_downward"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
           $listar_orcamento=$form->Listar("col-md-12", null,"/Venda/form/tipo/Receita/", $icone, $listar_orcamento, "tabela2", array(array("acao"=>"/Venda/form/tipo/Receita/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/tabela/Venda/classe/Venda/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
           $inputs=  $form->Abas($Tipo, "teste", "col-md-12", array(array("id"=>"Orcamento","icone"=>"request_page","descricao"=>"Orçamento","classe"=>" active"),array("id"=>"Receita","icone"=>"receipt_long","descricao"=>"Contas a Receber")),array(array("id"=>"Orcamento","dados"=>"$listar_orcamento","classe"=>" active"),array("id"=>"Receita","dados"=>"$listar_receita")));
           $form->card("Receitas",$inputs,"col-md-12",$comando,"POST","donut_small");
     } 
   }
    
public function despesa_listar(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/".__CLASS__."/despesa_listar/")==true){
            $filiais=$acesso->acesso_filial("Contas");
            $acesso = new SessionHelper();
            if(empty($_POST["pesquisa"])){ $pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $contas = new ContasModel();
            $listar_contas = $contas->listar_contas(
                    "INNER JOIN Venda ON Venda.id = Contas.id_tabela
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento 
                        INNER JOIN Pessoa AS PessoaFornecedor ON PessoaFornecedor.id = Venda.id_fornecedor 
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id=Venda.id_colaborador",
                    NULL,
                    "Contas.id_status='1' AND Contas.tipo='Despesa'  AND ({$filiais})",NULL,
                    'Contas.data_vencimento ASC',
                    "Contas.id,Contas.id_tabela,
                        Contas.tipo,
                        Contas.tabela,
                        Contas.id_filial,
                        Contas.parcela,
                        Contas.data_vencimento,
                        Contas.valor_parcela,
                        PessoaFornecedor.id AS id_fornecedor,
                        PessoaColaborador.nome as nome_colaborador,
                        PessoaColaborador.id AS id_colaborador,
                        PessoaFornecedor.nome AS nome_fornecedor,
                        TipoDocumento.descricao AS descricao_documento",null,$pesquisa); 
                    
            $orcamento_receita = new VendaModel();
            $listar_orcamento = $dados["listar_orcamento"] = $orcamento_receita->listar_Venda(
                    "INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN Pessoa As PessoaFornecedor ON PessoaFornecedor.id = Venda.id_fornecedor
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    NULL,
                    "Venda.id_status='1' AND Venda.tipo='Despesa'",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Venda.id_filial,
                        TipoDocumento.descricao AS descricao_tipo_documento,
                        TipoPagamento.descricao AS descricao_tipo_pagamento,
                        PessoaFornecedor.nome AS nome_fornecedor,
                        ContaBancaria.descricao AS descricao_conta_bancaria,
                        PessoaColaborador.nome AS nome_colaborador,
                        Venda.tipo,Status.descricao AS descricao_status,
                        Venda.data_lancamento"
                ,null,$pesquisa);
                    
 
            $form = new FormularioHelper(null,"col-md-12" ,null,null,null);
            
    

            $listar_receita=$form->Listar("col-md-12", null, null, $icone, $listar_contas, "tabela1", array(array("acao"=>"/Contas/form/tipo/Venda/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Contas/form_receber/tipo/Receita/","classe"=>"btn-sm btn-rose","icone"=>"arrow_downward"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);

            $listar_orcamento=$form->Listar("col-md-12", null,"/Venda/form/tipo/Despesa/", $icone, $listar_orcamento, "tabela2", array(array("acao"=>"/Venda/form/tipo/Despesa/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/tabela/Venda/classe/Venda/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
            $inputs= $form->Abas($Tipo, "teste", "col-md-12", array(array("id"=>"Orcamento","icone"=>"request_page","descricao"=>"Orçamento"),array("id"=>"Receita","icone"=>"receipt_long","descricao"=>"Contas a Pagar")),array(array("id"=>"Orcamento","dados"=>"$listar_orcamento","classe"=>" active"),array("id"=>"Receita","dados"=>"$listar_receita")));
            $form->card("Despesas",$inputs,"col-md-12",$comando,"POST","donut_small");
       }
    }

    public function form_especializacao (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/Contas/receita_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $contas = new ContasModel();
            $acesso = new SessionHelper();
            
            $tipo_documento = new TipoDocumentoModel();
            $id=$this->getParams("id"); 
            $stats = $this->getParams("stats"); 
            $tipo = $this->getParams("tipo"); 
            $menu = new MenuHelper("Bit a Bits - Alterar Contas", $Class, $AcaoForm, $MetodoDeEnvio);      
            echo $menu->Menu();              
            if(!empty($id)){
                $contas_dados=$contas->listar_contas($join, null, "id_status='1' AND id_tabela='$id'", $offset, $orderby);
                if(empty($contas_dados)){
                    if($tipo="Receita"){
                        echo "<script> window.location.href = '/Contas/receita_listar/' </script>";
                    }else{
                        echo "<script> window.location.href = '/Contas/despesa_listar/' </script>";
                    }
                     
                };
                $comando="/".__CLASS__."/alterar/";
                $form = new FormularioHelper();
               
                foreach($contas_dados as $dados):
                    $total_parcela_ativo=$total_parcela_ativo+$dados["valor_parcela"];
                    $inputs.= $form->Input("text", "valor_parcela".$dados["id"],"col-md-4", $dados["valor_parcela"], "required oninput='somarParcelas();' ",$dados["parcela"],null,"numero");  
                    $inputs.= $form->select("Tipo de Documento","id_tipo_documento".$dados["id"],"col-md-4",$tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status <> '99'",NULL,' TipoDocumento.id DESC',NULL),"descricao",$dados["id_tipo_documento"]);
                    $inputs.= $form->Input("date", "data_vencimento".$dados["id"],"col-md-4", $dados["data_vencimento"], "required ","Vencimento ".$dados["parcela"],null);  
                    $t[]= array("id"=>$dados["id"],"nome"=>"valor_parcela".$dados["id"],"id_tipo_documento"=>"id_tipo_documento".$dados["id"],"data_vencimento"=>"data_vencimento".$dados["id"]);
                endforeach;
                    $inputs.= $form->Input("hidden", "valor_total", "col-md-6",$total_parcela_ativo, $required,null,null,"valor_total");
                    $inputs.= $form->Input("hidden", "valor_sobra", "col-md-6",$total_parcela_ativo, $required,null,null,"valor_sobra");
                                
                    $a= $form->Input("text", "valor_total_falso", "",$total_parcela_ativo, "disabled",null,null,"valor_total_falso");
                    $b= $form->Input("text", "valor_sobra_falso", "",$total_parcela_ativo, "disabled",null,null,"valor_sobra_falso");
                   
                    $inputs.= $form->Abas($Tipo, "Caixa", "col-md-10 ", 
                    array(array("id" => "a", "icone" => "attach_money", "descricao" => "Total Venda $a"), 
                    array("id" => "b", "icone" => "money_off", "descricao" => "Sobra Parcela $b")));

                   $inputs.= $form->Button("btn btn-md btn-rose ","Finalizar","col-md-2"); 
                   $form->card("Especializar Venda",$inputs,"col-md-12","/Contas/form_especializacao/id/$id/stats/1/","POST","account_balance_wallet");             
             
             if($_POST[$tt[0]["nome"]]<>"0.00"){
               if($_POST["valor_total"]==$_POST["valor_sobra"]){
                foreach ($t as $tt):
                  if( $_POST[$tt["nome"]]<>"" AND $_POST[$tt["nome"]]<>"0.00"){
                    $contas->alterar_contas(
                      array(
                        'valor_parcela'=>$_POST[$tt["nome"]],
                        'id_tipo_documento'=>$_POST[$tt["id_tipo_documento"]],
                        'data_vencimento'=>$_POST[$tt["data_vencimento"]]
                        ),"id=".$tt["id"]
                    );
                  
                  }
                endforeach;
            
               echo "<script> 
                    status= $stats;
                    if(status=1){window.location.href = '/Contas/receita_listar/' }</script>";
                 
              }else{
                    echo "<div class='alert alert-danger col-md-10'>
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <i class='material-icons'>close</i>
                            </button>
                            <span>Atenção, <strong>Valor das Parcela diferente do valor total</strong></span>
                          </div>";

              }              
            }
        }            

    
      
         
        }else{
            $this->view('error_permisao');
        }

    }

   public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/Contas/receita_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $contas = new ContasModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits - Alterar Contas", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id");     
            $tipo=$this->getParams("tipo");     
            if(!empty($id)){
                $contas_dados=$contas->listar_contas($join, "1", "id=$id", $offset, $orderby);
                $contas_dados= $contas_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
            }            
            $form = new FormularioHelper();
                $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
                $inputs.= $form->Input("hidden", "tipo", null, $tipo, $required,null);                
                $inputs.= $form->select("Filial","id_filial","col-md-4",$filial->listar_Filial(NULL,NULL,"id_status <> 99",NULL,' Filial.id DESC',NULL),"nome_fantasia",$contas_dados["id_filial"]);
                $inputs.= $form->Input("date","data_vencimento","col-md-4",$contas_dados["data_vencimento"], "required","Data de Vencimento");
                $inputs.= $form->Input("number", "valor_parcela","col-md-4",$contas_dados["valor_parcela"], "required step=0.01","Valor da Parcela");
                $inputs.= $form->Input("text", "observacao","col-md-12",null, "required","Motivo da Alteração");
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar"); 
                $form->card("Alterar Contas",$inputs,"col-md-12",$comando,"POST","account_balance_wallet"); 
        }else{
            $this->view('error_permisao');
        }
    }

    
    public function form_receber (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/receber/";         
        if($acesso->acesso_valida("/Contas/receita_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $contas = new ContasModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits - Receber Parcela", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $id=$this->getParams("id"); 
            $tipo=$this->getParams("tipo"); 
            $hoje = date("Y-m-d");
            if(!empty($id)){
                $contas_dados=$contas->listar_contas($join, "1", "id=$id", $offset, $orderby,"Contas.id,Contas.id_tabela,Contas.id_filial,Contas.id_tipo_documento,Contas.parcela,Contas.valor_total,Contas.valor_troco,Contas.valor_parcela,Contas.data_vencimento,DATEDIFF ('$hoje',Contas.data_vencimento) AS dias_atraso,Contas.juros, if(DATEDIFF ('$hoje',Contas.data_vencimento)>=1, Contas.valor_parcela+(Contas.valor_parcela*((Contas.juros)* DATEDIFF ('$hoje',Contas.data_vencimento))), Contas.valor_parcela) AS 'valor_juros'");
                $contas_dados= $contas_dados[0];               
            }           
            $form = new FormularioHelper();
                $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
                $inputs.= $form->Input("hidden", "id_tabela", null, $contas_dados["id_tabela"], $required,null);                
                $inputs.= $form->Input("hidden", "id_filial", null, $contas_dados["id_filial"], $required,null);                
                $inputs.= $form->Input("hidden", "id_tipo_documento", null, $contas_dados["id_tipo_documento"], $required,null);                
                $inputs.= $form->Input("hidden", "tipo", null, $tipo, $required,null);                
                $inputs.= $form->Input("hidden", "parcela", null, $contas_dados["parcela"], $required,null);                
                $inputs.= $form->Input("hidden", "valor_total", null, $contas_dados["valor_total"], $required,null);                        
                $inputs.= $form->Input("hidden", "valor_troco", null, $contas_dados["valor_troco"], $required,null);         
                $inputs.= $form->Input("hidden", "valor_parcela",null,$contas_dados["valor_parcela"]);
                $inputs.= $form->Input("hidden", "dias_atraso",null,$contas_dados["dias_atraso"]); 
                
                if(empty($contas_dados["juros"])){  $contas_dados["juros"]=0; };
              
                $inputs.= $form->Input("text", "valor_pago","col-md-3","0", "required","Valor Pago");               
                $inputs.= $form->Input("text", "valor_parcela_falso","col-md-3",$contas_dados["valor_parcela"], "required","Valor da Parcela","disabled");
                if($contas_dados["dias_atraso"]<0){ 
                    $contas_dados["dias_atraso"]=1; 
                  $inputs.= $form->Input("text", "valor_juros_falso","col-md-3",$contas_dados["juros"], "required","Tx de Juros Dia","disabled");
                  $inputs.= $form->Input("hidden", "valor_juros",null,$contas_dados["juros"], "required");
                  $inputs.= $form->Input("text", "dias_atraso_falso","col-md-3",0, "required","Dias de Atraso","disabled");
                  $inputs.= $form->Input("hidden", "dias_atraso",null,$contas_dados["dias_atraso"], "required","Dias de Atraso");
                }else{
                  $inputs.= $form->Input("text", "valor_juros","col-md-3",$contas_dados["juros"], "required","Tx de Juros Dia");
                  $inputs.= $form->Input("text", "dias_atraso","col-md-3",$contas_dados["dias_atraso"], "required","Dias de Atraso","disabled");
                }
                
                $inputs.= $form->Input("text", "valor_juros_mes","col-md-3",money_format('%.2n', ((($contas_dados["juros"]*100)*30))), "required","Tx Juros Mês","disabled");
                
              
                $inputs.= $form->Input("text", "valor_parcela_juros","col-md-3",money_format('%.2n', ($contas_dados["valor_juros"])), "required","Parcela Com Juros");
                
                $inputs.= $form->Input("text", "valor_troco_falso","col-md-3 valor_troco_falso",$contas_dados["valor_troco"], "required","Troco");
                $inputs.= $form->Input("date","data_vencimento","col-md-3",$contas_dados["data_vencimento"], "required","Data de Vencimento");             
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                $form->card("Receber Parcela",$inputs,"col-md-12",$comando,"POST","account_balance_wallet");                      
        }else{
            $this->view('error_permisao');
        }
    }

    public function visualizar(){   
        $this->acesso_restrito();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        //echo $menu->Menu();
        $tipo ="Despesa"; //
        $id_conta_bancaria=$this->getParams("id_conta_bancaria");
        $comando='/Contas/visualizar/'; 
        $filial = new FilialModel();
        $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
        $dados['listar_filial'] = $listar_filial; 
        $tipo_documento = new TipoDocumentoModel();
        $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status<>99",NULL,' TipoDocumento.id DESC',NULL);
        $dados['listar_tipo_documento'] = $listar_tipo_documento;    
        $tipo_pagamento = new TipoPagamentoModel();
        $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99",NULL,' TipoPagamento.id DESC',NULL);
        $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento; 
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
        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Colaborador'",NULL,' Pessoa.id DESC',NULL);
        $dados['listar_colaborador'] = $listar_pessoa;    
        $status = new StatusModel();
        $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,' Status.id DESC',NULL);
        $dados['listar_status'] = $listar_status;  $venda = new VendaModel();
        $id = $this->getParams('id');
        $dados['id']=$id;

        if(!empty($id)){
            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens(NULL,NULL,"id_status<>99 AND id_tabela='{$id}' AND tabela='Venda'",NULL,' Itens.id DESC',NULL);
            $dados['listar_itens'] = $listar_itens;      
            $listar_venda = $venda->listar_venda(NULL,NULL,"id=$id AND id_status='4'",NULL,'Venda.id DESC');
            $dados['listar_contas'] = $listar_venda;
        } 
        $this->view('form_visualizar_contas',$dados);
    }
    
    public function atualiza_caixa($id,$valor) {
       // $venda = new VendaModel();
        $conta_bancaria = new ContaBancariaModel();
       $saldo= $conta_bancaria->listar_ContaBancaria($join, "1", "$id", $offset, $orderby, "saldo", $group, $pesquisa);
       $saldo = $saldo[0]["saldo"];
//$id = $venda->listar_Venda($join, "1", "id", $offset, $orderby, "id, id_conta_bancaria", $group, $pesquisa);
        $conta_bancaria->alterar_ContaBancaria(array("saldo"=>$valor+$saldo), "id='$id'");
        
    }
    public function receber(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Contas/receita_receber/';
        $tipo=$_POST["tipo"];
        if($acesso->acesso_valida($comando)==true){ 
            $id=$_POST['id'];         
            $data=$_POST["data_vencimento"];
            $id_venda=$_POST['id_tabela'];
            $venda = new VendaModel();
            $venda=$venda->listar_Venda($join, $limit, "id='$id_venda'", $offset, $orderby, "id_conta_bancaria");
            $id_conta_bancaria= $venda[0]["id_conta_bancaria"];
            $caixa_pai = new CaixaPModel();
            $caixa_pai = $caixa_pai->listar_Caixa($join, $limit, "id_status='10' AND id_caixa='{$id_conta_bancaria}'", $offset, $orderby, "id");
            if(!empty($caixa_pai[0]["id"])){
                if($_POST['valor_troco']<0){
                    $contas = new ContasModel();      
                    $teste=$contas->cadastrar_contas( 
                        array(
                            'id_filial'=>$_POST['id_filial'],//ok
                            'id_tabela'=>$_POST['id_tabela'],// 
                            'tabela'=>"Venda",
                            'tipo'=>"$tipo",                    
                            'parcela'=>"R/".$_POST['parcela'],    
                            'juros' => $_POST['valor_juros'],
                            'id_tipo_documento'=>$_POST['id_tipo_documento'],
                            'data_vencimento'=> $this->gerenciar_data($data, "m", 1),                  
                            'valor_total'=>$_POST['valor_total'],
                            'valor_parcela'=>abs($_POST['valor_troco']),
                            'id_status'=>"1",
                            'data_lancamento'=>  date("Y-m-d H:i:s"),
                        )
                    ); 
                }
                $valor_pago=$_POST['valor_pago'];
                if($_POST['valor_pago']>$_POST['valor_parcela_juros']){
                    $valor_pago=$_POST['valor_parcela_juros'];
                }
                $contas =  new ContasModel();
                $contas->alterar_contas(array(
                   'id_status'=>"4",
                   'valor_pago'=>$valor_pago,
                   'juros' => $_POST['valor_juros'],
                   'valor_troco'=>$_POST['valor_troco'],
                   'data_pagamento'=> date("Y-m-d H:i:s"),
                ), "id='$id'");
             
                $this->atualiza_caixa($id_conta_bancaria, $valor_pago);
                $caixa = new CaixaModel();
                $T=$caixa->cadastrar_caixa( 
                    array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_tabela'=>$id,
                        'tabela'=>"Contas",
                        'tipo'=>"$tipo",
                        'id_status'=>"1",
                        'id_caixa'=>$caixa_pai[0]["id"],
                        'data_lancamento'=>  date("Y-m-d H:i:s"),
                    ) 
                ); 
                $logs->cadastrar_logs($comando,$id);//Gera Logs 
                $redirect = new RedirectHelper();
                $redirect->goToUrl('/Contas/receita_listar/'); 
                }else{
                    $redirect = new RedirectHelper();
                    $redirect->goToUrl('/CaixaP/admin_listar/'); 
                }
        }else{
            $this->view('error_permisao');
        } 
    }     

    public function gerenciar_data(String $data , $tipo, $num){
        switch ($tipo){
            case "d":
                $tipo="1"+$num." day";
                break;
            case "m":
                $tipo="+"+$num." months";
                break;
            case "y":
                $tipo="1"+$num." year";
                break;
        }
        return date("Y-m-d",strtotime($tipo, strtotime($data)));
    }    

    public function gerenciar_plano_pagamento($id){
        $tipopagamento = new TipoPagamentoModel();
        return $tipopagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99 AND id={$id}",NULL,' TipoPagamento.id DESC',NULL);;
    }

    public function incluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Contas/incluir/';
        $id_venda=$_POST['id_tabela'];
        $id=$_POST['id'];
        $id_conta_bancaria=$_POST['id_conta_bancaria'];
        $id_tipo_documento=$_POST['id_tipo_documento'];
        $caixa_id= $_POST["caixa"];
        $tipo=$_POST['tipo'];
        $tipo2=$_POST['tipo2'];       
        if(!empty($_POST['valor_desconto']) OR $_POST['valor_desconto']>"0"){
          $valor_com_desconto=($_POST['valor_total']-($_POST['valor_desconto']*$_POST['valor_total']/100));
        }else{
          $valor_com_desconto=$_POST['valor_total'];
        }
        
        if( $_POST["opcao"]=="Salvar"){
            $venda = new VendaModel();      
            $venda->alterar_venda(
                array(
                    'id_filial'=>$_POST['id_filial'],
                    'id_tipo_documento'=>$id_tipo_documento,
                    'id_tipo_pagamento'=>$_POST['id_tipo_pagamento'],
                    'id_cliente'=>$_POST['id_pessoa'],
                    'id_conta_bancaria'=>$_POST['id_conta_bancaria'],
                    'id_plano_contas'=>$_POST['id_plano_contas'],
                    'id_colaborador'=>$_POST['id_colaborador'],
                    'valor_entrada'=>$_POST['valor_entrada'],
                    'valor_desconto'=>$_POST['valor_desconto'],
                    'data_vencimento'=>$_POST['data_vencimento'],
                    'codigo'=>$_POST['codigo'],
                    'tipo'=>$_POST['tipo'],
                    'id_status'=>$_POST['id_status'],
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
           $redirect = new RedirectHelper();
            if($tipo=="Receita"){
                $redirect->goToUrl('/Contas/receita_listar/');
            }else{
              $redirect->goToUrl('/Contas/despesa_listar/');
            }
            
        }else{
        $caixa_pai = new CaixaPModel();
        $caixa_pai = $caixa_pai->listar_Caixa($join, $limit, "id_status='10' AND id_caixa='{$id_conta_bancaria}'", $offset, $orderby, "id");
            if(!empty($caixa_pai[0]["id"])){
                $venda = new VendaModel();
                $id_cliente=0;
                $id_fornecedor=0;
                if($tipo=="Receita"){
                    $id_cliente=$_POST['id_pessoa'];
                }else{
                    $id_fornecedor=$_POST['id_pessoa'];
                }
                $venda->alterar_Venda(
                    array(
                        'id_filial'=>$_POST['id_filial'],  
                        'id_tipo_documento'=>$id_tipo_documento,
                        'id_tipo_pagamento'=>$_POST['id_tipo_pagamento'],  
                        'id_cliente'=>$id_cliente, 
                        'id_conta_bancaria'=>$id_conta_bancaria,
                        'id_colaborador'=>$_POST['id_colaborador'],
                        'valor_entrada'=>$_POST['valor_entrada'],
                        'valor_desconto'=>$_POST['valor_desconto'],
                        'data_vencimento'=>$_POST['data_vencimento'],
                        'id_fornecedor'=>$id_fornecedor,
                    ),"id=$id_venda"
                ); 
                if($acesso->acesso_valida($comando)==true){ 
                    $listar_tipopagamento=$this->gerenciar_plano_pagamento($_POST["id_tipo_pagamento"]);           
                    $controle_parcela=0;
                    $juros=$listar_tipopagamento[0]["juros"];
                    for($cont=1;$listar_tipopagamento[0]["quantidade"]>=$cont;$cont++){ 
                        if($listar_tipopagamento[0]["entrada"]=="1"){                   
                            $listar_tipopagamento[0]["entrada"]="0";
                            $contas = new ContasModel();   
                            if(($_POST['valor_entrada']>=$valor_com_desconto)OR($_POST['valor_entrada']==""OR($_POST['valor_entrada']=="0"))){
                                $valor_parcela=$valor_com_desconto; 
                                $valor_pago=$valor_com_desconto; 
                            }else{
                                $valor_parcela=$_POST['valor_entrada'];                    
                                $valor_pago=$_POST['valor_entrada'];        
                                $valor_com_desconto=$valor_com_desconto-$_POST["valor_entrada"];
                            }
                            if($_POST['valor_entrada']>=$valor_com_desconto){
                                $valor_parcela=$valor_com_desconto;                        
                            }else{
                                $valor_parcela=$_POST['valor_entrada'];                    
                            }
                            $id=$contas->cadastrar_contas( 
                                array(
                                    'id_filial'=>$_POST['id_filial'],//ok
                                    'id_tabela'=>$id_venda,//
                                    'tabela'=>$_POST['tabela'],
                                    'tipo'=>"$tipo",                   
                                    'parcela'=>$cont."/".$listar_tipopagamento[0]["quantidade"],                   
                                    'data_vencimento'=> $_POST["data_vencimento"],    
                                    'juros'=> $juros,    
                                    "id_tipo_documento" => $id_tipo_documento,
                                    'data_pagamento'=> date("Y-m-d H:i:s"),                  
                                    'valor_total'=>$valor_com_desconto,
                                    'valor_troco'=>"00",
                                    'valor_entrada'=>$_POST['valor_entrada'],
                                    'valor_desconto'=>$_POST['valor_desconto'],
                                    'valor_pago'=>$valor_pago,
                                    'valor_parcela'=>$valor_parcela,
                                    'id_status'=>$_POST['id_status'],
                                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                                )
                            );
                            $caixa = new CaixaModel();                   
                            $caixa->cadastrar_caixa( 
                                array(
                                    'id_filial'=>$_POST['id_filial'],
                                    'id_tabela'=>$id,
                                    'tabela'=>"Contas",
                                    'tipo'=>"$tipo",
                                    'id_caixa'=>$caixa_pai[0]["id"],
                                    'id_status'=>"1",
                                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                                ) 
                            );  
                            $this->atualiza_caixa($id_conta_bancaria, $valor_pago);
                            if($tipo=="Receita"){
                              $contas->alterar_contas( array("id_status"=>"4"), "id=$id"); 
                            }else{
                              $contas->alterar_contas( array("id_status"=>"121"), "id=$id"); 
                            }
                           // $contas->alterar_contas( array("id_status"=>"4"), "id=$id");                     
                            $controle_parcela=1;
                        }else{ 
                            $contas = new ContasModel();     
                            $mes=$cont-1;
                            $id=$contas->cadastrar_contas( 
                                array(
                                    'id_filial'=>$_POST['id_filial'],//ok
                                    'id_tabela'=>$id_venda,//
                                    'tabela'=>$_POST['tabela'],
                                    'tipo'=>"$tipo",                   
                                    'parcela'=>$cont."/".$listar_tipopagamento[0]["quantidade"],      
                                    "id_tipo_documento" => $id_tipo_documento,
                                    'data_vencimento'=> $this->gerenciar_data($_POST["data_vencimento"], "m", "$mes"), 
                                    'valor_desconto'=>$_POST['valor_desconto'],
                                    'juros'=> $juros,    
                                    'valor_total'=>$valor_com_desconto,
                                    'valor_parcela'=>(($valor_com_desconto)/(($listar_tipopagamento[0]["quantidade"]-$controle_parcela))),
                                    'id_status'=>$_POST['id_status'],
                                    'data_lancamento'=>  date("Y-m-d H:i:s"),
                                )
                            );
                        }
                        $venda = new VendaModel();
                        $venda->alterar_Venda( array("id_status"=>"3"), "id=$id_venda"); 
                        $logs->cadastrar_logs($comando,$id);//Gera Logs
                    }
                    $redirect = new RedirectHelper();
                    if($tipo2=="Pdv"){
                        $redirect->goToUrl("/Venda/pdv/id/0/caixa/$caixa_id/"); 
                    }elseif($tipo=="Receita"){ 
                       $redirect->goToUrl("/Contas/form_especializacao/id/$id_venda/tipo/$tipo"); 
                    }else{ 
                        $redirect->goToUrl('/Contas/despesa_listar/');
                    }           
                }else{
                    $this->view('error_permisao');
                }
            }else{
                $redirect = new RedirectHelper();
                $redirect->goToUrl('/CaixaP/admin_listar/');
            }
        }
    }
    public function alterar(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Contas/alterar/';
        if($acesso->acesso_valida($comando)==true){
            $id = $_POST['id'];
            $contas = new ContasModel();      
            $contas->alterar_contas(
                array(
                    'id_filial'=>$_POST['id_filial'],
//                  'id_tabela'=>$_POST['id_tabela'],
//                  'tabela'=>$_POST['tabela'],
//                  'tipo'=>$_POST['tipo'],
//                  'data_pagamento'=>$_POST['data_pagamento'],
                    'data_vencimento'=>$_POST['data_vencimento'],
//                  'valor_pago'=>$_POST['valor_pago'],
                    'valor_parcela'=>$_POST['valor_parcela'],
//                  'valor_total'=>$_POST['valor_total'],
//                  'valor_troco'=>$_POST['valor_troco'],
//                  'id_status'=>$_POST['id_status'],
//                  rs
//                  'data_lancamento'=>$_POST['data_lancamento'],
                ),'id='.$id
            );  
            $logs->cadastrar_logs($comando,$id,$_POST['observacao']);//Gera Logs
            $redirect = new RedirectHelper();
            if($_POST['tipo']=="Venda"){
                $redirect->goToUrl('/Contas/receita_listar/');              
            }else{
                $redirect->goToUrl('/Contas/despesa_listar/');                  
            }
        }else{
            $this->view('error_permisao');
        }
    }


    public function excluir(){    
        $this->acesso_restrito();   
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Contas/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            if(empty($id)){  $id = $_POST['id']; }
            $contas = new ContasModel();      
            $contas->excluir_contas( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs            
            $redirect = new RedirectHelper();           
            if($_POST['tabela']=="Venda"){
                $redirect->goToUrl('/Contas/receita_listar/');            
            }else{
                $redirect->goToUrl('/Contas/despesa_listar/');                  
            }    
        }else{
            $this->view('error_permisao');
        }
    } 
}

?>