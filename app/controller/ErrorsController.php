<?php
class Errors extends Controller {   
    
    public function index_action(){
        $this->error_404();
    }
    
    
    public function error_404(){  
        $acesso = new SessionHelper();
        $info_servidor=$acesso->selectSession("serverData");
        $info_usuario=$acesso->selectSession("userData");
        $dados["cliente"]=$info_servidor[0]["nome"];
       //echo $info_usuario["usuario"];       
      // echo $info_servidor[0]["nome"];
        $erro = $this->getParams("erro");
        $lc = base64_decode($this->getParams("LC"));
        $la = base64_decode($this->getParams("LA"));
        $dados["erro"]=$erro;
        $email = new EmailHelper();
        $variables = array(REMOTE_ADDR,
        HTTP_X_FORWARDED_FOR,
        HTTP_X_FORWARDED,
        HTTP_FORWARDED_FOR,
        HTTP_FORWARDED,
        HTTP_X_COMING_,
        HTTP_COMING_,
        HTTP_CLIENT_IP);
        foreach ($variables as $variable){ 
            if (isset($_SERVER[$variable])){
                $return.= $_SERVER[$variable];
                break;
            }
        }       
        $menssagem=$erro."\n".$return."\n LC[{$lc}] \n LA[{$la}]\n".date("Y-m-d H:i:s");
        //$email->enviar("erro@bitabits.com.br","error@bitabits.com.br","Cliente: ".$info_servidor[0]["nome"]."  ||  Funcionario: ".$info_usuario["usuario"], "ErroPagina","$menssagem");
       
        $this->view("error_404",$dados);
        
    }
    public function error_bloqueio(){  
       $acesso = new SessionHelper();
        $info_servidor=$acesso->selectSession("serverData");
        $info_usuario=$acesso->selectSession("userData");
         $dados["cliente"]=$info_servidor[0]["nome"];
        $erro = $this->getParams("erro");
        $lc = base64_decode($this->getParams("LC"));
        $la = base64_decode($this->getParams("LA"));
        $dados["erro"]=$erro;
        $email = new EmailHelper();
        $variables = array(REMOTE_ADDR,
        HTTP_X_FORWARDED_FOR,
        HTTP_X_FORWARDED,
        HTTP_FORWARDED_FOR,
        HTTP_FORWARDED,
        HTTP_X_COMING_,
        HTTP_COMING_,
        HTTP_CLIENT_IP);
        foreach ($variables as $variable){
            if (isset($_SERVER[$variable])){
                $return.= $_SERVER[$variable];
                break;
            }
        }
            $menssagem=$erro."\n".$return."\n LC[{$lc}] \n LA[{$la}]\n".date("Y-m-d H:i:s");
            //$email->enviar("logs_jiparana@bitabits.com.br","error@manoel.ro.gov.br", "Manoel", "Sistem Bloqueado","$menssagem");
      //  $email->enviar("erro@bitabits.com.br","error@bitabits.com.br","Cliente: ".$info_servidor[0]["nome"]."  ||  Funcionario: ".$info_usuario["usuario"], "Sistem Bloqueado","$menssagem");
        $this->view("error_bloqueio",$dados);
        
    }
    public function error_login(){  
        $acesso = new SessionHelper();
        $info_servidor=$acesso->selectSession("serverData");
        $info_usuario=$acesso->selectSession("userData");
        $dados["cliente"]=$info_servidor[0]["nome"];
        $erro = $this->getParams("erro");
        $lc = base64_decode($this->getParams("LC"));
        $la = base64_decode($this->getParams("LA"));
        $dados["erro"]=$erro;
        $email = new EmailHelper();
        $variables = array(REMOTE_ADDR,
        HTTP_X_FORWARDED_FOR,
        HTTP_X_FORWARDED,
        HTTP_FORWARDED_FOR,
        HTTP_FORWARDED,
        HTTP_X_COMING_,
        HTTP_COMING_,
        HTTP_CLIENT_IP);
        foreach ($variables as $variable){
            if (isset($_SERVER[$variable])){
                $return.= $_SERVER[$variable];
                break;
            }
        }
            $menssagem=$erro."\n".$return."\n LC[{$lc}] \n LA[{$la}]\n".date("Y-m-d H:i:s");
            //$email->enviar("logs_jiparana@bitabits.com.br","error@manoel.ro.gov.br", "Manoel", "ErroPagina","$menssagem");
          //  $email->enviar("erro@bitabits.com.br","error@bitabits.com.br","Cliente: ".$info_servidor[0]["nome"]."  ||  Funcionario: ".$info_usuario["usuario"], "Sistem Bloqueado","$menssagem");
        
     $this->view("form_login_error",$dados);
        
    }
}
        