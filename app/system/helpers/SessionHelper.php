<?php
    class SessionHelper {
        public function createSession( $nome, $value){
            $_SESSION[$nome]= $value;
            return $this;
        }
        
        public function addItensSession($nome, $value) {
            // Verifica se a sessão já existe e se é um array, caso contrário, inicializa como um array vazio
            if (!isset($_SESSION[$nome]) || !is_array($_SESSION[$nome])) {
                $_SESSION[$nome] = []; // Inicializa como array se não existir
            }
        
            // Adiciona o valor à sessão
            array_push($_SESSION[$nome], $value); // Agora usamos $_SESSION[$nome]
            return $this;
        }
        public function selectSession( $nome){
            return $_SESSION[$nome];
            return $this;
        }
        
        public function deleteSession( $nome){
            unset ($_SESSION[$nome]);
            return $this;
        }
        
        public function checkSession( $nome ){
            return isset ($_SESSION[$nome]);        
        }
        
        public function uniqueSession($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();
        
            foreach($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        }
    }