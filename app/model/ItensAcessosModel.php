<?php

class ItensAcessosModel extends Model{
    public $_tabela="ItensAcesso";
    
    public function  listar_itens_acessos($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$camposfrom=null,$group=null,$pesquisa=null){
        return $this->read($join,$where, $limit, $offset,$orderby,$camposfrom,$group,$pesquisa);                            
    }
}