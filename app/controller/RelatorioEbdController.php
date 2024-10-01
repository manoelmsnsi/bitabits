<?php 
//// class RelatorioEbd extends Controller {   
//    private  $auth,$db;
//    public function acesso_restrito(){          
//        $this->auth = new AutenticaHelper();
//        $this->auth->setLoginControllerAction('Index','')
//                   ->checkLogin('redirect');              
//        $this->db = new AdminModel(); 
//    } 
//public function moodelo(){
//        $this->acesso_restrito();
//        $acesso = new AcessoHelper();
//        $logs = new LogsModel();
//        $comando="/".__CLASS__."/incluir/";         
//        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
//            $filiais=$acesso->acesso_filial(__CLASS__);
//            $status= new StatusModel();
//            $filial = new FilialModel();           
//            $acesso = new SessionHelper();           
//            $status = new StatusModel();
//            
//            $menu = new MenuHelper();        
//            echo $menu->Menu();            
//            $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     
//            $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$status->listar_Status(NULL,NULL,"id_status<>99 AND ({$filiais})",NULL,' Status.id DESC',NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/".__CLASS__."/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")));
//            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","library_books");
//        }else{
//            $this->view('error_permisao');
//        }    
//    }
//   
//    
//    public function form(){ 
//       $acesso = new SessionHelper();
//        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
//        echo $menu->Menu();        
//        $comando="/".__CLASS__."/incluir/";
//               
//        $status= new StatusModel();
//        $classe = new ClasseModel();
//        $contato = new ContatoModel();
//        $contato_dado=$contato->listar_Contato($join, "1", $where, $offset, $orderby);
//        $contato_dado= $contato_dado[0];    
//        $form = new FormularioHelper("Relatorio Escola Biblica Dominical","col-md-12 ",$comando."tabela/$tabela/id_tabela/$id_tabela/","POST");
//            $inputs.= $form->select("Classe","id_classe", "col-md-2", $classe->listar_Classe(NULL,NULL,"id_status<>99",NULL,' Classe.id DESC',NULL),"descricao");
//            $inputs.= $form->Input("hidden", "id", null, $id, $required,null);        
//            $inputs.= $form->Input("text", "descricao", "col-md-4", $contato_dado["descricao"], "required","Descricao");
//            $inputs.= $form->Input("text", "contato", "col-md-4", $contato_dado["contato"], $required,"Contato");
//            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");
//            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
//            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","library_books");
//      
//    }
//
//    
//    public function relatorio_ebd(){
//        $acesso = new SessionHelper();
//        
//        $comando="/".__CLASS__."/incluir/";
//        $menu = new MenuHelper("B", "col-md-12", $AcaoForm, $MetodoDeEnvio);
//        echo $menu->Menu();         
//        $relatorio = new ItenTrimestreModel(); 
//        $listar_relatorio=$relatorio->listar_ItenTrimestre("INNER JOIN Trimestre ON Trimestre.id = ItenTrimestre.id_trimestre INNER JOIN Classe ON Classe.id = ItenTrimestre.id_classe INNER JOIN Licao ON Licao.id_classe = Classe.id INNER JOIN HistoricoChamada ON HistoricoChamada.id_licao= Licao.id", $limit,  "Trimestre.id_status=1 ", $offset, $orderby, "Classe.descricao AS 'Descrição',COUNT(HistoricoChamada.id_pessoa) AS 'Matriculados',SUM(HistoricoChamada.chamada='Presente') AS Presentes,SUM(HistoricoChamada.chamada='Ausente') AS Ausentes,Licao.total_biblia as Biblias,Licao.total_revista as Revista,Licao.total_visita AS Visitas,Licao.total_oferta as Ofertas" , "id_licao");
//        $status= new StatusModel();
//        $form = new FormularioHelper($Titulo, $Class, $AcaoForm, $MetodoDeEnvio,"school");
//        echo $form->Listar("col-md-12", $titulo, $action,$icone,$listar_relatorio,null,array(array("acao"=>"/Pessoa/form/","classe"=>"btn-sm btn-info","icone"=>"people"),array("acao"=>"/Pessoa/form/","classe"=>"btn-sm btn-danger","icone"=>"edit")));      
//        
//        foreach($listar_relatorio as $totais){
//            $total_matriculado=$total_matriculado+$totais["Matriculados"];
//            $total_presentes=$total_presentes+$totais["Presentes"];
//            $total_ausentes=$total_ausentes+$totais["Ausentes"];
//            $total_biblia=$total_biblia+$totais["Biblias"];
//            $total_revista=$total_revista+$totais["Revista"];
//            $total_visita=$total_visita+$totais["Visitas"];
//            $total_oferta=$total_oferta+$totais["Ofertas"];
//        }
//        echo $form->Input("text", NULL, "col-md-3", $total_matriculado, $Required,"Total de Matriculados","disabled");
//        echo $form->Input("text", NULL, "col-md-3", $total_presentes, $Required,"Total de Presentes","disabled");
//        echo $form->Input("text", NULL, "col-md-3", $total_ausentes, $Required,"Total de Ausentes","disabled");
//        echo $form->Input("text", NULL, "col-md-3", $total_visita, $Required,"Total de Visitas","disabled");
//        echo $form->Input("text", NULL, "col-md-4", $total_biblia, $Required,"Total de Biblias","disabled");
//        echo $form->Input("text", NULL, "col-md-4", $total_revista, $Required,"Total de Revistas","disabled");
//        echo $form->Input("text", NULL, "col-md-4", $total_oferta, $Required,"Total de Ofertas","disabled");
//       
//        
//    }
//
//    public function incluir(){     
//           $this->acesso_restrito();
//           $acesso = new AcessoHelper(); 
//           $logs = new LogsModel();
//           $comando='/Pessoa/incluir/';
//
//           if($acesso->acesso_valida($comando)==true){
//
//               $cliente = new PessoaModel();      
//               $cpf=$_POST['cpf'];
//               $tipo=$_POST['tipo'];
//               $cliente_listar= $cliente->listar_Pessoa(NULL, NULL, "id_status='1' AND Pessoa.cpf='$cpf' AND tipo='$tipo'", NULL, NULL);
//
//              if(count($cliente_listar)<=0){
//               $id=$cliente->cadastrar_Pessoa( 
//                   array(
//                       'id_filial'=>$_POST['id_filial'],
//                       'nome'=>$_POST['nome'],
//                       'apelido'=>$_POST['apelido'],
//                       'cpf'=>$_POST['cpf'],
//                       'tipo'=>$_POST['tipo'],
//                       'rg'=>$_POST['rg'],
//                       'id_status'=>$_POST['id_status'],
//                       'data_nascimento'=>$_POST['data_nascimento'],
//                       'data_lancamento'=>  date("Y-m-d H:i:s"),
//                   )
//               );  
//               $logs->cadastrar_logs($comando,$id);//Gera Logs
//               $redirect = new RedirectHelper();
//               $redirect->goToUrl('/Pessoa/admin_listar/');   
//              }else{
//                    echo "<script> alert('CPF, ja cadastrado')</script>";
//                   $redirect = new RedirectHelper();
//                   $redirect->goToUrl('/Pessoa/visualizar/id/'.$cliente_listar[0]["id"]);  
//              }
//           }else{
//               $this->view('error_permisao');
//           }
//       }
//    public function alterar(){    
//           $this->acesso_restrito();
//           $acesso = new AcessoHelper(); 
//           $logs = new LogsModel();
//           $comando='/Pessoa/alterar/';
//           if($acesso->acesso_valida($comando)==true){
//               $id = $_POST['id'];
//               $pessoa = new PessoaModel();      
//               $pessoa->alterar_Pessoa(
//                   array(
//                       'id_filial'=>$_POST['id_filial'],
//                       'nome'=>$_POST['nome'],
//                       'apelido'=>$_POST['apelido'],
//                       'cpf'=>$_POST['cpf'],
//                       //'tipo'=>$_POST['tipo'],
//                       'rg'=>$_POST['rg'],
//                       'id_status'=>$_POST['id_status'],
//                       'data_nascimento'=>$_POST['data_nascimento'],
//                   ),"id=$id"
//               );  
//               $logs->cadastrar_logs($comando,$id);//Gera Logs
//               $redirect = new RedirectHelper();
//               $redirect->goToUrl('/Pessoa/admin_listar/');    
//           }else{
//               $this->view('error_permisao');
//           }
//
//       }
//    public function excluir(){    
//        $this->acesso_restrito();
//        $acesso = new AcessoHelper(); 
//        $logs = new LogsModel();
//        $comando='/Pessoa/excluir/';
//        if($acesso->acesso_valida($comando)==true){
//            $id = $this->getParams('id');
//            $pessoa = new PessoaModel();      
//               $pessoa->alterar_Pessoa( array( 'id_status'=>'99' ),'id='.$id );  
//            $logs->cadastrar_logs($comando,$id);//Gera Logs
//            $redirect = new RedirectHelper();
//            $redirect->goToUrl('/Pessoa/admin_listar/');    
//        }else{
//            $this->view('error_permisao');
//        }
//    } 
// } ?> 