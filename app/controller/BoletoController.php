<?php class Boleto extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    }  
     public function incluirBoleto() {
        
    }
    public function listar_boleto() {
        $this->acesso_restrito();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);
        echo $menu->Menu();
        $form = new FormularioHelper();
        $numeroContrato="112763";
        $modalidade="1";
        $nossoNumero="6-9";
        $code = $this->getParams("code");
        echo "".$code;
        $token="910a58e0-ee99-3759-8f9c-392812c17ab4";
        $token="537cd780-ec04-3648-9e42-bf291473d1e6";
        $cooperativa="0001";
        $client_id="A3noVUa8aSvRZE389cIYdCmBr1Ea";
        $inputs1.= $form->Button("btn btn-md btn-rose ", "autenticar");
        $inputs2.= $form->Button("btn btn-md btn-rose ", "Gerar");
      //  $form->card("Gerar Boleto", $inputs1, "col-md-12", "https://sandbox.sicoob.com.br/oauth2/authorize?response_type=code&redirect_uri=https://develop.bitabits.com.br/Boleto/listar_boleto&client_id=A3noVUa8aSvRZE389cIYdCmBr1Ea", "GET", "monetization_on"); //autenticar
        $form->card("Gerar Boleto", $inputs2, "col-md-12", "https://sandbox.sicoob.com.br/cobranca-bancaria/v1/boletos/pagadores/32800402000167?code=$code&numeroContrato=112763&codigoSituacao=1&dataInicio=2021-01-01&dataFim=2021-02-01", "GET", "monetization_on");
   
      
   
        }
 } ?> 