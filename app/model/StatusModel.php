<?php
    class StatusModel extends Model{
        public $_tabela='Status';

        public function  listar_Status($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from);                            
        }
        public function  cadastrar_Status(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Status(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Status(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }