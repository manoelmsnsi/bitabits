<?php
    class UploadModel extends Model{
        public $_tabela='Upload';

        public function  listar_Upload($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$camposfrom=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$camposfrom);                            
        }
        public function  cadastrar_Upload(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Upload(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Upload(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }