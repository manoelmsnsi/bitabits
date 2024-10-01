<?php class PrimeiroAcesso extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    }  
    public function index_action() {      
        $redirect = new RedirectHelper();
        $redirect->goToUrl('/PrimeiroAcesso/form_filial/'); 
    }
// public function form_primeiro_acesso(){    
//       // $this->acesso_restrito();
//      //  $acesso = new AcessoHelper(); 
//        $logs = new LogsModel();
//           
//          
//        $redirect = new RedirectHelper();
//        $redirect->goToUrl('/PrimeiroAcesso/form_filial/');    
//   
//    }
  public function form_filial(){
     //$this->acesso_restrito();
      $acesso = new AcessoHelper();    
        $comando='/'.__CLASS__.'/filial_incluir/';
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
           // echo $menu->Menu();  
            $filial = new FilialModel();
            $filial=$filial->listar_Filial($join, $limit, $where, $offset, $orderby, $from, $group, $pesquisa);
            if(empty($filial) OR $acesso->acesso_valida("/Filial/incluir/")==true){
                $status = new StatusModel(); 
                $nome_form='Cadastrar Primeira Filial';
                $form = new FormularioHelper();             
                $inputs .= $form->Abas($Tipo, "Pefil", "col-md-12", 
                array(
                    array("id" => "Acessos", "icone" => "apartment", "descricao" => "Filial","classe" => " disabled active"),
                    array("id"=>"Filiais","icone"=>"people","descricao"=>"Colaborador","classe" => " disabled "),
                    array("id"=>"Filiais","icone"=>"lock_open","descricao"=>"Usuario","classe" => " disabled ")),
                array(
                    array("id" => "Acessos", "dados" => "","classe" => " active")));    
      
                $inputs.= $form->Input('hidden', 'id', $CSS, $id);
                $inputs.= $form->Input("text", 'nome_fantasia', "col-md-6", $filial_dados["nome_fantasia"], "required", 'Nome Fantasia', $disable);
                $inputs.= $form->Input("text", 'razao_social', "col-md-6", $filial_dados["razao_social"], "required", 'Razão Social', $disable);
                $inputs.= $form->Input("text", 'cnpj', "col-md-2", $filial_dados["cnpj"], "onkeypress="."maska(this.name,'00.000.00.0000-00');", 'CNPJ', $disable);
                $inputs.= $form->Input("text", 'inscricao', "col-md-2", $filial_dados["inscricao"], "required", 'IE', $disable);
                $inputs.= $form->Input("text", 'regiao', "col-md-4", $filial_dados["regiao"], "required", 'Região', $disable);            
                $inputs.= $form->Input("date", 'data_nascimento', "col-md-2", $filial_dados["data_nascimento"], "required", 'Criação da Empresa', $disable);
                $inputs.= $form->select('Status','id_status','col-md-2',$status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral'",NULL,NULL,NULL),'descricao',$filial_dados['id_status']);
                $inputs.= $form->Button('btn btn-md btn-rose ','Proximo');
                $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","apartment");                 
            }else{
                $redirect = new RedirectHelper();
                $redirect->goToUrl('/PrimeiroAcesso/form_colaborador/'); 
            }             
              
    
  }
public function form_colaborador(){
    $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
   //echo $menu->Menu(); 
    $acesso = new AcessoHelper(); 
    $colaborador = new PessoaModel();
    $colaborador=$colaborador->listar_Pessoa($join, $limit, "tipo='Colaborador'", $offset, $orderby, $from, $group, $pesquisa);
    if(empty($colaborador) OR $acesso->acesso_valida("/Pessoa/incluir/")==true){
        $filial = new FilialModel();
        $status= new StatusModel();
        $form = new FormularioHelper();
        $comando='/'.__CLASS__.'/colaborador_incluir/';
        $inputs .= $form->Abas($Tipo, "Pefil", "col-md-12", 
        array(
            array("id" => "Filial", "icone" => "apartment", "descricao" => "Filial","classe" => " disabled "),
            array("id"=>"Colaborador","icone"=>"people","descricao"=>"Colaborador","classe" => " disabled active"),
            array("id"=>"Usuario","icone"=>"lock_open","descricao"=>"Usuario","classe" => " disabled ")),
        array(
            array("id" => "Acessos", "dados" => "","classe" => " active"))); 
        $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia");
        $inputs.= $form->select("Tipo","tipo", "col-md-2",array( array("id"=>"Colaborador","descricao"=>"Colaborador")),"descricao",$pessoa_dados["tipo"]);
        $inputs.= $form->Input("hidden", "id", null, $id, $required,null);                
        $inputs.= $form->Input("text", "nome", "col-md-6", $pessoa_dados["nome"], "required","Nome");
        $inputs.= $form->Input("text", "apelido", "col-md-2", $pessoa_dados["apelido"], $required,"Apelido");
        $inputs.= $form->select("Estado Civil","estado_civil", "col-md-2",array(array("id"=>"Solteiro(a)","descricao"=>"Solteiro(a)"),array("id"=>"Casado(a)","descricao"=>"Casado(a)"),array("id"=>"Viuvo(a)","descricao"=>"Viuvo(a)"),array("id"=>"Divorciado(a)","descricao"=>"Divorciado(a)")),"descricao",$pessoa_dados["estado_civil"]);            
        $inputs.= $form->Input("text", "profissao", "col-md-4", $pessoa_dados["profissao"], "required","Profissao");
        $inputs.= $form->Input("text", "local_trabalho", "col-md-3", $pessoa_dados["local_trabalho"], "required","Local Trabalho");
        $inputs.= $form->Input("text", "nacionalidade", "col-md-3", $pessoa_dados["nacionalidade"], "required","Nacionalidade");
        $inputs.= $form->Input("text", "naturalidade", "col-md-3", $pessoa_dados["naturalidade"], "required","Naturalidade");
        $inputs.= $form->select("Genero","genero", "col-md-3",array(array("id"=>"Masculino","descricao"=>"Masculino"),array("id"=>"Feminino","descricao"=>"Feminino")),"descricao",$pessoa_dados["genero"]);
        $inputs.= $form->Input("text", "cpf", "col-md-3", $pessoa_dados["cpf"], "required onkeypress="."maska(this.name,'000.000.000-00');","CPF",null);
        $inputs.= $form->Input("text", "rg", "col-md-3", $pessoa_dados["rg"], "required","RG");
        $inputs.= $form->Input("text", "orgao_expedidor", "col-md-3", $pessoa_dados["rg"], "required","Orgão Expedidor");
        $inputs.= $form->Input("date", "data_expedicao", "col-md-2", $pessoa_dados["data_expedicao"], "required","Data Expedição");
        $inputs.= $form->Input("date", "data_nascimento", "col-md-2", $pessoa_dados["data_nascimento"], "required","Data Nascimento");
        $inputs.= $form->select("Tipo Sanguineo","tipo_sanguineo", "col-md-2",array(
                                                                            array("id"=>"A+","descricao"=>"A+"),array("id"=>"A-","descricao"=>"A-"),array("id"=>"B+","descricao"=>"B+"),array("id"=>"B-","descricao"=>"B-"),array("id"=>"O+","descricao"=>"O+"),array("id"=>"O-","descricao"=>"O-"),array("id"=>"AB+","descricao"=>"AB+"),array("id"=>"AB-","descricao"=>"AB-"),
                                                                            array("id"=>"Não Informado","descricao"=>"Não Informado")),"descricao",$pessoa_dados["tipo_sanguineo"]);
        $inputs.= $form->select("Status","id_status", "col-md-3", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral' OR tabela='Pessoa'",NULL,' Status.id ASC',NULL),"descricao",$pessoa_dados["id_status"]);
        $inputs.= $form->Button("btn btn-md btn-rose ","Proximo");
        $form->card("Cadastrar Primeiro Colaborador",$inputs,"col-md-12",$comando,"POST","people");
    }else{
        $redirect = new RedirectHelper();
        $redirect->goToUrl('/PrimeiroAcesso/form_usuario/');   
    }
  }
  
  public function form_usuario(){
        $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
      //  echo $menu->Menu();   
        $usuario = new UsuarioModel(); 
         $acesso = new AcessoHelper(); 
        $usuario=$usuario->listar_usuario($join, $limit, $where, $offset, $orderby, $from, $group, $pesquisa);
        if(empty($usuario) OR $acesso->acesso_valida("/Usuario/incluir/")==true){
            $filial = new FilialModel();
            $status= new StatusModel();
            $pessoa = new PessoaModel();
            $form = new FormularioHelper();          
            $comando="/".__CLASS__."/usuario_incluir/";  
            $inputs .= $form->Abas($Tipo, "Pefil", "col-md-12", 
            array(
                array("id" => "Acessos", "icone" => "apartment", "descricao" => "Filial","classe" => " disabled "),
                array("id"=>"Filiais","icone"=>"people","descricao"=>"Colaborador","classe" => " disabled "),
                array("id"=>"Filiais","icone"=>"lock_open","descricao"=>"Usuario","classe" => " disabled active")),
            array(
                array("id" => "Acessos", "dados" => "","classe" => " active"))); 
            $inputs .= $form->Input("hidden", "id", $Classe, $id);
            $inputs .= $form->select("Filial","id_filial", " col-md-3", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$usuario_dados["id_filial"]);
            $inputs .= $form->select("Colaborador","id_colaborador", " col-md-3",$pessoa->listar_Pessoa($join, $limit, "id_status<>'99' AND tipo='Colaborador'", $offset, $orderby, $from),"nome",$usuario_dados["id_colaborador"]);
            $inputs .= $form->Input("text", "usuario", " col-md-3", $usuario_dados["usuario"], "required", "Usuario", $disable);
            $inputs .= $form->Input("password", "senha", "col-md-3", $usuario_dados["senha"], "required","Senha", $disable);
            $inputs .=$form->Input("time", "inicio_funcionamento", "col-md-3", $usuario_dados["inicio_funcionamento"], "required", "Horario para Abrir o Sistema", $disable);
            $inputs .= $form->Input("time", "fim_funcionamento", "col-md-3", $usuario_dados["fim_funcionamento"], "required","Horario para Fechar o Sistema", $disable);
            $inputs .= $form->select("Administrador","administrador", " col-md-2",array(array("id"=>"SIM"),array("id"=>"NAO")),"id",$usuario_dados["administrador"]);
            $inputs .= $form->select("Status","id_status", "col-md-4", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao,id",$usuario_dados["id_status"]);
            $inputs .=  $form->Button("btn btn-md btn-rose ","Finalizar");          
            $form->card("Cadastrar Primeiro Usuario",$inputs,"col-md-12",$comando,"POST","people");
        }else{
             $redirect = new RedirectHelper();
            $redirect->goToUrl('/Admin/'); 
        }              
  }
  public function Acesso(){}
  
   public function usuario_incluir(){      
        $senha = md5($_POST['senha']);
        $usuario = new UsuarioModel();  
        $in=$_POST['inicio_funcionamento'];
        $fi=$_POST['fim_funcionamento'];
        if(empty($in)){ $in='07:00:00'; }            
        if(empty($fi)){$fi='18:00:00'; }           
        $id=$usuario->cadastrar_usuario( 
            array(
                'id_filial'=>$_POST['id_filial'],
                'id_colaborador'=>$_POST['id_colaborador'],
                'inicio_funcionamento'=>$in,
                'fim_funcionamento'=>$fi,
                'administrador'=>$_POST['administrador'],
                'usuario'=>$_POST['usuario'],
                'senha'=>$senha,
                'id_status'=>$_POST['id_status'],
                'data_lancamento'=>  date("Y-m-d H:i:s"),
            )
        );  
        $upload= new UploadModel();
        $upload->cadastrar_Upload(array(
           "tipo" => "IMG",      
            "id_filial" => $_POST['id_filial'],           
            "src" => "/web-files/sistema/imagens/sem_imagem.png",              
            "descricao" => "Perfil Primeiro Acesso",                 
            "tabela" => "Usuario",         
            "id_tabela" => $id,              
            "data_lancamento" =>date("Y-m-d H:i:s"),            
            "id_status" => "1"
          ));
        $vincula_filial = new VinculaFilialModel();
        $vincula_filial->cadastrar_VinculaFilial( 
            array(
                'id_filial'=>$_POST['id_filial'],
                'id_usuario'=>$id,
                'id_grupo'=>"10",
                'id_status'=>"1",
                'data_lancamento'=>  date("Y-m-d H:i:s"),
            ));  
        $acesso = new AcessoModel();
        $acesso->cadastrar_acesso(
            array(
                'id_filial' => $_POST['id_filial'],
                'id_usuario' => $id,
                'id_programa' => "0",
                'id_grupo' => "1",
                'id_status' => "1",
                'data_lancamento' => date("Y-m-d H:i:s"),
            ));
        $redirect = new RedirectHelper();
        $redirect->goToUrl("/Admin/Index/");  
   }
  
    public function colaborador_incluir(){ 
       $this->acesso_restrito();
           $acesso = new AcessoHelper(); 
           $logs = new LogsModel();
           $comando='/PrimeiroAcesso/colaborador_incluir/';
               $cliente = new PessoaModel();  
               $cliente->cadastrar_Pessoa( 
                   array(
                       'id_filial'=>$_POST['id_filial'],
                       'id_status'=>$_POST['id_status'],
                       'tipo'=>$_POST['tipo'],
                       'nome'=>$_POST['nome'],
                       'apelido'=>$_POST['apelido'],                       
                       'cpf'=>$_POST['cpf'],                       
                       'rg'=>$_POST['rg'],
                       'orgao_expedidor'=>$_POST['orgao_expedidor'],
                       'data_expedicao'=>$_POST['data_expedicao'],
                       'data_nascimento'=>$_POST['data_nascimento'],
                       'genero'=>$_POST['genero'], 
                       'naturalidade'=>$_POST['naturalidade'],
                       'nacionalidade'=>$_POST['nacionalidade'],
                       'estado_civil'=>$_POST['estado_civil'],                
                       'profissao'=>$_POST['profissao'],
                       'local_trabalho'=>$_POST['local_trabalho'],
                       'observacao'=>$_POST['observacao'],
                       'tipo_sanguineo'=>$_POST['tipo_sanguineo'],
                       'data_lancamento'=>  date("Y-m-d H:i:s"),
                  )
               );  
             //  $logs->cadastrar_logs($comando,$id);//Gera Logs
               $redirect = new RedirectHelper();
               $redirect->goToUrl("/PrimeiroAcesso/form_usuario/");   

    }
  
   public function filial_incluir(){    
       // $this->acesso_restrito();
      //  $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Filial/incluir/';         
            $filial = new FilialModel();      
            $id=$filial->cadastrar_filial( 
                array(
                     'nome_fantasia'=>$_POST['nome_fantasia'],
                    'razao_social'=>$_POST['razao_social'],
                    'cnpj'=>$_POST['cnpj'],
                    'inscricao'=>$_POST['inscricao'],
                    'regiao'=>$_POST['regiao'],
                    'id_status'=>$_POST['id_status'],
                    'data_nascimento'=>$_POST['data_nascimento'],
                   'data_lancamento'=>  date("Y-m-d H:i:s"),
                )
            );  
            $logs->cadastrar_logs($comando,$id);//Gera Logs
            $redirect = new RedirectHelper();
            $redirect->goToUrl('/PrimeiroAcesso/form_colaborador/');    
   
    }
}
?>

