<?php
    class ItensTrimestreModel extends Model{
        public $_tabela='ItensTrimestre';

        public function  listar_ItensTrimestre($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            
        }
        public function  cadastrar_ItensTrimestre(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_ItensTrimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_ItensTrimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }