<?php
    class LicaoModel extends Model{
        public $_tabela='Licao';

        public function  listar_Licao($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            
        }
        public function  cadastrar_Licao(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Licao(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Licao(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }