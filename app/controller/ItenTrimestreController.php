<?php class ItenTrimestre extends Controller {       private  $auth,$db;    public function acesso_restrito(){                  $this->auth = new AutenticaHelper();        $this->auth->setLoginControllerAction('Index','')                   ->checkLogin('redirect');                      $this->db = new AdminModel();     }             public function admin_listar(){        $this->acesso_restrito();        $acesso = new AcessoHelper();        $logs = new LogsModel();        $comando="/".__CLASS__."/incluir/";            //     if($acesso->acesso_valida("/".__CLASS__."/admin_listar/")==true){             $filiais=$acesso->acesso_filial(__CLASS__);            $status= new StatusModel();            $filial = new FilialModel();                       $acesso = new SessionHelper();                       $itens_trimestre = new ItensTrimestreModel();                       if(empty($_POST["pesquisa"])){$pesquisa=null; }else{ $pesquisa=$_POST["pesquisa"]; }            $menu = new MenuHelper("Bit a Bits", $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                        $form = new FormularioHelper();                 $inputs= $form->Listar("col-md-12", null, "/".__CLASS__."/form/", $icone,$itens_trimestre->listar_ItenTrimestre(' INNER JOIN Trimestre ON Trimestre.id = ItenTrimestre.id_trimestre INNER JOIN Classe ON Classe.id = ItenTrimestre.id_classe INNER JOIN Filial ON Filial.id = ItenTrimestre.id_filial INNER JOIN Status ON Status.id = ItenTrimestre.id_status' ,NULL,'ItenTrimestre.id_status<>99 AND '.$filiais,NULL,' ItenTrimestre.id DESC',"Classe.descricao,Classe.idade_minima,Classe.idade_maxima,ItenTrimestre.id",NULL), "tabela1", array(array("acao"=>"/".__CLASS__."/form/","classe"=>"btn-sm btn-warning","icone"=>"edit"),array("acao"=>"/".__CLASS__."/visualizar/","classe"=>"btn-sm btn-rose","icone"=>"remove_red_eye"),array("acao"=>"/Logs/form/","classe"=>"btn-sm btn-danger","icone"=>"close")));            $form->card(__CLASS__,$inputs,"col-md-12",$comando,"POST","ballot");     //   }else{     //       $this->view('error_permisao');     //   }        } public function form(){         $this->acesso_restrito();        $acesso = new AcessoHelper();            $comando='/'.__CLASS__.'/incluir/';        if($acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);                    echo $menu->Menu();                          $trimestre = new TrimestreModel();        $classe = new ClasseModel();        $filial = new FilialModel();        $status = new StatusModel();        $itentrimestre = new ItenTrimestreModel();        $id = $this->getParams('id');        $dados['id']=$id;        $nome_form='Cadastra Classe no Trimestre';        if(!empty($id)){            $itentrimestre_dados=$itentrimestre->listar_itentrimestre($JOIN, '1', "id=$id", $offset, $orderby);            $itentrimestre_dados = $itentrimestre_dados[0];             $comando='/'.__CLASS__.'/alterar/';            $nome_form='Alterar ItenTrimestre';        }          $form = new FormularioHelper();            $inputs.= $form->Input('hidden', 'id', $CSS, $id);            $inputs.= $form->select('Trimestre','id_trimestre','col-md-5',$trimestre->listar_Trimestre(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$itentrimestre_dados['id_trimestre']);            $inputs.= $form->select('Classe','id_classe','col-md-5',$classe->listar_Classe(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$itentrimestre_dados['id_classe']);            $inputs.= $form->select('Filial','id_filial','col-md-5',$filial->listar_Filial(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'nome_fantasia',$itentrimestre_dados['id_filial']);            $inputs.= $form->select('Status','id_status','col-md-5',$status->listar_Status(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'descricao',$itentrimestre_dados['id_status']);            $inputs.= $form->Button('btn btn-md btn-rose ','Salvar');            $form->card($nome_form,$inputs,"col-md-12",$comando,"POST","ballot");                    }else{            $this->view('error_permisao');        }    } public function incluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/ItenTrimestre/incluir/';                    if($acesso->acesso_valida($comando)==true){            $itentrimestre = new ItenTrimestreModel();                  $id=$itentrimestre->cadastrar_itentrimestre(                 array(                     'id_trimestre'=>$_POST['id_trimestre'], 'id_classe'=>$_POST['id_classe'], 'id_filial'=>$_POST['id_filial'], 'id_status'=>$_POST['id_status'], 'data_lancamento'=>date('Y-m-d H:i:s'),                )            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/ItenTrimestre/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function alterar(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/ItenTrimestre/alterar/';        if($acesso->acesso_valida($comando)==true){            $id = $_POST['id'];            $itentrimestre = new ItenTrimestreModel();                  $itentrimestre->alterar_itentrimestre(                array(                     'id_trimestre'=>$_POST['id_trimestre'], 'id_classe'=>$_POST['id_classe'], 'id_filial'=>$_POST['id_filial'], 'id_status'=>$_POST['id_status'], 'data_lancamento'=>date('Y-m-d H:i:s'),                ),'id='.$id            );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/ItenTrimestre/admin_listar/');            }else{            $this->view('error_permisao');        }    } public function excluir(){            $this->acesso_restrito();        $acesso = new AcessoHelper();         $logs = new LogsModel();        $comando='/ItenTrimestre/excluir/';        if($acesso->acesso_valida($comando)==true){            $id = $this->getParams('id');            $itentrimestre = new ItenTrimestreModel();                  $itentrimestre->excluir_itentrimestre( array( 'id_status'=>'99' ),'id='.$id );              $logs->cadastrar_logs($comando,$id);//Gera Logs            $redirect = new RedirectHelper();            $redirect->goToUrl('/ItenTrimestre/admin_listar/');            }else{            $this->view('error_permisao');        }    }  } ?> 