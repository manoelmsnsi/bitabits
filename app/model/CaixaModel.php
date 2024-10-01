<?php
    class CaixaModel extends Model{
        public $_tabela='Caixa';

        public function  listar_Caixa($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group);                            
        }
        public function  cadastrar_Caixa(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Caixa(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Caixa(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }