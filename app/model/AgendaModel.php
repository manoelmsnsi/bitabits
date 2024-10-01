<?php
    class AgendaModel extends Model{
        public $_tabela='Agenda';

        public function  listar_Agenda($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$camposfrom=null,$group=null){
            return $this->read($join,$where, $limit, $offset,$orderby,$camposfrom,$group);                            
        }
        public function  cadastrar_Agenda(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_Agenda(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_Agenda(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }