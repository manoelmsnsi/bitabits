<?php

class UsuariosModel extends Model{
    public $_tabela="Usuarios";
    
    public function  listar_usuarios($join=null,$limit=null,$where=null,$offset=null,$orderby=null){
        return $this->read($join,$where, $limit, $offset,$orderby);                            
    }
}