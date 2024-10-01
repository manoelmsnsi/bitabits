<?php

    class UsuarioModel extends Model{

        public $_tabela='Usuario';



        public function  listar_usuario($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null,$pesquisa=null){

            return $this->read($join,$where,$limit,$offset,$orderby,$from,$group,$pesquisa);

        }



        public function  cadastrar_usuario(array $dados){

            return $this->insert($dados);                            

        }

        public function  alterar_usuario(array $dados,$where){

            return $this->update($dados, $where);                            

        }

        public function  excluir_usuario(array $dados,$where){

            return $this->update($dados, $where);                            

        }
        
            public function usuarios_online($from=null) {
              $session= new SessionHelper();
              $logado = $session->selectSession("userData");       
              $logado = $logado["id"];
              $data['atual'] = date('Y-m-d H:i:s');        
                
              $this->alterar_usuario(array("online"=>$data['atual']),"id=$logado" );
              $data['online'] = strtotime($data['atual'] . " - 20 seconds");
      	      $data['online'] = date("Y-m-d H:i:s",$data['online']);
              return $this->listar_usuario($join, $limit, "online >= '" . $data['online'] . "'", $offset, $orderby, $from, $group, $pesquisa);
              
          }

    }