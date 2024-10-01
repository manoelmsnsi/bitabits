<?php
class Composicao extends Controller {
    private $auth, $db;

    public function acesso_restrito() {
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index', '')
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
            $composicao = new ComposicaoModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits - Composição", $Class, $AcaoForm, $MetodoDeEnvio);        
                echo $menu->Menu();            

            $form = new FormularioHelper();     
                $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$composicao->listar_Composicao(
                    "INNER JOIN Filial ON Filial.id=Composicao.id_filial
                        INNER JOIN Status ON Status.id=Composicao.id_status
                        INNER JOIN Produto ON Produto.id=Composicao.id_produto",
                    NULL,
                    "Composicao.id_status<>99 AND ({$filiais})",
                    NULL,
                    'Composicao.id DESC',
                    "Composicao.id,
                        Filial.nome_fantasia AS Filial,
                        Status.cor AS cor_Status,
                        Composicao.descricao AS Descrição,
                        Produto.descricao AS Produto,
                        Status.descricao AS Status
                        ",NULL,$pesquisa
                    ),"tabela1",
                    array(
                        array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),
                        array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),
                        array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")
                    )
                );
                $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","biotech");
        }else{
            $this->view('error_permisao');
        }    
    }
    public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando="/".__CLASS__."/incluir/";         

        if($acesso->acesso_valida("/Composicao/admin_listar/")==true){
            $status= new StatusModel();
            $filial = new FilialModel();
            $produto = new ProdutoModel();
            $acesso = new SessionHelper();
            $menu = new MenuHelper("Bit a Bits - Cadastro de Composição", $Class, $AcaoForm, $MetodoDeEnvio);        
                echo $menu->Menu();

            $id=$this->getParams("id");     

            if(!empty($id)){
                $composicao_dados = $composicao->listar_Produto($join, "1", "id=$id", $offset, $orderby);
                $composicao_dados = $composicao_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
            }            
            $form = new FormularioHelper("Cadastrar Composição","col-md-12" ,$comando,"POST","widgets","false");
                $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
                $inputs.= $form->select("Filial","id_filial","col-md-2",$filial->listar_Filial(NULL,NULL,"id_status <> 99",NULL,'Filial.id DESC',NULL),"nome_fantasia",$composicao_dados["id_filial"]);
                $inputs.= $form->select("Produto","id_produto","col-md-4",$produto->listar_Produto(NULL,NULL,"id_status<>99",NULL,'Produto.id DESC',NULL),"descricao",$composicao_dados["id_produto"]);
                $inputs.= $form->Input("text","descricao","col-md-4",$composicao_dados["descricao"],"required","Descrição");
                $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"Status.id_status<>99 AND tabela='Geral'",NULL,'Status.id ASC',NULL),"descricao",$composicao_dados["id_status"]);
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
                $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","biotech");
            }else{
                $this->view('error_permisao');
            }
    }

    public function incluir() {
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Composicao/incluir/';

        if ($acesso->acesso_valida($comando) == true) {
            $composicao = new ComposicaoModel();
            $id = $composicao->cadastrar_composicao(
                array(
                    'id_filial' => $_POST['id_filial'],
                    'id_produto' => $_POST['id_produto'],
                    'descricao' => $_POST['descricao'],
                    'id_status' => $_POST['id_status'],
                    'data_lancamento' => date("Y-m-d H:i:s"),
                )
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Composicao/admin_listar/');
        }else{
            $this->view('error_permisao');
        }
    }

    public function alterar() {
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Composicao/alterar/';

        if ($acesso->acesso_valida($comando) == true) { 
            $id = $_POST['id'];
            $composicao = new ComposicaoModel();
            $composicao->alterar_composicao(
                array(
                    'id_filial' => $_POST['id_filial'],
                    'id_produto' => $_POST['id_produto'],
                    'descricao' => $_POST['descricao'],
                    'id_status' => $_POST['id_status'],
                    'data_lancamento' => date("Y-m-d H:i:s")
                ),'id='.$id
            );
            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Composicao/admin_listar/');    
        }else{
            $this->view('error_permisao');
        }
    }

    public function excluir() {
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Composicao/excluir/';

        if ($acesso->acesso_valida($comando) == true) {
            $id = $this->getParams('id');
            $composicao = new ComposicaoModel();
            $composicao->excluir_composicao(array('id_status' => '99'), 'id=' . $id);
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Composicao/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
}
?>