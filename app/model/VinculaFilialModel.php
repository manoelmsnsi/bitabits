<?php
        class VinculaFilialModel extends Model{
        public $_tabela='VinculaFilial';

        public function  listar_VinculaFilial($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from);                            
        }
        public function  cadastrar_VinculaFilial(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_VinculaFilial(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_VinculaFilial(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }