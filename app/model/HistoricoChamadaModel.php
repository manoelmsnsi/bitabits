<?php
    class HistoricoChamadaModel extends Model{
        public $_tabela='HistoricoChamada';

        public function  listar_HistoricoChamada($join=null,$limit=null,$where=null,$offset=null,$orderby=null){
            return $this->read($join,$where, $limit, $offset,$orderby);                            
        }
        public function  cadastrar_HistoricoChamada(array $dados){
            return $this->insert($dados);                            
        }
        public function  alterar_HistoricoChamada(array $dados,$where){
            return $this->update($dados, $where);                            
        }
        public function  excluir_HistoricoChamada(array $dados,$where){
            return $this->update($dados, $where);                            
        }
    }