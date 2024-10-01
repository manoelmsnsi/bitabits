<?php

class Anamneses extends Controller {

private $auth, $db;

public function acesso_restrito() {
$this->auth = new AutenticaHelper();
$this->auth->setLoginControllerAction('Index', '')->checkLogin('redirect');
$this->db = new AdminModel();
}

public function form() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = "/" . __CLASS__ . "/incluir/";
//if ($acesso->acesso_valida("/" . __CLASS__ . "/admin_listar/") == true) {
        $status = new StatusModel();
        $filial = new FilialModel();
        $anamneses = new AnamnesesModel();
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
        echo $menu->Menu();
        $id_cliente = $this->getParams("id_cliente");
        $id = $this->getParams("id");
        if (!empty($id)) {
            $anamneses_dados = $anamneses->listar_Anamneses($join, "1", "id='$id'", $offset, $orderby);
            $anamneses_dados = $anamneses_dados[0];
            $comando = "/" . __CLASS__ . "/alterar/";
        } 
        $form = new FormularioHelper();
        $inputs .= $form->Input("hidden", "id_cliente", "col-md-12", $id_cliente, $Required,null, $disable, $id);
        $inputs .= $form->Input("hidden", "id", "col-md-12", $id, $Required,null, $disable, $id);
        $inputs .= $form->Input($Tipo, "queixa", "col-md-12", $anamneses_dados["queixa"], $Required, "Queixa Principal", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["depressao_anciedade"], "depressao_anciedade", "col-md-6","SIM" , $Required, "Depressão/Anciedade", $disable, $id);       
       $inputs .=  $form->Check($anamneses_dados["coronariopatia"], "coronariopatia", "col-md-6", "SIM", $Required, "Coronariopatia", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["valvopatia"], "valvopatia", "col-md-6", "SIM", $Required, "Valvopatia", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["diabetes"], "diabetes", "col-md-6", "SIM", $Required, "Diabetes", $disable, $id);
       $inputs .= $form->Input($Tipo, "historia", "col-md-12", $anamneses_dados["historia"], $Required, "História do problema Atual", $disable, $id);
       $inputs .= $form->Input($Tipo, "medicamentos", "col-md-4", $anamneses_dados["medicamentos"], $Required, "Medicamentos em uso", $disable, $id);       
       $inputs .= $form->Input($Tipo, "etilismo", "col-md-4", $anamneses_dados["etilismo"], $Required, "Etilismo", $disable, $id);       
       $inputs .= $form->Input($Tipo, "tabagismo", "col-md-4", $anamneses_dados["tabagismo"], $Required, "Tabagismo", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["has"], "has", "col-md-4", "SIM", $Required, "HAS", $disable, $id);              
       $inputs .=  $form->Check($anamneses_dados["convulsoes"], "convulsoes", "col-md-4", "SIM", $Required, "Convulsões", $disable, $id);       
       $inputs .=  $form->Check($anamneses_dados["cirurgias_previas"], "cirurgias_previas", "col-md-4", "SIM", $Required, "Cirurgias Prévias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["doenca_congenitas"], "doenca_congenitas", "col-md-4", "SIM", $Required, "Doenças Congênitas", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["hipo"], "hipo", "col-md-4", "SIM", $Required, "Hipo/Hipertiroidismo", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["internacoes"], "internacoes", "col-md-4", "SIM", $Required, "Internações Prévias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["neoplasias"], "neoplasias", "col-md-4", "SIM", $Required, "Neoplasias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["neuropatias"], "neuropatias", "col-md-4", "SIM", $Required, "Neuropatias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["nefropatias"], "nefropatias", "col-md-4", "SIM", $Required, "Nefropatias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["osteopatias"], "osteopatias", "col-md-4", "SIM", $Required, "Osteopatias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["pneumopatias"], "pneumopatias", "col-md-4", "SIM", $Required, "Pneumopatias", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["alergias"], "alergias", "col-md-4", "SIM", $Required, "Alergias", $disable, $id);
       $inputs .= $form->Input($Tipo, "tipos_alergia", "col-md-4", $anamneses_dados["tipos_alergia"], $Required, "Tipos de Alergias", $disable, $id);
       $inputs .= $form->Input($Tipo, "pratica_atividade_fisica", "col-md-4", $anamneses_dados["pratica_atividade_fisica"], $Required, "Pratica Ativade Fisica ", $disable, $id);
       $inputs .= $form->Input($Tipo, "substancias_ilicitas", "col-md-4", $anamneses_dados["substancias_ilicitas"], $Required, "Uso de Sustâncias Ilicitas ", $disable, $id);
       $inputs .= $form->Input($Tipo, "dst", "col-md-4", $anamneses_dados["dst"], $Required, "DST", $disable, $id);       
       $inputs .= $form->Input($Tipo, "exame_fisico", "col-md-4", $anamneses_dados["exame_fisico"], $Required, "Exame Fisico", $disable, $id);
       $inputs .= $form->Input($Tipo, "conduta", "col-md-4", $anamneses_dados["conduta"], $Required, "Conduta", $disable, $id);       
       $inputs .=  $form->Check($anamneses_dados["avc"], "avc", "col-md-6", "SIM", $Required, "AVC", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["dac"], "dac", "col-md-6", "SIM", $Required, "DAC", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["dm"], "dm", "col-md-6", "SIM", $Required, "DM", $disable, $id);
       $inputs .=  $form->Check($anamneses_dados["doencas_geneticas"], "doencas_geneticas", "col-md-6", "SIM", $Required, "Doenças Genéticas", $disable, $id);
       $inputs .= $form->Input($Tipo, "outros_nao_especificados", "col-md-12", $anamneses_dados["outros_nao_especificados"], $Required, "Outros não especificados", $disable, $id);
       $inputs .= $form->Button("btn btn-md btn-rose", "Salvar");
      $form->card("Cadastro de Anamneses",$inputs,"col-md-12","$comando","POST","work_outline");
   //$this->view("form_anamneses", $dados);
  //  } else {
  //   $this->view('error_permisao');
 //   }
}

public function incluir() {
$this->acesso_restrito();
$acesso = new AcessoHelper();
$logs = new LogsModel();
$comando = '/Modelo/incluir/';
if ($acesso->acesso_valida($comando) == true) {
$anamneses = new AnamnesesModel();
$id_cliente = $_POST['id_cliente'];

$id = $anamneses->cadastrar_Anamneses(
array(
'id_cliente' => $id_cliente,
 'queixa' => $_POST['queixa'],
 'historia' => $_POST['historia'],
 'medicamentos' => $_POST['medicamentos'],
 'etilismo' => $_POST['etilismo'],
 'tabagismo' => $_POST['tabagismo'],
 'substancias_ilicitas' => $_POST['substancias_ilicitas'],
 'pratica_atividade_fisica' => $_POST['pratica_atividade_fisica'],
 'dst' => $_POST['dst'],
 'exame_fisico' => $_POST['exame_fisico'],
 'conduta' => $_POST['conduta'],
 'depressao_anciedade' => $_POST['depressao_anciedade'],
 'coronariopatia' => $_POST['coronariopatia'],
 'valvopatia' => $_POST['valvopatia'],
 'diabetes' => $_POST['diabetes'],
 'has' => $_POST['has'],
 'alergias' => $_POST['alergias'],
 'convulsoes' => $_POST['convulsoes'],
 'cirurgias_previas' => $_POST['cirurgias_previas'],
 'doenca_congenitas' => $_POST['doenca_congenitas'],
 'hipo' => $_POST['hipo'],
 'internacoes' => $_POST['internacoes'],
 'neoplasias' => $_POST['neoplasias'],
 'neuropatias' => $_POST['neuropatias'],
 'osteopatias' => $_POST['osteopatias'],
 'pneumopatias' => $_POST['pneumopatias'],
 'outros' => $_POST['outros'],
 'outros_nao_especificados' => $_POST['outros_nao_especificados'],
 'avc' => $_POST['avc'],
 'dac' => $_POST['dac'],
 'dm' => $_POST['dm'],
 'doencas_geneticas' => $_POST['doencas_geneticas'],
 'data_lancamento' => date("Y-m-d H:i:s"),
 'id_status' => "1", ));

$logs->cadastrar_logs($comando, $id); //Gera Logs   
$redirect = new RedirectHelper();
$redirect->goToUrl("/Pessoa/visualizar/tabela/Cliente/id/$id_cliente");
} else {
$this->view('error_permisao');
}
}

public function alterar() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Anameneses/alterar/';
    //if ($acesso->acesso_valida($comando) == true) {
        $anamneses = new AnamnesesModel();
        $id_cliente = $_POST['id_cliente'];
        $id = $_POST['id'];

       $anamneses->alterar_Anamneses(array(
            'id_cliente' => $id_cliente,
             'queixa' => $_POST['queixa'],
             'historia' => $_POST['historia'],
             'medicamentos' => $_POST['medicamentos'],
             'etilismo' => $_POST['etilismo'],
             'tabagismo' => $_POST['tabagismo'],
             'substancias_ilicitas' => $_POST['substancias_ilicitas'],
             'pratica_atividade_fisica' => $_POST['pratica_atividade_fisica'],
             'dst' => $_POST['dst'],
             'exame_fisico' => $_POST['exame_fisico'],
             'conduta' => $_POST['conduta'],
             'depressao_anciedade' => $_POST['depressao_anciedade'],
             'coronariopatia' => $_POST['coronariopatia'],
             'valvopatia' => $_POST['valvopatia'],
             'diabetes' => $_POST['diabetes'],
             'has' => $_POST['has'],
             'alergias' => $_POST['alergias'],
             'convulsoes' => $_POST['convulsoes'],
             'cirurgias_previas' => $_POST['cirurgias_previas'],
             'doenca_congenitas' => $_POST['doenca_congenitas'],
             'hipo' => $_POST['hipo'],
             'internacoes' => $_POST['internacoes'],
             'neoplasias' => $_POST['neoplasias'],
             'neuropatias' => $_POST['neuropatias'],
             'osteopatias' => $_POST['osteopatias'],
             'pneumopatias' => $_POST['pneumopatias'],
             'outros' => $_POST['outros'],
             'outros_nao_especificados' => $_POST['outros_nao_especificados'],
             'avc' => $_POST['avc'],
             'dac' => $_POST['dac'],
             'dm' => $_POST['dm'],
             'doencas_geneticas' => $_POST['doencas_geneticas'],
             'data_lancamento' => date("Y-m-d H:i:s"),
             'id_status' => "1",), "id=$id");
        $logs->cadastrar_logs($comando, $id); //Gera Logs         
        $redirect = new RedirectHelper();
        $redirect->goToUrl("/Pessoa/visualizar/tabela/Cliente/id/$id_cliente");
     //   } else {
      //   $this->view('error_permisao');
       // }
}

public function excluir() {
    $this->acesso_restrito();
    $acesso = new AcessoHelper();
    $logs = new LogsModel();
    $comando = '/Modelo/excluir/';
    if ($acesso->acesso_valida($comando) == true) {
         $anamneses = new AnamnesesModel();
        $id_cliente = $_POST['id_tabela'];
        $id = $_POST['id'];

       $anamneses->alterar_Anamneses(array(            
             'id_status' => "99"), "id=$id");
        $logs->cadastrar_logs($comando, $id); //Gera Logs    
        $redirect = new RedirectHelper();
        $redirect->goToUrl("/Pessoa/visualizar/tabela/Cliente/id/$id_cliente");
        }else{ 
            $this->view('error_permisao');
        } 
    }
}

?> 