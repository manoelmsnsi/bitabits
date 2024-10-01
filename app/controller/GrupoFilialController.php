<?php class GrupoFilial extends Controller {   

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

             $grupo = new GrupoModel();

         

           if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper();     

            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$grupo->listar_Grupo("INNER JOIN Filial ON Filial.id=Grupo.id_filial INNER JOIN Status ON Status.id=Grupo.id_status ",NULL,"Grupo.id_status='1' AND Grupo.tabela='Filial' ",NULL,' Grupo.id DESC',"Grupo.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Descrição,Status.cor AS cor_Status,Status.descricao AS Status,Grupo.data_lancamento AS 'Data Lançamento'",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-warning","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","location_city");

        }else{

            $this->view('error_permisao');

        }    

    }

//    public function admin_listaar(){
//
//        $this->acesso_restrito();
//
//        $acesso = new AcessoHelper();
//
//        $logs = new LogsModel();
//
//        $comando="/".__CLASS__."/incluir/";         
//
//        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){ 
//
//            $filiais=$acesso->acesso_filial(__CLASS__);
//
//            $status= new StatusModel();
//
//            $filial = new FilialModel();           
//
//            $acesso = new SessionHelper();           
//
//            $grupo_filial = new GrupoFilialModel();
//
//            
//            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
//            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
//
//            echo $menu->Menu();            
//
//            $form = new FormularioHelper();     
//
//            $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,
//                    $grupo_filial->listar_GrupoFilial("INNER JOIN Grupo ON Grupo.id=GrupoFilial.id_grupo INNER JOIN Filial ON Filial.id=GrupoFilial.id_filial",NULL,"GrupoFilial.id_status<>99 AND {$filiais}",NULL,' GrupoFilial.id DESC',"GrupoFilial.id, Grupo.descricao AS Grupo,Filial.nome_fantasia AS Filial",NULL,$pesquisa)
//                            , "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));
//            $form->card("Grupos de Filiais",$inputs,"col-md-12",$comando,"POST","ballot");
//        }else{
//
//            $this->view('error_permisao');
//
//        }    
//
//    }
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

                $acessos_grupo=$grupo->listar_Grupo("INNER JOIN GrupoFilial ON GrupoFilial.id_grupo=Grupo.id 

                                                     INNER JOIN Filial ON Filial.id = GrupoFilial.id_filial", null, "GrupoFilial.id_grupo='$id' AND GrupoFilial.id_status<>'99'", $offset, $orderby, "GrupoFilial.id,Filial.nome_fantasia AS 'Nome Fantasia'", $group, $pesquisa);

                $usuarios_grupo=$usuario->listar_usuario("  INNER JOIN VinculaFilial ON VinculaFilial.id_usuario=Usuario.id
                                                            INNER JOIN GrupoFilial ON GrupoFilial.id_grupo=VinculaFilial.id_grupo 
                                                    ", null, "GrupoFilial.id_grupo='$id'AND VinculaFilial.id_grupo='$id' AND VinculaFilial.id_status<>'99'", $offset, $orderby, "Usuario.usuario, VinculaFilial.id", "Usuario.id", $pesquisa);                 

                $comando='/'.__CLASS__.'/alterar/';

                $nome_form='Alterar GrupoAcesso';

            } 

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }



            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

           echo $menu->Menu();            

            $form = new FormularioHelper("Grupo","col-md-12" ,null,null,"people");             

            $acessos_grupo= $form->Listar("col-md-12", null, "/".__CLASS__."/form/id_grupo/$id/", $icone,$acessos_grupo, "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $usuarios_grupo = $form->Listar("col-md-12", null, "/VinculaFilial/form/id_grupo/$id/", $icone,$usuarios_grupo, "tabela2", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form_alterar/classe/VinculaFilial/id_tabela/$id/tabela/VinculaFilial/acao/excluir/","classe"=>"btn-sm btn-danger","icone"=>"close")) );    

                       $inputs= $form->Abas($Tipo, "Caixa", "col-md-12", 

                array(array("id" => "Filiais", "icone" => "apartment", "descricao" => "Filiais"), 

                array("id" => "Usuarios", "icone" => "people", "descricao" => "Usuarios")),

                array(array("id" => "Filiais", "dados" => "$acessos_grupo", "classe" => " active"),

                array("id" => "Usuarios", "dados" => "$usuarios_grupo")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","location_city");                       

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

 $filial = new FilialModel();

 $status = new StatusModel();
 $grupofilial = new GrupoFilialModel();
        $id = $this->getParams('id');
        $dados['id']=$id;
        $nome_form='Cadastra GrupoFilial';

        if(!empty($id)){
            $grupofilial_dados=$grupofilial->listar_grupofilial($JOIN, '1', "id=$id", $offset, $orderby);
            $grupofilial_dados = $grupofilial_dados[0]; 
            $comando='/'.__CLASS__.'/alterar/';
            $nome_form='Alterar GrupoFilial';
        } 
         $form = new FormularioHelper($nome_form,'col-md-12' ,$comando,'POST','people');
            $inputs.= $form->Input('hidden', 'id', $CSS, $id);
            $inputs.= $form->select('Filial','id_filial','col-md-3',$filial->listar_Filial(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome_fantasia',$grupofilial_dados['id_filial']);
            $inputs.= $form->select('Grupo','id_grupo','col-md-3',$grupo->listar_Grupo(NULL,NULL,"id_status<>99 AND tabela='Filial'",NULL,NULL,NULL),'descricao',$grupofilial_dados['id_grupo']);         
            $inputs.= $form->select('Status','id_status','col-md-3',$status->listar_Status(NULL,NULL,"id_status<>'99' ",NULL,NULL,NULL),'descricao',$grupofilial_dados['id_status']);
            
            $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');;
            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","location_city");
        }else{
            $this->view('error_permisao');
        }
    }


 public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/GrupoFilial/incluir/';

            

     //   if($acesso->acesso_valida($comando)==true){



            $grupofilial = new GrupoFilialModel();      

            $id=$grupofilial->cadastrar_grupofilial( 

                array(

                    'id_grupo'=>$_POST['id_grupo'],

                    'id_filial'=>$_POST['id_filial'],                    

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoFilial/admin_listar/');    

      //  }else{

       //     $this->view('error_permisao');

      //  }

    }

 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/GrupoFilial/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];



            $grupofilial = new GrupoFilialModel();      

            $grupofilial->alterar_grupofilial(

                array(

                    'id_usuario'=>$_POST['id_usuario'],

                    'id_filial'=>$_POST['id_filial'],                    

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoFilial/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }



    }

 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/GrupoFilial/excluir/';

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $grupofilial = new GrupoFilialModel();      

            $grupofilial->excluir_grupofilial( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/GrupoFilial/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }     

}

?> 