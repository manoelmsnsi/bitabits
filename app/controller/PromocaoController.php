<?php class Promocao extends Controller {   

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

            $promocao = new PromocaoModel();

           

            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();            

            $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");     

            $inputs.= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$promocao->listar_Promocao("INNER JOIN Status ON Status.id=Promocao.id_status INNER JOIN Filial ON Filial.id=Promocao.id_filial",NULL,"Promocao.id_status<>99 AND ({$filiais})",NULL,' Promocao.id DESC',"Promocao.id,Filial.nome_fantasia AS Filial,Promocao.descricao AS Promoção,DATE_FORMAT(Promocao.data_inicio , '%d/%m/%Y') AS Inicio,DATE_FORMAT(Promocao.data_fim , '%d/%m/%Y') AS Fim,Status.cor AS cor_Status,Status.Descricao AS Status",null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","people");

        }else{

            $this->view('error_permisao');

        }    

    }

  

 public function form_item_promocao(){ 

    $this->acesso_restrito(); 

    $acesso = new AcessoHelper(); 

    $id_tabela=$this->getParams("id_tabela");

    $comando='/Itens/incluir/id_tabela/'.$id_tabela.'/tipo/promocao/';        

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    

            $logs = new LogsModel(); 

            $status= new StatusModel();

            $filial = new FilialModel();

            $produto = new ProdutoModel();             

            $id=$this->getParams("id");        

            $tabela=$this->getParams("tabela");     

            $nome_form="Cadastrar produto";              

            if(!empty($id)){

                $iten_dados =$produto->listar_Produto($join, "1", "id='$id'", $offset, $orderby);

                $iten_dados = $produto_dados[0]; 

                $comando="/".__CLASS__."/alterar/";

                $nome_form="Alterar Produtos";

            }             

            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio);      

            echo $menu->Menu();

            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");

            $inputs.= $form->Input("hidden", "id", $Classe, $id);

            $inputs.= $form->Input("hidden", "id_tabela", $Classe, $id_tabela);

            $inputs.= $form->Input("hidden", "tabela", $Classe, $tabela);

            $inputs.= $form->Input("hidden", "quantidade", $Classe, "0");

            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$iten_dados["id_filial"]);

            $inputs.= $form->Input("text", "valor_promocao", "col-md-4", $iten_dados["valor_promocao"], $Required, "Valor Promocional", $disable);

            $inputs.= $form->select("Produto","id_produto", "col-md-2",$produto->listar_Produto($join, $limit, "id_status<>'99'", $offset, $orderby, "descricao,id", $group, $pesquisa),"descricao",$iten_dados["valor_promocao"]);

            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");             

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","people");

        }else{

           $this->view('error_permisao');

        }   

    }



public function visualizar (){

    $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Promocao/visualizar/';         

       //if($acesso->acesso_valida($comando)==true){ 

            $id= $this->getParams("id");

            $dados["id"]=$id;

            $acesso = new SessionHelper();

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            echo $menu->Menu();             
 $filial = new FilialModel();
  $status= new StatusModel();
            $promocao = new PromocaoModel ();
            $dados['listar_filial'] = $filial->listar_filial(NULL,NULL," id_status<>99",NULL,'Filial.id DESC');
            $dados['listar_status'] = $status->listar_Status(NULL,NULL,"id_status<>99",NULL,'Status.id DESC');
            $listar_promocao = $promocao->listar_promocao(NULL,NULL,"id=$id AND id_status<>99",NULL,'Promocao.id DESC');

            $dados['listar_promocao'] = $listar_promocao;

            $itens = new ItensModel();

            $listar_itens = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>'99' AND id_tabela='{$id}' AND tabela='Promocao'",NULL,' Itens.id DESC',"Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Produto.valor_venda,Itens.valor_promocao");

            $dados['listar_itens'] = $listar_itens;                

            $this->view('form_visualizar_promocao',$dados); 

    }

    public function form(){ 

        $this->acesso_restrito();

        $acesso = new AcessoHelper();         

        $comando="/".__CLASS__."/incluir/";  

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    

            $logs = new LogsModel(); 

            $acesso = new SessionHelper();

            $status= new StatusModel();

            $promocao= new PromocaoModel();

            $filial = new FilialModel();

            $id=$this->getParams("id");     

 

            $listar_acesso=$acesso->selectSession('userAcesso');

            $user_dados=$acesso->selectSession('userData');

            $dados['listar_acesso']=$listar_acesso;

            $dados['user_dados']=$user_dados;

            $nome_form="Cadastra Promoção";

            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        

            

            if(!empty($id)){

                $promocao_dados=$promocao->listar_Promocao($join, "1", "id='$id'", $offset, $orderby);

                $promocao_dados= $promocao_dados[0]; 

                $comando="/".__CLASS__."/alterar/";

                $nome_form="Alterar Promoção";

            }            

            echo $menu->Menu();  

            $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");

            $inputs.= $form->Input("hidden", "id", null, $id, $Required, null, $disable);

            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' id ASC',NULL),"nome_fantasia");

            $inputs.= $form->Input("text", "descricao", "col-md-4", $promocao_dados["descricao"], $Required, "Descrição", $disable);           

            $inputs.= $form->Input("date", "data_inicio", "col-md-2", $promocao_dados["data_inicio"], $Required, "Data Inicial", $disable);           

            $inputs.= $form->Input("date", "data_fim", "col-md-2", $promocao_dados["comisdata_fimsao"], $Required, "Data Final", $disable);      

            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");           

            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar"); 

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","people");

        }else{

           $this->view('error_permisao');

        }   

    }



 public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Promocao/incluir/';           

        if($acesso->acesso_valida($comando)==true){

            $promocao = new PromocaoModel();      

            $id=$promocao->cadastrar_promocao( 

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'descricao'=>$_POST['descricao'],

                    'data_inicio'=>$_POST['data_inicio'],

                    'data_fim'=>$_POST['data_fim'],

                    'id_status'=>$_POST['id_status'],

                    'data_lancamento'=>  date("Y-m-d H:i:s"),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Promocao/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }



 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Promocao/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

            $promocao = new PromocaoModel();      

            $promocao->alterar_promocao(

                array(

                        'id_filial'=>$_POST['id_filial'],

                        'descricao'=>$_POST['descricao'],

                        'data_inicio'=>$_POST['data_inicio'],

                        'data_fim'=>$_POST['data_fim'],

                        'id_status'=>$_POST['id_status'],

                        'data_lancamento'=>  date("Y-m-d H:i:s"),

                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Promocao/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }



 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Promocao/excluir/';

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $promocao = new PromocaoModel();      

            $promocao->excluir_promocao( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Promocao/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

 } ?> 