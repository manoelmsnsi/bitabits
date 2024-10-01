<?php
    class ItensModel extends Model{
        public $_tabela='Itens';

        public function  listar_Itens($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$camposfrom=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$camposfrom,$group);                            
        }
        public function  cadastrar_Itens(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Itens(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Itens(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }