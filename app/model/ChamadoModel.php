<?php    class ChamadoModel extends ModelLiberacao{        public $_tabela='Chamado';         public function  listar_Chamado($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null,$pesquisa=null){            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group,$pesquisa );                                    }        public function  cadastrar_Chamado(array $dados){            return $this->insert($dados);                                    }        public function  alterar_Chamado(array $dados,$where){            return $this->update($dados, $where);                                    }        public function  excluir_Chamado(array $dados,$where){            return $this->update($dados, $where);                                    }    }