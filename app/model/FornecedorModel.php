<?php
    class FornecedorModel extends Model{
        public $_tabela='Fornecedor';

        public function  listar_Fornecedor($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from);                            
        }
        public function  cadastrar_Fornecedor(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Fornecedor(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Fornecedor(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }