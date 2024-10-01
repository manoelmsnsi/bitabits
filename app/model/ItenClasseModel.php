<?php
    class ItenClasseModel extends Model{
        public $_tabela='ItenClasse';

        public function  listar_ItenClasse($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            
        }
        public function  cadastrar_ItenClasse(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_ItenClasse(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_ItenClasse(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }