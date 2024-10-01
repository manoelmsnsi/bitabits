<?php
class Index extends Controller {   
    public function index_action(){ 
        
        $this->site();        
    }    
    
    public function site (){   
        $session = new SessionHelper();
        $login_imagem = new UploadModel();
        $login_logo=$login_imagem->listar_Upload($join, $limit, "tabela='Login' OR tabela='Sistema'", $offset, $orderby, "tipo,src");
        foreach ($login_logo as $logos):
            if($logos["tipo"]=="SM"){ $dados["login_sm"]=$logos["src"]; };
            if($logos["tipo"]=="MD"){ $dados["login_md"]=$logos["src"];};
            if($logos["tipo"]=="BACKGROUND"){ $dados["login_background"]=$logos["src"];};
        endforeach;
        $this->view("form_login",$dados);            
    }   
}
        