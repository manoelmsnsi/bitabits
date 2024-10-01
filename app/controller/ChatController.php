<?php
class Chat extends Controller {
  private  $auth,$db;

    public function acesso_restrito(){          

        $this->auth = new AutenticaHelper();

        $this->auth->setLoginControllerAction('Index','')

                   ->checkLogin('redirect');              

        $this->db = new AdminModel(); 

    }  


  public function admin_listar(){
    $this->acesso_restrito();
    $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
    echo $menu->Menu();  
    
    echo' 
    <style>
    .test{
   position: relative;
   width: 100%;
    }

    </style>

              <iframe class="test" name="test"
                src="/Mensagem/admin_listar/"
                width="100%"
                height="100%"
                frameborder="0"
              >
              </iframe>
            ';
  }
}