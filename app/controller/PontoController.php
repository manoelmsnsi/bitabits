<?php class Ponto extends Controller {       private  $auth,$db;    public function acesso_restrito(){                  $this->auth = new AutenticaHelper();        $this->auth->setLoginControllerAction('Index','')                   ->checkLogin('redirect');                      $this->db = new AdminModel();     }         public function admin_listar(){        $this->acesso_restrito();        $acesso = new AcessoHelper();        $logs = new LogsModel();        $comando="/".__CLASS__."/incluir/";                 if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){             $filiais=$acesso->acesso_filial(__CLASS__);            $status= new StatusModel();            $filial = new FilialModel();                       $acesso = new SessionHelper();                       $ponto = new PontoModel();                       if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }           $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                        $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");                 $inputs = $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$ponto->listar_Ponto(NULL,NULL,"id_status<>99 AND ({$filiais})",NULL,' Ponto.id DESC',NULL,null,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","fingerprint");        }else{            $this->view('error_permisao');        }        }       public function form(){         $this->acesso_restrito();        $acesso = new AcessoHelper();                 $comando="/".__CLASS__."/incluir/";          if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){                $logs = new LogsModel();             $status= new StatusModel();            $filial = new FilialModel();            $pessoa = new PessoaModel();            $ponto = new PontoModel();            $acesso = new SessionHelper();            $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu($acesso->selectSession('userAcesso'));                       $id=$this->getParams("id");                 $nome_form="Cadastrar produto";                             if(!empty($id)){                $ponto_dados =$ponto->listar_Ponto($join, "1", "id='$id'", $offset, $orderby);                $ponto_dados = $ponto_dados[0];                 $comando="/".__CLASS__."/alterar/";                $nome_form="Alterar Produto";            }                          $form = new FormularioHelper($nome_form,"col-md-12" ,$comando,"POST","people");            $inputs.= $form->select("Filial","id_filial", "col-md-2", $filial->listar_Filial(NULL,NULL,"Filial.id_status<>'99'",NULL,' Filial.id DESC',NULL),"nome_fantasia",$produto_dados["filial"]);            $inputs.= $form->select("Colaborador","id_colaborador", "col-md-2",$pessoa->listar_Pessoa("INNER JOIN Status ON Status.id=Pessoa.id_status",NULL,"Pessoa.id_status<>99 AND Pessoa.tipo='Colaborador'",NULL,' Pessoa.id DESC',"Pessoa.tipo,Pessoa.id_filial,Pessoa.nome,Pessoa.cpf,Status.descricao AS descricao_status,Pessoa.data_nascimento,Pessoa.data_lancamento,Pessoa.id"),"nome",$produto_dados["id_fornecedor"]);            $inputs.= $form->Input("text", "decricao", "col-md-6", $produto_dados["descricao"], $Required, "Descricao", $disable);            $inputs.= $form->select("Status","id_status", "col-md-2", $status->listar_Status(NULL,NULL,"id_status<>99 AND tabela='Geral'",NULL,' Status.id ASC',NULL),"descricao");            $inputs.= $form->Button("btn btn-md btn-rose ","Salvar");            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","fingerprint");        }else{           $this->view('error_permisao');        }       } public function incluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Ponto/incluir/';                    if($acesso->acesso_valida($comando)==true){            $ponto = new PontoModel();                  $id=$ponto->cadastrar_ponto(                 array(                    'id_filial'=>$_POST['id_filial'],                    'id_colaborador'=>$_POST['id_colaborador'],                    'descricao'=>$_POST['descricao'],                    'data_ponto'=>date("Y-m-d H:i:s"),                    'id_status'=>$_POST['id_status'],                    'data_lancamento'=>  date("Y-m-d H:i:s"),                )            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Ponto/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function alterar(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Ponto/alterar/';        if($acesso->acesso_valida($comando)==true){            $id = $_POST['id'];            $ponto = new PontoModel();                  $ponto->alterar_ponto(                array(                    'id_filial'=>$_POST['id_filial'],                    'id_colaborador'=>$_POST['id_colaborador'],                    'descricao'=>$_POST['descricao'],                    'data_ponto'=>$_POST['data_ponto'],                    'id_status'=>$_POST['id_status'],                   'data_lancamento'=>  date("Y-m-d H:i:s"),                ),'id='.$id            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Ponto/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function excluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Ponto/excluir/';        if($acesso->acesso_valida($comando)==true){            $id = $this->getParams('id');            $ponto = new PontoModel();                  $ponto->excluir_ponto( array( 'id_status'=>'99' ),'id='.$id );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Ponto/admin_listar/');            }else{            $this->view('error_permisao');        }    }  } ?> 