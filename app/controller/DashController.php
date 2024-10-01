<?php 

class Dash extends Controller {   

    private  $auth,$db;

    public function acesso_restrito(){          

        $this->auth = new AutenticaHelper();

        $this->auth->setLoginControllerAction('Index','')

                   ->checkLogin('redirect');              

        $this->db = new AdminModel(); 

    }  

 public function financeiro(){}
 
 
 public function doutores(){
    $this->acesso_restrito();    
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    
    if (empty($_POST["pesquisa"])) { $pesquisa = null; } else { $pesquisa = $_POST["pesquisa"]; }

    $acesso = new SessionHelper();
       $agenda = new AgendaModel();
    $contas = new ContasModel();
    $pessoa = new PessoaModel();
    $chamado = new ChamadoModel();
    
    $serverData = $acesso->selectSession('serverData');
    $userData = $acesso->selectSession('userData');
    $id_colaborador = $userData["id_colaborador"];
    $id_cliente_servidor = $serverData[0]["id_cliente"];
    $hoje=date("Y-m-d");
    
    $data_incial= mktime(0, 0, 0, date('m') , 1 , date('Y'));
    $data_final = mktime(23, 59, 59, date('m')+1, date('d')-date('j'), date('Y'));    
    $data_incial = date("Y-m-d",$data_incial);
    $data_final = date("Y-m-d",$data_final);
    
    $segunda = date("Y-m-d",mktime(23, 59, 59, date('m'), date('d')-date('N'), date('Y'))) ;

    $intranet = new IntranetModel();
 
    $listar_intranet = $intranet->listar_intranet(
    "INNER JOIN Status ON Status.id=Intranet.id_status ",
    "3",
    $where,
    $offset,
    "Intranet.id DESC",
    "Intranet.noticia AS 'Notícia',Status.cor AS cor_Status,Status.Descricao AS Status, Intranet.id,Intranet.titulo,Intranet.modulo,Intranet.funcionalidade,Intranet.data_lancamento",
    null,
    $pesquisa);
    $form = new FormularioHelper();
    echo $form->Listar("col-md-8", "Agendamentos do Dia", null, "warning",$agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status<>'99' AND Agenda.data_atendimento='$hoje' AND Agenda.id_colaborador='$id_colaborador'",NULL,' Agenda.data_atendimento ASC,Agenda.hora_inicio',"Cliente.nome as 'Cliente',Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento AS 'Data Atendimento',Agenda.hora_inicio AS Inicio,Agenda.hora_fim AS Fim,Agenda.tempo_atendimento AS 'Tempo Médio',Status.cor AS cor_Modelo,Agenda.tipo AS Modelo,Agenda.observacao AS 'Observação',Cliente.nome as 'Cliente',(SELECT Contato.contato FROM Contato  WHERE Contato.descricao = 'Celular' and Contato.id_tabela = Cliente.id LIMIT 1) as 'Telefone'") , "tabela_agenda", null);
    

  
     $chart = new ChartHelper();
  //echo $data_incial;
  //echo $data_final;
            $contas_grafico = $pessoa->listar_Pessoa("INNER JOIN Venda ON Venda.id_cliente= Pessoa.id OR Venda.id_fornecedor= Pessoa.id
                                                               INNER JOIN Contas ON Contas.id_tabela=Venda.id 
                                                               INNER JOIN Status  ON Status.id = Contas.id_status",
                                                               $limit, "Venda.tipo='Receita' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final'AND  Venda.id_colaborador='$id_colaborador'", $offset, $orderby,"
                                                               (select SUM(Contas.valor_pago) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='Receita' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='4'AND  Venda.id_colaborador='$id_colaborador')AS total_parcela_recebida, 
                                                               ((select SUM(Contas.valor_parcela)    from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='Receita' AND Contas.data_vencimento>'$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='1'AND  Venda.id_colaborador='$id_colaborador')-(select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE   Venda.tipo='Receita' AND Contas.data_vencimento>='$hoje' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<='$data_final' AND Contas.id_status='1'AND  Venda.id_colaborador='$id_colaborador'))AS total_parcela_avencer, 
                                                               (select SUM(Contas.valor_parcela) from Pessoa INNER JOIN Venda ON Venda.id_cliente = Pessoa.id INNER JOIN Contas ON Contas.id_tabela= Venda.id WHERE Venda.tipo='Receita' AND Contas.data_vencimento>='$data_incial' AND Contas.data_vencimento<'$hoje' AND Contas.id_status='1'AND  Venda.id_colaborador='$id_colaborador')AS total_parcela_vencida 
                                                            ");
            
           $agenda_grafico = $agenda->listar_Agenda($join, "1", "id_colaborador='$id_colaborador'", $offset, $orderby, "(SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 1 day ) AND id_colaborador='$id_colaborador') AS segunda,
                                                                                            (SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 2 day )AND id_colaborador='$id_colaborador') AS terca,
                                                                                            (SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 3 day )AND id_colaborador='$id_colaborador') AS quarta,
                                                                                            (SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 4 day )AND id_colaborador='$id_colaborador') AS quinta,
                                                                                            (SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 5 day )AND id_colaborador='$id_colaborador') AS sexta,
                                                                                            (SELECT count( Agenda.id) FROM Agenda WHERE Agenda.data_atendimento=date_add(DATE('$segunda'), INTERVAL 6 day )AND id_colaborador='$id_colaborador') AS sabado", $group);
         
         
    
           
           echo $chart->chart("Atendimentos da Semana","pie_chart","Dias","line",                 
            array(
                "Segunda"=>  $agenda_grafico[0]["segunda"],
                "Terça"  => $agenda_grafico[0]["terca"],
                "Quarta" => $agenda_grafico[0]["quarta"],
                "Quinta" => $agenda_grafico[0]["quinta"],
                "Sexta"  => $agenda_grafico[0]["sexta"],
                "Sabado" => $agenda_grafico[0]["sabado"]              

            ),array(
                    '#54bc9b'

                ),"col-md-4");
    echo $chart->chart("Resumo do Mês","pie_chart","ContasDia","pie",                 
            array(
                "A Vencer" => $contas_grafico[0]["total_parcela_avencer"],
                "Vencido" => $contas_grafico[0]["total_parcela_vencida"],
                "Recebido" => $contas_grafico[0]["total_parcela_recebida"]

            ),array(
                    '#54bc9b',
                    '#f14948',
                    '#3590a5',

                ),"col-md-4");
    
                
    echo $form->Listar("col-md-8", "Vencimentos do Dia", null, "request_page",$contas->listar_contas(
                    "INNER JOIN Filial ON Filial.id = Contas.id_filial
                        INNER JOIN Venda ON Venda.id = Contas.id_tabela
                        INNER JOIN TipoDocumento ON TipoDocumento.id = Contas.id_tipo_documento
                        INNER JOIN Pessoa AS PessoaCliente ON PessoaCliente.id = Venda.id_cliente",
                    "25",
                    "Contas.id_status='1' AND Contas.tipo='Receita' AND Contas.data_vencimento='$hoje' AND Venda.id_colaborador='$id_colaborador'",
                    NULL,
                    'Contas.data_vencimento ASC',
                    "Venda.id,
                        Filial.nome_fantasia AS Filial,
                        PessoaCliente.nome AS Cliente,
                        PessoaCliente.cpf AS CPF,
                        Contas.valor_total AS 'Valor Total',
                        Contas.valor_parcela AS 'Valor Parcela',
                        TipoDocumento.descricao AS Documento,                       
                        Contas.data_vencimento AS 'Data Vencimento',
                        Contas.parcela AS Parcela
                       
                        ",null,$pesquisa), "tabela", 
      array(
     
        array("acao" => "/Venda/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye")
      )
    );
    
    
     echo $form->Listar("col-md-12", "Alertas", null, "warning", $listar_intranet, "tabela2", $acao);
      
    foreach ($listar_intranet AS $noticia):
        echo "<div class='col-md-4' >";
        echo $form->MiniCard(array(       
          array("css"=>"col-md-12","cor"=>"$noticia[cor_Status]","icone"=>"campaign","nome"=>"$noticia[titulo]","valor"=>"<h6 class='card-title'>$noticia[Notícia]</h6>","footer"=>"$noticia[data_lancamento]"),
        ));
        echo "</div>";
    endforeach;
 }
 public function logs(){
    $this->acesso_restrito();    
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    
    if (empty($_POST["pesquisa"])) { $pesquisa = null; } else { $pesquisa = $_POST["pesquisa"]; }

    $acesso = new SessionHelper();
    $logs = new LogsModel();
    
    $serverData = $acesso->selectSession('serverData');
    $userData = $acesso->selectSession('userData');
    $id_colaborador = $userData["id_colaborador"];
    $id_cliente_servidor = $serverData[0]["id_cliente"];
    $hoje=date("Y-m-d");
     
    $data_incial= mktime(0, 0, 0, date('m') , 1 , date('Y'));
    $data_final = mktime(23, 59, 59, date('m')+1, date('d')-date('j'), date('Y'));    
    $data_incial = date("Y-m-d",$data_incial);
    $data_final = date("Y-m-d",$data_final);
    $segunda = date("Y-m-d",mktime(23, 59, 59, date('m'), date('d')-date('N'), date('Y'))) ;
    $form = new FormularioHelper();
    echo $form->Listar("col-md-12", "Contas a Receber Excluidas", $action, "close", $logs->listar_logs("INNER JOIN Usuario ON Usuario.id=Logs.id_usuario INNER JOIN Contas ON Logs.id_comando=Contas.id INNER JOIN Venda ON Venda.id=Contas.id_tabela INNER JOIN Pessoa ON Pessoa.id=Venda.id_cliente", $limit, "Logs.comando='/Contas/excluir/'", $offset, $orderby, "Contas.id_tabela AS id,Usuario.usuario,Pessoa.nome AS Cliente,Contas.data_pagamento AS 'Data Pagamento',Contas.data_vencimento AS 'Data Vencimento',Contas.valor_pago AS 'Valor Pago',Contas.valor_total AS 'Valor Total',Contas.valor_parcela AS 'Valor Parcela',Contas.parcela,Logs.observacao,Logs.data_lancamento AS 'Data Exclusão'", $group, $pesquisa), "exclusaoes_contas", array(array("acao"=>"/Venda/visualizar/tipo/Receita/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")), $pesquisa);
    echo $form->Listar("col-md-12", "Estornos Realizado no Caixa", $action, "close", $logs->listar_logs("INNER JOIN Usuario ON Usuario.id=Logs.id_usuario INNER JOIN Caixa ON Logs.id_comando=Caixa.id INNER JOIN Contas ON Contas.id=Caixa.id_tabela INNER JOIN Venda ON Venda.id=Contas.id_tabela INNER JOIN Pessoa ON Pessoa.id=Venda.id_cliente", $limit, "Logs.comando='/Caixa/estorna/'", $offset, $orderby, "Contas.id_tabela AS id,Usuario.usuario,Pessoa.nome AS Cliente,Contas.data_pagamento AS 'Data Pagamento',Contas.data_vencimento AS 'Data Vencimento',Contas.valor_pago AS 'Valor Pago',Contas.valor_total AS 'Valor Total',Contas.valor_parcela AS 'Valor Parcela',Contas.parcela,Logs.observacao,Logs.data_lancamento AS 'Data Exclusão'", $group, $pesquisa), "estorno_caixa", array(array("acao"=>"/Venda/visualizar/tipo/Receita/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")), $pesquisa);
      

 }
 
 public function administradores(){}
 
public function chamado(){
    $this->acesso_restrito();
    
    $menu = new MenuHelper("Bitabits | Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);
    echo $menu->Menu();
    if (empty($_POST["pesquisa"])) { $pesquisa = null; } else { $pesquisa = $_POST["pesquisa"]; }

    $acesso = new SessionHelper();
    $serverData = $acesso->selectSession('serverData');
    $id_cliente_servidor = $serverData[0]["id_cliente"];
    $intranet = new IntranetModel();
    $chamado = new ChamadoModel();
    $listar_intranet = $intranet->listar_intranet(
    "INNER JOIN Status ON Status.id=Intranet.id_status ",
    $limit,
    $where,
    $offset,
    $orderby,
    "Intranet.noticia,Status.cor AS cor_Status,Status.Descricao AS Status, Intranet.id,Intranet.titulo,Intranet.modulo,Intranet.funcionalidade",
    null,
    $pesquisa);
    $form = new FormularioHelper();
    echo $form->Listar("col-md-12", "Alertas", null, "warning", $listar_intranet, "tabela2", $acao);
    
   
   echo $form->Listar(
      "col-md-4", "Chamados em Aberto", "/Chamado/form/", $icone, $chamado->listar_Chamado(
        "INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade
                        INNER JOIN Status ON Status.id=Chamado.id_status
                        INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente",
        NULL,
        "( Chamado.id_status='100')AND(Cliente.cpf='".$serverData[0]["cpf"]."') ",

        NULL,

        'Chamado.data_lancamento ASC',
        "Chamado.id,
                        Chamado.tipo,
                        Cliente.nome AS nome_cliente,
                        Chamado.titulo,Chamado.observacao,
                        Status.descricao AS Status,
                        Status.cor,

                        Prioridade.descricao AS descricao_prioridade"

      ), "tabela1",
      array(
        array("acao" => "/ChamadoGrupo/form/", "classe" => "btn-sm btn-warning", "icone" => "supervised_user_circle"),
        array("acao" => "/Chamado/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
        array("acao" => "/Chamado/excluir/", "classe" => "btn-sm btn-danger", "icone" => "close")
      )
    );
     $chart = new ChartHelper();
   
            $listar_chamado_aguardando_tec = $chamado->listar_Chamado(NULL,NULL,"id_status='100'",NULL,NULL,NULL);
            $listar_chamado_em_andamento = $chamado->listar_Chamado(NULL,NULL,"id_status='104'",NULL,NULL,NULL);
            $listar_chamado_em_reuniao = $chamado->listar_Chamado(NULL,NULL,"id_status='107'",NULL,NULL,NULL);
            $listar_chamado_excluido = $chamado->listar_Chamado(NULL,NULL,"id_status='105'",NULL,NULL,NULL);
    echo $chart->chart("Chamados","pie",                 
            array(
                "Ag. Técnico" => count($listar_chamado_aguardando_tec),
                "Em Andamento" => count($listar_chamado_em_andamento),
                "Ag. Reunião" => count($listar_chamado_em_reuniao),
                "Finalizado" => count($listar_chamado_excluido)

            ),array(
                    '#f14948',
                    '#54bc9b',
                    '#8562a2', 
                    '#3590a5',

                ),"col-md-4");
    echo $form->Listar("col-md-4", "Meus Chamados", null, $icone, $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade INNER JOIN Status ON Status.id=Chamado.id_status INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador",
    NULL,
    "(Chamado.id_status<>'99' OR Chamado.id_status<>'100') AND ( Chamado.id_cliente='$id_cliente_servidor')",
    NULL,
    'Chamado.id_status,Chamado.data_lancamento  ASC',
    "Chamado.id,Chamado.tipo,Cliente.nome AS nome_cliente,Colaborador.id AS id_colaborador,
                        Colaborador.nome AS nome_colaborador,
                        Chamado.titulo,
                        Chamado.observacao,
                        Status.cor AS cor_Status,
                        Status.descricao AS Status,
                        Prioridade.cor AS cor_Prioridade,
                        Prioridade.descricao AS Prioridade ",



        null, $pesquisa), "tabela", 
      array(
        array("acao" => "/Chamado/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
        array("acao" => "/Chamado/visualizar/", "classe" => "btn-sm btn-rose", "icone" => "remove_red_eye"),
        array("acao" => "/Chamado/excluir/", "classe" => "btn-sm btn-danger", "icone" => "close")
      )
    );
}
 

   

  

 

 } ?> 