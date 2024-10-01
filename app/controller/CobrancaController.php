<?php
class Cobranca extends Controller {
  private  $auth,
  $db;

  public function acesso_restrito() {
    $this->auth = new AutenticaHelper();
    $this->auth->setLoginControllerAction('Index', '')
    ->checkLogin('redirect');
    $this->db = new AdminModel();
  }
//  public function admin_listar() {
//    $this->acesso_restrito();
//    $acesso = new AcessoHelper();
//    $logs = new LogsModel();
//    $comando = "/".__CLASS__."/incluir/";
//    if ($acesso->acesso_valida("/Contas/admin_listar/") == true) {
//      $filiais = $acesso->acesso_filial(__CLASS__);
//      $cobranca =  new ContasModel();
//      $pessoa = new PessoaModel();    
//      $dias_cobranca=$_POST["dias"];
//      $Pesquisa1=$_POST["Pesquisa"];
//      if(empty($Pesquisa1)){
//        $Pesquisa="";
//      }else{
//        $Pesquisa="(Venda.id_cliente='$Pesquisa1' OR Venda.id_colaborador='$Pesquisa1' )AND";
//      }
//      $data_cobranca = date("Y-m-d", mktime(0, 0, 0, date('m') ,date('d')-$dias_cobranca, date('Y')));
//      $data_final = date("Y-m-d", mktime(0, 0, 0, date('m') ,date('d'), date('Y')));
//    
//      //Contas.id_status='1' AND Contas.tipo='Receita' AND
//       $listar_cobranca_parcela = $cobranca->listar_contas(
//                    "INNER JOIN Filial ON Filial.id = Contas.id_filial
//                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
//                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
//                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente",
//                    "25",
//                    " $Pesquisa Contas.data_vencimento >= '$data_cobranca' AND Contas.data_vencimento <= '$data_final' AND Contas.id_status='1'",
//                    NULL,
//                    'Contas.data_vencimento ASC',
//                    "Contas.id,
//                        Filial.nome_fantasia AS Filial,
//                        PessoaCliente.nome AS Cliente,
//                        PessoaCliente.cpf AS CPF,
//                        Contas.valor_total AS 'Valor Total',
//                        Contas.valor_parcela AS 'Valor Parcela',
//                        TipoDocumento.descricao AS Documento,                       
//                        Contas.data_vencimento AS 'Data Vencimento',
//                        DATEDIFF ( '$data_final',Contas.data_vencimento) AS 'Atraso',
//                        Contas.parcela AS Parcela
//                        ",null,$pesquisa);
//       
//       
//      $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);
//      echo $menu->Menu(); 
//      $form = new FormularioHelper();
//      $inputs.= $form->select("Cliente/Colaborador", "Pesquisa", "col-md-9", $pessoa->listar_Pessoa($join, $limit, "id_status='1' AND tipo<>'Fornecedor'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
//      $inputs.= $form->Input("number", "dias", "col-md-3", $comissao_doutor, "Required", "Dias de Atraso", $disable, $id);
//      $inputs.= $form->Button("btn btn-md btn-rose ", "Pesquisa");
//   
//      $inputs.= $form->Listar("col-md-12", null, null, $icone, $listar_cobranca_parcela, "tabela1", array(array("acao"=>"/Cobranca/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")),$pesquisa);
//   
//      $form->card("Gestao de Acesso",$inputs,"col-md-12","#","POST","list");
//    } else {
//      $this->view('error_permisao');
//    }
//  }
   
  public function admin_listar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/".__CLASS__."/incluir/";
    if ($acesso->acesso_valida("/Contas/admin_listar/") == true) {
      $filiais = $acesso->acesso_filial(__CLASS__);
      $cobranca =  new ContasModel();
      $pessoa = new PessoaModel();  
      $historico = new HistoricoModel();    
      $id = $this->getParams("id"); 
      $id_cliente = $_POST["Pesquisa"];//$this->getParams("id_cliente"); 
      if(empty($id_cliente)){ $id_cliente =$id; }
      $atraso= $this->getParams("Atraso"); 
      $hoje=date("Y-m-d");
      echo $id_cliente;
      $endereco = new EnderecoModel();
       $contato = new ContatoModel();

      
    
      $listar_cobranca_conta= $cobranca->listar_contas(  "INNER JOIN Filial ON Filial.id = Contas.id_filial
                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
                        INNER JOIN Pessoa AS PessoaCliente ON (PessoaCliente.id = Venda.id_cliente )
                        INNER JOIN Pessoa AS PessoaColaborador ON (PessoaColaborador.id = Venda.id_colaborador )", 
                    null,
                    " (Venda.id_cliente='$id_cliente') AND DATEDIFF ('$hoje',Contas.data_vencimento)>'0'AND Contas.id_status='1' AND Venda.tipo='Receita'",
                    NULL,
                    'Contas.data_vencimento ASC',
                    "Venda.id, 
                      Contas.id AS id_tabela,
                      Filial.id AS id_filial,
                      Venda.id AS 'Contrato', 
                      PessoaColaborador.nome AS Doutor,
                      PessoaCliente.id AS id_cliente,
                      PessoaCliente.nome AS Cliente,
                      PessoaCliente.cpf AS CPF,
                      Contas.valor_parcela AS 'Valor Parcela',
                      Contas.valor_total AS 'Valor Total',
                      TipoDocumento.descricao AS Documento,                       
                      Contas.data_vencimento AS 'Data Vencimento',
                      DATEDIFF ('$hoje',Contas.data_vencimento) AS 'Dias de Atraso',
                      Contas.parcela AS Parcela
                        ",null,$pesquisa);
      $listar_cobranca_historico= $cobranca->listar_contas("
                        INNER JOIN Filial ON Filial.id = Contas.id_filial
                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente                         
                        INNER JOIN Pessoa AS PessoaColaborador ON (PessoaColaborador.id = Venda.id_colaborador )
                        INNER JOIN Historico ON Historico.id_tabela=Contas.id",
                    null,
                    " (Venda.id_cliente='$id_cliente') AND DATEDIFF ('$hoje',Contas.data_vencimento)>'0' AND Venda.tipo='Receita'",
                    NULL,
                    'Contas.data_vencimento ASC',
                    "Contas.id, 
                      Contas.id AS id_tabela,
                      Filial.id AS id_filial,
                      Venda.id AS 'Contrato',
                      PessoaColaborador.nome AS Doutor,
                      PessoaCliente.nome AS Cliente,
                      PessoaCliente.cpf AS CPF,
                      Contas.valor_parcela AS 'Valor Parcela',
                      TipoDocumento.descricao AS Documento,                       
                      Contas.data_vencimento AS 'Data Vencimento',
                      DATEDIFF ('$hoje',Contas.data_vencimento) AS 'Dias de Atraso',
                      Contas.parcela AS Parcela,
                      Historico.observacao AS 'Cobrança',
                      Historico.data_lancamento AS 'Data da Cobrança'
                        ",null,$pesquisa);
      
            $orcamento_receita = new VendaModel();
            $listar_cobranca_venda =  $orcamento_receita->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_status<>'1' AND Venda.id_status<>'99' AND Venda.tipo='Receita' AND Venda.id_cliente='$id_cliente'",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,   
                        Venda.id AS id_tabela,  
                      Venda.id AS 'Contrato',
                       Filial.id AS id_filial,
                       PessoaCliente.id AS id_cliente,
                        PessoaCliente.nome AS Cliente,
                        PessoaCliente.cpf AS CPF,
                        TipoDocumento.descricao AS Documento,     
                        Status.cor AS cor_Status,
                        (SELECT valor_total FROM Contas WHERE Contas.id_tabela=Venda.id limit 1)AS 'Valor Total',
                        Status.descricao AS Status,
                        Venda.data_lancamento
                        ",null,$pesquisa);
            
                       
                        
      $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);
      echo $menu->Menu(); 
      $form = new FormularioHelper();
              
//                    $inputs.= '<nav aria-label="" role="navigation" >
//                                <ol class="breadcrumb" style="background:white">
//                                  <li class="breadcrumb-item btn-rose"><a href="javascript:;">Cobrança</a></li>
//                                  <li class="breadcrumb-item"><a href="javascript:;">Formulario</a></li>
//                                  
//                                </ol>
//                              </nav>';
               
      
      $inputs.= $form->select("Pessoa", "Pesquisa", "col-md-12", $pessoa->listar_Pessoa($join, $limit, "id_status='1' AND tipo='Cliente'", $offset, $orderby, "id,nome", $group), "nome", $id_cliente); 

      $inputs.= $form->Button("btn btn-md btn-rose ", "Pesquisa"); 
      $inputs.=  $form->Listar("col-md-8", "Endereços para Cobrança", null, $icone,  $endereco->listar_Endereco($join,NULL,"Endereco.id_status<>'99' AND Endereco.tabela='Cliente' AND Endereco.id_tabela='{$id_cliente}'",NULL,' Endereco.id DESC',"Endereco.id,Endereco.pais AS 'Pais',Endereco.estado AS 'Estado',Endereco.cep AS CEP,Endereco.cidade AS 'Cidade',Endereco.logradouro AS 'Logradouro', Endereco.complemento") , "tabela_endereco",$acao,$pesquisa);  
      $inputs.=  $form->Listar("col-md-3", "Contatos para Cobrança", null, $icone,  $contato->listar_Contato(NULL,NULL,"id_status<>'99' AND tabela='Cliente' AND id_tabela='{$id_cliente}'",NULL,' Contato.id DESC',"Contato.id,descricao AS 'Descrição', contato AS 'Contato'")  , "tabela_contato",$acao,$pesquisa);  
    
      $listar_cobranca_conta = $form->Listar("col-md-12", "Cobrança Por Parcela em Atraso", null, "list",  $listar_cobranca_conta, "tabela1", array(array("acao"=>"/Historico/form/tabela/Cobranca/","classe"=>"btn-sm btn-rose","icone"=>"+"),array("acao"=>"/Venda/visualizar/tipo/Receita/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye")),$pesquisa);  
        $listar_cobranca_venda=$form->Listar("col-md-12", "Cobrança Por Contrato de Venda","/Venda/form/tipo/Receita/", "fact_check", $listar_cobranca_venda, "tabela_orcamento", array(array("acao"=>"/Historico/form/tabela/Venda/","classe"=>"btn-sm btn-rose","icone"=>"+"),array("acao"=>"/Venda/visualizar/tipo/Receita/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye")),$pesquisa);
        $listar_cobranca_historico = $form->Listar("col-md-12", "Cobrança Por Parcela em Atraso", null, "list",  $listar_cobranca_historico, "tabela_historico", $action,$pesquisa);  
       $inputs.= $form->Abas($Tipo, "Pefil", "col-md-12", 
                array(array("id" => "Acessos", "icone" => "apartment", "descricao" => "Cobranças","classe" => "active"),
                    array("id" => "Historico", "icone" => "apartment", "descricao" => "Historico")),
                array(array("id" => "Acessos", "dados" =>" $listar_cobranca_conta $listar_cobranca_venda " ,"classe" => " active"),
                    array("id" => "Historico", "dados" =>" $listar_cobranca_historico  " ))); 
                
   
      $form->card("Historico de cobrança",$inputs,"col-md-12","#","POST","list");
    } else {
      $this->view('error_permisao');
    }
  }
  
//   public function visualizar() {
//    $this->acesso_restrito();
//    $acesso = new AcessoHelper();
//    $logs = new LogsModel();
//    $comando = "/".__CLASS__."/incluir/";
//    if ($acesso->acesso_valida("/Contas/admin_listar/") == true) {
//      $filiais = $acesso->acesso_filial(__CLASS__);
//      $cobranca =  new ContasModel();
//      $pessoa = new PessoaModel();  
//      $historico = new HistoricoModel();    
//      $id = $this->getParams("id"); 
//      $atraso= $this->getParams("Atraso"); 
//      
//      $listar_historico = $historico->listar_Historico(null,null,"Tabela='Cobranca' AND id_tabela='$id'");
//    
//      
//      $listar_cobranca= $cobranca->listar_contas(  "INNER JOIN Filial ON Filial.id = Contas.id_filial
//                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
//                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
//                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente",
//                    "25",
//                    " Contas.id='$id'",
//                    NULL,
//                    'Contas.data_vencimento ASC',
//                    "Contas.id,
//                      Filial.id AS id_filial,
//                      Filial.nome_fantasia AS Filial,
//                      PessoaCliente.nome AS Cliente,
//                      PessoaCliente.cpf AS CPF,
//                      Contas.valor_total AS 'Valor Total',
//                      Contas.valor_parcela AS 'Valor Parcela',
//                      TipoDocumento.descricao AS Documento,                       
//                      Contas.data_vencimento AS 'Data Vencimento',
//                      DATEDIFF ( '$data_final',Contas.data_vencimento) AS 'Dias de Atraso',
//                      Contas.parcela AS Parcela
//                        ",null,$pesquisa);
//      $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);
//      echo $menu->Menu(); 
//      $form = new FormularioHelper();
//      $inputs.="<form class='col-md-12 row'>";
//        $inputs.= $form->Input("text", "dias", "col-md-3",$listar_cobranca[0]["Filial"], "Required", "Filial", $disable, $id);
//        $inputs.= $form->Input("text", "dias", "col-md-6",$listar_cobranca[0]["Cliente"], "Required", "Cliente", $disable, $id);
//        $inputs.= $form->Input("text", "dias", "col-md-3",$listar_cobranca[0]["cpf"], "Required", "CPF", $disable, $id);
//        $inputs.= $form->Input("number", "dias", "col-md-3",$listar_cobranca[0]["Valor Total"], "Required", "Valor Total", $disable, $id);
//        $inputs.= $form->Input("text", "dias", "col-md-3",$listar_cobranca[0]["Documento"], "Required", "Documento", $disable, $id);
//        $inputs.= $form->Input("number", "dias", "col-md-2",$listar_cobranca[0]["Dias de Atraso"], "Required", "Dias de Atraso", $disable, $id);
//        $inputs.= $form->Input("text", "dias", "col-md-2",$listar_cobranca["Parcela"], "Required", "Parcela", $disable, $id);
//        $inputs.= $form->Input("date", "dias", "col-md-2",$listar_cobranca[0]["Data Vencimento"], "Required", "Data Vencimento", $disable, $id);
//      $inputs.="</form>";
//      
//       $listar_historico = $form->Listar("col-md-12", null, "/Historico/form/id_filial/".$listar_cobranca[0]["id_filial"]."/id_tabela/".$listar_cobranca[0]["id"]."/tabela/Cobranca/", "list", $listar_historico, "tabela1", array(array("acao"=>"#","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
//      
//       $inputs.= $form->Abas($Tipo, "Pefil", "col-md-12", 
//                array(array("id" => "Acessos", "icone" => "apartment", "descricao" => "Cobranças","classe" => "active")),
//                array(array("id" => "Acessos", "dados" =>"$listar_historico" ,"classe" => " active"))); 
//                
//   
//      $form->card("Historico de cobrança",$inputs,"col-md-12",null,"POST","list");
//    } else {
//      $this->view('error_permisao');
//    }
//  }
  
   public function form_historico(){                

        $this->acesso_restrito();        
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/Pessoa/alterar_historico_pessoa/";         
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $pessoa = new PessoaModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
            $pessoa_dados=$pessoa->listar_Pessoa($join, null, "id_status<>'99'", $offset, $orderby);                
            $id = $this->getParams("id");

            $form = new FormularioHelper();
            $inputs.= $form->select("Pessoa","id_tabela", "col-md-8",$pessoa_dados,"nome",$id);
            $inputs.= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral' OR tabela='Pessoa'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Input("text", "observacao", "col-md-12",null, "required","Observação");
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card("Cadastro de $tipo",$inputs,"col-md-12",$comando,"POST","people");

        }else{
               $this->view('error_permisao');
           }

    }
  
  
   
} 