<?php

class Model{

   public $data; 
   public $_tabela;  
   // DB DESENVOLVIMENTO 
   public  $host="mysql_default", $host_nome="bitabits",$host_user="root", $host_pass="mysql";  
   // DB SICOOB
   // public  $host="db_sicoob.mysql.dbaas.com.br", $host_nome="db_sicoob", $host_pass="Bit@bits#25207"; 
   //  
   // DB REDEUNICLINICA
   //public  $host="db_uniclinica.mysql.dbaas.com.br",$host_nome="db_uniclinica",$host_pass="dB#Uni@25207";
   // DB MR TECH
    //public  $host="mrtech.mysql.dbaas.com.br",$host_nome="mrtech",$host_pass="Bit@bits#25207";
   // 
   // DB IGREJA IPBMS
   // public  $host="db_ipbms.mysql.dbaas.com.br",$host_nome="db_ipbms",$host_pass="Bit@bits#25207";
   // 
   // DB BOREAL DESIGNER
   // public  $host="boreal.mysql.dbaas.com.br",$host_nome="boreal",$host_pass="Bit@bits#25207";
   //
   // DB IGREJA IEAD OPO
   // public  $host="db_adopo.mysql.dbaas.com.br",$host_nome="db_adopo",$host_pass="Bit@bits#25207";
   // 
   // DB MATRIZ BIT A BITS 
   // public  $host="db_bitabits.mysql.dbaas.com.br",$host_nome="db_bitabits",$host_pass="dB#Bitab@25207";
      

   public function __construct() {
        try {
            $this->data = new PDO("mysql:host=$this->host;port=3306;dbname=$this->host_nome;charset=utf8", $this->host_user, $this->host_pass);
            $this->data->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Isso ajudará a capturar erros do PDO
        } catch (Exception $ex) {
            error_log("Erro ao conectar ao banco de dados: " . $ex->getMessage()); // Log do erro
            $redirect = new RedirectHelper(); 
            $redirect->goToUrl("/Errors/error_404/erro/CC001/"); 
        }
    }




     public function debug($tipo,$status,$dados){
       if($tipo == "insert" && $status=="true"){
          echo "<div class='content'>
                    <div class='container-fluid'>
                        <div class='col-md-9 offset-md-3'>
                            <div class='card'>
                                <div style='color:red'>DEBUG ON -> {$this->_tabela}</div>
                                {$dados}
                            </div>
                        </div> 
                    </div>
                </div>"; //**DEBUG**
       }

       if($tipo == "read" && $status=="true"){
          echo "<div class='content'>
                    <div class='container-fluid'>
                        <div class='col-md-9 offset-md-3'>
                            <div class='card'> 
                                <div style='color:red'>DEBUG ON -> {$this->_tabela}</div>
                                {$dados}
                            </div>
                        </div> 
                    </div>
                </div>"; //**DEBUG**
       }

       if($tipo == "update" && $status=="true"){
          echo "<div class='content'>
                    <div class='container-fluid'>
                        <div class='col-md-9 offset-md-3'>
                           <div class='card'>
                                <div style='color:red'>DEBUG ON -> {$this->_tabela}</div>
                                {$dados}
                            </div>
                        </div> 
                    </div>
                </div>"; //**DEBUG**
       } 
   }
    
    public function insert(  array $dados ){ 
        try {
            $campos = implode(", ", array_keys($dados));
            $cont = count($dados) ;
            $param="?";
            for($c=2;$c<=$cont;$c++){ $param.=", ?"; }
            $valores = "'".implode("','", array_values($dados))."'";
            
            //$this->debug("insert", "true","INSERT INTO `{$this->_tabela}` ({$campos}) VALUES ({$valores})");
     
            $STM=$this->data->prepare("INSERT INTO `{$this->_tabela}` ({$campos}) VALUES ({$param})");
            $STM->execute(array_values($dados));
            return $this->data->lastInsertId();  
        } catch (Exception $ex) { 
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Errors/error_404/erro/CI001/");  
        }
    }

    public function read($join = null,$where = null,$limit = null,$offset = null,$orderby = null,$camposfrom = null,$group=null,$pesquisa=null){
        try {
                $join = ($join != null ? "  {$join} " : "");
                $where = ($where != null ? " WHERE {$where}   " : "");
                $limit = ($limit != null ? " LIMIT {$limit} " : "");
                $offset = ($offset != null ? " OFFSET {$offset} " : "");
                $orderby = ($orderby != null ? " ORDER BY {$orderby} " : "");
                $group = ($group != null ? " GROUP BY {$group} " : "");
                $camposfrom = ($camposfrom != null ? "  {$camposfrom} " : "*");              
                $pesquisa =preg_replace("/[^0-9A-Za-zéáãèà .-]\//", " ",$pesquisa  );          
                if(!empty($pesquisa)){  
                    $campos=explode(",", $camposfrom);  
                    foreach ($campos as $pesq){
                      $partes = explode(" AS ",$pesq);// " OR ( $pesq LIKE '%$pesquisa%' )"
                        if ((mb_strpos($partes[0], 'data') !== false)OR(mb_strpos($partes[0], 'data') !== false)OR(mb_strpos($partes[0], "'") !== false)) {
                            if((mb_strpos($partes[0], "'") !== false)){
                                } else{
                                    $campo[]= preg_replace('/[^a-zA-Z0-9\s._= ]/', "",$partes[0] )."=date_format(STR_TO_DATE( '".$pesquisa."', '%d/%m/%Y' ), '%Y-%m-%d') ";
                                }
                        } else{
                            
                            $campo[]="  ".preg_replace('/[^a-zA-Z0-9\s._]/', "",$partes[0] )." LIKE '%$pesquisa%'";
                        }
                    }
                    $like = implode(" OR ",$campo);        
                    $like=" AND( ".$like." )";
                } 
   
                // $this->debug("read", "true","SELECT $camposfrom FROM {$this->_tabela} {$join} {$where} {$like} {$group} {$orderby} {$limit} {$offset} ");
                $q=$this->data->query("SELECT $camposfrom FROM {$this->_tabela} {$join} {$where} {$like} {$group} {$orderby} {$limit} {$offset} ");
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->fetchAll();
        } catch (Exception $ex) {
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Errors/error_404/erro/CR001/"); 
        }
    }

    public function comando($comando){    
        $q=$this->data->query("$comando"); 
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q->fetchAll();
    }

    public function update( array $dados,$where){ 
        try {
            foreach($dados as $inds =>$vals){ 
                $campos[]="{$inds} = ?";  
                $debug[] ="{$inds}=$vals";  
            }  
            $campos = implode(", ",$campos);                 
          //  $this->debug("read", "true"," UPDATE `{$this->_tabela}` SET {$campos} WHERE {$where}<br>");  
          //  print_r(array_values($dados));
            
            $STM = $this->data->prepare(" UPDATE `{$this->_tabela}` SET {$campos} WHERE {$where}");
            return   $STM->execute(array_values($dados));
        } catch (Exception $ex) {
            $redirect = new RedirectHelper();
            $redirect->goToUrl("/Errors/error_404/erro/CU001/");  
        } 
    }

    public function delete($where){
        //$this->data->query("DELETE FROM `{$this->_tabela}` WHERE {$where}");
    } 
}