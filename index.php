<?php 

    session_start(); 
    define("CONTROLLERS","app/controller/");
    define("VIEWS","app/view/");
    define("MODELS","app/model/");
    define('HELPERS','app/system/helpers/');
    define("IMAGENS","web-files/imagens/");
    
    require_once ("./app/system/system.php");
    require_once ("./app/system/controller.php"); 
    require_once ("./app/system/model.php");
    
    spl_autoload_register(function($file) {
        if (file_exists(MODELS . $file . ".php")) {
            require_once(MODELS . $file . ".php");            
        } else if (file_exists(HELPERS . $file . ".php")) {
            require_once(HELPERS . $file . ".php");             
        } else {
            echo(HELPERS . $file . ".php") . "<br>";
            die("ERRO 404, Model ou Helper Não Encontrado!");
        }
    });
    
    $start = new System;
    $start->run();
?>  
