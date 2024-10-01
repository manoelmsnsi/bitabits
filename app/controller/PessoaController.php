<?php class Pessoa extends Controller {   
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
            $acesso = new SessionHelper();  
            $status= new StatusModel();
            $filial = new FilialModel(); 
            $pessoa = new PessoaModel();   
            $id=$this->getParams("id");      
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; } 
            $listar_cliente = $pessoa->listar_Pessoa("INNER JOIN Status ON Status.id=Pessoa.id_status INNER JOIN Filial ON Filial.id=Pessoa.id_filial","25","Pessoa.id_status<>99 AND Pessoa.tipo='Cliente'",null,' Pessoa.id DESC',"Pessoa.id,Filial.nome_fantasia AS Filial,Pessoa.nome AS Nome,Pessoa.cpf AS CPF,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa);
            $listar_colaborador = $pessoa->listar_Pessoa("INNER JOIN Status ON Status.id=Pessoa.id_status INNER JOIN Filial ON Filial.id=Pessoa.id_filial","25","Pessoa.id_status<>99 AND Pessoa.tipo='Colaborador'",null,' Pessoa.id DESC',"Pessoa.id,Filial.nome_fantasia AS Filial,Pessoa.nome AS Nome,Pessoa.cpf AS CPF,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa);
            $listar_fornecedor  = $pessoa->listar_Pessoa("INNER JOIN Status ON Status.id=Pessoa.id_status INNER JOIN Filial ON Filial.id=Pessoa.id_filial","25","Pessoa.id_status<>99 AND Pessoa.tipo='Fornecedor'",null,' Pessoa.id DESC',"Pessoa.id,Filial.nome_fantasia AS Filial,Pessoa.nome AS Nome,Pessoa.cpf AS CPF,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa);
            $listar_acesso=$acesso->selectSession('userAcesso');
            $user_dados=$acesso->selectSession('userData');        
            $menu = new MenuHelper("Bitabits", $Class, $AcaoForm, $MetodoDeEnvio);              
            echo $menu->Menu($pesquisa);         
            $form = new FormularioHelper();
            $listar_pessoa=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Cliente/", $icone, $listar_cliente, "tabela1", array(array("acao"=>"/Pessoa/form/tipo/Cliente/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Cliente/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
            $listar_colaborador=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Colaborador/", $icone, $listar_colaborador, "tabela2", array(array("acao"=>"/Pessoa/form/tipo/Cliente/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Colaborador/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
            $listar_fornecedor=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Fornecedor/", $icone, $listar_fornecedor, "tabela3", array(array("acao"=>"/Pessoa/form/tipo/Fornecedor/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Fornecedor/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);          
            $inputs.= $form->Abas($Tipo, "teste", "col-md-12", array(array("id"=>"cliente","icone"=>"people","descricao"=>"Cliente","classe"=>" active"),array("id"=>"colaborador","icone"=>"people","descricao"=>"Colaborador"),array("id"=>"fornecedor","icone"=>"people","descricao"=>"Fornecedor")),array(array("id"=>"cliente","dados"=>"$listar_pessoa","classe"=>" active"),array("id"=>"colaborador","dados"=>"$listar_colaborador"),array("id"=>"fornecedor","dados"=>"$listar_fornecedor")));
            
            $form->card("Cadastro de Pessoa",$inputs,"col-md-12",$comando,"POST","people");
          
        }  
    }  
   
    public function visualizar(){
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Pessoa/visualizar/';
           if($acesso->acesso_valida($comando)==true){ 
             $acesso = new SessionHelper();
           $userData=$acesso->selectSession("userData");
           $id_colaborador= $userData["id_colaborador"];
                $id = $this->getParams('id');
                $id_cliente=$this->getParams('id_cliente');
                if($id<>$id_cliente){
                  if(empty($id_cliente)){
                    
                  }else{
                    $id=$id_cliente;
                  }
                  
                }
                $tabela = $this->getParams('tabela');
                $acesso = new SessionHelper();
                $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);       
                echo $menu->Menu();
              
                $form = new FormularioHelper();
                $cliente = new PessoaModel();
                $listar_cliente = $cliente->listar_Pessoa(NULL,NULL,"id_status<>'99 'AND id='{$id}'",NULL,' Pessoa.id DESC',NULL);            
                            
                $status = new StatusModel();
                $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,' Status.id DESC',NULL); 
              
                $filial = new FilialModel();
                $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL); 
                          
                $eclesiastico = new EclesiasticoModel();  
                $eclesiastico_dados =$eclesiastico->listar_Eclesiastico("INNER JOIN Status ON Status.id = Eclesiastico.id_status INNER JOIN Filial ON Filial.id = Eclesiastico.id_filial",NULL,"Eclesiastico.id_status<>99 AND Eclesiastico.id_tabela='{$id}'",NULL,' Eclesiastico.id DESC',"Eclesiastico.id,Eclesiastico.batismo_espirito,Eclesiastico.data_batismo_espirito,Eclesiastico.batismo_agua,Eclesiastico.data_batismo_agua,Eclesiastico.cidade_batismo,Eclesiastico.igreja_batismo,Eclesiastico.data_conversao,Eclesiastico.classe_ebd,Eclesiastico.codigo_membro,Eclesiastico.data_admissao,Eclesiastico.tipo_admissao,Eclesiastico.data_emissao_carteira,Eclesiastico.vencimento_carteira,Eclesiastico.id_tabela,Eclesiastico.tabela,Eclesiastico.data_lancamento,Eclesiastico.observacoes,Status.descricao AS descricao_status,Filial.nome_fantasia AS nome_filial");
                $eclesiastico_dados=$eclesiastico_dados[0];  
                if(empty($eclesiastico_dados)){
                       $listar_eclesiastico =$form->Listar($classe, "Eclesiastico", "/Eclesiastico/form/id_tabela/$id/tabela/$tabela/", $icone, $eclesiastico_dados, "tabela_eclesiastico", $acao, $pesquisa);
                   }else{
                        $listar_eclesiastico.= $form->Input("text", "codigo_membro", "col-md-4", $eclesiastico_dados["nome_filial"], "required","Filial","disabled");
                        $listar_eclesiastico.= $form->Input("text", "codigo_membro", "col-md-4", $eclesiastico_dados["codigo_membro"], "required","Cód. Membro","disabled");
                        $listar_eclesiastico.= $form->Input("text", "data_admissao", "col-md-3", $eclesiastico_dados["tipo_admissao"], $required,"Tipo Admissão","disabled");
                        $listar_eclesiastico.= "<a class='btn btn-sm btn-warning btn-just-icon ' href='/Eclesiastico/form/id/".$listar_eclesiastico["id"]."/id_tabela/".$listar_cliente["id"]."/tabela/".$listar_cliente["tipo"]."'>  <i class='material-icons'>edit</i></a></a>";
                        
                        $listar_eclesiastico.= $form->Input("date", "data_admissao", "col-md-3", $eclesiastico_dados["data_admissao"], $required,"Data de Admissão","disabled");
                        $listar_eclesiastico.= $form->Input("date", "data_emissao_carteira", "col-md-3", $eclesiastico_dados["data_emissao_carteira"], $required,"Emissão do Cartão","disabled");
                        $listar_eclesiastico.= $form->Input("date", "vencimento_carteira", "col-md-3", $eclesiastico_dados["vencimento_carteira"], $required,"Vencimento Cartão","disabled");
                        $listar_eclesiastico.= $form->Input("date", "data_conversao", "col-md-3", $eclesiastico_dados["data_conversao"], $required,"Data da Conversão","disabled");
                        
                        $listar_eclesiastico.= $form->Input("text", "data_batismo_agua", "col-md-3", $eclesiastico_dados["batismo_agua"], $required,"Batizado nas Águas?","disabled");
                        $listar_eclesiastico.= $form->Input("date", "data_batismo_agua", "col-md-3", $eclesiastico_dados["data_batismo_agua"], $required,"Data","disabled");
                        $listar_eclesiastico.= $form->Input("text", "igreja_batismo", "col-md-3", $eclesiastico_dados["igreja_batismo"], $required,"Igreja do Batismo","disabled");
                        $listar_eclesiastico.= $form->Input("text", "cidade_batismo", "col-md-3", $eclesiastico_dados["cidade_batismo"], $required,"Cidade do Batismo","disabled");
                        
                        $listar_eclesiastico.= $form->Input("text", "data_batismo_espirito", "col-md-3", $eclesiastico_dados["batismo_espirito"], $required,"Batizado no Espirito Santo?","disabled");
                        $listar_eclesiastico.= $form->Input("date", "data_batismo_espirito", "col-md-3", $eclesiastico_dados["data_batismo_espirito"], $required,"Data","disabled");
                        $listar_eclesiastico.= $form->Input("text", "classe_ebd", "col-md-3", $eclesiastico_dados["classe_ebd"], $required,"Classe EBD","disabled");           
                        $listar_eclesiastico.= $form->Input("text", "observacoes", "col-md-3", $eclesiastico_dados["id_status"], $required,"Status","disabled"); 
                        $listar_eclesiastico.= $form->Input("text", "observacoes", "col-md-12", $eclesiastico_dados["observacoes"], $required,"Observações","disabled"); 
                   }
//                                              
                $cargo = new CargoModel();               
                $listar_cargo = $form->Listar("col-md-12", "Cargo", "/Cargo/form/id_tabela/$id/tabela/$tabela/", "file_copy", $cargo->listar_Cargo("INNER JOIN Status ON Status.id = Cargo.id_status ",NULL,"Cargo.id_status<>'99' AND Cargo.tabela='$tabela' AND Cargo.id_tabela='{$id}'",NULL,' Cargo.id DESC',"Cargo.id,Cargo.descricao AS 'Descrição',Cargo.data_posse AS 'Data Posse',Cargo.data_saida AS 'Data Saida',Status.cor AS 'cor_Status',Status.descricao AS Status, Cargo.observacao AS 'Observação'"), "tabela_cargo", array(array("acao"=>"/Cargo/form/tabela/$tabela/id_tabela/$id/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/id_tabela/$id/classe/Cargo/$tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);
               
                $familia = new FamiliaModel();               
                $listar_familia = $form->Listar("col-md-12", $titulo, "/Familia/form/id_tabela/$id/tabela/$tabela/", "wc",  $familia->listar_Familia("INNER JOIN Status ON Status.id = Familia.id_status",NULL,"Familia.id_status<>99 AND Familia.tabela='$tabela' AND Familia.id_tabela='{$id}'",NULL,' Familia.id DESC',"Familia.id,Familia.tipo AS 'Tipo',Familia.nome AS 'Nome',Familia.contato as Contato,Status.cor AS 'cor_Status',Status.descricao AS Status,Familia.data_nascimento AS 'Data Nascimento'"), "tabela_familia", array(array("acao"=>"/Familia/form/tabela/$tabela/id_tabela/$id/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/id_tabela/$id/classe/Familia/$tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);
        
                $escolaridade = new EscolaridadeModel();               
                $listar_escolaridade = $form->Listar("col-md-12", $titulo, "/Escolaridade/form/id_tabela/$id/tabela/$tabela/", "school",$escolaridade->listar_Escolaridade("INNER JOIN Status ON Status.id = Escolaridade.id_status ",NULL,"Escolaridade.id_status<>'99' AND Escolaridade.tabela='$tabela' AND Escolaridade.id_tabela='{$id}'",NULL,' Escolaridade.id DESC',"Escolaridade.id, Escolaridade.tipo AS 'Formação',Escolaridade.escola AS 'Escola' ,Escolaridade.prev_termino AS 'Termino', Escolaridade.ano_conclusao AS 'Conclusão', Status.cor AS 'cor_Status', Status.descricao AS Status, Escolaridade.observacoes AS 'Observação', Escolaridade.data_lancamento AS 'Data Lançamento'") , "tabela_escolaridade", array(array("acao"=>"/Escolaridade/form/tipo/$tabela/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/id_tabela/$id/classe/Escolaridade/$tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);             
                
                 
                $historico = new HistoricoModel();               
                $listar_historico = $form->Listar("col-md-12", 'Historico Pessoa', "/Pessoa/form_historico/id/$id/", "assignment",$historico->listar_Historico("INNER JOIN Status ON Status.id = Historico.id_status ",NULL,"Historico.id_status<>'99' AND Historico.tabela='Pessoa' AND Historico.id_tabela='{$id}'",NULL,' Historico.id DESC',"Historico.id ,Historico.observacao AS 'Observação',Status.cor AS 'cor_Status',Status.descricao AS Status,Historico.data_lancamento AS 'Data Lançamento'") , "tabela_historico", array(array("acao"=>"/Logs/form_alterar/id_tabela/$id/classe/Historico/tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 
               
                $agenda = new AgendaModel();               
                $listar_agenda = $form->Listar("col-md-12", null, "/Agenda/form/", $icone,$agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status<>'99'AND Agenda.id_cliente='$id' ",NULL,' Agenda.data_atendimento ASC,Agenda.hora_inicio',"Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento AS 'Data Atendimento',Agenda.hora_inicio AS Inicio,Agenda.hora_fim AS Fim,Agenda.tempo_atendimento AS 'Tempo Médio',Status.cor AS cor_Modelo,Agenda.tipo AS Modelo,Agenda.observacao AS 'Observação',Status.cor AS cor_Status,Status.descricao AS 'Status',Colaborador.nome as 'Colaborador',(SELECT Contato.contato FROM Contato  WHERE Contato.descricao = 'Celular' and Contato.id_tabela = Cliente.id LIMIT 1) as 'Telefone'"), "tabela1" );
                                     
                
                $upload = new UploadModel();
                $listar_upload = $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/$id/tabela/$tabela/id_filial/".$listar_cliente[0]["id_filial"]."/", "upload",$upload->listar_Upload(NULL,NULL,"id_status<>'99' AND tabela='Cliente' AND id_tabela='{$id}'",NULL,' Upload.id DESC',"Upload.id,Upload.descricao AS 'Descrição',Upload.src, Upload.data_lancamento AS 'Data Lançamento'") , "tabela_upload", array(array("acao"=>"/Logs/form_alterar/classe/Upload/id_tabela/$id/tabela/$tabela/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 

                $endereco = new EnderecoModel();
                $listar_endereco = $form->Listar("col-md-8", "Endereço", "/Endereco/form/id_tabela/$id/tabela/$tabela", "location_on",$endereco->listar_Endereco("INNER JOIN Status ON Status.id = Endereco.id_status",NULL,"Endereco.id_status<>'99' AND Endereco.tabela='$tabela' AND Endereco.id_tabela='{$id}'",NULL,' Endereco.id DESC',"Endereco.id,Endereco.pais AS 'Pais',Endereco.estado AS 'Estado',Endereco.cep AS CEP,Endereco.cidade AS 'Cidade',Endereco.logradouro AS 'Logradouro', Endereco.numero,Status.cor AS 'cor_Status',Status.descricao AS Status,Endereco.complemento") , "tabela_endereco",  array(array("acao"=>"/Endereco/form/tipo/$tabela/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/id_tabela/$id/classe/Endereco/tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);             

                $contas = new ContasModel();
                $listar_financeiro = $form->Listar("col-md-7", "Resumo Receita", $action, $icone, $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela INNER JOIN Status ON Status.id=Contas.id_status ",NULL,"Contas.id_status<>'99' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.tipo,Contas.id_tabela AS id,Contas.id_filial,Contas.valor_parcela AS 'Valor Parcela',Contas.valor_total AS 'Valor Venda',Contas.parcela AS 'Parcela',Status.cor AS 'cor_Status',Status.descricao AS Status,Contas.data_vencimento AS 'Data Vencimento'"), $id, array(array("acao"=>"/Venda/visualizar/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye")), $pesquisa); 
                
            $orcamento_receita = new VendaModel();
            $listar_orcamento =  $orcamento_receita->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_status<>'1' AND Venda.id_status<>'99' AND Venda.tipo='Receita' AND Venda.id_colaborador='$id_colaborador' AND Venda.id_cliente='$id'",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        PessoaColaborador.nome AS Colaborador,
                        Status.cor AS cor_Status,
                        Status.descricao AS Status,
                        Venda.data_lancamento
                        ",null,$pesquisa);
                        $listar_orcamento=$form->Listar("col-md-12", "Plano de Tratamento","/Venda/form/tipo/Receita/", "fact_check", $listar_orcamento, "tabela_orcamento", array(array("acao"=>"/Venda/visualizar/tipo/Receita/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye")),$pesquisa);
               
            
               
                $contato = new ContatoModel();
                $listar_contato = $form->Listar("col-md-4", "Contato", "/Contato/form/id_tabela/$id/tabela/Cliente/", "contact_phone", $contato->listar_Contato(NULL,NULL,"id_status<>'99' AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Contato.id DESC',"Contato.id,descricao AS 'Descrição', contato AS 'Contato',Contato.tabela") , "tabela_contato", array(array("acao"=>"/Contato/form/tipo/$tabela/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/classe/Contato/id_tabela/$id/tabela/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 
                
                $anamneses = new AnamnesesModel(); 
                $listar_anamneses = $form->Listar("col-md-12", "Anamneses", "/Anamneses/form/id_cliente/$id/", "local_hospital",$anamneses->listar_Anamneses("INNER JOIN Status ON Status.id = Anamneses.id_status",NULL,"Anamneses.id_cliente='{$id}'",NULL,' Anamneses.id DESC',"Anamneses.id,Anamneses.id_cliente,Anamneses.queixa,Anamneses.historia,Anamneses.medicamentos,Anamneses.alergias,Anamneses.dst,Status.cor AS 'cor_Status',Status.descricao AS Status,Anamneses.data_lancamento") , "tabela_anamneses", array(array("acao"=>"/Anamneses/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/classe/Anamneses/tabela/Cliente/id_tabela/$id/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);    
               
                $atestado = new AtestadoModel();
                $listar_atestado = $form->Listar("col-md-12", "Atestado", "/Atestado/form/id_cliente/$id/", "receipt_long",$atestado->listar_Atestado("INNER JOIN Pessoa ON Pessoa.id = Atestado.id_colaborador INNER JOIN Status ON Status.id=Atestado.id_status",NULL,"Atestado.id_cliente='{$id}'",NULL,' Atestado.id DESC',"Pessoa.nome AS 'Doutor',Atestado.id,Status.cor AS 'cor_Status',Status.descricao AS Status, Atestado.data_atestado AS 'Data do Atestado', Atestado.texto AS 'Observação'")  , "tabela_atestado", array(array("acao"=>"/Cupom/receituario_form/tabela/Atestado/","classe"=>"btn-sm btn-warning","icone"=>"send"),array("acao"=>"/Logs/form_alterar/classe/Atestado/tabela/id_tabela/$id/Cliente/acao/excluir/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 
                                             
                $receituario = new ReceituarioModel(); 
                $listar_receituario = $form->Listar("col-md-12", "Receituario", "/Receituario/form/id_cliente/$id/", "local_pharmacy",$receituario->listar_Receituario("INNER JOIN Pessoa ON Pessoa.id = Receituario.id_colaborador INNER JOIN Status ON Status.id=Receituario.id_status",NULL,"Receituario.id_cliente='{$id}'",NULL,' Receituario.id DESC',"Pessoa.nome AS 'Doutor', Receituario.id,Status.cor AS 'cor_Status',Status.descricao AS Status,  Receituario.data_receita AS 'Data da Receita', Receituario.texto AS 'Observação'") , "tabela_receituario", array(array("acao"=>"/Cupom/receituario_form/tabela/Receituario/","classe"=>"btn-sm btn-warning","icone"=>"send"),array("acao"=>"/Logs/form_alterar/classe/Receituario/tabela/id_tabela/$id/Cliente/acao/excluir/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 
                
                $procedimento = new ProcedimentosModel(); 
                $listar_procedimentos = $form->Listar("col-md-12", "Procedimentos Realizados", "/Procedimentos/form/id_cliente/$id/", "list",$procedimento->listar_Procedimentos("INNER JOIN Pessoa ON Pessoa.id = Procedimentos.id_colaborador INNER JOIN Status ON Status.id=Procedimentos.id_status",NULL,"Procedimentos.id_cliente='{$id}'",NULL,' Procedimentos.id DESC',"Pessoa.nome AS 'Doutor', Procedimentos.id,Status.cor AS 'cor_Status',Status.descricao AS Status,  Procedimentos.data_lancamento AS 'Data do Lançamento', Procedimentos.listar_procedimentos AS 'Procedimentos Realizados'") , "tabela_procedimento", array(array("acao"=>"/Cupom/receituario_form/tabela/Procedimentos/","classe"=>"btn-sm btn-warning","icone"=>"send"),array("acao"=>"/Procedimentos/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/classe/Procedimentos/tabela/id_tabela/$id/Cliente/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa); 

                
                $listar_contas_ativo = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='1' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");
                $listar_contas_inativo = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='4' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");
                $listar_contas_excluido = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='99' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");

                $chart = new ChartHelper();
                $listar_chart = $chart->chart("Receita","chart_pie","Receita","doughnut", 
                   array( 
                       "Areceber" => count($listar_contas_ativo),
                       "Recebida" => count($listar_contas_inativo),
                       "Excluido" => count($listar_contas_excluido),
                   ),array(
                       '#40E0D0',
                       '#008B8B',
                       '#FF6347',

                   ),"col-md-5");    

          
         
           // $logs->cadastrar_logs($comando,$id);//Gera Logs
            $inputs.="<form class='col-md-12 row'>";
            $inputs.= $form->Input("text", "titulo", "col-md-1", $listar_cliente[0]["id_filial"], $required,"Filial","disabled");            
            $inputs.= $form->Input("text", "cliente", "col-md-1", $listar_cliente[0]["id"], $required,"ID","disabled");            
            $inputs.= $form->Input("text", "Nome", "col-md-6", $listar_cliente[0]["nome"], $required,"Nome","disabled");            
            $inputs.= $form->Input("text", "Apelido", "col-md-4", $listar_cliente[0]["apelido"], $required,"Apelido","disabled");                     
            $inputs.= $form->Input("text", "CPF", "col-md-3", $listar_cliente[0]["cpf"], $required,"CPF","disabled");            
            $inputs.= $form->Input("text", "RG", "col-md-3", $listar_cliente[0]["rg"], $required,"RG","disabled");
            $inputs.= $form->Input("text", "Orgão Expedidor", "col-md-3", $listar_cliente[0]["orgao_expedidor"], $required,"Orgão Expedidor","disabled");
            $inputs.= $form->Input("text", "Data Expedição", "col-md-3", $listar_cliente[0]["data_expedidor"], $required,"Data Expedição","disabled");
            $inputs.= $form->Input("date", "Data Nascimento", "col-md-3", $listar_cliente[0]["data_nascimento"], $required,"Data Nascimento","disabled");
            $inputs.= $form->Input("text", "Sexo", "col-md-3", $listar_cliente[0]["sexo"], $required,"Sexo","disabled");
            $inputs.= $form->Input("text", "Naturalidade", "col-md-3", $listar_cliente[0]["naturalidade"], $required,"Naturalidade","disabled");
            $inputs.= $form->Input("text", "Nacionalidade", "col-md-3", $listar_cliente[0]["nascionalidade"], $required,"Nacionalidade","disabled");
            $inputs.= $form->Input("text", "Estado Civil", "col-md-3", $listar_cliente[0]["estado_civil"], $required,"Estado Civil","disabled");
            $inputs.= $form->Input("text", "Profissão", "col-md-3", $listar_cliente[0]["profissao"], $required,"Profissão","disabled");
            $inputs.= $form->Input("text", "Local de Trabalho", "col-md-2", $listar_cliente[0]["local_trabalho"], $required,"Local de Trabalho","disabled");
            $inputs.= $form->Input("text", "Tipo Sanguineo", "col-md-2", $listar_cliente[0]["tipo_sanguineo"], $required,"Tipo Sanguineo","disabled");
            $inputs.= $form->Input("text", "Status", "col-md-2", $listar_cliente[0]["id_status"], $required,"Status","disabled");
            $inputs.="</form>";         
            
            $inputs.= $form->Abas($Tipo, "Pessoa", "col-md-12", 
                array(
                    array("id" => "Contatos", "icone" => "contacts", "descricao" => "Contatos"), 
                    array("id" => "Eclesiastico", "icone" => "local_library", "descricao" => "Eclesiastico"),
                    array("id" => "Cargo", "icone" => "fact_check", "descricao" => "Cargo"),
                    array("id" => "Familia", "icone" => "wc", "descricao" => "Familia"),
                    array("id" => "Escolaridade", "icone" => "school", "descricao" => "Escolaridade"),
                    array("id" => "Imagens", "icone" => "photo_library", "descricao" => "Imagens"),
                    array("id" => "Historico", "icone" => "assignment", "descricao" => "Historico"),
                    array("id" => "Prontuario", "icone" => "fact_check", "descricao" => "Prontuário"),
                    array("id" => "FinanceiroPessoaV", "icone" => "account_balance_wallet", "descricao" => "Financeiro")
                    ),                    
                array(
                    array("id" => "Contatos",     "dados" => "$listar_endereco $listar_contato","classe" => " active"),
                    array("id" => "Eclesiastico", "dados" => "$listar_eclesiastico "),
                    array("id" => "Cargo",      "dados" => "$listar_cargo"),
                    array("id" => "Familia",      "dados" => "$listar_familia"),
                    array("id" => "Escolaridade", "dados" => "$listar_escolaridade"),
                    array("id" => "Imagens",      "dados" => "$listar_upload"),
                    array("id" => "Historico",    "dados" => "$listar_agenda $listar_historico"),
                    array("id" => "Prontuario",   "dados" => "$listar_anamneses $listar_atestado $listar_receituario $listar_prontuario    $listar_orcamento $listar_procedimentos"),
                    array("id" => "FinanceiroPessoaV",   "dados" => "$listar_financeiro $listar_chart")
                )); 
           
            
            $form->card("Visualizar Pessoa",$inputs,"col-md-12","/Pessoa/form/tipo/Cliente/id/$id","POST","donut_small");        
           }else{

               $this->view('error_permisao');

           }

       } 
        public function visualizar_OLD(){
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Pessoa/visualizar/';
           if($acesso->acesso_valida($comando)==true){ 
                $tabela = $this->getParams('tabela');
                $dados['tabela']=$tabela;         
                $id = $this->getParams('id');
                $tabela = $this->getParams('tabela');
                $acesso = new SessionHelper();
                $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
                echo $menu->Menu();
                $dados["id_cliente"]=$id;
                $cliente = new PessoaModel();
                $listar_cliente = $cliente->listar_Pessoa(NULL,NULL,"id_status<>99 AND id='{$id}'",NULL,' Pessoa.id DESC',NULL);            
                $dados['listar_cliente'] = $listar_cliente;                
               $status = new StatusModel();
                $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,' Status.id DESC',NULL); 
                $dados['listar_status'] = $listar_status; 
                $filial = new FilialModel();
                $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
                $dados['listar_filial'] = $listar_filial;               
                $eclesiastico = new EclesiasticoModel();
                $listar_eclesiastico = $eclesiastico->listar_Eclesiastico("INNER JOIN Status ON Status.id = Eclesiastico.id_status INNER JOIN Filial ON Filial.id = Eclesiastico.id_filial",NULL,"Eclesiastico.id_status<>99 AND Eclesiastico.id_tabela='{$id}'",NULL,' Eclesiastico.id DESC',"Eclesiastico.id,Eclesiastico.batismo_espirito,Eclesiastico.data_batismo_espirito,Eclesiastico.batismo_agua,Eclesiastico.data_batismo_agua,Eclesiastico.cidade_batismo,Eclesiastico.igreja_batismo,Eclesiastico.data_conversao,Eclesiastico.classe_ebd,Eclesiastico.codigo_membro,Eclesiastico.data_admissao,Eclesiastico.tipo_admissao,Eclesiastico.data_emissao_carteira,Eclesiastico.vencimento_carteira,Eclesiastico.id_tabela,Eclesiastico.tabela,Eclesiastico.data_lancamento,Eclesiastico.observacoes,Status.descricao AS descricao_status,Filial.nome_fantasia AS nome_filial");
                $dados['listar_eclesiastico'] = $listar_eclesiastico;                 
                $dados['id_eclesiastico']=count($listar_eclesiastico);                               
               
                $cargo = new CargoModel();               
                $listar_cargo = $cargo->listar_Cargo(NULL,NULL,"id_status<>99 AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Cargo.id DESC',NULL);            
                $dados['listar_cargo'] = $listar_cargo;  
               
               $familia = new FamiliaModel();               
                $listar_familia = $familia->listar_Familia(NULL,NULL,"id_status<>99 AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Familia.id DESC',NULL);             
                $dados['listar_familia'] = $listar_familia;  
               
                $escolaridade = new EscolaridadeModel();               
                $listar_escolaridade = $escolaridade->listar_Escolaridade(NULL,NULL,"id_status<>99 AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Escolaridade.id DESC',NULL);            
                $dados['listar_escolaridade'] = $listar_escolaridade;    
                
                $historico = new HistoricoModel();               
                $listar_historico = $historico->listar_Historico("INNER JOIN Status ON Status.id = Historico.id_status ",NULL,"Historico.id_status<>99 AND Historico.tabela='Pessoa' AND Historico.id_tabela='{$id}'",NULL,' Historico.id DESC',"Historico.observacao,Historico.data_lancamento,Status.descricao ,Status.cor");            
                $dados['listar_historico'] = $listar_historico;    
                
                $upload = new UploadModel();
                $listar_uplaod = $upload->listar_Upload(NULL,NULL,"id_status<>99 AND tabela='Pessoa' AND id_tabela='{$id}'",NULL,' Upload.id DESC',NULL);
                $dados['listar_uplaod'] = $listar_uplaod;
                $endereco = new EnderecoModel();
                $listar_endereco = $endereco->listar_Endereco(NULL,NULL,"id_status<>'99' AND tabela='$tabela'  AND id_tabela='{$id}'",NULL,' Endereco.id DESC',NULL);
                $dados['listar_endereco'] = $listar_endereco;  

                

                $contas = new ContasModel();

                $listar_contas = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela INNER JOIN Status ON Status.id=Contas.id_status ",NULL,"Contas.id_status<>'99' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id,Contas.id_tabela,Contas.tabela,Contas.id_filial,Contas.parcela,Contas.data_vencimento,Contas.valor_parcela,Contas.valor_total,Contas.data_vencimento,Status.descricao,Status.cor");

                $dados['listar_contas'] = $listar_contas;

                

                $contato = new ContatoModel();

                $listar_contato = $contato->listar_Contato(NULL,NULL,"id_status<>99 AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Contato.id DESC',NULL);            

                $dados['listar_contato'] = $listar_contato; 

                

                $anamneses = new AnamnesesModel();

                $listar_anamneses = $anamneses->listar_Anamneses(NULL,NULL,"id_cliente='{$id}'",NULL,' id DESC',NULL);            

                $dados['listar_anamneses'] = $listar_anamneses; 
                
               
               $atestado = new AtestadoModel();
                        $listar_atestado = $atestado->listar_Atestado("INNER JOIN Pessoa ON Pessoa.id = Atestado.id_colaborador ",NULL,"Atestado.id_cliente='{$id}'",NULL,' Atestado.id DESC',"Pessoa.nome,Atestado.id,  Atestado.data_atestado, Atestado.texto"); 
                //   

                $dados['listar_atestado'] = $listar_atestado; 
                
                
              
                $receituario = new ReceituarioModel();

                $listar_receituario = $receituario->listar_Receituario("INNER JOIN Pessoa ON Pessoa.id = Receituario.id_colaborador",NULL,"Receituario.id_cliente='{$id}'",NULL,' Receituario.id DESC',"Pessoa.nome, Receituario.id,  Receituario.data_receita, Receituario.texto");            

                $dados['listar_receituario'] = $listar_receituario;



                $contas = new ContasModel();

                $listar_contas_ativo = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='1' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");

                $listar_contas_inativo = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='2' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");

                $listar_contas_excluido = $contas->listar_contas("INNER JOIN Venda ON Venda.id = Contas.id_tabela  ",NULL,"Contas.id_status='99' AND Contas.tabela='Venda' AND Venda.id_cliente='{$id}'",NULL,' Contas.id DESC',"Contas.id");



                $chart = new ChartHelper();

                $listar_chart = $chart->chart("Receita","doughnut", 

                   array( 

                       "Areceber" => count($listar_contas_ativo),

                       "Recebida" => count($listar_contas_inativo),

                       "Excluido" => count($listar_contas_excluido),

                   ),array(

                       '#40E0D0',

                       '#008B8B',

                       '#FF6347',

                   ));    

                $dados["listar_chart"]=$listar_chart;
 


               $logs->cadastrar_logs($comando,$id);//Gera Logs

               $this->view('form_visualizar_cliente',$dados); 

           }else{

               $this->view('error_permisao');

           }

       } 



    public function form(){                

        $this->acesso_restrito();        

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){

            $status= new StatusModel();

            $filial = new FilialModel();

            $pessoa = new PessoaModel();

            $acesso = new SessionHelper();

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

                echo $menu->Menu();

        

            $id=$this->getParams("id");     
            $tipo = $this->getParams('tipo'); 
      $pessoa_dados["tipo"]=$tipo;

            if(!empty($id)){

                $pessoa_dados=$pessoa->listar_Pessoa($join, "1", "id='$id'", $offset, $orderby);                

                $pessoa_dados= $pessoa_dados[0]; 

                $comando="/".__CLASS__."/alterar/";

            }            

          

            

            if ($tipo=="Cliente" OR $tipo=="Colaborador"){

               

            $form = new FormularioHelper();
            
             $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");
             $inputs.= $form->select("Tipo","tipo", "col-md-2",array(
                                                                array("id"=>"Cliente","descricao"=>"Cliente"),
                                                                array("id"=>"Colaborador","descricao"=>"Colaborador"),
                                                                array("id"=>"Fornecedor","descricao"=>"Fornecedor")
                                                            ),"descricao",$pessoa_dados["tipo"]);

                 $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                

                 $inputs.= $form->Input("text", "nome", "col-md-6", $pessoa_dados["nome"], "required","Nome");

                 $inputs.= $form->Input("text", "apelido", "col-md-2", $pessoa_dados["apelido"], $required,"Apelido");

                 $inputs.= $form->select("Estado Civil","estado_civil", "col-md-2",array(

                                                                                array("id"=>"Solteiro(a)","descricao"=>"Solteiro(a)"),

                                                                                array("id"=>"Casado(a)","descricao"=>"Casado(a)"),

                                                                                array("id"=>"Viuvo(a)","descricao"=>"Viuvo(a)"),

                                                                                array("id"=>"Divorciado(a)","descricao"=>"Divorciado(a)")

                                                                            ),"descricao",$pessoa_dados["estado_civil"]);            

                $inputs.= $form->Input("text", "profissao", "col-md-4", $pessoa_dados["profissao"], $required,"Profissao");

                $inputs.= $form->Input("text", "local_trabalho", "col-md-3", $pessoa_dados["local_trabalho"], $required,"Local Trabalho");

                $inputs.= $form->Input("text", "nacionalidade", "col-md-3", $pessoa_dados["nacionalidade"], $required,"Nacionalidade");

                $inputs.= $form->Input("text", "naturalidade", "col-md-3", $pessoa_dados["naturalidade"], $required,"Naturalidade");

                $inputs.= $form->select("Genero","genero", "col-md-3",array(

                                                                    array("id"=>"Masculino","descricao"=>"Masculino"),

                                                                    array("id"=>"Feminino","descricao"=>"Feminino")

                                                                ),"descricao",$pessoa_dados["genero"]);
 
                $inputs.= $form->Input("text", "cpf", "col-md-3", $pessoa_dados["cpf"], "onkeypress="."maska(this.name,'000.000.000-00');","CPF",null);

                $inputs.= $form->Input("text", "rg", "col-md-3", $pessoa_dados["rg"], $required,"RG");

                $inputs.= $form->Input("text", "orgao_expedidor", "col-md-3", $pessoa_dados["rg"], $required,"Orgão Expedidor");

                $inputs.= $form->Input("date", "data_expedicao", "col-md-2", $pessoa_dados["data_expedicao"], $required,"Data Expedição");

                $inputs.= $form->Input("date", "data_nascimento", "col-md-2", $pessoa_dados["data_nascimento"], $required,"Data Nascimento");

                $inputs.= $form->select("Tipo Sanguineo","tipo_sanguineo", "col-md-2",array(

                                                                                    array("id"=>"A+","descricao"=>"A+"),

                                                                                    array("id"=>"A-","descricao"=>"A-"),

                                                                                    array("id"=>"B+","descricao"=>"B+"),

                                                                                    array("id"=>"B-","descricao"=>"B-"),

                                                                                    array("id"=>"O+","descricao"=>"O+"),

                                                                                    array("id"=>"O-","descricao"=>"O-"),

                                                                                    array("id"=>"AB+","descricao"=>"AB+"),

                                                                                    array("id"=>"AB-","descricao"=>"AB-"),

                                                                                    array("id"=>"Não Informado","descricao"=>"Não Informado")

                                                                                ),"descricao",$pessoa_dados["tipo_sanguineo"]);

                $inputs.= $form->select("Status","id_status", "col-md-3", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral' OR tabela='Pessoa'",NULL,' Status.id ASC',NULL),"descricao",$pessoa_dados["id_status"]);
                  
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
              $form->card("Cadastro de $tipo",$inputs,"col-md-12",$comando,"POST","people");
                

            }else if ($tipo=="Fornecedor"){

                

                $form = new FormularioHelper("Cadastro de Fornecedores","col-md-12" ,$comando,"POST","people");

                    $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");

                    $inputs.= $form->select("Tipo","tipo", "col-md-2",array(

                                                                    array("id"=>"Fornecedor","descricao"=>"Fornecedor")

                                                                ),"descricao");

                    $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                

                    $inputs.= $form->Input("text", "nome", "col-md-4", $pessoa_dados["nome"], "required","Nome Fantazia");

                    $inputs.= $form->Input("text", "apelido", "col-md-4", $pessoa_dados["apelido"], $required,"Razão Social");           

                    $inputs.= $form->Input("text", "cpf", "col-md-3", $pessoa_dados["cpf"],  "onkeypress="."maska(this.name,'00.000.000/0000-00');","CNPJ/CPF");

                    $inputs.= $form->Input("text", "rg", "col-md-2", $pessoa_dados["rg"], $required,"Inscrição Estadual");

                    $inputs.= $form->Input("text", "observacao", "col-md-5", $pessoa_dados["observacao"], $required,"Observação");

                    $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

                    $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                    
                    $form->card("Cadastro de $tipo",$inputs,"col-md-12",$comando,"POST","people");
                    

            }

        }else{

               $this->view('error_permisao');

           }

   

    }
    
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
            $inputs.= $form->select("Pessoa","id_pessoa", "col-md-8",$pessoa_dados,"nome",$id);
            $inputs.= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral' OR tabela='Pessoa'",NULL,' Status.id ASC',NULL),"descricao");
            $inputs.= $form->Input("text", "observacao", "col-md-12",null, "required","Observação");
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card("Cadastro de $tipo",$inputs,"col-md-12",$comando,"POST","people");

        }else{
               $this->view('error_permisao');
           }

    }



    public function incluir(){    

           $this->acesso_restrito();

           $acesso = new AcessoHelper(); 

           $logs = new LogsModel();

           $comando='/Pessoa/incluir/';



           if($acesso->acesso_valida($comando)==true){

               $cliente = new PessoaModel();      

               $cpf=$_POST['cpf'];

               $tipo=$_POST['tipo'];

               if(empty($cpf)){                   

               }else{

                   $cliente_listar= $cliente->listar_Pessoa(NULL, NULL, "id_status='1' AND Pessoa.cpf='$cpf' AND tipo='$tipo'", NULL, NULL,NUlL,NULL,NULL);

               }   

               print_r($cliente_listar);



              if(count($cliente_listar)<=0){

               $id=$cliente->cadastrar_Pessoa( 

                   array(

                       'id_filial'=>$_POST['id_filial'],

                       'id_status'=>$_POST['id_status'],

                       'tipo'=>$_POST['tipo'],

                       'nome'=>$_POST['nome'],

                       'apelido'=>$_POST['apelido'],                       

                       'cpf'=>$_POST['cpf'],                       

                       'rg'=>$_POST['rg'],

                       'orgao_expedidor'=>$_POST['orgao_expedidor'],

                       'data_expedicao'=>$_POST['data_expedicao'],

                       'data_nascimento'=>$_POST['data_nascimento'],

                       'genero'=>$_POST['genero'], 

                       'naturalidade'=>$_POST['naturalidade'],

                       'nacionalidade'=>$_POST['nacionalidade'],

                       'estado_civil'=>$_POST['estado_civil'],                

                       'profissao'=>$_POST['profissao'],

                       'local_trabalho'=>$_POST['local_trabalho'],

                       'observacao'=>$_POST['observacao'],

                       'tipo_sanguineo'=>$_POST['tipo_sanguineo'],

                       'data_lancamento'=>  date("Y-m-d H:i:s"),

                   )

               );  

               $logs->cadastrar_logs($comando,$id);//Gera Logs

               $redirect = new RedirectHelper();

               $redirect->goToUrl("/Pessoa/visualizar/id/$id/tabela/$tipo");   

              }else{

                   echo "<script> alert('CPF, ja cadastrado')</script>";

                   $redirect = new RedirectHelper();

                   $redirect->goToUrl('/Pessoa/visualizar/id/'.$cliente_listar[0]["id"]);  

              }

           }else{

               $this->view('error_permisao');

           }

       }
       
       public function alterar_historico_pessoa(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Pessoa/alterar/';
           if($acesso->acesso_valida($comando)==true){
               $id = $_POST['id_pessoa'];
               $historico = new HistoricoModel();
               $historico->cadastrar_Historico(array(
                   "tabela"=>"Pessoa",
                   "id_tabela"=>"$id",
                   "id_status"=>$_POST['id_status'],
                   "observacao"=>$_POST['observacao'],
                   "data_lancamento"=>date("Y-m-d")
                 ));
               $pessoa = new PessoaModel();      
               $pessoa->alterar_Pessoa(
                   array(
                       'id_status'=>$_POST['id_status'],
                 ),"id=$id"
               );  
               $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Pessoa/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }
       }

    public function alterar(){    

           $this->acesso_restrito();

           $acesso = new AcessoHelper(); 

           $logs = new LogsModel();

           $comando='/Pessoa/alterar/';

           if($acesso->acesso_valida($comando)==true){

               $id = $_POST['id'];

               $pessoa = new PessoaModel();      

               $pessoa->alterar_Pessoa(

                   array(

                       'id_filial'=>$_POST['id_filial'],

                       'id_status'=>$_POST['id_status'],

                       'tipo'=>$_POST['tipo'],

                       'nome'=>$_POST['nome'],

                       'apelido'=>$_POST['apelido'],                       

                       'cpf'=>$_POST['cpf'],                       

                       'rg'=>$_POST['rg'],

                       'orgao_expedidor'=>$_POST['orgao_expedidor'],

                       'data_expedicao'=>$_POST['data_expedicao'],

                       'data_nascimento'=>$_POST['data_nascimento'],

                       'genero'=>$_POST['genero'],

                       'naturalidade'=>$_POST['naturalidade'],

                       'nacionalidade'=>$_POST['nacionalidade'],

                       'estado_civil'=>$_POST['estado_civil'],

                       'conjuge'=>$_POST['conjuge'],

                       'pai'=>$_POST['pai'],

                       'mae'=>$_POST['mae'],

                       'profissao'=>$_POST['profissao'],

                       'local_trabalho'=>$_POST['local_trabalho'],

                       'observacao'=>$_POST['observacao'],

                       'tipo_sanguineo'=>$_POST['tipo_sanguineo'],

                       'data_lancamento'=>  date("Y-m-d H:i:s"),

                   ),"id=$id"

               );  

               $logs->cadastrar_logs($comando,$id);//Gera Logs

               $redirect = new RedirectHelper();

               $redirect->goToUrl('/Pessoa/admin_listar/');    

           }else{

               $this->view('error_permisao');

           }



       }

    public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Pessoa/excluir/';

        

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            

            $pessoa = new PessoaModel();      

            $pessoa->alterar_Pessoa( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Pessoa/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

 }