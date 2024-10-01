<?php class GrupoAcesso extends Controller {   
    private  $auth,$db;

    public function acesso_restrito(){          

        $this->auth = new AutenticaHelper();

        $this->auth->setLoginControllerAction('Index','')

                  ->checkLogin('redirect');              

       $this->db = new AdminModel(); 

   } 



    public function admin_listar_antigo(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 

           $filiais=$acesso->acesso_filial(__CLASS__);

            $status= new StatusModel();

            $filial = new FilialModel();           

            $acesso = new SessionHelper();  

            $grupo_acesso = new GrupoAcessoModel();

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);  

            echo $menu->Menu();            

            $form = new FormularioHelper();  

           $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$grupo_acesso->listar_GrupoAcesso("INNER JOIN Programa ON Programa.id=GrupoAcesso.id_programa INNER JOIN Status ON Status.id = Programa.id_status INNER JOIN Grupo ON Grupo.id=GrupoAcesso.id_grupo ","25","Programa.id_status<>'99'",NULL,' GrupoAcesso.id ASC',"GrupoAcesso.id,Grupo.descricao AS Grupo,Programa.comando AS Comando,Status.cor AS cor_Status,Status.descricao AS Status",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

           $form->card("Grupos de Acessos",$inputs,"col-md-12",$comando,"POST","ballot");

        }else{

           $this->view('error_permisao');

        }    

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

             $grupo = new GrupoModel();

         

           if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper();     

            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$grupo->listar_Grupo("INNER JOIN Filial ON Filial.id=Grupo.id_filial INNER JOIN Status ON Status.id=Grupo.id_status ",NULL,"Grupo.id_status='1' AND Grupo.tabela='Acesso' ",NULL,' Grupo.id DESC',"Grupo.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Descrição,Status.cor AS cor_Status,Status.descricao AS Status,Grupo.data_lancamento AS 'Data Lançamento'",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");

        }else{

            $this->view('error_permisao');

        }    

    }



    



    public function visualizar2(){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         
        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
            $filiais=$acesso->acesso_filial(__CLASS__);
            $status= new StatusModel();
            $filial = new FilialModel();           
            $acesso = new SessionHelper();        
            $grupo = new GrupoModel();
            $programa = new ProgramaModel();
            $grupoacesso = new GrupoAcessoModel();
            $usuario = new UsuarioModel();           
            $id = $this->getParams('id');
            if(!empty($id)){
                $grupo_dados=$grupo->listar_Grupo($join, "1", "id='$id'", $offset, $orderby, $from, $group, $pesquisa);
//                $acessos_grupo=$grupo->listar_Grupo("INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Grupo.id 
//
//                                                     INNER JOIN Programa ON Programa.id = GrupoAcesso.id_programa", null, "GrupoAcesso.id_grupo='$id' AND GrupoAcesso.id_status<>'99'", $offset, $orderby, "GrupoAcesso.id,Programa.descricao AS 'Programa',Programa.id AS id_programa ", $group, $pesquisa);
                $acessos_grupo=$grupoacesso->listar_GrupoAcesso("INNER JOIN Programa ON Programa.id = GrupoAcesso.id_programa", null, "GrupoAcesso.id_grupo='$id' AND GrupoAcesso.id_status<>'99'", $offset, $orderby, "GrupoAcesso.id,Programa.descricao AS 'Programa',Programa.id AS id_programa ", $group, $pesquisa);
//
//                $usuarios_grupo=$usuario->listar_usuario("  INNER JOIN Acesso ON Acesso.id_usuario=Usuario.id
//
//                                                            INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Acesso.id_grupo 
//
//                                                    ", null, "Acesso.id_status<>'99' AND GrupoAcesso.id_status<>'99' AND GrupoAcesso.id_grupo='$id' AND Usuario.id_status<>'99'", $offset, $orderby, "Acesso.id,Usuario.usuario", "Usuario.id", $pesquisa);                 
                $comando="/GrupoAcesso/visualizar2/id/$id/";
                $nome_form='Alterar GrupoAcesso';
            } 
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $listar_programa = $programa->listar_programa($join, $limit, "id_status<>'99'", $offset, $orderby, $from, $group, $pesquisa);
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();            
            $form = new FormularioHelper("Grupo","col-md-12" ,null,null,"people");       
            $post = $_POST["acessos"];
            
            if(!empty($post)){                
                foreach($post AS $progran){
                    $grupoacesso->cadastrar_grupoacesso( 
                        array(
                            'id_grupo'=>$id,
                            'id_programa'=>$progran,
                            'id_status'=>'1',
                        )
                    );
                }                
            }             
           
         //   foreach ($acessos_grupo AS $acesso_grupo):
                foreach ($listar_programa AS $programas):
            //        if($acesso_grupo["id_programa"]==$programas["id"]){
                        $inputs.=  $form->Check("1", "acessos[]", "col-md-6",$programas["id"] , $Required,$programas["descricao"], $disable, $id);       
          //          }
                endforeach;             
       //     endforeach;
               $inputs.=  $form->Button("btn btn-md btn-rose", "Salvar");
        
//            $acessos_grupo.= $form->Listar("col-md-12", null, "/".__CLASS__."/form/id_grupo/$id/", $icone,$acessos_grupo, "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
//              
//            $usuarios_grupo = $form->Listar("col-md-12", null, "/Acesso/form/id_grupo/$id/", $icone,$usuarios_grupo, "tabela2", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/tabela/GrupoAcesso/id_tabela/$id/classe/Acesso/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")) );    
//
//                       $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 
//
//                array(array("id" => "Programas", "icone" => "layers", "descricao" => "Programas"), 
//
//                array("id" => "Usuarios", "icone" => "people", "descricao" => "Usuarios")),
//
//                array(array("id" => "Programas", "dados" => "$acessos_grupo", "classe" => " active"),
//
//                array("id" => "Usuarios", "dados" => "$usuarios_grupo")));

            $form->card("Grupo de Acesso::".$grupo_dados[0][descricao],$inputs,"col-md-12",$comando,"POST","ballot");                       

        }else{

            $this->view('error_permisao');

        }    

    }
    public function visualizar(){

        $this->acesso_restrito();

        $acesso = new AcessoHelper();

        $logs = new LogsModel();

        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 

            $filiais=$acesso->acesso_filial(__CLASS__);

            $status= new StatusModel();

            $filial = new FilialModel();           

            $acesso = new SessionHelper();        

            $grupo = new GrupoModel();

            $usuario = new UsuarioModel();           

           $id = $this->getParams('id');

            if(!empty($id)){

                $grupo_dados=$grupo->listar_Grupo($join, "1", "id='$id'", $offset, $orderby, $from, $group, $pesquisa);

                $acessos_grupo=$grupo->listar_Grupo("INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Grupo.id 

                                                     INNER JOIN Programa ON Programa.id = GrupoAcesso.id_programa", null, "GrupoAcesso.id_grupo='$id' AND GrupoAcesso.id_status<>'99'", $offset, $orderby, "GrupoAcesso.id,Programa.descricao AS 'Programa'", $group, $pesquisa);

                $usuarios_grupo=$usuario->listar_usuario("  INNER JOIN Acesso ON Acesso.id_usuario=Usuario.id

                                                            INNER JOIN GrupoAcesso ON GrupoAcesso.id_grupo=Acesso.id_grupo 

                                                    ", null, "Acesso.id_status<>'99' AND GrupoAcesso.id_status<>'99' AND GrupoAcesso.id_grupo='$id' AND Usuario.id_status<>'99'", $offset, $orderby, "Acesso.id,Usuario.usuario", "Usuario.id", $pesquisa);                 

                $comando="/GrupoAcesso/form/id/$id/";

                $nome_form='Alterar GrupoAcesso';

            } 

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }



            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

           echo $menu->Menu();            

            $form = new FormularioHelper("Grupo","col-md-12" ,null,null,"people");             
         
            $acessos_grupo= $form->Listar("col-md-12", null, "/".__CLASS__."/form/id_grupo/$id/", $icone,$acessos_grupo, "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
              
            $usuarios_grupo = $form->Listar("col-md-12", null, "/Acesso/form/id_grupo/$id/", $icone,$usuarios_grupo, "tabela2", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/tabela/GrupoAcesso/id_tabela/$id/classe/Acesso/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")) );    

                       $inputs.= $form->Abas($Tipo, "Caixa", "col-md-12", 

                array(array("id" => "Programas", "icone" => "layers", "descricao" => "Programas"), 

                array("id" => "Usuarios", "icone" => "people", "descricao" => "Usuarios")),

                array(array("id" => "Programas", "dados" => "$acessos_grupo", "classe" => " active"),

                array("id" => "Usuarios", "dados" => "$usuarios_grupo")));

            $form->card("Grupo de Acesso::".$grupo_dados[0][descricao],$inputs,"col-md-12",$comando,"POST","ballot");                       

        }else{

            $this->view('error_permisao');

        }    

    }



     public function form(){ 

        $this->acesso_restrito();

        $acesso = new AcessoHelper();    

        $comando='/'.__CLASS__.'/incluir/';

        if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){

             $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();                  

       $grupo = new GrupoModel();

        $programa = new ProgramaModel();

        $status = new StatusModel();

        $grupoacesso = new GrupoAcessoModel();

        $id = $this->getParams('id');

        $id_grupo = $this->getParams('id_grupo');

        $dados['id']=$id;

        $nome_form='Cadastra GrupoAcesso';

        if(!empty($id)){

            $grupoacesso_dados=$grupoacesso->listar_grupoacesso($JOIN, '1', "id=$id", $offset, $orderby);

            $grupoacesso_dados = $grupoacesso_dados[0]; 

            $comando='/'.__CLASS__.'/alterar/';

            $nome_form='Alterar Grupo de Acesso';

        } 

         $form = new FormularioHelper();

            $inputs.= $form->Input('hidden', 'id', $CSS, $id);

            $inputs.= $form->select('Grupo','id_grupo','col-md-3',$grupo->listar_Grupo(NULL,NULL,"id_status<>'99' AND tabela='Acesso'",NULL,NULL,NULL),'descricao',$id_grupo);

            $inputs.= $form->select('Programa','id_programa','col-md-3',$programa->listar_Programa(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$grupoacesso_dados['id_programa']);

            $inputs.= $form->select('Status','id_status','col-md-3',$status->listar_Status(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$grupoacesso_dados['id_status']);

            $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');;

            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","ballot");  

        }else{

            $this->view('error_permisao');

        }

     }

    public function incluir(){    

        $this->acesso_restrito();

       $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/GrupoAcesso/incluir/';            

       if($acesso->acesso_valida($comando)==true){

           $grupoacesso = new GrupoAcessoModel();      

            $id=$grupoacesso->cadastrar_grupoacesso( 

                array(

                   'id_grupo'=>$_POST['id_grupo'],

                   'id_programa'=>$_POST['id_programa'],

                    'id_status'=>$_POST['id_status'],

                )

           );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoAcesso/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }

   public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/GrupoAcesso/alterar/';

        if($acesso->acesso_valida($comando)==true){

          $id = $_POST['id'];

            $grupoacesso = new GrupoAcessoModel();      

            $grupoacesso->alterar_grupoacesso(

             array(

                    'id_grupo'=>$_POST['id_grupo'],

                    'id_programa'=>$_POST['id_programa'],

                    'id_status'=>$_POST['id_status'],

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoAcesso/admin_listar/');    

        }else{

           $this->view('error_permisao');

        }

    }



  public function excluir(){    

       $this->acesso_restrito();

       $acesso = new AcessoHelper(); 

       $logs = new LogsModel();

       $comando='/GrupoAcesso/excluir/';

      if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

         $grupoacesso = new GrupoAcessoModel();      

          $grupoacesso->excluir_GrupoAcesso( array( 'id_status'=>'99' ),"id=$id" );  

          $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoAcesso/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

 } ?> 