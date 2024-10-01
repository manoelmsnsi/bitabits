<?php class Produto extends Controller {   



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

            $status= new StatusModel();

            $filial = new FilialModel();           

            $acesso = new SessionHelper();

            $produto = new ProdutoModel();

         

           if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }

           $menu = new MenuHelper(null,null,null,null);        

            echo $menu->Menu();

            $id=$this->getParams("id");   

            $form = new FormularioHelper();     

            $inputs= $form->Listar("col-md-12", null, "/Produto/form/", $icone, $produto->listar_Produto("

                INNER JOIN Pessoa ON Pessoa.id = Produto.id_fornecedor

                INNER JOIN Filial ON Filial.id = Produto.id_filial

                INNER JOIN Modelo ON Modelo.id = Produto.id_modelo

                INNER JOIN Marca ON Marca.id = Produto.id_marca

                INNER JOIN Grupo ON Grupo.id = Produto.id_grupo ","25","Produto.id_status<>'99' AND Pessoa.tipo='Fornecedor'",NULL,"Produto.id DESC",

                "Produto.id,Filial.nome_fantasia AS Filial,Grupo.descricao AS Grupo,Produto.descricao AS Descricao,Produto.valor_venda AS Venda",null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));

            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","add_business");                  

           }else{

            $this->view('error_permisao');

        }    

    } 



    public function form(){ 



        $this->acesso_restrito();

        $acesso = new AcessoHelper();         

        $comando="/".__CLASS__."/incluir/";  

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    

            $logs = new LogsModel(); 

            $status= new StatusModel();

            $filial = new FilialModel();            

            $pessoa = new PessoaModel();

            $marca = new MarcaModel();

            $modelo = new ModeloModel();

            $grupo = new GrupoModel();

            $produto = new ProdutoModel();         

            $acesso = new SessionHelper();            



            $id=$this->getParams("id");     

            $nome_form="Cadastrar produto";             



            if(!empty($id)){

                $produto_dados =$produto->listar_Produto($join, "1", "id='$id'", $offset, $orderby);

                $produto_dados = $produto_dados[0]; 

                $comando="/".__CLASS__."/alterar/";

                $nome_form="Alterar Produtos";

            }             



            $menu = new MenuHelper("Bit", $Class, $AcaoForm, $MetodoDeEnvio);      

            echo $menu->Menu();

            $form = new FormularioHelper();

            $inputs.= $form->Input("hidden", "id", $Classe, $id);

            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$produto_dados["filial"]);

            $inputs.= $form->select("Fornecedor","id_fornecedor", "col-md-2",$pessoa->listar_Pessoa("INNER JOIN Status ON Status.id=Pessoa.id_status",NULL,"Pessoa.id_status<>99 AND Pessoa.tipo='Fornecedor'",NULL,' Pessoa.id DESC',"Pessoa.tipo,Pessoa.id_filial,Pessoa.nome,Pessoa.cpf,Status.descricao AS descricao_status,Pessoa.data_nascimento,Pessoa.data_lancamento,Pessoa.id"),"nome",$produto_dados["id_fornecedor"]);

            $inputs.= $form->select("Modelo","id_modelo", "col-md-2",$modelo->listar_Modelo(NULL,NULL,"id_status<>99",NULL,' Modelo.id DESC',NULL),"descricao",$produto_dados["id_modelo"]);

            $inputs.= $form->select("Marca","id_marca", "col-md-2",$marca->listar_Marca(NULL,NULL,"id_status<>99",NULL,' Marca.id DESC',NULL),"descricao",$produto_dados["id_marca"]);

            $inputs.= $form->select("Tipo","tipo", "col-md-2",array(array("id"=>"Produto"),array("id"=>"Servico")),"id",$produto_dados["tipo"]);

            $inputs.= $form->select("Grupo","id_grupo", "col-md-2",$grupo->listar_Grupo(NULL,NULL,"id_status<>99 AND tabela='Produto'",NULL,' Grupo.id DESC',NULL),"descricao",$produto_dados["id_grupo"]);

            $inputs.= $form->select("Unidade Cormecial","un_comercial", "col-md-2",array(array("id"=>"UN"),array("id"=>"CX"),array("id"=>"LT"),array("id"=>"ML"),array("id"=>"KG")),"id",$produto_dados["un_comercial"]);

            $inputs.= $form->Input("text", "descricao", "col-md-8", $produto_dados["descricao"], $Required, "Descricao", $disable);

            $inputs.= $form->Input("text", "quantidade", "col-md-2", $produto_dados["quantidade"], $Required, "Quantidade", $disable);

            $inputs.= $form->Input("text", "quantidade_max", "col-md-2", $produto_dados["quantidade_max"], $Required, "Qtd Maxima", $disable);

            $inputs.= $form->Input("text", "quantidade_min", "col-md-3", $produto_dados["quantidade_min"], $Required, "Qtd Minima", $disable);

            $inputs.= $form->Input("text", "valor_venda", "col-md-3", $produto_dados["valor_venda"], $Required, "Valor de Venda", $disable);

            $inputs.= $form->Input("text", "valor_desconto", "col-md-4", $produto_dados["valor_desconto"], $Required, "Valor de Desconto", $disable);

            $inputs.= $form->Input("text", "valor_compra", "col-md-4", $produto_dados["valor_compra"], $Required, "Valor de Compra", $disable);

            $inputs.= $form->Input("text", "valor_custo", "col-md-4", $produto_dados["valor_custo"], $Required, "Valor de Custo", $disable);

            $inputs.= $form->Input("text", "localidade", "col-md-4", $produto_dados["localidade"], $Required, "Localidade", $disable);

            $inputs.= $form->Input("text", "codigo_barra", "col-md-8", $produto_dados["codigo_barra"], $Required, "Codigo de Barras", $disable);

            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");

            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");

             $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","add_business"); 



        }else{

           $this->view('error_permisao');

        }   

    }



    public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Produto/incluir/';

        if($acesso->acesso_valida($comando)==true){

            $produto = new ProdutoModel();      

            $id=$produto->cadastrar_produto( 

                array(

                    'id_filial'=>$_POST['id_filial'],

                    'id_fornecedor'=>$_POST['id_fornecedor'],

                    'id_modelo'=>$_POST['id_modelo'],

                    'id_marca'=>$_POST['id_marca'],

                    'id_grupo'=>$_POST['id_grupo'],

                    'descricao'=>$_POST['descricao'],

                    'quantidade'=>$_POST['quantidade'],

                    'tipo'=>$_POST['tipo'],

                    'quantidade_max'=>$_POST['quantidade_max'],

                    'quantidade_min'=>$_POST['quantidade_min'],

                    'valor_venda'=>$_POST['valor_venda'],

                    'valor_desconto'=>$_POST['valor_desconto'],

                    'un_comercial'=>$_POST['un_comercial'],

                    'valor_compra'=>$_POST['valor_compra'],

                    'valor_custo'=>$_POST['valor_custo'],

                    'localidade'=>$_POST['localidade'],

                    'codigo_barra'=>$_POST['codigo_barra'],

                    'id_status'=>$_POST['id_status'],

                   'data_lancamento'=>  date("Y-m-d H:i:s"),



                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Produto/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    }

 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Produto/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];

           $produto = new ProdutoModel();  

           $produto->alterar_Produto(

                   array(

                   'id_filial'=>$_POST['id_filial'],

                   'id_fornecedor'=>$_POST['id_fornecedor'],

                   'id_modelo'=>$_POST['id_modelo'],

                   'id_marca'=>$_POST['id_marca'],

                   'id_grupo'=>$_POST['id_grupo'],

                   'descricao'=>$_POST['descricao'],

                    'quantidade'=>$_POST['quantidade'],

                    'quantidade_max'=>$_POST['quantidade_max'],

                    'quantidade_min'=>$_POST['quantidade_min'],

                    'valor_venda'=>$_POST['valor_venda'],

                    'un_comercial'=>$_POST['un_comercial'],

                    'valor_desconto'=>$_POST['valor_desconto'],

                    'valor_compra'=>$_POST['valor_compra'],

                    'valor_custo'=>$_POST['valor_custo'],

                    'tipo'=>$_POST['tipo'],

                    'localidade'=>$_POST['localidade'],

                    'codigo_barra'=>$_POST['codigo_barra'],

                    'id_status'=>$_POST['id_status']

                ),"id=$id");  



            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Produto/admin_listar/');    



        }else{



            $this->view('error_permisao');



        }







    }



 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Produto/excluir/';

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $produto = new ProdutoModel();      

            $produto->excluir_produto( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Produto/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 

} ?> 