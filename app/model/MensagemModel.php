<?php

    class MensagemModel extends Model{

        public $_tabela='Mensagem';



        public function  listar_Mensagem($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null){

            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group); ;                            

        }

        public function  cadastrar_Mensagem(array $dados){

            return $this->insert($dados);                            

        }

        public function  alterar_Mensagem(array $dados,$where){

            return $this->update($dados, $where);                            

        }

        public function  excluir_Mensagem(array $dados,$where){

            return $this->update($dados, $where);                            

        }

    }