<?php class ItenClasse extends Controller {   

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
        $comando='/ItenClasse/admin_listar/';
   //     if($acesso->acesso_valida($comando)==true){ 
           
            $filiais=$acesso->acesso_filial('ItenClasse');

            $acesso = new SessionHelper();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

            $id= $this->getParams("id_classe");
            $dados["id_classe"]=$id;
            $dados["id_trimestre"]=$this->getParams("id_trimestre");
            $hoje= date("Y-m-d");
            $licao = new LicaoModel();
            $licao_dados=$licao->listar_Licao($join, $limit, "data_lancamento='$hoje' AND id_classe='$id'", $offset, $orderby, $from, $group);
            $dados["licao"] = $licao_dados[0];
            $id_licao=$licao_dados[0]["id"];
           
            $historico = new HistoricoChamadaModel();
            $itenclasse = new ItenClasseModel();
             $classe = $itenclasse->listar_ItenClasse("  INNER JOIN Classe ON Classe.id = ItenClasse.id_classe INNER JOIN Status ON Status.id = Classe.id_status INNER JOIN Trimestre ON Trimestre.id = ItenClasse.id_iten_trimestre INNER JOIN Filial ON Filial.id = Classe.id_filial" ,"1","(ItenClasse.id_status<>'99') AND (Classe.id='{$id}') AND ($filiais) ",NULL,' ItenClasse.id DESC',"Filial.nome_fantasia AS filial,Classe.idade_minima,Classe.idade_maxima, Classe.descricao,Status.cor AS cor_Status,Status.Descricao AS Status,Classe.data_lancamento,Classe.id",NULL);
            if(!empty($id_licao)){
                $dados["historico"] = $historico->listar_HistoricoChamada($join, $limit, "id_licao='$id_licao' AND data_lancamento=DATE('$hoje')", $offset, $orderby);
                // $dados["historico"] AND data_lancamento='$hoje'            
            }
            $listar_aluno_classe = $itenclasse->listar_ItenClasse(" INNER JOIN Pessoa ON Pessoa.id = ItenClasse.id_pessoa INNER JOIN Classe ON Classe.id = ItenClasse.id_classe INNER JOIN Status ON Status.id = ItenClasse.id_status INNER JOIN Trimestre ON Trimestre.id = Classe.id_trimestre INNER JOIN Filial ON Filial.id = ItenClasse.id_filial" ,NULL,"(ItenClasse.id_status<>'99') AND (ItenClasse.id_classe='{$id}') AND ($filiais) ",NULL,' Pessoa.nome ASC',"ItenClasse.id,Pessoa.nome,ItenClasse.tipo,Status.cor AS cor_Status,Status.Descricao AS Status,ItenClasse.data_lancamento,Pessoa.id as id_pessoa",NULL);
            $listar_itenclasse = $itenclasse->listar_ItenClasse(" INNER JOIN Pessoa ON Pessoa.id = ItenClasse.id_pessoa INNER JOIN Classe ON Classe.id = ItenClasse.id_classe INNER JOIN Status ON Status.id = ItenClasse.id_status INNER JOIN Trimestre ON Trimestre.id = Classe.id_trimestre INNER JOIN Filial ON Filial.id = ItenClasse.id_filial" ,NULL,"(ItenClasse.id_status='1') AND (ItenClasse.id_classe='{$id}') AND ($filiais) ",NULL,' Pessoa.nome ASC',"ItenClasse.id,Pessoa.nome,ItenClasse.tipo,Status.cor AS cor_Status,Status.Descricao AS Status,ItenClasse.data_lancamento,Pessoa.id as id_pessoa",NULL);
            
           
          
            
            $logs->cadastrar_logs($comando,'0');//Gera Logs
            $dados['listar_itenclasse'] = $listar_itenclasse;           
             $dados['listar_aluno_classe'] = $listar_aluno_classe;
            $dados['classe'] = $classe;           
            $this->view('listar_itenclasse',$dados); 

//        }else{

//            $this->view('error_permisao');

//        }

    } 
 public function admin_listar_novo(){

        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/ItenClasse/admin_listar/';
   //     if($acesso->acesso_valida($comando)==true){ 
           
            $filiais=$acesso->acesso_filial('ItenClasse');

            $acesso = new SessionHelper();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();

            $id= $this->getParams("id_classe");
            $dados["id_classe"]=$id;
            $dados["id_trimestre"]=$this->getParams("id_trimestre");
            $hoje= date("Y-m-d");
            $licao = new LicaoModel();
            $licao_dados=$licao->listar_Licao($join, $limit, "data_lancamento='$hoje' AND id_classe='$id'", $offset, $orderby, $from, $group);
            $dados["licao"] = $licao_dados[0];
            $id_licao=$licao_dados[0]["id"];
           
            $historico = new HistoricoChamadaModel();
            $itenclasse = new ItenClasseModel();
             $classe = $itenclasse->listar_ItenClasse("  INNER JOIN Classe ON Classe.id = ItenClasse.id_classe INNER JOIN Status ON Status.id = Classe.id_status INNER JOIN Trimestre ON Trimestre.id = ItenClasse.id_iten_trimestre INNER JOIN Filial ON Filial.id = Classe.id_filial" ,"1","(ItenClasse.id_status<>'99') AND (Classe.id='{$id}') AND ($filiais) ",NULL,' ItenClasse.id DESC',"Filial.nome_fantasia AS filial,Classe.idade_minima,Classe.idade_maxima, Classe.descricao,Status.cor AS cor_Status,Status.Descricao AS Status,Classe.data_lancamento,Classe.id",NULL);
            if(!empty($id_licao)){
                $listar_historico = $historico->listar_HistoricoChamada($join, $limit, "id_licao='$id_licao' AND data_lancamento=DATE('$hoje')", $offset, $orderby);
                // $dados["historico"] AND data_lancamento='$hoje'            
            }
            $listar_itenclasse = $itenclasse->listar_ItenClasse(" INNER JOIN Pessoa ON Pessoa.id = ItenClasse.id_pessoa INNER JOIN Classe ON Classe.id = ItenClasse.id_classe INNER JOIN Status ON Status.id = ItenClasse.id_status INNER JOIN Trimestre ON Trimestre.id = Classe.id_trimestre INNER JOIN Filial ON Filial.id = ItenClasse.id_filial" ,NULL,"(ItenClasse.id_status<>'99') AND (ItenClasse.id_classe='{$id}') AND ($filiais) ",NULL,' Pessoa.nome ASC',"Pessoa.nome,ItenClasse.tipo,Status.cor AS cor_Status,Status.Descricao AS Status,ItenClasse.data_lancamento,Pessoa.id as id_pessoa",NULL);
            $logs->cadastrar_logs($comando,'0');//Gera Logs
          
            $form = new FormularioHelper();
            
            $inputs.= $form->Input("text", "filial", "col-md-4", $classe[0]["filial"], "required","Filial");
            $inputs.= $form->Input("text", "classe", "col-md-4", $classe[0]["descricao"], "required","Classe");
            $inputs.= $form->Input("text", "idade_minima", "col-md-4", $classe[0]["idade_minima"], "required","Idade Minima");
            $inputs.= $form->Input("text", "idade_maxima", "col-md-4", $classe[0]["idade_maxima"], "required","Idade Maxima");
            $inputs.= $form->Input("text", "id_status", "col-md-4", $classe[0]["Status"], "required","Status");
            $inputs.= $form->Input("text", "data_lancamento", "col-md-4", $classe[0]["data_lancamento"], "required","Criação");
            
            $listar_classe=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Cliente/", $icone, $classe, "tabela1", array(array("acao"=>"/Pessoa/form/tipo/Cliente/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Cliente/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);
            $listar_iten_classe.=$form->Input("text", "id_classe", "col-md-4", $id, "required","id_classe");
            $listar_iten_classe.=$form->Input("text", "licao", "col-md-4", $listar_historico[0]["licao"], "required","Titulo da Lição");
            $listar_iten_classe.=$form->Input("text", "total_biblia", "col-md-4", $listar_historico[0]["total_biblia"], "required","Biblias");
            $listar_iten_classe.=$form->Input("text", "total_visita", "col-md-4", $listar_historico[0]["total_visita"], "required","Visitas");
            $listar_iten_classe.=$form->Input("text", "total_revista", "col-md-4", $listar_historico[0]["total_revista"], "required","Liçoes");
            $listar_iten_classe.=$form->Input("text", "total_oferta", "col-md-4", $listar_historico[0]["total_oferta"], "required","Oferta");
                 
            $listar_iten_classe.=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Cliente/", $icone, $listar_itenclasse, "tabela2", array(array("acao"=>"/Pessoa/form/tipo/Cliente/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Colaborador/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);    
            $listar_aluno_classe=$form->Listar("col-md-12", null, "/Pessoa/form/tipo/Cliente/", $icone, $listar_itenclasse, "tabela_aluno", array(array("acao"=>"/Pessoa/form/tipo/Cliente/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Pessoa/visualizar/tabela/Colaborador/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")),$pesquisa);  
            
            
           
            
            
            $inputs.= $form->Abas($Tipo, "teste", "col-md-12", array(
                array("id"=>"Alunos","icone"=>"people","descricao"=>"Alunos"),
                array("id"=>"Chamadas","icone"=>"rule","descricao"=>"Chamadas")
              ),
              array(
                array("id"=>"Alunos","dados"=>"$listar_aluno_classe","classe"=>" active"),
                array("id"=>"Chamadas","dados"=>"$listar_iten_classe")
              ));
            $form->card("Classe Escola Dominical",$inputs,"col-md-12",$comando,"POST","people");
            
//        }else{

//            $this->view('error_permisao');

//        }

    } 



    

    public function teste(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

       // $comando='/ItenClasse/incluir/';

        $chamada = $_POST["chamada"];

        $licao = new LicaoModel();

        $descricao_licao=$_POST["licao"];

        $id_classe_licao=$_POST["id_classe"];

        $licao_existe=$licao->listar_Licao($join, $limit, "licao='{$descricao_licao}' AND id_classe='{$id_classe_licao}'", $offset, $orderby, "id", $group);

        if(empty($licao_existe)){

            $id_licao=$licao->cadastrar_Licao(array(

                "licao"=>$descricao_licao,

                "id_classe"=>$id_classe_licao,

                "id_status"=>"1",

                "data_lancamento"=>date("Y-m-d"),

                "total_biblia"=>$_POST["total_biblia"],

                "total_revista"=>$_POST["total_revista"],

                "total_visita"=>$_POST["total_visita"],

                "total_oferta"=>$_POST["total_oferta"],

            ));

         }else{

             $id_licao=$licao_existe[0]["id"];
              $licao->alterar_Licao(array(

                "licao"=>$descricao_licao,

                "id_classe"=>$id_classe_licao,

                "id_status"=>"1",

                "data_lancamento"=>date("Y-m-d"),

                "total_biblia"=>$_POST["total_biblia"],

                "total_revista"=>$_POST["total_revista"],

                "total_visita"=>$_POST["total_visita"],

                "total_oferta"=>$_POST["total_oferta"],

            ),"id=".$licao_existe[0]["id"]);

         }

        $historico_chamada = new HistoricoChamadaModel();

        foreach($chamada as $itens_chamada):
            $itens_chamada = explode(",",$itens_chamada) ;
            print_r($itens_chamada);
            if(empty($itens_chamada[2])){
                    $historico_chamada->cadastrar_HistoricoChamada(array(
                    "id_licao"=>$id_licao,
                    "id_pessoa"=>$itens_chamada[1],
                    "chamada"=>$itens_chamada[0],
                    "data_lancamento"=>date("Y-m-d"),
                ));     
            }else{
                $historico_chamada->alterar_HistoricoChamada(array(
                    "id_licao"=>$id_licao,
                    "id_pessoa"=>$itens_chamada[1],
                    "chamada"=>$itens_chamada[0],
                    "data_lancamento"=>date("Y-m-d"),
                ),"id_pessoa='$itens_chamada[1]' AND id_licao='$id_licao'");
            }
        endforeach;
        
        
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Relatorio/relatorio_geral_ebd/");    


    }



    public function form(){ 
        $this->acesso_restrito();
        $acesso = new AcessoHelper();    
        $comando='/'.__CLASS__.'/incluir/';
       // if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){
             $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();  
                
 $pessoa = new PessoaModel();

 $classe = new ClasseModel();

 $status = new StatusModel();

 $Iten_trimestre = new ItenTrimestreModel();

 $filial = new FilialModel();
 $itenclasse = new ItenClasseModel();
        $id = $this->getParams('id');
            $id_classe = $this->getParams('id_classe');
        $dados['id']=$id;
        $nome_form='Cadastra ItenClasse';

        if(!empty($id)){
            $itenclasse_dados=$itenclasse->listar_itenclasse($JOIN, '1', "id=$id", $offset, $orderby);
            $itenclasse_dados = $itenclasse_dados[0]; 
            $comando='/'.__CLASS__.'/alterar/';
            $nome_form='Alterar ItenClasse';
        } 
         $form = new FormularioHelper();
            $id_trimestre=$this->getParams("id_trimestre");
            $inputs.= $form->Input('hidden', 'id', $CSS, $id);
        //    $inputs.= $form->Input('hidden', 'id_iten_trimestre', $CSS, $id_trimestre);
            $inputs.= $form->select('Pessoa','id_pessoa','col-md-3',$pessoa->listar_Pessoa(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome',$itenclasse_dados['id_pessoa']);
            $inputs.= $form->select('Classe','id_classe','col-md-3',$classe->listar_Classe(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$id_classe);
            $inputs.= $form->select('Aluno/Professor','tipo','col-md-2',array(array("id"=>"Aluno"),array("id"=>"Professor")),'id',$itenclasse_dados['tipo']);
            //$inputs.= $form->Input("text", 'tipo', "col-md-3", $itenclasse_dados["tipo"], $Required, 'tipo', $disable);
            $inputs.= $form->select('Status','id_status','col-md-2',$status->listar_Status(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$itenclasse_dados['id_status']);
         // $inputs.= $form->select('ItenTrimestre','id_iten_trimestre','col-md-5',$Iten_trimestre->listar_ItenTrimestre("INNER JOIN Trimestre ON Trimestre.id =ItenTrimestre.id_trimestre",NULL,NULL,NULL,NULL),'descricao',$itenclasse_dados['id_iten_trimestre']);
            $inputs.= $form->select('Filial','id_filial','col-md-2',$filial->listar_Filial(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome_fantasia',$itenclasse_dados['id_filial']);
        //    $inputs.= $form->Input("text", 'data_lancamento', "col-md-3", $itenclasse_dados["data_lancamento"], $Required, 'data_lancamento', $disable);
            $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');
            $form->card(__CLASS__,$inputs,"col-md-12",$comando."id_trimestre/$id_trimestre/","POST","ballot");
       // }else{
            //$this->view('error_permisao');
        //}
    }
    
 public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/ItenClasse/incluir/';

            

       // if($acesso->acesso_valida($comando)==true){

$id_trimestre=$this->getParams("id_trimestre");

            $id_classe= $_POST['id_classe'];

            $itenclasse = new ItenClasseModel();      

            $id=$itenclasse->cadastrar_itenclasse( 

                array(

                    'id_pessoa'=>$_POST['id_pessoa'],

                    'id_classe'=>$id_classe,

                    'tipo'=>$_POST['tipo'],

                    'id_status'=>$_POST['id_status'],

                    'id_iten_trimestre'=>$id_trimestre,

                    'id_filial'=>$_POST['id_filial'],

                    'data_lancamento'=>date('Y-m-d H:i:s'),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl("/ItenClasse/admin_listar/id_classe/$id_classe/id_trimestre/$id_trimestre");    

//        }else{

//            $this->view('error_permisao');

//        }

    }

 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/ItenClasse/alterar/';

       // if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $id_trimestre=$this->getParams("id_trimestre");

            $id_classe= $_POST['id_classe'];


            $itenclasse = new ItenClasseModel();      

            $itenclasse->alterar_itenclasse(

                array(

                     'id_pessoa'=>$_POST['id_pessoa'],

                    'id_classe'=>$id_classe,

                    'tipo'=>$_POST['tipo'],

                    'id_status'=>$_POST['id_status'],

                    'id_iten_trimestre'=>$id_trimestre,

                    'id_filial'=>$_POST['id_filial'],



                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

           $redirect->goToUrl("/ItenClasse/admin_listar/id_classe/$id_classe/id_trimestre/$id_trimestre");    

//        }else{

  //          $this->view('error_permisao');
//
 //       }



    }

 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/ItenClasse/excluir/';

      //  if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $itenclasse = new ItenClasseModel();      

            $itenclasse->excluir_itenclasse( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/ItenClasse/admin_listar/');    

//        }else{

//            $this->view('error_permisao');

//        }

    } 

 } ?> 