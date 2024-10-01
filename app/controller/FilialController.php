<?php class Filial extends Controller {       private  $auth,$db;    public function acesso_restrito(){                  $this->auth = new AutenticaHelper();        $this->auth->setLoginControllerAction('Index','')                   ->checkLogin('redirect');                      $this->db = new AdminModel();     }         public function admin_listar(){        $this->acesso_restrito();        $acesso = new AcessoHelper();        $logs = new LogsModel();        $comando="/".__CLASS__."/incluir/";                 if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){             $filiais=$acesso->acesso_filial(__CLASS__);            $status= new StatusModel();            $filial = new FilialModel();                       $acesso = new SessionHelper();                       $filial = new FilialModel();                      if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                        $form = new FormularioHelper(__CLASS__,"col-md-12" ,null,null,"people");                 $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$filial->listar_Filial("INNER JOIN Status ON Status.id=Filial.id_status ",NULL,"Filial.id_status<>99",NULL,' Filial.id DESC',"Filial.nome_fantasia AS 'Nome Fantasia', Filial.razao_social AS 'Razao Social',Filial.cnpj AS CNPJ,Filial.inscricao AS IE,Status.cor AS 'cor_Status',Status.descricao AS Status,regiao AS Regiao,Filial.id_status,Filial.id",NULL,$pesquisa), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/tabela/Filial/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","apartment");         }else{            $this->view('error_permisao');        }        }        public function form(){         $this->acesso_restrito();        $acesso = new AcessoHelper();            $comando='/'.__CLASS__.'/incluir/';        if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                              $status = new StatusModel();            $filial = new FilialModel();            $id = $this->getParams('id');            $dados['id']=$id;            $nome_form='Cadastra Filial';            if(!empty($id)){                $filial_dados=$filial->listar_filial($JOIN, '1', "id=$id", $offset, $orderby);                $filial_dados = $filial_dados[0];                 $comando='/'.__CLASS__.'/alterar/';                $nome_form='Alterar Filial';            }              $form = new FormularioHelper();                $inputs.= $form->Input('hidden', 'id', $CSS, $id);                $inputs.= $form->Input("text", 'nome_fantasia', "col-md-6", $filial_dados["nome_fantasia"], $Required, 'Nome Fantasia', $disable);                $inputs.= $form->Input("text", 'razao_social', "col-md-6", $filial_dados["razao_social"], $Required, 'Razão Social', $disable);                $inputs.= $form->Input("text", 'cnpj', "col-md-2", $filial_dados["cnpj"], "onkeypress="."maska(this.name,'00.000.00.0000-00');", 'CNPJ', $disable);                $inputs.= $form->Input("text", 'inscricao', "col-md-2", $filial_dados["inscricao"], $Required, 'IE', $disable);                $inputs.= $form->Input("text", 'regiao', "col-md-2", $filial_dados["regiao"], $Required, 'Região', $disable);                            $inputs.= $form->Input("date", 'data_nascimento', "col-md-2", $filial_dados["data_nascimento"], $Required, 'Data Criação', $disable);                $inputs.= $form->select('Status','id_status','col-md-2',$status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral'",NULL,NULL,NULL),'descricao',$filial_dados['id_status']);                $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');                $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","apartment");           }else{            $this->view('error_permisao');        }    }     public function visualizar(){         $this->acesso_restrito();        $acesso = new AcessoHelper();            $comando='/'.__CLASS__.'/incluir/';       // if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                              $status = new StatusModel();            $filial = new FilialModel();            $id = $this->getParams('id');            $tabela = $this->getParams('tabela');            $dados['id']=$id;            $nome_form='Cadastra Filial';                $form = new FormularioHelper();                $contato = new ContatoModel();                $listar_contato = $form->Listar("col-md-4", "Contato", "/Contato/form/id_tabela/$id/tabela/$tabela/", "contact_phone", $contato->listar_Contato(NULL,NULL,"id_status<>'99' AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Contato.id DESC',"Contato.id,descricao AS 'Descrição', contato AS 'Contato'") , "tabela_contato", array(array("acao"=>"/Contato/form/tipo/$tabela/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);                                       $historico = new HistoricoModel();                               $listar_historico = $form->Listar("col-md-12", 'Historico Pessoa', "/Pessoa/form_historico/id/$id/", "assignment",$historico->listar_Historico("INNER JOIN Status ON Status.id = Historico.id_status ",NULL,"Historico.id_status<>'99' AND Historico.tabela='Pessoa' AND Historico.id_tabela='{$id}'",NULL,' Historico.id DESC',"Historico.observacao AS 'Observação',Status.cor AS 'cor_Status',Status.descricao AS Status,Historico.data_lancamento AS 'Data Lançamento'") , "tabela_historico", array(array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);                                                      $pessoa = new PessoaModel();                $listar_pessoa = $form->Listar("col-md-12", 'Colaboradores', "/Pessoa/form/tipo/Cliente/", "assignment", $pessoa->listar_Pessoa("INNER JOIN Cargo ON Cargo.id_tabela=Pessoa.id ", $limit, "Pessoa.tipo='Colaborador' AND Pessoa.id_status<>'99' AND Pessoa.id_filial='$id'", $offset, $orderby, "Pessoa.nome AS Nome,Pessoa.id,Cargo.descricao AS Cargo,Cargo.data_posse AS 'Data Admissão'", $group, $pesquisa) , "tabela_pessoa", $acao, $pesquisa);                                 $upload = new UploadModel();                $listar_upload = $form->Listar("col-md-12", $titulo, "/Upload/form/id_tabela/$id/tabela/$tabela/id_filial/$id/", "upload",$upload->listar_Upload(NULL,NULL,"id_status<>'99' AND tabela='$tabela' AND id_tabela='{$id}'",NULL,' Upload.id DESC',"Upload.descricao AS 'Descrição',Upload.src, Upload.data_lancamento AS 'Data Lançamento'") , "tabela_upload", array(array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);                 $endereco = new EnderecoModel();                $listar_endereco = $form->Listar("col-md-8", "Endereço", "/Endereco/form/id_tabela/$id/tabela/$tabela", "location_on",$endereco->listar_Endereco("INNER JOIN Status ON Status.id = Endereco.id_status",NULL,"Endereco.id_status<>'99' AND Endereco.tabela='$tabela' AND Endereco.id_tabela='{$id}'",NULL,' Endereco.id DESC',"Endereco.id,Endereco.pais AS 'Pais',Endereco.estado AS 'Estado',Endereco.cep AS CEP,Endereco.cidade AS 'Cidade',Endereco.logradouro AS 'Logradouro', Endereco.numero,Status.cor AS 'cor_Status',Status.descricao AS Status,Endereco.complemento") , "tabela_endereco",  array(array("acao"=>"/Endereco/form/tipo/$tabela/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")), $pesquisa);                         if(!empty($id)){                $filial_dados=$filial->listar_filial($JOIN, '1', "id=$id", $offset, $orderby);                $filial_dados = $filial_dados[0];                 $comando='/'.__CLASS__.'/alterar/';                $nome_form='Visualizar Filial';            }                         $inputs.="<form class='col-md-12 row'>";                $inputs.= $form->Input('hidden', 'id', $CSS, $id);                $inputs.= $form->Input("text", 'nome_fantasia', "col-md-6", $filial_dados["nome_fantasia"], $Required, 'Nome Fantasia', $disable);                $inputs.= $form->Input("text", 'razao_social', "col-md-6", $filial_dados["razao_social"], $Required, 'Razão Social', $disable);                $inputs.= $form->Input("text", 'cnpj', "col-md-2", $filial_dados["cnpj"], "onkeypress="."maska(this.name,'00.000.00.0000-00');", 'CNPJ', $disable);                $inputs.= $form->Input("text", 'inscricao', "col-md-2", $filial_dados["inscricao"], $Required, 'IE', $disable);                $inputs.= $form->Input("text", 'regiao', "col-md-2", $filial_dados["regiao"], $Required, 'Região', $disable);                            $inputs.= $form->Input("date", 'data_nascimento', "col-md-2", $filial_dados["data_nascimento"], $Required, 'Data Criação', $disable);                $inputs.= $form->select('Status','id_status','col-md-2',$status->listar_Status(NULL,NULL,"id_status<>'99' AND tabela='Geral'",NULL,NULL,NULL),'descricao',$filial_dados['id_status']);                $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');                 $inputs.="</form >";                                 $inputs.= $form->Abas($Tipo, "Pessoa", "col-md-12",                 array(                    array("id" => "Contatos", "icone" => "contacts", "descricao" => "Contatos"),                     array("id" => "Imagens", "icone" => "photo_library", "descricao" => "Imagens"),                    array("id" => "Historico", "icone" => "assignment", "descricao" => "Historico"),                    array("id" => "Colaboradores", "icone" => "people", "descricao" => "Colaboradores"),                    ),                                    array(                    array("id" => "Contatos",     "dados" => "$listar_endereco $listar_contato","classe" => " active"),                    array("id" => "Imagens",      "dados" => "$listar_upload"),                    array("id" => "Historico",    "dados" => "$listar_historico"),                    array("id" => "Colaboradores",    "dados" => "$listar_pessoa"),                ));                 $form->card($nome_form,$inputs,"col-md-12","/Filial/form/id/$id/","POST","apartment");          // }else{      //      $this->view('error_permisao');      //  }    } public function incluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Filial/incluir/';                   if($acesso->acesso_valida($comando)==true){            $filial = new FilialModel();                  $id=$filial->cadastrar_filial(                 array(                     'nome_fantasia'=>$_POST['nome_fantasia'],                    'razao_social'=>$_POST['razao_social'],                    'cnpj'=>$_POST['cnpj'],                    'inscricao'=>$_POST['inscricao'],                    'regiao'=>$_POST['regiao'],                    'id_status'=>$_POST['id_status'],                    'data_nascimento'=>$_POST['data_nascimento'],                   'data_lancamento'=>  date("Y-m-d H:i:s"),                )            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Filial/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function alterar(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Filial/alterar/';        if($acesso->acesso_valida($comando)==true){            $id = $_POST['id'];            $filial = new FilialModel();                  $filial->alterar_filial(                array(                    'nome_fantasia'=>$_POST['nome_fantasia'],                    'razao_social'=>$_POST['razao_social'],                    'cnpj'=>$_POST['cnpj'],                    'inscricao'=>$_POST['inscricao'],                    'regiao'=>$_POST['regiao'],                    'id_status'=>$_POST['id_status'],                    'data_nascimento'=>$_POST['data_nascimento'],                       'data_lancamento'=>  date("Y-m-d H:i:s"),                ),'id='.$id            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Filial/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function excluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/Filial/excluir/';        if($acesso->acesso_valida($comando)==true){            $id = $this->getParams('id');            $filial = new FilialModel();                  $filial->excluir_filial( array( 'id_status'=>'99' ),'id='.$id );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/Filial/admin_listar/');            }else{            $this->view('error_permisao');        }    }     } ?> 