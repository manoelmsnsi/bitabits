<?php        class ChequeModel extends Model{        public $_tabela='Cheque';        public function  listar_Cheque($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null,$pesquisa=null){            return $this->read($join,$where, $limit, $offset,$orderby,$from,$group,$pesquisa);                                    }        public function  cadastrar_Cheque(array $dados){            return $this->insert($dados);                                    }        public function  alterar_Cheque(array $dados,$where){            return $this->update($dados, $where);                                    }        public function  excluir_Cheque(array $dados,$where){            return $this->update($dados, $where);                                    }    }