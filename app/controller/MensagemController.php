<?php class Mensagem extends Controller {   

    private  $auth,$db;

    public function acesso_restrito(){         
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    } 
 public function admin_listar_old(){
        $this->acesso_restrito();
        $acesso = new AcessoHelper(); 
        $logs = new LogsModel();
        $comando='/Mensagem/admin_listar/';
       // if($acesso->acesso_valida($comando)==true){ 
            $filiais=$acesso->acesso_filial(__CLASS__);
            $acesso = new SessionHelper();
            if(empty($_POST['pesquisa'])){ $pesquisa=null; }else{ $pesquisa=$_POST['pesquisa']; }
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();                  
            $form = new FormularioHelper();                 
            $mensagem = new MensagemModel();
            $usuario= new UsuarioModel();
            
            $inputs.= $form->Listar(
                    'col-md-12', null, '/'.__CLASS__.'/form/',$icone,$usuario->listar_usuario(
                        $join, 
                        $limit, 
                        "Usuario.id_status<>'99'", 
                        $offset,
                        $orderby,
                        "Usuario.id,Usuario.usuario AS 'Nome'", 
                        $group,
                        $pesquisa)
                        ,"tabela1",
                    array(
                      //  array('acao'=>'/'.__CLASS__.'/form/','classe'=>'btn-sm btn-warning','icone'=>'edit'),
                        array('acao'=>'/'.__CLASS__.'/form/','classe'=>'btn-sm btn-warning','icone'=>'edit'),
                       // array('acao'=>'/Upload/form/tabela/Mensagem','classe'=>'btn-sm btn-rose','icone'=>'cloud_upload'),
                   //     array('acao'=>'/Logs/form/','classe'=>'btn-sm btn-danger','icone'=>'close')
                        )
                );     
            
            $logs->cadastrar_logs($comando,'0');//Gera Logs
            $form->card(__CLASS__,$inputs,'col-md-12',$comando,'POST','speaker_notes');

     //   }else{
     //       $this->view('error_permisao');
     //   }
    } 
    
 public function admin_listar(){ 

        $this->acesso_restrito();
        $acesso = new AcessoHelper();    
        $comando='/'.__CLASS__.'/incluir/';
        echo '<meta http-equiv="refresh" content="30">
        <style>       
            .container{max-width:100%; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background-image: url("/web-files/sistema/imagens/bb.png");
  background-repeat: no-repeat, repeat;
    background-color: rgba(0,0,0,0.8);
      background-size: cover;
      background-size: contain;
  float: left;
  overflow: hidden;
  color:red;
  width: 25%; border-right:1px solid #c4c4c4;
}
    .testt{
      position: relative;
        width: 100%;
        height: 100%;
        background: #000000;
        overflow: hidden;
        opacity: 0.8;
    }


.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 75%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #white;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: white;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:white; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#fff; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
color:white
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list { 
  border-bottom: 1px solid #8474A1 transparent;
  color:white;
  margin: 0;
  padding: 18px 16px 10px;
 transition: all .5s;

}
.chat_list:hover{
   
   opacity:0.5
   }
.inbox_chat { height: 100%px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.sent_msg_img {
  float: right;
  width: 6%;
}
.received_msg {

  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #435f7a none repeat scroll 0 0;
  border-radius: 3px;
  color: #fff;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
  white-space: normal
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { 

width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 75%;
  height:100%
}

 .sent_msg p {
  background: #f5f5f5 none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:black;
  padding: 5px 10px 5px 12px;
  width:100%;
  white-space: normal
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #2c3e50;
  font-size: 15px;
  min-height: 80px;
  width: 100%;
   white-space: normal
}

.type_msg {width:60%border-top: 1px solid black ;position: relative;}
.msg_send_btn {
  background: #2c3e50 none repeat scroll 0 0; //scroll chat
  border: medium none;
  border-radius: 50%;
  color: white;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}

.msg_history {
  height:700px;
  overflow-y: auto;
}
::-webkit-scrollbar {
    width: 12px;
}
  
::-webkit-scrollbar-thumb {
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: white; 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
        




#frame #sidepanel {
  float: left;
  
  width: 100%;
  height: 700px;

  color: #f5f5f5;
  overflow: hidden;
  position: auto;
  opacity:1;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel {
    width: 100%;
    min-width: 58px;
  }
}
#frame #sidepanel #profile {
  width: 100%;
  margin: 25px auto;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile {
    width: 100%;
    margin: 0 auto;
    padding: 5px 0 0 0;
    background: #32465a;
  }
}
#frame #sidepanel #profile.expanded .wrap {
  height: 100%;
  line-height: initial;
}
#frame #sidepanel #profile.expanded .wrap p {
  margin-top: 20px;
}
#frame #sidepanel #profile.expanded .wrap i.expand-button {
  -moz-transform: scaleY(-1);
  -o-transform: scaleY(-1);
  -webkit-transform: scaleY(-1);
  transform: scaleY(-1);
  filter: FlipH;
  -ms-filter: "FlipH";
}
#frame #sidepanel #profile .wrap {
  height: 60px;
  line-height: 60px;
  overflow: hidden;
  -moz-transition: 0.3s height ease;
  -o-transition: 0.3s height ease;
  -webkit-transition: 0.3s height ease;
  transition: 0.3s height ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap {
    height: 55px;
  }
}
#frame #sidepanel #profile .wrap img {
  width: 50px;
  border-radius: 50%;
  padding: 3px;
  border: 2px solid #e74c3c;
  height: auto;
  float: left;
  cursor: pointer;
  -moz-transition: 0.3s border ease;
  -o-transition: 0.3s border ease;
  -webkit-transition: 0.3s border ease;
  transition: 0.3s border ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap img {
    width: 40px;
    margin-left: 4px;
  }
}
#frame #sidepanel #profile .wrap img.online {
  border: 2px solid #2ecc71;
}
#frame #sidepanel #profile .wrap img.away {
  border: 2px solid #f1c40f;
}
#frame #sidepanel #profile .wrap img.busy {
  border: 2px solid #e74c3c;
}
#frame #sidepanel #profile .wrap img.offline {
  border: 2px solid #95a5a6;
}
#frame #sidepanel #profile .wrap p {
  float: left;
  margin-left: 15px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap p {
    display: none;
  }
}
#frame #sidepanel #profile .wrap i.expand-button {
  float: right;
  margin-top: 23px;
  font-size: 0.8em;
  cursor: pointer;
  color: #435f7a;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap i.expand-button {
    display: none;
  }
}
#frame #sidepanel #profile .wrap #status-options {
  position: absolute;
  opacity: 0;
  visibility: hidden;
  width: 150px;
  margin: 70px 0 0 0;
  border-radius: 6px;
  z-index: 99;
  line-height: initial;
  background: #435f7a;
  -moz-transition: 0.3s all ease;
  -o-transition: 0.3s all ease;
  -webkit-transition: 0.3s all ease;
  transition: 0.3s all ease;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options {
    width: 58px;
    margin-top: 57px;
  }
}
#frame #sidepanel #profile .wrap #status-options.active {
  opacity: 1;
  visibility: visible;
  margin: 75px 0 0 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options.active {
    margin-top: 62px;
  }
}


@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
    height: 18px;
    width: 18px;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li p {
  padding-left: 12px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #profile .wrap #status-options ul li p {
    display: none;
  }
}
#frame #sidepanel #profile .wrap #status-options ul li#status-online span.status-circle {
  background: #2ecc71;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-online.active span.status-circle:before {
  border: 1px solid #2ecc71;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-away span.status-circle {
  background: #f1c40f;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-away.active span.status-circle:before {
  border: 1px solid #f1c40f;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-busy span.status-circle {
  background: #e74c3c;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-busy.active span.status-circle:before {
  border: 1px solid #e74c3c;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-offline span.status-circle {
  background: #95a5a6;
}
#frame #sidepanel #profile .wrap #status-options ul li#status-offline.active span.status-circle:before {
  border: 1px solid #95a5a6;
}
#frame #sidepanel #profile .wrap #expanded {
  padding: 100px 0 0 0;
  display: block;
  line-height: initial !important;
}
#frame #sidepanel #profile .wrap #expanded label {
  float: left;
  clear: both;
  margin: 0 8px 5px 0;
  padding: 5px 0;
}
#frame #sidepanel #profile .wrap #expanded input {
  border: none;
  margin-bottom: 6px;
  background: #32465a;
  border-radius: 3px;
  color: #f5f5f5;
  padding: 7px;

}
#frame #sidepanel #profile .wrap #expanded input:focus {
  outline: none;
  background: #435f7a;
}
#frame #sidepanel #search {
  border-top: 1px solid #32465a;
  border-bottom: 1px solid #32465a;
  font-weight: 300;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #search {
    display: none;
  }
}
#frame #sidepanel #search label {
  position: absolute;
  margin: 10px 0 0 20px;
}
#frame #sidepanel #search input {
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
  padding: 10px 0 10px 46px;
  width: calc(100% - 25px);
  border: none;
  background: #32465a;
  color: #f5f5f5;
}
#frame #sidepanel #search input:focus {
  outline: none;
  background: #435f7a;
}
#frame #sidepanel #search input::-webkit-input-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input::-moz-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input:-ms-input-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #search input:-moz-placeholder {
  color: #f5f5f5;
}
#frame #sidepanel #contacts {
  height: calc(100% - 50px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts {
   
    overflow-y: scroll;
    overflow-x: hidden;
  }
  #frame #sidepanel #contacts::-webkit-scrollbar {
    display: none;
  }
}
#frame #sidepanel #contacts.expanded {
  height: calc(100% - 334px);
}
#frame #sidepanel #contacts::-webkit-scrollbar {
  width: 8px;
     background-color: rgba(0,0,0,0.8);
}
#frame #sidepanel #contacts::-webkit-scrollbar-thumb {
      background-color: rgba(255,255,255,0.2); //scroll Contatos
}
#frame #sidepanel #contacts ul li.contact {
  position: relative;
  padding: 10px 0 15px 0;
  font-size: 0.9em;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact {
    padding: 6px 0 46px 8px;
  }
}
#frame #sidepanel #contacts ul li.contact:hover {
     background-color: rgba(255,255,255,0.3);
}
#frame #sidepanel #contacts ul li.contact.active {
background-color: rgba(255,255,255,0.3);
  border-right: 10px solid white;
}
#frame #sidepanel #contacts ul li.contact.active span.contact-status {
  border: 2px solid #32465a !important;
}
#frame #sidepanel #contacts ul li.contact .wrap {
  width: 88%;
  margin: 0 auto;
  position: relative;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap {
    width: 100%;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap span {
  position: absolute;
  left: 0;
  margin: -2px 0 0 -2px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: 2px solid #2c3e50;
  background: #95a5a6;
}
#frame #sidepanel #contacts ul li.contact .wrap span.online {
  background: #2ecc71;
}
#frame #sidepanel #contacts ul li.contact .wrap span.away {
  background: #f1c40f;
}
#frame #sidepanel #contacts ul li.contact .wrap span.busy {
  background: #e74c3c;
}
#frame #sidepanel #contacts ul li.contact .wrap img {
  width: 40px;
  border-radius: 50%;
  float: left;
  margin-right: 10px;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap img {
    margin-right: 0px;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap .meta {
  padding: 5px 0 0 0;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #contacts ul li.contact .wrap .meta {
    display: none;
  }
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .name {
  font-weight: 600;
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .preview {
  margin: 5px 0 0 0;
  padding: 0 0 1px;
  font-weight: 400;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  -moz-transition: 1s all ease;
  -o-transition: 1s all ease;
  -webkit-transition: 1s all ease;
  transition: 1s all ease;
}
#frame #sidepanel #contacts ul li.contact .wrap .meta .preview span {
  position: initial;
  border-radius: initial;
  background: none;
  border: none;
  padding: 0 2px 0 0;
  margin: 0 0 0 1px;
  opacity: .5;
}
#frame #sidepanel #bottom-bar {
  position: absolute;
  width: 100%;
  bottom: 0;
}
#frame #sidepanel #bottom-bar button {
  float: left;
  border: none;
  width: 50%;
  padding: 10px 0;
  background: #32465a;
  color: #f5f5f5;
  cursor: pointer;
  font-size: 0.85em;
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button {
    float: none;
    width: 100%;
    padding: 15px 0;
  }
}
#frame #sidepanel #bottom-bar button:focus {
  outline: none;
}
#frame #sidepanel #bottom-bar button:nth-child(1) {
  border-right: 1px solid #2c3e50;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button:nth-child(1) {
    border-right: none;
    border-bottom: 1px solid #2c3e50;
  }
}
#frame #sidepanel #bottom-bar button:hover {
  background: #435f7a;
}
#frame #sidepanel #bottom-bar button i {
  margin-right: 3px;
  font-size: 1em;
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button i {
    font-size: 1.3em;
  }
}
@media screen and (max-width: 735px) {
  #frame #sidepanel #bottom-bar button span {
    display: none;
  }
}
#frame .content {
  float: right;
  width: 60%;
  height: 100%;
  overflow: hidden;
  position: relative;
}
@media screen and (max-width: 735px) {
  #frame .content {
    width: calc(100% - 58px);
    min-width: 300px !important;
  }
}
@media screen and (min-width: 900px) {
  #frame .content {
    width: calc(100% - 340px);
  }
}
#frame .content .contact-profile {
  width: 100%;
  height: 60px;
  line-height: 60px;
  background: #f5f5f5;
}
#frame .content .contact-profile img {
  width: 40px;
  border-radius: 50%;
  float: left;
  margin: 9px 12px 0 9px;
}
#frame .content .contact-profile p {
  float: left;
}
#frame .content .contact-profile .social-media {
  float: right;
}
#frame .content .contact-profile .social-media i {
  margin-left: 14px;
  cursor: pointer;
}
#frame .content .contact-profile .social-media i:nth-last-child(1) {
  margin-right: 20px;
}
#frame .content .contact-profile .social-media i:hover {
  color: #435f7a;
}
#frame .content .messages {
  height: auto;
  min-height: calc(100% - 93px);
  max-height: calc(95% - 93px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  #frame .content .messages {
    max-height: calc(100% - 105px);
  }
}
#frame .content .messages::-webkit-scrollbar {
  width: 8px;
  background: transparent;
}
#frame .content .messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.3);
}
#frame .content .messages ul li {
  display: inline-block;
  clear: both;
  float: left;
  margin: 15px 15px 5px 15px;
  width: calc(100% - 25px);
  font-size: 0.9em;
}
#frame .content .messages ul li:nth-last-child(1) {
  margin-bottom: 20px;
}
#frame .content .messages ul li.sent img {
  margin: 6px 8px 0 0;
}
#frame .content .messages ul li.sent p {
  background: #435f7a;
  color: #f5f5f5;
}
#frame .content .messages ul li.replies img {
  float: right;
  margin: 6px 0 0 8px;
}
#frame .content .messages ul li.replies p {
  background: #f5f5f5;
  float: right;
}
#frame .content .messages ul li img {
  width: 100%;
  border-radius: 50%;
  float: left;
}
#frame .content .messages ul li p {
  display: inline-block;
  padding: 10px 15px;
  border-radius: 20px;
  max-width: 205px;
  line-height: 100%;
}
@media screen and (min-width: 735px) {
  #frame .content .messages ul li p {
    max-width: 300px;
  }
}
#frame .content .message-input {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 99;
}
#frame .content .message-input .wrap {
  position: relative;
}
#frame .content .message-input .wrap input {
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
  float: left;
  border: none;
  width: calc(100% - 90px);
  padding: 11px 32px 10px 8px;
  font-size: 0.8em;
  color: #32465a;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap input {
    padding: 15px 32px 16px 8px;
  }
}
#frame .content .message-input .wrap input:focus {
  outline: none;
}
#frame .content .message-input .wrap .attachment {
  position: absolute;
  right: 60px;
  z-index: 4;
  margin-top: 10px;
  font-size: 1.1em;
  color: #435f7a;
  opacity: .5;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap .attachment {
    margin-top: 17px;
    right: 65px;
  }
}
#frame .content .message-input .wrap .attachment:hover {
  opacity: 1;
}
#frame .content .message-input .wrap button {
  float: right;
  border: none;
  width: 50px;
  padding: 12px 0;
  cursor: pointer;
  background: #32465a;
  color: #f5f5f5;
}

</style>';
        
             $acesso = new SessionHelper();
            $dados_usuario=$acesso->selectSession('userData');
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
         //   echo $menu->Menu();                 
            $usuario = new UsuarioModel();
            $status = new StatusModel();
            $mensagem = new MensagemModel();
            $id = $this->getParams('id');
          
            $id_origem = $dados_usuario["id"];
      
            $nome_form='Cadastra Mensagem';
           // print_r($dados_usuario);
            
           
                $model = new Model();
                $model->_tabela='Usuario';
                $usuario_dados=$model->read("INNER JOIN Upload ON Upload.id_tabela=Usuario.id ",  "Usuario.id_status<>'99'AND( Upload.tabela='Usuario')AND Upload.id_status<>'99' ", $limit,$offset, $orderby, "Usuario.id,Usuario.usuario, Upload.src,(select count( Mensagem.id) FROM Mensagem WHERE  Mensagem.id_origem=Usuario.id AND Mensagem.data_visualizacao='0000-00-00 00:00:00') AS New", "Usuario.id", $pesquisa);           
                $model->_tabela='Mensagem';
                if($model->update(array("data_visualizacao"=>date("Y-m-d H:m:s")), "id_origem='$id' AND id_destino='$id_origem' AND data_visualizacao='0000-00-00 00:00:00'")){
                        $model->_tabela='Usuario';
                        $usuario_dados=$model->read("INNER JOIN Upload ON Upload.id_tabela=Usuario.id ",  "Usuario.id_status<>'99'AND( Upload.tabela='Usuario')AND Upload.id_status<>'99' ", $limit,$offset, $orderby, "Usuario.id,Usuario.usuario, Upload.src,(select count( Mensagem.id) FROM Mensagem WHERE  Mensagem.id_origem=Usuario.id AND Mensagem.data_visualizacao='0000-00-00 00:00:00') AS New", "Usuario.id", $pesquisa);           
                }             
                 $model->_tabela='Mensagem'; 
               
                 $mensagem_dados = $model->read("INNER JOIN Usuario AS Origem ON Origem.id = Mensagem.id_origem
                                                INNER JOIN Usuario AS Destino ON Destino.id = Mensagem.id_destino 
                                                INNER JOIN Upload ON Upload.id_tabela=Origem.id", 
                        "((id_destino='$id' AND id_origem='$id_origem')OR(id_origem='$id' AND id_destino='$id_origem' ))AND Upload.tabela='Usuario'AND (Upload.id_tabela='$id'OR Upload.id_tabela='$id_origem') AND Upload.id_status='1'",
                        $limit, $offset, "Mensagem.id DESC", "Origem.usuario AS remetente,Destino.usuario AS destinatario,Mensagem.mensagem,Mensagem.id_origem,Mensagem.id_destino,Mensagem.data_lancamento,Upload.src AS origem_src", $group, $pesquisa);
          
        $inputs.= '
              


<div class=" col-md-12 ">
<h4 class=" text-center">.</h4>
<p class="text-center top_spac"> </p> 

      <div class="inbox_msg ">
        <div class="inbox_people" >
         
            <div id="frame">
            <div class=""id="sidepanel"style="widht:100%;height:800px" >
             <div id="contacts">
              <ul  class="testt"style="list-style-type: none;  padding: 10">';
                foreach ($usuario_dados AS $chat):
                    if($id==$chat["id"]){ $active="active"; }else{$active="";}
                    if($chat["id"]==$dados_usuario["id"]){ 
                   $inputs.='  <a style="color:white" href="/Mensagem/admin_listar/id/'.$chat["id"].'/">
                           <div id="profile" >
                              <li class="contact '.$active.'">                     
                      					<div class="wrap">
                      						<img style="width:60px;height:60px" src="'.$chat["src"].'"class="online" alt="" />
                      						<div class="meta">
                      							<p class="name">'.$chat["usuario"].'</p>
                      						</div>   
                      					</div>
                      				</li>
                                  <hr>
                            </div></a> ';
                            }else{
                             $inputs.='<a style="color:white" href="/Mensagem/admin_listar/id/'.$chat["id"].'/">
                             <li class="contact '.$active.'">
                    					<div class="wrap">
                    						<img style="width:60px;height:60px" src="'.$chat["src"].'" alt="" />
                    						<div class="meta">
                    							<p class="name"><span class="new busy">'.$chat["New"].'</span>'.$chat["usuario"].'</p>
                    							<p class="preview">'.$dados_usuario["inicio_funcionamento"]." AS ".$dados_usuario["fim_funcionamento"].'</p>
                                </div>
                    					</div>
                    				</li>  
                </a>';
         } endforeach;
              
           $inputs.='</ul>
           </div>
          </div>
          </div>
        </div>
        
        <div class="mesgs ">
          <div class="msg_history">
          
            
            ' ;
        foreach ($mensagem_dados AS $mensagens):
                if($mensagens["id_origem"]==$dados_usuario["id"]){
                     $inputs.='
                          
                                <div class="outgoing_msg">
                         
                         <div class="sent_msg_img"> <img style="border-radius: 50%" width="80px" height="40px"  src="'.$mensagens["origem_src"].'" alt="sunil"> </div> 
                            <div class="sent_msg">                          
                              <p>'.$mensagens["mensagem"].'</p>
                              <span class="time_date">'.substr($mensagens["data_lancamento"],11,10).'    |    '.substr($mensagens["data_lancamento"],0,11).'</span> 
                            </div>
                            
                        </div>';
                    }else{
                $inputs.= '<div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="'.$mensagens["origem_src"].'" alt="sunil"> </div>
                          <div class="received_msg">
                            <div class="received_withd_msg">
                               <p> '.$mensagens["mensagem"].'</p>
                                <span class="time_date">'.substr($mensagens["data_lancamento"],11,10).'    |    '.substr($mensagens["data_lancamento"],0,11).'</span> 
                            </div>
                          </div>
                      </div>';
                }
            endforeach;    
             $inputs.='              
          </div>	
         <form action="'.$comando.'" method="POST">          
            <div class="type_msg">
               <input type="hidden" name="id_destino" value="'.$id.'" class="" />
               <input type="hidden" name="id_origem"  value="'.$id_origem.'" class=""  />
                <div class="input_msg_write">             
                  <input type="text" required name="mensagem" class="write_msg" placeholder="Digite a Mensagem" />
                  <button class="msg_send_btn" type="submit"><i class="fa material-icons" aria-hidden="true">send</i></button>
                </div>            
            </div>
          </form>
      </div>
      </div>
        </div>
      
      
      
   
    </div>
   ';
        
   $form = new FormularioHelper();
  echo$inputs;
 //   $form->card("Chat", $inputs, "col-md-10", $acao_formulario, $metodo_envio, "send");
        
     
    }
    public function teste(){
        $acesso = new SessionHelper();
            $dados_usuario=$acesso->selectSession('userData');
            $menu = new MenuHelper('Bit a Bits', $Class, $AcaoForm, $MetodoDeEnvio);        
            echo $menu->Menu();                 
            $usuario = new UsuarioModel();
            $status = new StatusModel();
            $mensagem = new MensagemModel();
            $id = $this->getParams('id');
            $id_origem = $dados_usuario["id"];
            //sprint_r($dados_usuario);
            $dados["usuario"]=$dados_usuario["usuario"];
            $dados["usuario_imagem"]=$dados_usuario["src"];
            $nome_form='Cadastra Mensagem';
            //print_r($dados_usuario);
                    $dados["id"]=$id;
            $dados["id_origem"]=$id_origem;
           
                $model = new Model();
                $model->_tabela='Usuario';
                $dados["listar_usuario"]=$model->read("INNER JOIN Upload ON Upload.id_tabela=Usuario.id",  "Usuario.id_status<>'99'AND( Upload.tabela='Usuario') ", $limit,$offset, $orderby, "Usuario.id,Usuario.usuario, Upload.src", "Usuario.id", $pesquisa);
            
                $model->_tabela='Mensagem';
               $dados["listar_mensagem"]=  $model->read("INNER JOIN Usuario AS Origem ON Origem.id = Mensagem.id_origem
                                                INNER JOIN Usuario AS Destino ON Destino.id = Mensagem.id_destino 
                                                INNER JOIN Upload ON Upload.id_tabela=Origem.id", 
                        "((id_destino='$id' AND id_origem='$id_origem')OR(id_origem='$id' AND id_destino='$id_origem' ))AND Upload.tabela='Usuario'AND (Upload.id_tabela='$id'OR Upload.id_tabela='$id_origem')",
                        $limit, $offset, "Mensagem.id DESC", "Origem.usuario AS remetente,Destino.usuario AS destinatario,Mensagem.mensagem,Mensagem.id_origem,Mensagem.id_destino,Mensagem.data_lancamento,Upload.src AS origem_src", $group, $pesquisa);
                        
        $this->view("chat",$dados); 
    }
 public function incluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Mensagem/incluir/';

            

       // if($acesso->acesso_valida($comando)==true){



            $mensagem = new MensagemModel();      

            $id=$mensagem->cadastrar_mensagem( 

                array(

                     'id_origem'=>$_POST['id_origem'],
 'id_destino'=>$_POST['id_destino'],
 'mensagem'=>$_POST['mensagem'],
 'id_status'=>"1",
 'data_lancamento'=>date("Y-m-d H:m:s"),

                )

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Mensagem/admin_listar/id/'.$_POST['id_destino']);    

     //   }else{

       //     $this->view('error_permisao');

      //  }

    }
 public function alterar(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Mensagem/alterar/';

        if($acesso->acesso_valida($comando)==true){

            $id = $_POST['id'];



            $mensagem = new MensagemModel();      

            $mensagem->alterar_mensagem(

                array(

                     'id_origem'=>$_POST['id_origem'],
 'id_destino'=>$_POST['id_destino'],
 'mensagem'=>$_POST['mensagem'],
 'id_status'=>$_POST['id_status'],


                ),'id='.$id

            );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Mensagem/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }



    }
 public function excluir(){    

        $this->acesso_restrito();

        $acesso = new AcessoHelper(); 

        $logs = new LogsModel();

        $comando='/Mensagem/excluir/';

        if($acesso->acesso_valida($comando)==true){

            $id = $this->getParams('id');

            $mensagem = new MensagemModel();      

            $mensagem->excluir_mensagem( array( 'id_status'=>'99' ),'id='.$id );  

            $logs->cadastrar_logs($comando,$id);//Gera Logs

            $redirect = new RedirectHelper();

            $redirect->goToUrl('/Mensagem/admin_listar/');    

        }else{

            $this->view('error_permisao');

        }

    } 
 } ?> 