<?php
    class ClasseModel extends Model{
        public $_tabela='Classe';

        public function  listar_Classe($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            
        }
        public function  cadastrar_Classe(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Classe(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Classe(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }