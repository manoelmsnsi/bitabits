<?php class ChamadoInterno extends Controller {   

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

            $serverData = $acesso->selectSession('serverData');

            $id_cliente_servidor=$serverData[0]["id_cliente"];

           

            $chamado = new ChamadoInternoModel();

             
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper(); 

            $inputs = $form->Listar("col-md-6", "Meus Chamados", "/".__CLASS__."/form/", "list",$chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 

                INNER JOIN Status ON Status.id=Chamado.id_status 

                INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 

                INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id 

                INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador", NULL, "(Chamado.id_status<>'99' OR Chamado.id_status<>'100') ", NULL, ' Chamado.id_status,Chamado.data_lancamento  ASC', "Chamado.id,Chamado.tipo AS Tipo,Cliente.nome AS Cliente,Colaborador.id AS id_colaborador,Colaborador.nome AS Colaborador,Chamado.titulo,Chamado.observacao AS 'Observação',Status.cor AS cor_Status,Status.Descricao AS Status,Prioridade.cor AS cor_Prioridade,Prioridade.descricao AS 'Prioridade',Prioridade.cor AS cor_prioridade, Chamado.id_tabela",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

                $inputs.= $form->Listar("col-md-6 ", "Aguardando Técnico", "/".__CLASS__."/form/", "list",$chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 

                INNER JOIN Status ON Status.id=Chamado.id_status 

                INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 

            ", NULL, "( Chamado.id_status='100') ", NULL, ' Chamado.data_lancamento  ASC', "Chamado.id,Chamado.tipo AS Tipo, Cliente.nome AS Cliente,Chamado.titulo,Chamado.observacao AS 'Observação',Status.cor AS cor_Status,Status.descricao AS 'Status',Prioridade.cor AS cor_Prioridade,Prioridade.descricao AS 'Prioridade'",NULL,$pesquisa), "tabela2", array(array("acao"=>"/ChamadoGrupoInterno/form/","classe"=>"btn-sm btn-warning","icone"=>"supervised_user_circle"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
///AND(Cliente.cpf='".$serverData[0]["cpf"]."')//colocar no where para os clientes
  $form->card("Gerenciamento de Chamados [INTERNO]", $inputs, "col-md-12", $comando, "POST", "list");
 
        }else{

            $this->view('error_permisao');

        }    

    }

    
    public function form (){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/Chamado/admin_listar/")==true){

        $status= new StatusModel();

        $filial = new FilialModel();

        $chamado = new ChamadoInternoModel();

        

        $acesso = new SessionHelper();

        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

        echo $menu->Menu();

        $id=$this->getParams("id"); 
        
        $id_chamado=$this->getParams("id_chamado");     

           $pessoa=new PessoaModel(); 

        

        if(!empty($id)){
    //   $id=$this->getParams("id");  
            $chamado_dados=$chamado->listar_Chamado($join, "1", "id=$id", $offset, $orderby);

            $chamado_dados= $chamado_dados[0]; 

            $comando="/".__CLASS__."/alterar/";

        }            

            $form = new FormularioHelper();
            $inputs.= $form->Input("hidden", "id", null, $id, $required,null);
            $inputs.= $form->Input("hidden", "id_chamado", null, $id_chamado, $required,null);
            $inputs.= $form->Input("hidden", "tabela", null, "Chamado", $required,null);
            $inputs.= $form->Input("text", "titulo", "col-md-6", $chamado_dados["titulo"], $required,"Título");            
            $inputs.= $form->select("Cliente","id_cliente", "col-md-6", $pessoa->listar_Pessoa($join, $limit, "id_status='1' AND tipo='Cliente'", $offset, $orderby, $from, $group, $pesquisa),"nome",$chamado_dados["id_cliente"]);
            
            $inputs.= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral' OR tabela='Chamado'",NULL,' Status.id ASC',NULL),"descricao",$chamado_dados["id_status"]);            
            $inputs.= $form->select("Prioridade","id_prioridade", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Prioridade'",NULL,NULL,NULL),"descricao",$chamado_dados["id_prioridade"]);
            $inputs.= $form->select("Tipo","tipo", "col-md-4",array(array("id"=>"Requisição","descricao"=>"Requisição"),array("id"=>"Incicidente","descricao"=>"Incicidente")),"descricao");            
            $inputs.= $form->Input("text", "observacaos", "col-md-12", $chamado_dados["observacao"], $required,"Observação");            
          
            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
          
            $form->card("Cadastrar Chamado",$inputs,"col-md-12",$comando,"POST","donut_small");

            }else{

               $this->view('error_permisao');

           }

        

    }



    public function data_intervalo($dt_inicial,$dt_final){

        $entrada = strtotime( $dt_inicial);

        $saida = strtotime( $dt_final );

    

        $diferenca = $saida - $entrada;

        $diferenca=$diferenca/3600;

      $mes=0;

      $dia=0;

      $minuto=0;

        while($i<=$diferenca){



//            if($diferenca>="720" ){

//                $mes=$mes+1;

//                $diferenca=$diferenca-720;         

//                $i=0;

//            }//&& $diferenca<"720" 

            if($diferenca>="24" ){

                $dia=$dia+1;

                $diferenca=$diferenca-24;

                $i=0;

            }

            if($diferenca>="1" && $diferenca<"24"){

                $hora=$hora+1;

                $diferenca=$diferenca-1;

                $i=0;

            }

            if($diferenca<"1" && $diferenca<"1"){     

                $diferenca=$diferenca*60;

                while($i2<$diferenca){

                    $minuto=$minuto+1;

                    $diferenca=$diferenca-1;

                    $i2=0;

                }



            }

            $i++; 

        }

       // return  $dados["data"]=  $mes." / ".$dia." - ".$hora." : ".$minuto;

        return   $dia." Dias e 0".$hora." : ".$minuto."0";

    }



    public function visualizar(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/visualizar/"; 

        

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 

          //  $filiais=$acesso->acesso_filial(__CLASS__); 

           $id= $this->getParams("id");  
            $id_venda=$this->getParams("id_tabela");
           $acesso = new SessionHelper();
           $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();              
 
            $form = new FormularioHelper();
            $chamado = new ChamadoInternoModel();
            $upload = new UploadModel();
            $itens= new ItensModel();
            $listar_chamado = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 
            INNER JOIN Status ON Status.id=Chamado.id_status 
            INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 
            INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id
            INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador", "1", "Chamado.id_status<>99 AND Chamado.id='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS nome_cliente,Colaborador.nome AS nome_colaborador,Chamado.data_inicio,Chamado.data_lancamento,Chamado.titulo,Chamado.observacao,Status.descricao AS descricao_status,Status.cor as cor_status,Prioridade.descricao AS descricao_prioridade,Prioridade.cor AS cor_prioridade");
       
            
            $listar_chamado_filho = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 
            INNER JOIN Status ON Status.id=Chamado.id_status 
            INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 
            INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id
            INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador ", NULL, "Chamado.id_status<>'99' AND Chamado.id_tabela='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS Cliente,Colaborador.nome AS Colaborador,Chamado.data_inicio AS 'Data Inicio',Chamado.data_lancamento AS 'Data Lançamento',Chamado.titulo AS 'Titulo',Status.cor as cor_Status,Status.descricao AS Status,Prioridade.cor AS cor_Prioridade,Prioridade.descricao AS Prioridade,Chamado.observacao AS 'Observação' ");
            
            $lista2= $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/$id/tabela/Chamado/id_filial/1/",$icone,$upload->listar_Upload($join, $limit,"Tabela='Chamado' AND id_tabela='$id'" , $offset, $orderby, "descricao AS 'Descrição',src AS src_Imagem,data_lancamento AS 'Data Lançamento'"),"tabela2");    
            $lista1= $form->Listar("col-md-12", $titulo, "/ChamadoInterno/form/id_chamado/$id/",$icone,$listar_chamado_filho,"tabela1",array(array("acao"=>"/ChamadoInterno/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye")));    
            $dados['listar_chamado_filho'] = $listar_chamado_filho;
            $lista3 = $form->Listar("col-md-12", "Serviços Prestado", "/Itens/form/tabela/Servico/tipo/Receita/id_tabela/$id_venda/", "construction", $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='$id_venda' AND tabela='Servico'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.codigo_barra AS 'Código de Barra',Produto.descricao AS 'Descrição',Itens.id,Itens.quantidade AS Quantidade,Itens.valor_venda AS 'Valor'"), "tabela_itens", $acao, $pesquisa);

            $logs->cadastrar_logs($comando, '0'); //Gera Logs

            $dados["tempo_atendimento"]= $this->data_intervalo($listar_chamado[0]["data_inicio"], date("Y-m-d H:m:s"));
            $inputs.="<form class='col-md-12 row'>";
            $inputs.= $form->Input("text", "titulo", "col-md-12", $listar_chamado[0]["titulo"], $required,"Título");            
            $inputs.= $form->Input("text", "cliente", "col-md-8", $listar_chamado[0]["nome_cliente"], $required,"Cliente");            
            $inputs.= $form->Input("text", "data_abertura", "col-md-4", $listar_chamado[0]["data_lancamento"], $required,"Data Abertura");            
            $inputs.= $form->Input("text", "Colaborador", "col-md-8", $listar_chamado[0]["nome_colaborador"], $required,"Colaborador");                     
            $inputs.= $form->Input("text", "data_inicio_atendimento", "col-md-4", $listar_chamado[0]["data_inicio"], $required,"Data Inicio Atendimento");            
            $inputs.= $form->Input("text", "observacao", "col-md-12", $listar_chamado[0]["observacao"], $required,"Observação");
            
            $inputs.=  "<button type='submit' class='btn btn-sm' style='background:".$listar_chamado[0]['cor_prioridade']."'>".$listar_chamado[0]['descricao_prioridade']."</button>";
            $inputs.=  "<button type='submit' class='btn btn-sm' style='background:".$listar_chamado[0]['cor_status']."'>".$listar_chamado[0]['descricao_status']."</button>";
            $inputs.="</form>";
            
            $inputs.= $form->Abas($Tipo, "EBD", "col-md-12", 
                array(array("id" => "Chamados", "icone" => "contacts", "descricao" => "Chamados Relacionados"), 
                array("id" => "Serviços", "icone" => "construction", "descricao" => "Serviços","classe" => " active"),
                array("id" => "Imagens", "icone" => "image", "descricao" => "Imagens")),
                array(array("id" => "Imagens", "dados" => "$lista2"),
                    array("id" => "Serviços", "dados" => "$lista3","classe" => " active"),
                array("id" => "Chamados", "dados" => "$lista1"))); 
          
            $form->card("Cadastrar Chamado",$inputs,"col-md-12","/ChamadoInterno/form/id/$id/","POST","donut_small");

        }else{

            $this->view('error_permisao');

        }

    }
    public function visualizar_antigo(){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/visualizar/";
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
          //  $filiais=$acesso->acesso_filial(__CLASS__); 
           $id= $this->getParams("id");
           $dados["id"]=$id;
           $acesso = new SessionHelper();
           $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();
             
           $chamado = new ChamadoInternoModel();
            $listar_chamado = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 
            INNER JOIN Status ON Status.id=Chamado.id_status 
            INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 

            INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id
            INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador", "1", "Chamado.id_status<>99 AND Chamado.id='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS nome_cliente,Colaborador.nome AS nome_colaborador,Chamado.data_inicio,Chamado.data_lancamento,Chamado.titulo,Chamado.observacao,Status.descricao AS descricao_status,Status.cor as cor_status,Prioridade.descricao AS descricao_prioridade,Prioridade.cor AS cor_prioridade");
            $dados['listar_chamado'] = $listar_chamado;
            
            $listar_chamado_filho = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 
            INNER JOIN Status ON Status.id=Chamado.id_status 
            INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente 

            INNER JOIN ChamadoGrupo ON ChamadoGrupo.id_chamado=Chamado.id
            INNER JOIN Pessoa AS Colaborador ON Colaborador.id=ChamadoGrupo.id_colaborador ", NULL, "Chamado.id_status<>99 AND Chamado.id_tabela='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS nome_cliente,Colaborador.nome AS nome_colaborador,Chamado.data_inicio,Chamado.data_lancamento,Chamado.titulo,Chamado.observacao,Status.descricao AS descricao_status,Status.cor as cor_status,Prioridade.descricao AS descricao_prioridade,Prioridade.cor AS cor_prioridade");
            $dados['listar_chamado_filho'] = $listar_chamado_filho;
           
            $logs->cadastrar_logs($comando, '0'); //Gera Logs

            $dados["tempo_atendimento"]= $this->data_intervalo($listar_chamado[0]["data_inicio"], date("Y-m-d H:m:s"));

            $this->view('form_visualizar_chamado',$dados); 

        }else{

            $this->view('error_permisao');

        }

    }

    public function visualizar_tab(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 



        $logs = new LogsModel();

        $comando='/Chamado/visualizar/';

    

      

        if($acesso->acesso_valida($comando)==true){ 

            $id= $this->getParams("id");

            $dados["id"]=$id;

            $acesso = new SessionHelper();

            $listar_acesso = $acesso->selectSession('userAcesso'); 

            $user_dados = $acesso->selectSession('userData');

            $dados['listar_acesso'] = $listar_acesso;

            $dados['user_dados'] = $user_dados;
           
            

            $upload = new UploadModel();

               $listar_uplaod = $upload->listar_Upload(NULL,NULL,"id_status<>99 AND tabela='Chamado' AND id_tabela='{$id}'",NULL,' Upload.id DESC',NULL);

               $dados['listar_uplaod'] = $listar_uplaod;

               

            $chamado = new ChamadoInternoModel();

            $listar_chamado = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 

            INNER JOIN Status ON Status.id=Chamado.id_status 

            INNER JOIN Pessoa AS Cliente ON Cliente.id=Chamado.id_cliente ", NULL, "Chamado.id_status<>99 AND Chamado.id='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS nome_cliente,Chamado.data_inicio,Chamado.data_lancamento,Chamado.titulo,Chamado.observacao,Status.descricao AS descricao_status,Status.cor as cor_status,Prioridade.descricao AS descricao_prioridade,Prioridade.cor AS cor_prioridade");

                        $dados['listar_chamado'] = $listar_chamado;



                        $listar_chamado_filho = $chamado->listar_Chamado("INNER JOIN Status AS Prioridade ON Prioridade.id=Chamado.id_prioridade 

            INNER JOIN Status ON Status.id=Chamado.id_status 

            INNER JOIN Pessoa AS  Cliente ON Cliente.id=Chamado.id_cliente 

", NULL, "Chamado.id_status<>99 AND Chamado.id_tabela='$id'", NULL, ' Chamado.id DESC', "Chamado.id,Cliente.nome AS nome_cliente,Chamado.data_inicio,Chamado.data_lancamento,Chamado.titulo,Chamado.observacao,Status.descricao AS descricao_status,Status.cor as cor_status,Prioridade.descricao AS descricao_prioridade,Prioridade.cor AS cor_prioridade");

            $dados['listar_chamado_filho'] = $listar_chamado_filho;

            

            $logs->cadastrar_logs($comando, '0'); //Gera Logs

           $dados["tempo_atendimento"]= $this->data_intervalo($listar_chamado[0]["data_inicio"], date("Y-m-d H:m:s"));

            $this->view('form_visualizar_chamado',$dados); 

        }else{

            $this->view('error_permisao');

        }

    } 

   

    

    

    public function incluir() {

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando = '/Chamado/incluir/';



        if ($acesso->acesso_valida($comando) == true) {

            $acesso = new SessionHelper();

            $info_servidor=$acesso->selectSession("serverData");

            $info_usuario=$acesso->selectSession("userData");

           
            
            $chamado = new ChamadoInternoModel();
            $id = $chamado->cadastrar_chamado(
                    array(
                        'titulo' => $_POST['titulo'], 
                        'id_status' => '100',
                        'id_prioridade' => $_POST['id_prioridade'],
                        'id_cliente' => $_POST['id_cliente'],
                        'id_tabela' => $_POST['id_chamado'],
                        'tabela' => $_POST['tabela'],
                        'tipo' =>$_POST['tipo'],
                        'observacao' => $_POST['observacao'],
                        'data_lancamento' => date('Y-m-d H:i:s'), 
                    )
            );
           $email = new EmailHelper();
           $email->enviar("manoelmsnsi@gmail.com", "suporte@bitabits.com.br", "Bit a Bits", "Abertura de Chamado", "A Empresa <b>".$info_servidor[0]['nome']."</b>, com o Usuario: <b>".$info_usuario["usuario"]."</b>, TIULO: ".$_POST['titulo']." OBSERVAÇÃO: ".$_POST['observacao'],null,null,"Enviado COm Sucesso!");
           $logs->cadastrar_logs($comando, $id); //Gera Logs
           $redirect = new RedirectHelper();
           $redirect->goToUrl('/ChamadoInterno/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
  
    public function alterar() {
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Chamado/alterar/';
        if ($acesso->acesso_valida($comando) == true) {
            $id = $_POST['id'];

            $chamado = new ChamadoInternoModel();
            $chamado->alterar_chamado(
                    array(
                        'titulo' => $_POST['titulo'],
                        'id_status' => $_POST['id_status'],
                        'id_prioridade' => $_POST['id_prioridade'],
                        'id_cliente' => $_POST['id_cliente'],
                        'tipo' =>$_POST['tipo'],
                        'observacao' => $_POST['observacao'],
                        'data_inicio' => date('Y-m-d H:i:s'),
                       
                    ), 'id=' . $id
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ChamadoInterno/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }


    public function excluir(){    
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Chamado/excluir/';
        if($acesso->acesso_valida($comando)==true){
            $id = $this->getParams('id');
            $chamado = new ChamadoInternoModel();      
            $chamado->excluir_chamado( array( 'id_status'=>'99' ),"id=$id" );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/ChamadoInterno/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    } 
 } ?> 