<?php
    class ItenTrimestreModel extends Model{
        public $_tabela='ItenTrimestre';

        public function  listar_ItenTrimestre($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            
        }
        public function  cadastrar_ItenTrimestre(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_ItenTrimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_ItenTrimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }