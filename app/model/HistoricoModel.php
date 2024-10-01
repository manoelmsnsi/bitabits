<?php   
class HistoricoModel extends Model{  
  public $_tabela='Historico';   
  
  public function  listar_Historico($join=null,$limit=null,$where=null,$offset=null,$orderby=null,$from=null,$group=null,$pesquisa=null){      
    return $this->read($join,$where, $limit, $offset,$orderby,$from,$group,$pesquisa);        
  }       
  public function  cadastrar_Historico(array $dados){   
    return $this->insert($dados);                         
  }       
    
  public function  alterar_Historico(array $dados,$where){
    return $this->update($dados, $where);                    
  }    
  public function  excluir_Historico(array $dados,$where){ 
    return $this->update($dados, $where);                        
  }   
}