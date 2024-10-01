<?php
class Classe extends Controller{
    private $auth, $db;

    public function acesso_restrito(){
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index', '')
                   ->checkLogin('redirect');
        $this->db = new AdminModel();
    }

    public function admin_listar(){
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = "/" . __CLASS__ . "/incluir/";

        if ($acesso->acesso_valida("/" . __CLASS__ . "/admin_listar/") == true) {
            $filiais = $acesso->acesso_filial(__CLASS__);
            $status = new StatusModel();
            $filial = new FilialModel();
            $acesso = new SessionHelper();
            $classe = new ClasseModel();
            if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }
            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);
            echo $menu->Menu();

                $form = new FormularioHelper(__CLASS__, "col-md-12", null, null, "people");
                $inputs.= $form->Listar(
                    "col-md-12", null, "/" . __CLASS__ . "/form/", $icone, $classe->listar_Classe(
                        "INNER JOIN Status ON Status.id = Classe.id_status
                            INNER JOIN Trimestre ON Trimestre.id = Classe.id_trimestre
                            INNER JOIN Filial ON Filial.id = Classe.id_filial",
                        null,
                        'Classe.id_status<>99 AND ' . $filiais,
                        null,
                        'Classe.id DESC',
                        "Filial.nome_fantasia AS 'Filial|Setor',Classe.id AS id_classe,
                            Classe.descricao AS Nome,
                            Classe.idade_minima AS 'Idade Minima',
                            Classe.idade_maxima AS 'Idade Maxima',
                            Status.cor AS cor_Status,
                            Status.Descricao AS Status,
                            Trimestre.id as id_trimestre,
                            Trimestre.descricao AS Timestre"
                    ),"tabela1",
                    array(
                        array("acao" => "/ItenClasse/admin_listar/", "classe" => "btn-sm btn-rose", "icone" => "rule"),
                        array("acao" => "/" . __CLASS__ . "/form/", "classe" => "btn-sm btn-warning", "icone" => "edit"),
                        array("acao" => "/Logs/form/", "classe" => "btn-sm btn-danger", "icone" => "close")));
                $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","list");
        } else {
            $this->view('error_permisao');
        }
    }

    public function form (){
        $this->acesso_restrito();
        $acesso = new AcessoHelper();         
        $comando="/".__CLASS__."/incluir/";  

        if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){    
            $logs = new LogsModel(); 
            $status= new StatusModel();
            $filial = new FilialModel();
            $trimestre = new TrimestreModel();
            $pessoa = new PessoaModel();

            $id=$this->getParams("id_classe");  
            $nome_form="Cadastrar Classe";  
            if(!empty($id)){
                $classe= new ClasseModel();
                $classe_dados = $classe->listar_Classe($join, "1", "id='$id'", $offset, $orderby);
                $classe_dados = $classe_dados[0]; 
                $comando="/".__CLASS__."/alterar/";
                $nome_form="Alterar Classe";
            }             

            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();

            $form = new FormularioHelper($nome_form, $Class, $comando, "POST", "school", "false");
                $inputs.= $form->Input("hidden", "id", $Classe, $id);
                $inputs.= $form->Input("text", "descricao", "col-md-6", $classe_dados["descricao"], $Required, "Descrição", $disable);
                $inputs.= $form->Input("text", "idade_minima", "col-md-6", $classe_dados["idade_minima"], $Required, "Idade Mínima", $disable);
                $inputs.= $form->Input("text", "idade_maxima", "col-md-6", $classe_dados["idade_maxima"], $Required, "Idade Máxima", $disable);
                $inputs.= $form->select("Trimestre", "id_trimestre", "col-md-6", $trimestre->listar_Trimestre(NULL, NULL, "id_status <> 99", NULL, "Trimestre.id DESC", NULL), "descricao", $classe_dados["id_trimestre"]);
                $inputs.= $form->select("Filial/Setor", "id_filial", "col-md-6", $filial->listar_Filial(NULL, NULL, "id_status<>99", NULL, "Filial.id DESC", NULL), "nome_fantasia", $classe_dados["id_filial"]);
                $inputs.= $form->select("Status","id_status", "col-md-6", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao",$classe_dados["id_status"]);     
                $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");
            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","list");            
        }else{
           $this->view('error_permisao');
        }   
    }

    public function incluir(){
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Classe/incluir/';

        if ($acesso->acesso_valida($comando) == true) {
            $classe = new ClasseModel();
            $id = $classe->cadastrar_classe(
                array(
                    'descricao' => $_POST['descricao'],
                    'idade_minima' => $_POST['idade_minima'],
                    'idade_maxima' => $_POST['idade_maxima'],
                    'id_trimestre' => $_POST['id_trimestre'],
                    'id_filial' => $_POST['id_filial'],
                    'id_status' => $_POST['id_status'],
                    'data_lancamento' => date('Y-m-d H:i:s'),
                )
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Classe/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }

    public function alterar(){
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Classe/alterar/';

        if ($acesso->acesso_valida($comando) == true) {
            $id = $_POST['id'];
            $classe = new ClasseModel();
            $classe->alterar_classe(
                array(
                    'descricao' => $_POST['descricao'],
                    'idade_minima' => $_POST['idade_minima'],
                    'idade_maxima' => $_POST['idade_maxima'],
                    'id_trimestre' => $_POST['id_trimestre'],
                    'id_filial' => $_POST['id_filial'],
                    'id_status' => $_POST['id_status'],
                    'data_lancamento' => date('Y-m-d H:i:s'),
                ),
                'id=' . $id
            );
            $logs->cadastrar_logs($comando, $id); //Gera Logs

            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Classe/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }

    public function excluir(){
        $this->acesso_restrito();

        $acesso = new AcessoHelper();
        $logs = new LogsModel();
        $comando = '/Classe/excluir/';

        if ($acesso->acesso_valida($comando) == true) {
            $id = $this->getParams('id');
            $classe = new ClasseModel();
            $classe->excluir_classe(array('id_status' => '99'), 'id=' . $id);
            $logs->cadastrar_logs($comando, $id); //Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/Classe/admin_listar/');
        } else {
            $this->view('error_permisao');
        }
    }
}