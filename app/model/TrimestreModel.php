<?php
    class TrimestreModel extends Model{
        public $_tabela='Trimestre';

        public function  listar_Trimestre($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group);                            
        }
        public function  cadastrar_Trimestre(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Trimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Trimestre(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }