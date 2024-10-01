<?php
class UploadHelper {
    protected $path = "uploads/",$file,$fileNome,$fileTmpNome;
    
    public function setPath( $path ){
        if(!file_exists($_SERVER["DOCUMENT_ROOT"].$path)){
            if (!mkdir($_SERVER["DOCUMENT_ROOT"].$path, 0775, true)) {//0755
                die('[ERRO->UPLOAD-01] Falha ao Criar Diretorio! Contate o ADMINISTRADOR');
            }

        }
        $this->path = $path;
        return $this;
    }
    
    public function setFile( $file,$file_name ,$controle){
        $this->file = $file;       
        $this->setFileNome($file_name,$controle);
        $this->setFileTmpNome($controle);  
        return $this;
    }
    
    public function setFileNome($file_name,$controle){
      $this->fileNome = $file_name.substr($this->file['name'][$controle], -4,4);
             
    }
    
    public function setFileTmpNome($controle){
        $this->fileTmpNome = $this->file['tmp_name'][$controle];
    }
    
    public function upload(){
        if(move_uploaded_file($this->fileTmpNome, $_SERVER["DOCUMENT_ROOT"].$this->path.$this->fileNome))
            return $this->path.$this->fileNome ;
        else
            return false;                
    }
    
}
