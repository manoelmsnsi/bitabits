<?php class Relatorio extends Controller {   
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
            $programa = new ProgramaModel();           

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();         
            $form = new FormularioHelper();    
            $inputs.= $form->Listar("col-md-12", null, null, $icone,$programa->listar_programa(NULL,null,"id_status<>'99' AND tipo='RELATORIO' ",NULL,' Programa.comando ASC',"Programa.id AS Id,Programa.descricao AS Descrição, Programa.comando",null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/relatorio/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")));
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","library_books");
        }else{
            $this->view('error_permisao');
        }   
    }

    public function relatorio_pessoa_status(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_pessoa_status/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();                
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){               
            }else{
                $relatorio = new PessoaModel();
                $relatorio = $relatorio->listar_Pessoa($join, $limit, "id_status='$pesquisa'", $offset, $orderby, "id,nome,cpf,rg,genero,naturalidade,tipo", $group);
            }            
                   
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select($label, "Pesquisa", "col-md-10 col-sm-10", array(array("id"=>"1","descricao"=>"Ativo"),array("id"=>"2","descricao"=>"Inativo"),array("id"=>"99","descricao"=>"Excluido")), "descricao", $pesquisa);
            $inputs.= $form->Button("btn btn-sm btn-rose ", "Pesquisa");  
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);    
            $form->card("RELATORIO DE PESSOA POR STATUS",$inputs,"col-md-12","#","POST","library_books");
            // $inputs.= $form->Abas($Tipo, "teste", "col-md-12", array(array("id"=>"relatorio","icone"=>"people","descricao"=>"Relatorio de Pessoa, Filtrada por Status")),array(array("id"=>"relatorio","dados"=>"$relatorio","classe"=>" active")));
        }else{
              $this->view('error_permisao');
        }       
    } 
    public function relatorio_pessoa_aniverssariantes() {
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();    
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_pessoa_aniverssariantes/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();
            $data_inicial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];     
            
            $dia_inicial= substr($data_inicial, 8,2);
            $mes_inicial=substr($data_inicial, 5,2);
            $dia_final=substr($data_final, 8,2);
            $mes_final=substr($data_final, 5,2);

            if(empty($data_inicial) AND empty($data_final)){             
            }else{
                $relatorio = new PessoaModel();
                $relatorio = $relatorio->listar_Pessoa(
                    NULL,
                    null,
                    "MONTH(data_nascimento)>='$mes_inicial' AND DAY(data_nascimento)>='$dia_inicial' AND MONTH(data_nascimento)<='$mes_final' AND DAY(data_nascimento)<='$dia_final'",
                    NULL,
                    "Pessoa.id DESC",
                    "Pessoa.id,
                    Pessoa.nome AS Nome ,data_nascimento AS 'Data Nascimento'",
                    null);
            }  
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->Input("date", "data_inicial", "col-md-5", $data_inicial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-5", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");    
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("Aniverssariantes entre $dia_inicial/$mes_inicial a $dia_final/$mes_final",$inputs,"col-md-12","#","POST","library_books");
        }else{
              $this->view('error_permisao');
        }       
    }

    public function relatorio_conta_cliente(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_conta_cliente/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();
           
            $pesquisa=$_POST["Pesquisa"];
            $hoje=date("Y-m-d");
            if(empty($pesquisa)){         
            }else{
                $relatorio_analitico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                            INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                            INNER JOIN Status  ON Status.id = Contas.id_status
                                                            ", 
                                                            $limit, "Pessoa.id='$pesquisa' ", $offset, $orderby,"Contas.id,Contas.tipo,Pessoa.nome,Status.cor AS cor_Status,Status.descricao AS Status,(select observacao from Logs WHERE Logs.id_comando=Contas.id ORDER BY id DESC LIMIT 1) AS Motivo,Contas.valor_parcela AS 'Valor Parcela',Contas.valor_pago AS 'Valor Pago',Contas.data_vencimento AS 'Data Vencimento'",);
                                              
                $relatorio_sintetico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                        INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                        INNER JOIN Status  ON Status.id = Contas.id_status", 
                                                        $limit, "Pessoa.id='$pesquisa'", $offset, $orderby,"Contas.id,Contas.tipo,Status.cor AS cor_Status,Status.descricao AS Status,SUM(Contas.valor_parcela) AS valor_grupo, COUNT(Contas.id_status) AS doc ","Contas.id_status");
                
                
                $relatorio_rodape = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, " Pessoa.id='$pesquisa'", $offset, $orderby,"
                                                              (select SUM(Contas.valor_pago)    from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Pessoa.id='$pesquisa' AND Contas.id_status='4')AS total_parcela_recebida, 
                                                              (select SUM(Contas.valor_parcela)    from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Pessoa.id='$pesquisa' AND Contas.id_status='99')AS total_parcela_excluida, 
                                                                (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Pessoa.id='$pesquisa' AND Contas.data_vencimento>'$hoje' AND Contas.id_status='1')AS total_parcela_avencer , 
                                                              (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Pessoa.id='$pesquisa' AND Contas.data_vencimento<='$hoje' AND Contas.id_status='1')AS total_parcela_vencida 
                                                            ");
            } 
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Nome do Cliente", "Pesquisa", "col-md-9", $pessoa->listar_Pessoa($join, $limit,  "tipo='Cliente'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela", NULL);           
            setlocale(LC_MONETARY, 'pt_BR');       
            $listar_analitica.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio_analitico, "tabela1", NULL);
            $listar_sintetico = $form->Listar("col-md-12", null, NULL, $icone,$relatorio_sintetico, "tabela2", NULL);
             
            $teste= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array( 
                    array("id" => "X", "icone" => null, "descricao" => "Excluido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_excluida"])), 
                    array("id" => "X", "icone" => null, "descricao" => "Recebido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_recebida"])), 
                    array("id" => "X", "icone" => null, "descricao" => "A Vencer. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_avencer"])), 
                    array("id" => "X", "icone" => null, "descricao" => "Vencido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"])),
                    array("id" => "X", "icone" => null, "descricao" => "Total. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"]+ $relatorio_rodape[0]["total_parcela_avencer"]+$relatorio_rodape[0]["total_parcela_recebida"]))
            ));            
            
            $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "attach_money", "descricao" => "Analitico"), 
                array("id" => "Sintetico", "icone" => "money_off", "descricao" => "Sintetico")),
                array(array("id" => "Analitico", "dados" => "$listar_analitica.$teste", "classe" => " active"),
                array("id" => "Sintetico", "dados" => "$listar_sintetico")));
            $form->card("RELATORIO DE CONTAS POR CLIENTE",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }     
    }

    public function relatorio_conta_colaborador(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       

        if($acesso->acesso_valida("/".__CLASS__."/relatorio_conta_colaborador/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);      
            $filial = new FilialModel();    
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();           

            $data_incial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
            $pesquisa=$_POST["Pesquisa"];
            $hoje=date("Y-m-d");
            $acesso = new SessionHelper();
            $user_dados=$acesso->selectSession("userData");       
            if(empty($pesquisa)){                
            }else{
                if($user_dados["administrador"]=="SIM"){
                    }else{ 
                        echo $user_dados["id_colaborador"];
                        $pesquisa = $user_dados["id_colaborador"]; 
                    }
              
            if($data_final<$hoje){ $hoje=$data_final; }
               $comissao_doutor=$_POST["comissao"];
               $comissao_clinica=100-$comissao_doutor;
              
               $relatorio_analitico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id OR Venda.id_fornecedor=Pessoa.id
                                                              INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                              INNER JOIN Status  ON Status.id = Contas.id_status", 
                                                              $limit, "Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$data_final' ", $offset, $orderby,"Contas.id,Contas.tipo AS 'C/D',Pessoa.nome AS Cliente,Status.cor AS cor_Status,Status.descricao AS Status,Contas.valor_pago AS 'Valor Pago',(($comissao_doutor*Contas.valor_pago)/100) AS 'Doutor $comissao_doutor%',Contas.valor_parcela AS 'Valor Parcela', Contas.parcela,(($comissao_clinica*Contas.valor_pago)/100) AS 'Clinica $comissao_clinica%', Contas.data_vencimento AS 'Data Vencimento',Contas.data_lancamento AS 'Data Lançamento' ",null);

                $relatorio_sintetico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$data_final'", $offset, $orderby,"Contas.id,Contas.tipo,Venda.id_tipo_documento,Status.cor AS cor_Status,Status.descricao AS Status,SUM(Contas.valor_pago) AS valor_grupo, COUNT(Contas.id_status) AS Contas ","Contas.id_status");
                
                $relatorio_rodape = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               "1", "Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$data_final'", $offset, $orderby,"
                                                              (select SUM(Contas.valor_pago)    from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$data_final' AND Contas.id_status='4')AS total_parcela_recebida, 
                                                                (select SUM(Contas.valor_pago)    from Pessoa INNER JOIN Venda ON Venda.id_fornecedor = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$data_final' AND Contas.id_status='121')AS total_despesa_paga, 
                                                              (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.id_colaborador='$pesquisa' AND Contas.data_lancamento>='$data_incial' AND Contas.data_lancamento<='$hoje' AND Contas.id_status='1')AS total_parcela_vencida 
                                                            ");

            }    

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Nome do Colaborador", "Pesquisa", "col-md-5", $pessoa->listar_Pessoa($join, $limit, "tipo='Colaborador'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
            $inputs.= $form->Input("number", "comissao", "col-md-1", $comissao_doutor, "Required", "Comissão", $disable, $id);
            $inputs.= $form->Input("date", "data_inicial", "col-md-2", $data_incial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-2", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");     
            
            setlocale(LC_MONETARY, 'pt_BR');
            
            $listar_analitica.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio_analitico, "tabela1", NULL);        //  
            $listar_sintetico = $form->Listar("col-md-12", null, NULL, $icone,$relatorio_sintetico, "tabela2", NULL);              
            $teste= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => null, "descricao" => "Comissão Doutor(a). ". money_format('%.2n', (($comissao_doutor*$relatorio_rodape[0]["total_parcela_recebida"])/100)-$relatorio_rodape[0]["total_despesa_paga"])), 
                array("id" => "Analitico", "icone" => null, "descricao" => "Despesas. ". money_format('%.2n', ($relatorio_rodape[0]["total_despesa_paga"]))),
                array("id" => "X", "icone" => null, "descricao" => "Comissão Clinica. ". money_format('%.2n', ($comissao_clinica*$relatorio_rodape[0]["total_parcela_recebida"])/100)), 
                array("id" => "X", "icone" => null, "descricao" => "Recebido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_recebida"])), 
                array("id" => "X", "icone" => null, "descricao" => "Vencido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"]))
            ));
            
            $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "attach_money", "descricao" => "Analitico"), 
                array("id" => "Sintetico", "icone" => "money_off", "descricao" => "Sintetico")),
                array(array("id" => "Analitico", "dados" => "$listar_analitica.$teste", "classe" => " active"),
                array("id" => "Sintetico", "dados" => "$listar_sintetico")));
            $form->card("RELATORIO DE CONTAS POR COLABORADOR, $data_incial ATÉ $data_final",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }  
    }

   
   public function relatorio_conta_data(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_conta_data/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();            
            $data_incial=$_POST["data_inicial"];
            $tipo=$_POST["tipo"];

            $data_final=$_POST["data_final"];
            $hoje=date("Y-m-d");
            if(empty($data_incial)AND empty($data_final)){
            }else{
                $relatorio_analitico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id OR Venda.id_fornecedor= Pessoa.id
                                                            INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                            INNER JOIN Status  ON Status.id = Contas.id_status", 
                                                            $limit, "Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND ('$data_final'>=Contas.data_vencimento)", $offset, $orderby,"Contas.id,Contas.tipo,Pessoa.nome,Status.id AS COD,Status.cor AS cor_Status,Status.descricao AS Status,Contas.valor_parcela AS 'Valor Parcela',Contas.valor_pago AS 'Valor Pago',Contas.data_vencimento AS 'Data Vencimento'",null);
                                              
                  $relatorio_sintetico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id OR Venda.id_fornecedor= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final'", $offset, $orderby,"Contas.id,Contas.tipo,Venda.id_tipo_documento,Status.cor AS cor_Status,Status.descricao AS Status,SUM(Contas.valor_pago) AS valor_grupo, COUNT(Contas.id_status) AS Contas ","Contas.id_status");
                
                
                $relatorio_rodape = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id OR Venda.id_fornecedor= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final'", $offset, $orderby,"
                                                              (select SUM(Contas.valor_pago) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='4')AS total_parcela_recebida, 
                                                               ((select SUM(Contas.valor_parcela)    from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='1')-(select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE   Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_incial' AND Contas.id_status='1'))AS total_parcela_avencer, 
                                                              (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='$tipo' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$hoje' AND Contas.id_status='1')AS total_parcela_vencida 
                                                            ");

            }    
            $menu = new MenuHelper("Relatorio de Contas $data_incial até $data_final", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        

            $form = new FormularioHelper();     
            $inputs.= $form->select("Receita/Despesa", "tipo", "col-md-4",array(array("id"=>"Receita"),array("id"=>"Despesa")), "id", $tipo);
            $inputs.= $form->Input("date", "data_inicial", "col-md-4", $data_incial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-4", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");                   
            setlocale(LC_MONETARY, 'pt_BR'); 
            $listar_analitica.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio_analitico, "tabela1", NULL);
            $listar_sintetico = $form->Listar("col-md-12", null, NULL, $icone,$relatorio_sintetico, "tabela2", NULL);
            $teste= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array( 
              array("id" => "X", "icone" => null, "descricao" => "Recebido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_recebida"])), 
                 array("id" => "X", "icone" => null, "descricao" => "A Vencer. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_avencer"])), 
                array("id" => "X", "icone" => null, "descricao" => "Vencido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"])),
                array("id" => "X", "icone" => null, "descricao" => "Total. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"]+ $relatorio_rodape[0]["total_parcela_avencer"]+$relatorio_rodape[0]["total_parcela_recebida"]))
            ));           
            
            $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "attach_money", "descricao" => "Analitico"), 
                array("id" => "Sintetico", "icone" => "money_off", "descricao" => "Sintetico")),
                array(array("id" => "Analitico", "dados" => "$listar_analitica.$teste", "classe" => " active"),
                array("id" => "Sintetico", "dados" => "$listar_sintetico")));
            $form->card("Relatorio de Contas $data_incial até $data_final",$inputs,"col-md-12","#","POST","library_books");

        }else{
            $this->view('error_permisao');
        }    
    }

    public function relatorio_conta_vencimento(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();    
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_conta_vencimento/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();            
            $data_incial=$_POST["data_inicial"];
            $pesquisa=$_POST["Pesquisa"];
            $data_final=$_POST["data_final"];
            $hoje=date("Y-m-d");
            if(empty($data_incial)AND empty($data_final)){
            }else{              
                if($user_dados["administrador"]=="SIM"){
            }else{ 
                echo $user_dados["id_colaborador"];
                $pesquisa = $user_dados["id_colaborador"]; 
            }
            $relatorio_analitico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                            INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                            INNER JOIN Status  ON Status.id = Contas.id_status", 
                                                            $limit, "Contas.data_vencimento>='$data_incial' AND ('$data_final'>=Contas.data_vencimento) AND Venda.id_colaborador='$pesquisa'", $offset, $orderby,"Contas.id,Contas.tipo,Pessoa.nome,Status.id AS COD,Status.cor AS cor_Status,Status.descricao AS Status,Contas.valor_parcela AS 'Valor Parcela',Contas.valor_pago AS 'Valor Pago',Contas.data_vencimento AS 'Data Vencimento'",null);
                                              

                                       
            $relatorio_sintetico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final'AND Venda.id_colaborador='$pesquisa'", $offset, $orderby,"Contas.id,Contas.tipo,Venda.id_tipo_documento,Status.cor AS cor_Status,Status.descricao AS Status,SUM(Contas.valor_pago) AS valor_grupo, COUNT(Contas.id_status) AS Contas ","Contas.id_status");
                
                
            $relatorio_rodape = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final'AND Venda.id_colaborador='$pesquisa'", $offset, $orderby,"
                                                              (select SUM(Contas.valor_pago) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='4')AS total_parcela_recebida, 
                                                               (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Contas.data_vencimento<='$data_final' AND Contas.data_vencimento>'$hoje' AND Contas.id_status='1')AS total_parcela_avencer ,  
                                                              (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$hoje' AND Contas.id_status='1')AS total_parcela_vencida 
                                                            ");

            }         
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();   
            $inputs.= $form->Input("date", "data_inicial", "col-md-4", $data_incial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->select("Nome do Colaborador", "Pesquisa", "col-md-4", $pessoa->listar_Pessoa($join, $limit, "tipo='Colaborador'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
            $inputs.= $form->Input("date", "data_final", "col-md-4", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            setlocale(LC_MONETARY, 'pt_BR');            
            $listar_analitica.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio_analitico, "tabela1", NULL);
            $listar_sintetico = $form->Listar("col-md-12", null, NULL, $icone,$relatorio_sintetico, "tabela2", NULL);
              
            $teste= $form->Abas($Tipo, "Caixa", "col-md-12", 
            array( 
                array("id" => "X", "icone" => null, "descricao" => "Recebido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_recebida"])), 
                array("id" => "X", "icone" => null, "descricao" => "A Vencer. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_avencer"])), 
                array("id" => "X", "icone" => null, "descricao" => "Vencido. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"])),
                array("id" => "X", "icone" => null, "descricao" => "Total. ". money_format('%.2n', $relatorio_rodape[0]["total_parcela_vencida"]+ $relatorio_rodape[0]["total_parcela_avencer"]+$relatorio_rodape[0]["total_parcela_recebida"]))
            ));           
            $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "attach_money", "descricao" => "Analitico"), 
                array("id" => "Sintetico", "icone" => "money_off", "descricao" => "Sintetico")),
                array(array("id" => "Analitico", "dados" => "$listar_analitica.$teste", "classe" => " active"),
                array("id" => "Sintetico", "dados" => "$listar_sintetico")));         
            $form->card("RELATORIO DE CONTAS POR VENCIMENTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }    
    }

    public function relatorio_conta_status(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();      
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_conta_status/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status = new StatusModel();
            $pessoa = new PessoaModel();            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){              
            }else{
                $relatorio = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id
                                                        INNER JOIN Contas ON Contas.id_tabela=Venda.id
                                                        INNER JOIN Status  ON Status.id = Contas.id_status", 
                                                $limit, "Contas.id_status='$pesquisa'", $offset, $orderby,"Contas.id,Contas.tipo,Pessoa.nome,Status.cor AS cor_Status,Status.descricao AS Status,Contas.valor_parcela AS valor_grupo, Contas.data_vencimento ",null);
            }                                 
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Selecio o Status Para Pesquisa", "Pesquisa", "col-md-9", $status->listar_Status($join, $limit, "tabela='Geral' OR tabela='Contas'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE CONTAS POR STATUS",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }    
    }

    public function relatorio_caixa_data(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_caixa_data/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $caixa = new CaixaModel();           
            $data_incial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
            if(empty($data_incial)AND empty($data_final)){                
            }else{
                $relatorio_analitico = $caixa->listar_Caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela 
                                                  INNER JOIN Venda ON Venda.id= Contas.id_tabela  
                                                  INNER JOIN Pessoa ON Pessoa.id = Venda.id_cliente OR Pessoa.id = Venda.id_fornecedor 
                                                  INNER JOIN TipoDocumento  ON TipoDocumento.id = Venda.id_tipo_documento  
                                                  INNER JOIN TipoPagamento  ON TipoPagamento.id = Venda.id_tipo_pagamento",NULL," Contas.data_pagamento>='$data_incial' AND Contas.data_pagamento<='$data_final' ",NULL,' Caixa.id DESC',"Caixa.id_status,Pessoa.nome as Nome,Caixa.id,Caixa.tipo AS 'Receita/Despesa',Contas.valor_pago AS 'Valor',TipoPagamento.descricao AS Pagamento,TipoDocumento.descricao AS Documento ,Caixa.data_lancamento AS 'Lançamento',Contas.data_vencimento AS 'Data Vencimento', Contas.data_pagamento AS 'Data Pagamento'",null);
                                                  
                                                  
                 $relatorio_rodape= $caixa->listar_Caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela 
                                           ",NULL," Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' ",NULL,' Caixa.id DESC',"
                                                  (select SUM(Contas.valor_pago) from Contas  WHERE Contas.tipo='Receita' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='4')AS total_receita,
                                                  (select SUM(Contas.valor_pago) from Contas  WHERE Contas.tipo='Despesa' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='4')AS total_despesa
                                                   ",null);

                 }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper("","col-md-12" ,"#","POST","people");     
            $inputs.= $form->Input("date", "data_inicial", "col-md-5", $data_incial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-5", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");                   
            setlocale(LC_MONETARY, 'pt_BR');
            $listar_analitica.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio_analitico, "tabela1", NULL);
            $listar_sintetico = $form->Listar("col-md-12", null, NULL, $icone,$relatorio_sintetico, "tabela2", NULL);
              
            $teste= $form->Abas($Tipo, "Caixa", "col-md-12", 
            array( 
                array("id" => "X", "icone" => null, "descricao" => "Receita. ". money_format('%.2n', $relatorio_rodape[0]["total_receita"])), 
                array("id" => "X", "icone" => null, "descricao" => "Despesa. ". money_format('%.2n', $relatorio_rodape[0]["total_despesa"]))
            ));            
            
            $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "attach_money", "descricao" => "Analitico"), 
                array("id" => "Sintetico", "icone" => "money_off", "descricao" => "Sintetico")),
                array(array("id" => "Analitico", "dados" => "$listar_analitica.$teste", "classe" => " active"),
                array("id" => "Sintetico", "dados" => "$listar_sintetico")));         
            $form->card("RELATORIO DE CAIXA POR DATA",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }                 
    }

    public function relatorio_caixa_documento(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();      
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_caixa_documento/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $tipo_documento = new TipoDocumentoModel();           
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $caixa= new CaixaModel();
                $relatorio = $caixa->listar_Caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela INNER JOIN Venda ON Venda.id= Contas.id_tabela INNER JOIN Pessoa ON Pessoa.id=Venda.id_cliente  INNER JOIN TipoDocumento  ON TipoDocumento.id = Venda.id_tipo_documento  INNER JOIN TipoPagamento  ON TipoPagamento.id = Venda.id_tipo_pagamento",NULL," TipoDocumento.id='$pesquisa' ",NULL,' Caixa.id DESC',"Caixa.id,Caixa.tipo,Pessoa.nome,Contas.valor_pago,TipoPagamento.descricao AS Pagamento,TipoDocumento.descricao AS Documento ,Caixa.data_lancamento",null);
            }         
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper("","col-md-12" ,"#","POST","people");     
            $inputs.= $form->select("Selecio o Status Para Pesquisa", "Pesquisa", "col-md-9", $tipo_documento->listar_TipoDocumento($join, $limit, "id_status<>'99'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            echo  $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE CAIXA POR TIPO DE DOCUMENTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }         
    }

    public function relatorio_caixa_pagamento(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_caixa_pagamento/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $tipo_pagamento= new TipoPagamentoModel();
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $caixa= new CaixaModel();
                $relatorio = $caixa->listar_Caixa("INNER JOIN Contas ON Contas.id=Caixa.id_tabela INNER JOIN Venda ON Venda.id= Contas.id_tabela INNER JOIN Pessoa ON Pessoa.id=Venda.id_cliente  INNER JOIN TipoDocumento  ON TipoDocumento.id = Venda.id_tipo_documento  INNER JOIN TipoPagamento  ON TipoPagamento.id = Venda.id_tipo_pagamento",NULL," TipoPagamento.id='$pesquisa' ",NULL,' Caixa.id DESC',"Caixa.id,Caixa.tipo,Pessoa.nome,Contas.valor_pago,TipoPagamento.descricao AS Pagamento,TipoDocumento.descricao AS Documento ,Caixa.data_lancamento",null);
            }    
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Selecio o Status Para Pesquisa", "Pesquisa", "col-md-9", $tipo_pagamento->listar_TipoPagamento($join, $limit, "id_status<>'99'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE CAIXA POR TIPO DE PAGAMENTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }        
    }

    public function relatorio_produto_status(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_produto_status/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $produto= new ProdutoModel();
                $relatorio = $produto->listar_Produto("
                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor
                INNER JOIN Filial ON Filial.id = Produto.id_filial
                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo
                INNER JOIN Marca ON Marca.id = Produto.id_marca
                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ",null,"Produto.id_status='$pesquisa' AND Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",
                "Produto.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Grupo,Produto.descricao AS Descricao,Produto.valor_venda AS Venda",null);
            }
                       
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Selecio o Status Para Pesquisa", "Pesquisa", "col-md-9", $status->listar_Status($join, $limit, "id_status<>'99' AND tabela='Geral' OR tabela='Produto'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE PRODUTO POR STATUS",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }       
    }

    public function relatorio_produto_estoque(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_produto_estoque/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();     
            $acesso = new SessionHelper();     
                $produto= new ProdutoModel();
                $relatorio = $produto->listar_Produto("
                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor
                INNER JOIN Filial ON Filial.id = Produto.id_filial
                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo
                INNER JOIN Marca ON Marca.id = Produto.id_marca
                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ",null,"Produto.quantidade<=Produto.quantidade_min AND Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",
                "Produto.id,Filial.nome_fantasia AS Filial,Produto.quantidade,Produto.quantidade_min ,Produto.descricao AS Descricao,Produto.valor_venda AS Venda",null);

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();       
            $form = new FormularioHelper();  
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE PRODUTOS COM POUCA QUANTIDADE EM ESTOQUE",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }         
    }

    public function relatorio_produto_valor(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();     
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_produto_valor/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();            
            $valor_inicial=$_POST["valor_inicial"];
            $valor_final=$_POST["valor_final"];
            if(empty($valor_inicial)AND empty($valor_final)){                
            }else{
                $produto= new ProdutoModel();
                $relatorio = $produto->listar_Produto("
                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor
                INNER JOIN Filial ON Filial.id = Produto.id_filial
                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo
                INNER JOIN Marca ON Marca.id = Produto.id_marca
                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ",null,"Produto.valor_venda>='$valor_inicial' AND Produto.valor_venda<='$valor_final'  AND Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",
                "Produto.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Grupo,Produto.descricao AS Descricao,Produto.valor_venda AS Venda",null);
            }                    
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->Input("number", "valor_inicial", "col-md-5", $valor_inicial, $Required, "Valor Inicial", $disable, $id);
            $inputs.= $form->Input("number", "valor_final", "col-md-5", $valor_final, $Required, "Valor Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO POR VALOR DO PRODUTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }      
    }

    public function relatorio_produto_data(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_produto_data/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();            
            $data_inicial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
            if(empty($valor_inicial)AND empty($valor_final)){                
            }else{
                $produto= new ProdutoModel();
                $relatorio = $produto->listar_Produto("
                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor
                INNER JOIN Filial ON Filial.id = Produto.id_filial
                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo
                INNER JOIN Marca ON Marca.id = Produto.id_marca
                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ",null,"Produto.data_lancamento>='$data_inicial' AND Produto.data_lancamento<='$data_final'  AND Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",
                "Produto.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Grupo,Produto.descricao AS Descricao,Produto.valor_venda AS Venda",null);

            }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->Input("date", "data_inicial", "col-md-5", $data_inicial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-5", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO POR DATA DE LANÇAMENTO DO PRODUTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }      
    }

    public function relatorio_produto_venda(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
          if($acesso->acesso_valida("/".__CLASS__."/relatorio_produto_venda/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $produto= new ProdutoModel();
                $relatorio = $produto->listar_Produto("
                INNER JOIN Itens ON Itens.id_produto = Produto.id
                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor
                INNER JOIN Filial ON Filial.id = Produto.id_filial
                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo
                INNER JOIN Marca ON Marca.id = Produto.id_marca
                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ","$pesquisa","Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",
                "Produto.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Grupo,Produto.descricao AS Descricao,SUM(Itens.quantidade) AS quantidade ,Produto.valor_venda AS Venda",null);
            }                     
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->Input("number", "Pesquisa", "col-md-5", $data_inicial, $Required, "Total de Registro", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO DE VENDA POR PRODUTO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }       
    }

    public function relatorio_venda_cliente(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_venda_cliente/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();           
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){               
            }else{
                $relatorio = new VendaModel();
                $relatorio = $relatorio->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_cliente='$pesquisa'",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        TipoDocumento.descricao AS Documento,
                        TipoPagamento.descricao AS Pagamento, 
                        ContaBancaria.descricao AS Caixa,
                        PessoaColaborador.nome AS Colaborador, 
                        Status.descricao AS Status
                        ",null);
            }            
            
            
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Nome do Cliente", "Pesquisa", "col-md-9", $pessoa->listar_Pessoa($join, $limit,  "tipo='Cliente'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela2", NULL,NULL);
            $form->card("RELATORIO VENDA POR CLIENTE",$inputs,"col-md-12","#","POST","library_books");
        }else{
              $this->view('error_permisao');
        }             
    }   

    public function relatorio_venda_planoconta(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
       if($acesso->acesso_valida("/".__CLASS__."/relatorio_venda_planoconta/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $plano_conta = new PlanoContasModel();
            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $relatorio = new VendaModel();
                $relatorio = $relatorio->listar_Venda(
                   "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_plano_contas='$pesquisa' ",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        TipoDocumento.descricao AS Documento,
                        TipoPagamento.descricao AS Pagamento, 
                        ContaBancaria.descricao AS Caixa,
                        PessoaColaborador.nome AS Colaborador, 
                        Status.descricao AS Status
                        ",null);
            }           
                       
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Nome do Cliente", "Pesquisa", "col-md-9", $plano_conta->listar_PlanoContas($join, $limit,  null, $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO VENDA POR PLANO DE CONTA",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }  
    }

    public function relatorio_venda_colaborador(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();   
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_venda_colaborador/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $pessoa = new PessoaModel();            
            $pesquisa=$_POST["Pesquisa"];
            $data_incial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
            if(empty($pesquisa)){               
            }else{
                $relatorio = new VendaModel();
                $relatorio = $relatorio->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    "50",
                    "Venda.id_colaborador='$pesquisa' AND Venda.data_lancamento>'$data_incial' AND Venda.data_lancamento<'$data_final' ",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        TipoDocumento.descricao AS Documento,
                        TipoPagamento.descricao AS Pagamento, 
                        ContaBancaria.descricao AS Caixa,
                        PessoaColaborador.nome AS Colaborador,
                        Venda.data_lancamento AS Lançamento,
                        Status.descricao AS Status
                        ",null);
            }              
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Nome do Cliente", "Pesquisa", "col-md-10", $pessoa->listar_Pessoa($join, $limit,  "tipo='Colaborador'", $offset, $orderby, "id,nome", $group), "nome", $pesquisa);
            $inputs.= $form->Input("date", "data_inicial", "col-md-5", $data_incial, "required", "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-5", $data_final, "required", "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO VENDA POR COLABORADOR",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }   

    }

    public function relatorio_venda_data(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();      
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_venda_data/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $status= new StatusModel();
            
            $data_inicial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
              
            if(empty($data_inicial) AND empty($data_final)){ 
            }else{
                $relatorio = new VendaModel();
                $relatorio = $relatorio->listar_Venda(
                    "INNER JOIN Filial ON Filial.id = Venda.id_filial
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Venda.id_tipo_documento
                        INNER JOIN TipoPagamento ON TipoPagamento.id = Venda.id_tipo_pagamento
                        INNER JOIN ContaBancaria ON ContaBancaria.id = Venda.id_conta_bancaria
                        INNER JOIN Pessoa AS PessoaColaborador ON PessoaColaborador.id = Venda.id_colaborador
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente
                        INNER JOIN Status ON Status.id = Venda.id_status",
                    null,
                    "Venda.data_lancamento>='$data_inicial' AND Venda.data_lancamento<='$data_final'",
                    NULL,
                    "Venda.id DESC",
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        TipoDocumento.descricao AS Documento,
                        TipoPagamento.descricao AS Pagamento, 
                        ContaBancaria.descricao AS Caixa,
                        PessoaColaborador.nome AS Colaborador, 
                        Status.descricao AS Status
                        ",null);
            }             
            
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,"#","post","people");     
            $inputs.= $form->Input("date", "data_inicial", "col-md-5", $data_inicial, $Required, "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-5", $data_final, $Required, "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");                

            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO VENDA DE $data_inicial A $data_final",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }       
    }

    public function relatorio_acesso_programa(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_acesso_programa/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $programa= new ProgramaModel();
           
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
              //  $relatorio = new AcessoModel();
                $relatorio = $programa->listar_programa(
                       "INNER JOIN Acesso ON Acesso.id_programa=Programa.id
                        INNER JOIN Usuario ON Usuario.id=Acesso.id_usuario",
                    null,
                    "Programa.id='$pesquisa'",
                    NULL,
                    "Acesso.id DESC",
                    "Programa.comando, Programa.descricao, Usuario.usuario,Acesso.data_lancamento ",null);
                
                $relatorio1 = $programa->listar_programa(
                       "INNER JOIN GrupoAcesso ON GrupoAcesso.id_programa=Programa.id
                        INNER JOIN Grupo ON Grupo.id=GrupoAcesso.id_grupo
                        INNER JOIN Acesso ON Acesso.id_grupo=Grupo.id
                        INNER JOIN Usuario ON Usuario.id=Acesso.id_usuario
                        ",
                    null,
                    "Programa.id='$pesquisa'",
                    NULL,
                    "Acesso.id DESC",
                    "Grupo.descricao,Programa.comando, Programa.descricao, Usuario.usuario ",null);
            }             
            
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select($label, "Pesquisa", "col-md-10", $programa->listar_programa($join, $limit, $where, $offset, $orderby, "id,descricao", $group, null), "descricao", $Value);
            echo  $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-6", "Acesso Direto", NULL, $icone,$relatorio, "tabela1", NULL);
            $inputs.= $form->Listar("col-md-6", "Acesso Por Grupo", NULL, $icone,$relatorio1, "tabela2", NULL);
            $form->card("RELATORIO DE ACESSOS",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }       
    }

    public function relatorio_acesso_usuario(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_acesso_usuario/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $usuario= new UsuarioModel();            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $relatorio = new ProgramaModel();
                $relatorio1 = $relatorio->listar_programa(
                       "INNER JOIN Acesso ON Acesso.id_programa=Programa.id
                        INNER JOIN Usuario ON Usuario.id=Acesso.id_usuario",
                    null,
                    "Usuario.id='$pesquisa'",
                    NULL,
                    "Acesso.id DESC",
                    "Programa.comando, Programa.descricao, Usuario.usuario,Acesso.data_lancamento ",null);               
                $relatorio2 = $relatorio->listar_programa(
                       "INNER JOIN GrupoAcesso ON GrupoAcesso.id_programa=Programa.id
                        INNER JOIN Grupo ON Grupo.id=GrupoAcesso.id_grupo
                        INNER JOIN Acesso ON Acesso.id_grupo=Grupo.id
                        INNER JOIN Usuario ON Usuario.id=Acesso.id_usuario
                        ",
                    null,
                    "Usuario.id='$pesquisa'",
                    NULL,
                    "Acesso.id DESC",
                    "Grupo.descricao,Programa.comando, Programa.descricao, Usuario.usuario ",null);
            }                      
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,"#","POST","people");     
            $inputs.= $form->select($label, "Pesquisa", "col-md-10", $usuario->listar_usuario($join, $limit, "id_status<>'99'", $offset, $orderby, "id,usuario", $group, null), "usuario", $Value);
            echo  $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-6", "Acesso Direto", NULL, $icone,$relatorio1, "tabela1", NULL);
            $inputs.= $form->Listar("col-md-6", "Acesso Por Grupo", NULL, $icone,$relatorio2, "tabela2", NULL);
            $form->card("RELATORIO DE ACESSO POR USUARIO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }     
    }

    public function relatorio_acesso_grupo(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();       
        if($acesso->acesso_valida("/".__CLASS__."/relatorio_acesso_grupo/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $grupo= new GrupoModel();
            
            $pesquisa=$_POST["Pesquisa"];
            if(empty($pesquisa)){                
            }else{
                $relatorio = new ProgramaModel();                            
                $relatorio2 = $relatorio->listar_programa(
                       "INNER JOIN GrupoAcesso ON GrupoAcesso.id_programa=Programa.id
                        INNER JOIN Grupo ON Grupo.id=GrupoAcesso.id_grupo
                        ",
                    null,
                    "Grupo.id='$pesquisa'",
                    NULL,
                    "GrupoAcesso.id DESC",
                    "Grupo.descricao,Programa.comando, Programa.descricao ",null);
            }                        
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
            echo $menu->Menu();        
            $form = new FormularioHelper(__CLASS__,"col-md-12" ,"#","POST","people");     
            $inputs.= $form->select($label, "Pesquisa", "col-md-10", $grupo->listar_Grupo($join, $limit, "id_status<>'99' AND tabela='Acesso'", $offset, $orderby, "id,descricao", $group, null), "descricao", $Value);
            echo  $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", "Acesso do Grupo", NULL, $icone,$relatorio2, "tabela2", NULL);
            $form->card("RELATORIO DE ACESSO POR GRUPO",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }     
    }

   public function relatorio_geral_ebd(){
        $acesso = new AcessoHelper();   
              
            if($acesso->acesso_valida("/".__CLASS__."/relatorio_geral_ebd/")==true){
                $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);   
                echo $menu->Menu();      
                $filial = new FilialModel();
                $form = new FormularioHelper(__CLASS__,"col-md-12" ,"#","POST","people");   
                $data=$_POST["data"];
                $setor=$_POST["setor"];              
                if(empty($data)){ 
                    $data= date("Y-m-d");
                }else{                
                    $relatorio = new LicaoModel(); 
                    $listar_relatorio=$relatorio->listar_Licao("INNER JOIN Classe ON Classe.id = Licao.id_classe INNER JOIN HistoricoChamada ON HistoricoChamada.id_licao= Licao.id", $limit,  "Licao.data_lancamento=DATE('$data') AND Classe.id_filial='$setor'", $offset, $orderby, "Classe.id AS id_classe, Classe.descricao AS 'Descrição',COUNT(HistoricoChamada.id_pessoa) AS 'Matriculados',SUM(HistoricoChamada.chamada='Presente') AS Presentes,SUM(HistoricoChamada.chamada='Ausente') AS Ausentes,Licao.total_biblia as Biblias,Licao.total_revista as Revista,Licao.total_visita AS Visitas,Licao.total_oferta as Ofertas" , "HistoricoChamada.id_licao");
                    $lista1= $form->Listar("col-md-12", $titulo, $action,$icone,$listar_relatorio,"tabela1",array(array("acao"=>"/ItenClasse/admin_listar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")));    
                    $lista2= $form->Listar("col-md-12", $titulo, $action,$icone,$listar_relatorio,"tabela2",array(array("acao"=>"/ItenClasse/admin_listar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")));    
                }                              

                $inputs.= $form->Input("date", "data", "col-md-5", $data, $Required, "Data Da Lição", $disable, $id);
                $inputs.= $form->select("Setor", "setor", "col-md-5", $filial->listar_Filial($join, $limit, "id_status<>'99'", $offset, $orderby, "id,nome_fantasia", $group, null), "nome_fantasia", $setor);
                $inputs.= $form->Button("btn btn-rose", "Pesquisa");                

            foreach($listar_relatorio as $totais){
                $total_matriculado=$total_matriculado+$totais["Matriculados"];
                $total_presentes=$total_presentes+$totais["Presentes"];
                $total_ausentes=$total_ausentes+$totais["Ausentes"];
                $total_biblia=$total_biblia+$totais["Biblias"];
                $total_revista=$total_revista+$totais["Revista"];
                $total_visita=$total_visita+$totais["Visitas"];
                $total_oferta=$total_oferta+$totais["Ofertas"];
            }
            $total_presentes=$total_presentes+$total_visita;//
            $modelo2= $form->Abas($Tipo, "Caixa", "col-md-12", 
                array(array("id" => "Analitico", "icone" => "groups", "descricao" => "Matriculados ". $total_matriculado), 
                array("id" => "X", "icone" => "check_circle_outline", "descricao" => "Presentes ".$total_presentes), 
                array("id" => "X", "icone" => "highlight_off", "descricao" => "Ausentes ".$total_ausentes), 
                array("id" => "X", "icone" => "person", "descricao" => "Visitas ".$total_visita),
                array("id" => "X", "icone" => "import_contacts", "descricao" => "Biblias ".$total_biblia),
                array("id" => "X", "icone" => "class", "descricao" => "Revistas ".$total_revista),
                array("id" => "X", "icone" => "monetization_on", "descricao" => "Ofertas ".$total_oferta)
            ));       
            $modelo1=$form->MiniCard(array(
                array("css"=>"col-md-3","cor"=>"card-header-rose","icone"=>"groups","nome"=>"Matriculados","valor"=>"$total_matriculado"),
                array("css"=>"col-md-3","cor"=>"card-header-info","icone"=>"check_circle_outline","nome"=>"Presentes","valor"=>"$total_presentes"),
                array("css"=>"col-md-3","cor"=>"card-header-danger","icone"=>"highlight_off","nome"=>"Ausentes","valor"=>"$total_ausentes"),
                array("css"=>"col-md-3","cor"=>"card-header-warning","icone"=>"person","nome"=>"Visitas","valor"=>"$total_visita"),
                array("css"=>"col-md-4","cor"=>"card-header-success","icone"=>"import_contacts","nome"=>"Biblias","valor"=>"$total_biblia"),
                array("css"=>"col-md-4","cor"=>"card-header-success","icone"=>"class","nome"=>"Revistas","valor"=>"$total_revista"),
                array("css"=>"col-md-4","cor"=>"card-header-danger","icone"=>"monetization_on","nome"=>"Ofertas","valor"=>"$total_oferta"),
            ));

           $inputs.= $form->Abas($Tipo, "EBD", "col-md-12", 
                    array(array("id" => "card", "icone" => "list", "descricao" => "Modelo 1"), 
                    array("id" => "linha", "icone" => "list", "descricao" => "Modelo 2")),
                    array(array("id" => "linha", "dados" => "$lista2.$modelo2"),
                    array("id" => "card", "dados" => "$lista1.$modelo1","classe" => " active"))); 
           $form->card("RELATORIO DA ESCOLA DOMINICAL",$inputs,"col-md-12","#","POST","school");     
        }else{
            $this->view('error_permisao');
        }       
    }    
    
    public function relatorio_presentes_ausentes_ebd(){
        $this->acesso_restrito();        
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();

        if($acesso->acesso_valida("/".__CLASS__."/relatorio_presentes_ausentes_ebd/")==true){
            $filiais=$acesso->acesso_filial(__CLASS__);       
            $filial = new FilialModel();      
            $acesso = new SessionHelper();     
            $classe = new ClasseModel();
            $trimestre = new TrimestreModel();
            $pesquisa=$_POST["Pesquisa"];
            $data_incial=$_POST["data_inicial"];
            $data_final=$_POST["data_final"];
            if(empty($pesquisa)){
            }else{
                $relatorio = new LicaoModel();
                $relatorio = $relatorio->listar_Licao(
                    "INNER JOIN HistoricoChamada ON Licao.id = HistoricoChamada.id_licao
                     INNER JOIN Pessoa ON Pessoa.id = HistoricoChamada.id_pessoa
                       ",
                    "50",
                    "Licao.id_classe='$pesquisa' AND HistoricoChamada.data_lancamento>'$data_incial' AND HistoricoChamada.data_lancamento<'$data_final' ",
                    NULL,
                    "Licao.id DESC","Pessoa.nome,
                 (select COUNT(chamada)  FROM Licao INNER JOIN HistoricoChamada ON Licao.id = HistoricoChamada.id_licao  WHERE HistoricoChamada.id_pessoa=Pessoa.id AND HistoricoChamada.chamada='Presente' AND HistoricoChamada.data_lancamento>'$data_incial' AND HistoricoChamada.data_lancamento<'$data_final') AS Presente,
                    (select COUNT(chamada)  FROM Licao INNER JOIN HistoricoChamada ON Licao.id = HistoricoChamada.id_licao WHERE HistoricoChamada.id_pessoa=Pessoa.id AND HistoricoChamada.chamada='Ausente' AND HistoricoChamada.data_lancamento>'$data_incial' AND HistoricoChamada.data_lancamento<'$data_final') AS Ausente","HistoricoChamada.id_pessoa");
            }              
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  
            echo $menu->Menu();        
            $form = new FormularioHelper();     
            $inputs.= $form->select("Classe", "Pesquisa", "col-md-6", $classe->listar_Classe($join, $limit,  "id_status<>'99'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
           // $inputs.= $form->select("Trimestre", "Pesquisa", "col-md-6", $trimestre->listar_Trimestre($join, $limit,  "id_status<>'99'", $offset, $orderby, "id,descricao", $group), "descricao", $pesquisa);
            $inputs.= $form->Input("date", "data_inicial", "col-md-3", $data_incial, "required", "Data Inicial", $disable, $id);
            $inputs.= $form->Input("date", "data_final", "col-md-3", $data_final, "required", "Data Final", $disable, $id);
            $inputs.= $form->Button("btn btn-rose", "Pesquisa");            
            $inputs.= $form->Listar("col-md-12", null, NULL, $icone,$relatorio, "tabela1", NULL);
            $form->card("RELATORIO AUSENTES/PRESENTES EBD",$inputs,"col-md-12","#","POST","library_books");
        }else{
            $this->view('error_permisao');
        }
    }    
 

 } ?> 