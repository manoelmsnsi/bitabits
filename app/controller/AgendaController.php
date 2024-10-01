<?php class Agenda extends Controller {   
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
           $comando='/Agenda/admin_listar/';
           if($acesso->acesso_valida($comando)==true){                
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);     
            echo $menu->Menu();   
            $hoje=date("Y-m-d");  
            $data_inicial =$_POST["data_inicial"];                          
            $data_final =$_POST["data_final"];                          
            $id_colaborador =$_POST["id_colaborador"];                           
            $acesso  =new SessionHelper();
            $user_dados=$acesso->selectSession("userData");
     
            $pessoa = new PessoaModel(); 
            $listar_pessoa=$pessoa->listar_Pessoa($join, $limit, "tipo='Colaborador'", $offset, $orderby, null);
            $dados["listar_pessoa"]=$listar_pessoa;
            $agenda = new AgendaModel();
            if($user_dados["administrador"]=="SIM"){                 
                if(empty($id_colaborador) ){ 
                    $listar_agenda = $agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status<>'99' AND Agenda.data_atendimento>='$data_inicial'   AND Agenda.data_atendimento<='$data_final' ",NULL,' Agenda.data_atendimento ASC,Agenda.hora_inicio',"Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento AS 'Data Atendimento',Agenda.hora_inicio AS Inicio,Agenda.hora_fim AS Fim,Agenda.tempo_atendimento AS 'Tempo Médio',Status.cor AS cor_Modelo,Agenda.tipo AS Modelo,Agenda.observacao AS 'Observação',Status.cor AS cor_Status,Status.descricao AS 'Status',Colaborador.nome as 'Colaborador',Cliente.nome as 'Cliente',(SELECT Contato.contato FROM Contato  WHERE Contato.descricao = 'Celular' and Contato.id_tabela = Cliente.id LIMIT 1) as 'Telefone'");  
                }else{
                    $listar_agenda = $agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status<>'99' AND Agenda.data_atendimento>='$data_inicial'   AND Agenda.data_atendimento<='$data_final' AND Agenda.id_colaborador='$id_colaborador'",NULL,' Agenda.data_atendimento ASC,Agenda.hora_inicio',"Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento AS 'Data Atendimento',Agenda.hora_inicio AS Inicio,Agenda.hora_fim AS Fim,Agenda.tempo_atendimento AS 'Tempo Médio',Status.cor AS cor_Modelo,Agenda.tipo AS Modelo,Agenda.observacao AS 'Observação',Status.cor AS cor_Status,Status.descricao AS 'Status',Colaborador.nome as 'Colaborador',Cliente.nome as 'Cliente',(SELECT Contato.contato FROM Contato  WHERE Contato.descricao = 'Celular' and Contato.id_tabela = Cliente.id LIMIT 1) as 'Telefone'");    
                }
            }else{
                $id_colaborador=$user_dados["id_colaborador"];
                $listar_agenda = $agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status<>'99' AND Agenda.data_atendimento>='$data_inicial'   AND Agenda.data_atendimento<='$data_final' AND Agenda.id_colaborador='$id_colaborador'",NULL,' Agenda.data_atendimento ASC,Agenda.hora_inicio',"Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento AS 'Data Atendimento',Agenda.hora_inicio AS Inicio,Agenda.hora_fim AS Fim,Agenda.tempo_atendimento AS 'Tempo Médio',Status.cor AS cor_Modelo,Agenda.tipo AS Modelo,Agenda.observacao AS 'Observação',Status.cor AS cor_Status,Status.descricao AS 'Status',Colaborador.nome as 'Colaborador',Cliente.nome as 'Cliente',(SELECT Contato.contato FROM Contato  WHERE Contato.descricao = 'Celular' and Contato.id_tabela = Cliente.id LIMIT 1) as 'Telefone'");           
              }          
                $form = new FormularioHelper();    
                $inputs.= $form->Input("hidden", "Pesquisa", null, "Pesquisa", $required,null);   
                $inputs.= $form->Input("date", "data_inicial", "col-md-4", $data_inicial, "required","Data Inicial");
                $inputs.= $form->select("Colaborador","id_colaborador", "col-md-4",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Colaborador'", $offset, $orderby, $from),"nome",$user_dados["id_colaborador"],false);  
                $inputs.= $form->Input("date", "data_final", "col-md-4", $data_final, "required","Data Final");
                $inputs.= $form->Button(" btn btn-rose ","Pesquisa");
                $form->card("Pesquisa",$inputs,"col-md-12","#","POST","search");
                
                $inputs2.= $form->Listar("col-md-12", null, "/Agenda/form/", $icone,$listar_agenda, "tabela1", array(array("acao"=>"/Pessoa/visualizar/tabela/Cliente/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Venda/incluir/tipo/Receita/","classe"=>"btn-sm btn-rose","icone"=>"add_task"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
              $form->card("Agendamentos",$inputs2,"col-md-12","/Agenda/form/","POST","list");
           }else{
               $this->view('error_permisao');
           }
       } 

    public function visualizar(){
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Agenda/visualizar/';
               if($acesso->acesso_valida($comando)==true){                
               $id_agenda=$this->getParams("id");
               $id_cliente=$this->getParams("id_cliente");                                         
               $acesso = new SessionHelper();
               $listar_acesso=$acesso->selectSession('userAcesso');
               $user_dados=$acesso->selectSession('userData');
               $dados['listar_acesso']=$listar_acesso;
               $dados['user_dados']=$user_dados;
               $agenda = new AgendaModel();             
               $listar_agenda = $agenda->listar_Agenda("INNER JOIN Pessoa AS Colaborador ON Colaborador.id = Agenda.id_colaborador INNER JOIN Pessoa AS Cliente ON Cliente.id = Agenda.id_cliente INNER JOIN Status ON Status.id = Agenda.id_status",NULL,"Agenda.id_status='1' AND Agenda.id='$id_agenda'",NULL,' Agenda.id DESC',"Agenda.id,Agenda.id_filial,Agenda.id_colaborador,Agenda.id_cliente,Agenda.id_status,Agenda.data_atendimento,Agenda.hora_inicio,Agenda.hora_fim,Agenda.tempo_atendimento,Agenda.tipo,Agenda.observacao,Status.descricao,Status.cor,Colaborador.nome as colaborador,Cliente.nome as cliente,(SELECT Contato.contato FROM Contato WHERE Contato.descricao = 'Celular' LIMIT 1) as telefone");                            
               $dados['listar_agenda'] = $listar_agenda;               
               $logs->cadastrar_logs($comando,'0');//Gera Logs                        
               $this->view('form_visualizar_agenda',$dados); 
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
            $agenda= new AgendaModel();
            $status= new StatusModel();
            $filial = new FilialModel();
            $pessoa = new PessoaModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bitabits - Desenvolvimento", $Class, $AcaoForm, $MetodoDeEnvio);       
               echo $menu->Menu();
                $id=$this->getParams("id");     
                if(!empty($id)){
                    $agenda_dados=$agenda->listar_Agenda($join, "1", "id='$id'", $offset, $orderby, $camposfrom, $group);                                     
                    $agenda_dados= $agenda_dados[0]; 
                    $comando="/".__CLASS__."/alterar/";
                }            
            $form = new FormularioHelper(); 
             $inputs.= $form->Input("hidden", "id", null, $id, $required,null);   
             $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$agenda_dados["id_filial"]);
             $inputs.= $form->select("Colaborador","id_colaborador", "col-md-4",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Colaborador'", $offset, $orderby, $from),"nome",$agenda_dados["id_colaborador"]);  
             $inputs.= $form->select("Cliente","id_cliente", "col-md-4",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Cliente'", $offset, $orderby, $from),"nome",$agenda_dados["id_cliente"]);            
             $inputs.= $form->Input("date", "data_atendimento", "col-md-2", $agenda_dados["data_atendimento"], "required","Data do Atendimento");
             $inputs.= $form->Input("time", "hora_inicio", "col-md-3", $agenda_dados["hora_inicio"], $required,"Horário Inicial");
             $inputs.= $form->Input("time", "hora_fim", "col-md-3", $agenda_dados["hora_fim"], $required,"Previsão de Término");        
           // echo $form->Input("text", "tempo_atendimento", "col-md-2", $agenda_dados["tempo_atendimento], $required,"Tempo do Atendimento");
             $inputs.= $form->select("Tipo","tipo", "col-md-3",$status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='AgendaTipo'",NULL,' Status.id ASC',"Status.descricao AS id"),"id",$agenda_dados["tipo"]);            
             $inputs.= $form->select("Status","id_status", "col-md-3", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Agenda'",NULL,' Status.id ASC',NULL),"descricao",$agenda_dados["id_status"]);
             $inputs.= $form->Input("text", "observacao", "col-md-12", $agenda_dados["observacao"], $required,"Observações");                        

            $inputs.= $form->Button("btn btn-md btn-rose pull-right ","Salvar");  
            $form->card("Criar Agendamento",$inputs,"col-md-12","$comando","POST","schedule");
        }else{

               $this->view('error_permisao');
           }
    }



    public function incluir(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Agenda/incluir/';        
            $tempo_medio=" ";
            $tempo_medio.= substr($_POST['hora_fim'], 0,2)-substr($_POST['hora_inicio'], 0,2)." Hora e ";
            $tempo_medio.=substr($_POST['hora_fim'], 3,2)-substr($_POST['hora_inicio'], 3,2)." Minutos.";
           if($acesso->acesso_valida($comando)==true){
               $agenda = new AgendaModel();   
               $id=$agenda->cadastrar_agenda( 
                   array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_colaborador'=>$_POST['id_colaborador'],
                        'id_cliente'=>$_POST['id_cliente'],
                        'id_status'=>$_POST['id_status'],
                        'data_atendimento'=>$_POST['data_atendimento'],
                        'hora_inicio'=>$_POST['hora_inicio'],
                        'hora_fim'=>$_POST['hora_fim'],
//                        'data_atendimento'=>(new DateTime($_POST['data_atendimento']))->format("Y-m-d"), 
//                        'hora_inicio'=>(new DateTime($_POST['hora_inicio']))->format("Y-m-d H:i:"), 
//                        'hora_fim'=>(new DateTime($_POST['hora_fim']))->format("Y-m-d H:i:s"),
                        'tempo_atendimento'=>$tempo_medio,
                        'tipo'=>$_POST['tipo'],
                        'observacao'=>$_POST['observacao'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                   )
               );                
              $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Agenda/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }
       }
    public function alterar(){    
           $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/Agenda/alterar/';
           if($acesso->acesso_valida($comando)==true){
               $id = $_POST['id'];
               $agenda = new AgendaModel();      
               $agenda->alterar_agenda(
                   array(
                        'id_filial'=>$_POST['id_filial'],
                        'id_colaborador'=>$_POST['id_colaborador'],
                        'id_cliente'=>$_POST['id_cliente'],
                        'id_status'=>$_POST['id_status'],
                        'data_atendimento'=>$_POST['data_atendimento'],
                        'hora_inicio'=>$_POST['hora_inicio'],
                        'hora_fim'=>$_POST['hora_fim'],
//                        'data_atendimento'=>(new DateTime($_POST['data_atendimento']))->format("Y-m-d"), 
//                        'hora_inicio'=>(new DateTime($_POST['hora_inicio']))->format("Y-m-d H:i:"), 
//                        'hora_fim'=>(new DateTime($_POST['hora_fim']))->format("Y-m-d H:i:s"),
                        'tempo_atendimento'=>$_POST['tempo_atendimento'],
                        'tipo'=>$_POST['tipo'],
                        'observacao'=>$_POST['observacao'],
                        'data_lancamento'=>date('Y-m-d H:i:s'),
                   ),'id='.$id
               );  

               $logs->cadastrar_logs($comando,$id);//Gera Logs               
               $redirect = new RedirectHelper();
               $redirect->goToUrl('/Agenda/admin_listar/');    
           }else{
               $this->view('error_permisao');
           }
       }

    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Agenda/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');            
            $agenda = new AgendaModel();      
            $agenda->excluir_agenda( array( 'id_status'=>'99' ),'id='.$id );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Agenda/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 