<?php    class GrupoModel extends Model{        public $_tabela='Grupo';        public function  listar_Grupo($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null,$pesquisa=null){            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group,$pesquisa);                                    }        public function  cadastrar_Grupo(array $dados){            return $this->insert($dados);                                    }        public function  alterar_Grupo(array $dados,$where){            return $this->update($dados, $where);                                    }        public function  excluir_Grupo(array $dados,$where){            return $this->update($dados, $where);                                    }    }