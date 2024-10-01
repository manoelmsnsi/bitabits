<?php
 class MenuHelper {
    private $script;
    function __construct ($Titulo,$Class,$AcaoForm, $MetodoDeEnvio) {
     
    echo '<html lang="pt_br">
    <head>
       <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport"/>
       
        <link rel="icon" href="/web-files/sistema/imagens/favicon-white.png"/>
        <!--        == ESTILOS ==       -->
    
        <link rel="stylesheet" href="/web-files/sistema/css/material-dashboard.css"/>
        <link rel="stylesheet" href="/web-files/sistema/css/material-dashboard.css?v=2.1.0"/>       
        <link rel="stylesheet" href="/web-files/sistema/css/pg-restaurant.css"/>
        <link rel="stylesheet" href="/web-files/sistema/css/demo_admin.css"/>    
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" href="/web-files/sistema/css/style.css"/>
        <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
        
        <style>
       .select2-selection { overflow: hidden; }
       .select2-selection__rendered { white-space: normal; word-break: break-all; }
        </style>
        
      
       <title> '.$Titulo.' </title>       

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>

    </head>  
      
       ';
//$online = new UsuarioModel(); ?>    
<script>
 //    function funcaoJava(){
 //        let retornoPHP = '<?php // $online->usuarios_online(); ?>';
 //  }
</script>

  <?php 
    }
    function __destruct () {
//<script src="/web-files/sistema/js/demo.js"></script>
 
        echo '</div>        
        </div>'.$this->script='  
        <script src="/web-files/sistema/js/popper.min.js"></script>
        <script src="/web-files/sistema/js/bootstrap-material-design.min.js"></script>
        <script src="/web-files/sistema/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="/web-files/sistema/js/material-dashboard.js" type="text/javascript"></script>            
        <script src="/web-files/sistema/js/jquery.bootstrap_wizard_admin.js" type="text/javascript"></script>   
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

     <script>
     
     var elem = document.documentElement;
     function requestFullScreen() {
     alert("Para Sair do Modo Tela Cheia aperte ");
        if (elem.requestFullscreen) {
           elem.requestFullscreen();
         } else if (elem.webkitRequestFullscreen) { /* Safari */
           elem.webkitRequestFullscreen();
         } else if (elem.msRequestFullscreen) { /* IE11 */
           elem.msRequestFullscreen();
         }
        }

                var segX =59;

                var minX =9 ;

                function contagemRegressiva()  

               {          
                
                    if(segX == 0 & minX==0)

                    {
                        location.href="/Admin/logout/"

                    }               

                    segX--;

                    if (segX==0 && minX!=0){
                        
                         segX =59;

                        minX--;
                       
                         
                      funcaoJava();
                        
                    }
            
                    document.getElementById("cronometro").innerHTML = " "+minX+ " : " + segX ;

                }
           
                setInterval("contagemRegressiva()", 2000);



            </script>
            

        <!-- ==== FIM TIMER DO SISTEMA ====--> 

        

        <!-- ==== INICIA SCRIP PARA GRÁFICO ====--> 

         <script>

            $(document).ready(function() {

              // Javascript method"s body can be found in assets/assets-for-demo/js/demo.js

              demo.initCharts();

            });

          </script>

          
  
          <script>
          

            $(document).ready(function() {

               $(".select").select2({
                    width: "resolve" // need to override the changed default
                });

           });

           </script>

          <script>

            $(document).ready(function() {

               $(".select1").select2();

           });

           

function Imprimir(){

     var imprimir = document.querySelector("#imprimir");

     var voltar = document.querySelector("#voltar");

		    imprimir.onclick = function() {

		    	imprimir.style.display = "none";

                        voltar.style.display = "none"

		    	window.print();

                

		    	var time = window.setTimeout(function() {

		    		imprimir.style.display = "block";

                                voltar.style.display = "block";

		    	}, 1000);

		    }

 }

           </script>

';

    }

 

    function Menu ($pesquisa=null) {   

        $acesso = new SessionHelper();

        $Acessos=$acesso->selectSession('userAcesso');
        $userFilial=$acesso->selectSession('userFilial');
        $user=$acesso->selectSession('userData'); 
      //  print_r($userFilial); 
      $menu=' <div class="wrapper ">

            <div class="sidebar" data-color="rose" data-background-color="white" data-image="/web-files/sistema/imagens/bb.png">

                <div class="logo">

                    <a href="#" class="simple-text logo-mini"> <img src="/web-files/sistema/imagens/bb.png" width="33"/> </a>

                    <a href="#" class="simple-text logo-normal"><strong style="">'.substr($userFilial[0]["nome_fantasia"], 0,3)."</strong><strong style='color: #1eb6b1'>".substr($userFilial[0]["nome_fantasia"], 3).' </strong></a> 

                </div>'; 

    $menu.='<div class="sidebar-wrapper">

            <ul class="nav">';

        foreach ($Acessos AS $acesso): 
 
            if($acesso["id_programa"] == $acesso["id_pai"]){ 

               $menu.='

                    <li class="nav-item ">

                    <a class="nav-link"    data-toggle="tooltip" href="'.$acesso["comando"].'"> <i class="material-icons" style="color:'.$acesso["cor"].'">'.$acesso["icone"].'</i> <p >'.$acesso["descricao"].' </p> </a>

                </li>'; 

            }else{                        

                if($acesso["tipo"]=="PAI"){ 

                    $controle=$acesso["id_programa"];

                    $menu.='<li class="nav-item  ">

                            <a class="nav-link"  data-toggle="collapse" href="#'.$acesso["descricao"].'"> <i class="material-icons" style="color:'.$acesso["cor"].'">'.$acesso["icone"].'</i> <p >'.$acesso["descricao"].' <b class="caret"></b> </p> </a>

                            <div class="collapse" id="'.$acesso["descricao"].'">

                                <ul class="nav">';

                    foreach ($Acessos AS $acesso): 

                        if($acesso["tipo"]=="FILHO" && $acesso["id_pai"] == $controle ){

                            $menu.='  <li class="nav-item ">

                                            <a class="nav-link" href="'.$acesso["comando"].'"> <span class="sidebar-mini"><i class="material-icons" style="color:'.$acesso["cor"].'">'.$acesso["icone"].'</i> </span> <span class="sidebar-normal">'.$acesso["descricao"].' </span> </a>

                                        </li>      ';    

                        } 

                   endforeach;                  

                   $menu.= '</ul></div></li>';

                  }    

                }

        endforeach ;   

        $menu.=' </ul>

                </div>

                </div>

                <div class="main-panel">

                <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">

                    <div class="container-fluid">

                        <div class="navbar-wrapper">
                        


                        

                            <div class="navbar-minimize">

                                <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">

                                  <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>

                                  <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>

                                </button> 

                            </div>                                           

                        </div>
                        

                        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">

                            <span class="sr-only">Toggle navigation</span>

                            <span class="navbar-toggler-icon icon-bar"></span>

                            <span class="navbar-toggler-icon icon-bar"></span>

                            <span class="navbar-toggler-icon icon-bar"></span>

                        </button> 
                       

                     <div class="collapse navbar-collapse justify-content-end">
                        <form class="navbar-form" action="" method="POST">
                            <span class="bmd-form-group">
                            <div class="input-group no-border">
                            
                              <input type="text" name="pesquisa" value="'.$pesquisa.'" class="form-control" placeholder="Pesquisa...">
                              <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">search</i>
                                <div class="ripple-container"></div>
                              </button>
                              <select class="col-md-2 form-control" name="limit">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="9999999">Todos</option>
                            </select>
                            </div></span>
                        </form>

                        <i class="material-icons">timer</i> &nbsp;&nbsp;<div id="cronometro">10</div>

                        <ul class="navbar-nav">

                            <li class="nav-item">

                                <a class="nav-link" href="#"> <!--<i class="material-icons">dashboard</i>--> <p class="d-lg-none d-md-block"> Stats </p> </a>

                            </li>

                            <li class="nav-item dropdown">

                                <div class="card-avatar" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <a href="#"> 

                                        <img class="img" src="'.$user["src"].'" width="30" height="30" style="  border-radius: 50%;"/> 

                                        <span style="font-size:12px; color:grey; text-transform: uppercase;">'.$user["usuario"].'<b class="caret"></b></span>

                                    </a>

                                </div>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">

                                    <a class="dropdown-item" href="/Usuario/form/id/'.$user["id"].'"><i class="material-icons">face</i>&nbsp; &nbsp;   Pefil</a>

                                    <a class="dropdown-item" href="#"><i class="material-icons">settings</i>&nbsp;&nbsp;Configurações</a>
                                    <a class="dropdown-item" href="#" onclick="requestFullScreen();"><i class="material-icons">fullscreen</i>&nbsp;&nbsp;Tela Cheia</a>
                                  
                                  

                                    <!--<a class="dropdown-item" href="/Admin/bloquear/"><i class="material-icons">lock</i>&nbsp;&nbsp;Bloquear Tela</a>-->

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="/Admin/logout/"><i class="material-icons">power_settings_new</i>&nbsp;&nbsp;Sair</a>

                                </div>

                            </li>

                       </ul>

                     </div>

                   </div>

                </nav>


                

                

';

        return $menu;
      
    }
    public function chat($dados){
      
      
               $chat= '';
                
                return $chat;
    }

    

      function Script ($script) {

          

        $this->script=$script;;

    }

}

    



