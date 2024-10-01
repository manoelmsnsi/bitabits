<?php

class FormHelper{

    

    public function criar_form($tb,$tipo){        

        $tb= preg_replace("/[^0-9A-Za-z]/", "",  $tb);

        $tipo= ( $tipo);

        $nome_form= $tipo;
        $model = new Model();
        $conn = new mysqli($model->host , $model->host_nome , $model->host_pass , $model->host_nome );



    
 
    if ( !$conn->connect_error ){

        if ( ( $res = $conn->query( "SHOW FIELDS FROM `{$tb}`;" ) ) ){

            $res1=$res;

            $res2 = $conn->query( "SHOW FIELDS FROM `{$tb}`;" );

            $res3 = $conn->query( "SHOW FIELDS FROM `{$tb}`;" );

            $id = 0;

            $controle=1;

    $tb1=  strtolower($tb);

    

    

    if($tipo=="Controller"){

        $html.= "<?php class {$tb} extends Controller {   

    private  $"."auth,$"."db;

    public function acesso_restrito(){         
        $"."this->auth = new AutenticaHelper();
        $"."this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $"."this->db = new AdminModel(); 
    }";

    $controle=1;      
    while ( ( $row = $res->fetch_row() ) ){
        $name = $row[ 0 ];     
         while ( ( $row3 = $res3->fetch_row() ) ){
            $name3 = $row3[ 0 ];
            if (substr($name3, 0,3) == "id_" ){
                $join.=" INNER JOIN ".ucfirst(substr($name3, 3))." ON ".ucfirst(substr($name3, 3)).".id = $tb.$name3";
            }
        }         

        $mtc  = array();

        if ( preg_match( "/((d+))/" , $row[ 1 ] , $mtc ) ){

            $size = (float) $mtc[ 1 ];

        }

    $html.=" \n public function admin_listar(){
        $"."this->acesso_restrito();
        $"."acesso = new AcessoHelper(); 
        $"."logs = new LogsModel();
        $"."comando='/$tb/admin_listar/';
        if($"."acesso->acesso_valida($"."comando)==true){ 
            $"."filiais=$"."acesso->acesso_filial(__CLASS__);
            $"."acesso = new SessionHelper();
            if(empty($"."_POST['pesquisa'])){ $"."pesquisa=null; }else{ $"."pesquisa=$"."_POST['pesquisa']; }
            $"."menu = new MenuHelper('Bit a Bits', $"."Class, $"."AcaoForm, $"."MetodoDeEnvio);        
            echo $"."menu->Menu();  
                
            $"."form = new FormularioHelper(); 
                
            $"."$tb1 = new {$tb}Model();
                
   $"."inputs.= $"."form->Listar(
                    'col-md-12', null, '/'.__CLASS__.'/form/',$"."icone,$"."$tb1"."->listar_$tb(
                        '".$join."',
                        NULL,
                      ".'"'.$tb.'.id_status<>99 AND ($filiais)"'.",
                        NULL,
                        '$tb.id DESC',
                        NULL
                            ,null,$"."pesquisa),tabela1,
                    array(
                        array('acao'=>'/'.__CLASS__.'/form/','classe'=>'btn-sm btn-warning','icone'=>'edit'),
                        array('acao'=>'/Upload/form/tabela/$tb/','classe'=>'btn-sm btn-rose','icone'=>'cloud_upload'),
                        array('acao'=>'/Logs/form/','classe'=>'btn-sm btn-danger','icone'=>'close'))
                );
      
            $"."logs->cadastrar_logs($"."comando,'0');//Gera Logs
            $"."form->card(__CLASS__,$"."inputs,'col-md-12',$"."comando,'POST','NULL');

        }else{
            $"."this->view('error_permisao');
        }
    }";

    $html.=" \n public function form(){ 
        $"."this->acesso_restrito();
        $"."acesso = new AcessoHelper();    
        $"."comando='/'.__CLASS__.'/incluir/';
        if($"."acesso->acesso_valida('/'.__CLASS__.'/admin_listar/')==true){
             $"."menu = new MenuHelper('Bit a Bits', $"."Class, $"."AcaoForm, $"."MetodoDeEnvio);        
            echo $"."menu->Menu();  
                ";
        $cp_form .=" echo $"."form->Input('hidden', 'id', $"."CSS, $"."id);\n";
            while ( ( $row1 = $res1->fetch_row() ) ){
                $name1 = $row1[ 0 ];
                $mtc1  = array();

                if (substr($name1, 0,3) == "id_" ){
                    $campo=substr($name1,3); 
                    $posicao= strpos($campo,"_");
                    $campo1= substr($campo,0,$posicao);
                    if( substr($campo,$posicao,1)=="_"){$campo2= substr($campo,$posicao+1);
                        }else{ $campo2= substr($campo,$posicao);}                   
                            $campo_mai=ucfirst($campo1);
                            $campo_mai.=ucfirst($campo2);

                            $html.="\n $"."$campo = new $campo_mai"."Model();\n";
                            $cp_form.="$"."inputs.= $"."form->select('$campo_mai','id_$campo','col-md-5',$$campo"."->listar_$campo_mai(NULL,NULL,'id_status<>99',NULL,NULL,NULL),'NOME_CAMPO',$$tb1"."_dados['$name1']);\n";
                           
                        }    
                        
                    if(substr($name1, 0,3) == "id_"){                        
                    }elseif($name1=="id"){
                        $cp_form .=" $"."inputs.= $"."form->Input('hidden', 'id', $."."CSS, $"."id);\n";
                    }else{ 
                        $cp_form .='$"."inputs.= $'.'form->Input("text", '."'$name1'".', "col-md-3", $'.$tb1.'_dados["'.$name1.'"], $Required, '."'$name1'".', $disable);'."\n";
                    }
                    
                    if($controle==1){
                        if($name1=="data_lancamento"){
                           // $cp .=" '{$name1}'=>"."date('Y-m-d H:i:s'),\n";
                        }else{
                            $cp .=" '{$name1}'=>"."$"."_POST['{$name1}'],\n"; 
                        }
                    }  
            }
            $cp_form.= "$"."inputs.= $"."form->Button('btn btn-md btn-rose ','Salvar');";  
            $controle++;

       $html.=" $"."$tb1 = new {$tb}Model();
        $"."id = $"."this->getParams('id');
        $"."dados['id']=$"."id;
        $"."nome_form='Cadastra $tb';

        if(!empty($"."id)){
            $".$tb1."_dados=$".$tb1."->listar_$tb1($"."JOIN, '1', ".'"id=$id"'.", $"."offset, $"."orderby);
            $".$tb1."_dados = $"."$tb1"."_dados[0]; 
            $"."comando='/'.__CLASS__.'/alterar/';
            $"."nome_form='Alterar $tb';
        } 
         $"."form = new FormularioHelper();
        $cp_form;
            $"."form->card(__CLASS__,$"."inputs,'col-md-12',$"."comando,'POST','NULL');
        }else{
            $"."this->view('error_permisao');
        }
    }";

           
           
    $html.="\n public function incluir(){    

        $"."this->acesso_restrito();

        $"."acesso = new AcessoHelper(); 

        $"."logs = new LogsModel();

        $"."comando='/{$tb}/incluir/';

            

        if($"."acesso->acesso_valida($"."comando)==true){



            $"."{$tb1} = new {$tb}Model();      

            $"."id=$"."{$tb1}->cadastrar_{$tb1}( 

                array(

                    {$cp}

                )

            );  

            $"."logs->cadastrar_logs($"."comando,$"."id);//Gera Logs

            $"."redirect = new RedirectHelper();

            $"."redirect->goToUrl('/{$tb}/admin_listar/');    

        }else{

            $"."this->view('error_permisao');

        }

    }";



    $html.="\n public function alterar(){    

        $"."this->acesso_restrito();

        $"."acesso = new AcessoHelper(); 

        $"."logs = new LogsModel();

        $"."comando='/{$tb}/alterar/';

        if($"."acesso->acesso_valida($"."comando)==true){

            $"."id = $"."_POST['id'];



            $"."{$tb1} = new {$tb}Model();      

            $"."{$tb1}->alterar_{$tb1}(

                array(

                    {$cp}

                ),'id='.$"."id

            );  

            $"."logs->cadastrar_logs($"."comando,$"."id);//Gera Logs

            $"."redirect = new RedirectHelper();

            $"."redirect->goToUrl('/{$tb}/admin_listar/');    

        }else{

            $"."this->view('error_permisao');

        }



    }";

    $html.="\n public function excluir(){    

        $"."this->acesso_restrito();

        $"."acesso = new AcessoHelper(); 

        $"."logs = new LogsModel();

        $"."comando='/{$tb}/excluir/';

        if($"."acesso->acesso_valida($"."comando)==true){

            $"."id = $"."this->getParams('id');

            $"."{$tb1} = new {$tb}Model();      

            $"."{$tb1}->excluir_{$tb1}( array( 'id_status'=>'99' ),'id='.$"."id );  

            $"."logs->cadastrar_logs($"."comando,$"."id);//Gera Logs

            $"."redirect = new RedirectHelper();

            $"."redirect->goToUrl('/{$tb}/admin_listar/');    

        }else{

            $"."this->view('error_permisao');

        }

    }";

    }

   $html.= " \n } ?> " ;

        

    }

    if($tipo=="Model"){

        $html.= "<?php

    class $tb"."Model extends Model{

        public $"."_tabela='$tb';



        public function  listar_$tb($"."join=null,$"."limit=null,$"."where=null,$"."offset=null,$"."orderby=null,$"."from=null,$"."group=null){

            return $"."this->read($"."join,$"."where, $"."limit, $"."offset,$"."orderby,$"."from,$"."group); ;                            

        }

        public function  cadastrar_$tb(array $"."dados){

            return $"."this->insert($"."dados);                            

        }

        public function  alterar_$tb(array $"."dados,$"."where){

            return $"."this->update($"."dados, $"."where);                            

        }

        public function  excluir_$tb(array $"."dados,$"."where){

            return $"."this->update($"."dados, $"."where);                            

        }

    }";

        

    }

    if($tipo=="listar_"){

        $html.= "

            <?php 

                require_once 'inicio.phtml';

            ?>

        <div class='content'>

        <div class='container-fluid'>

            <div class='row'>

                <div class='col-md-12'>

                    <div class='card'>

                        <div class='card-header card-header-rose card-header-icon'>

                            <div class='card-icon'>

                                <i class='material-icons'>account_balance_wallet</i>

                            </div>

                            <h4 class='card-title'>Listar {$tb}</h4>

                            <a href='/{$tb}/form/' type='submit' class='btn btn-md btn-rose pull-right  '><i class='material-icons '>add_circle_outline</i></a>

                        </div>

                        <div class='card-body'>

                            <div class='toolbar'> </div>

                            <div class='material-datatables'>

                                <table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>";



                                while ( ( $row = $res->fetch_row() ) ){

                                    $name = $row[ 0 ];

                                    $mtc  = array();



                                    if ( preg_match( "/((d+))/" , $row[ 1 ] , $mtc ) ){

                                        $size = (float) $mtc[ 1 ];

                                    }

                                    if($controle==1){

                                        $html .="<thead>

                                                    <tr>";

                                        $controle++;

                                    }

                                     if($controle==2){

                                        while ( ( $row1 = $res1->fetch_row() ) ){

                                            $name1 = $row1[ 0 ];

                                            $mtc1  = array();

                                            if ( preg_match( "/((d+))/" , $row1[ 1 ] , $mtc1 ) ){

                                                $size1 = (float) $mtc1[ 1 ];

                                            }

                                                $th.=" <th scope='col'>{$name1}</th>\n";  

                                                $td .=" <td><?php echo $"."listar_{$tb1}['{$name1}']; ?></td>\n"; 

                                            }                                  

                                        $controle++;

                                    }

                                    $th.=" <th scope='col'>Ação</th>\n"; 

                                    $td.="<td class='text-right'>

                                            <form class='form-check-inline' ><a href='/$tb/form/id/<?php echo $"."listar_$tb1"."['id']; ?>' type='submit' class='btn btn-sm btn-warning btn-just-icon '><i class='material-icons'>edit</i></a></form>

                                            <form class='form-check-inline'><a href='/$tb/visualizar/id/<?php echo $"."listar_$tb1"."['id']; ?>' type='submit' class='btn btn-sm btn-rose btn-just-icon ' target='_blank'><i class='material-icons'>remove_red_eye</i></a></form>

                                            <form class='form-check-inline'><a href='/Upload/form/id_tabela/<?php echo $"."listar_$tb1"."['id'].'/tabela/$tb/'; ?>' type='submit' class='btn btn-sm btn-rose btn-just-icon '><i class='material-icons'>publish</i></a></form>

                                            <form class='form-check-inline'><a onclick=".'"return confirm(' .''."'Você tem certeza que deseja excluir este registro ?'".");" .'"'."   href='/$tb/excluir/id/<?php echo $"."listar_$tb1"."['id']; ?>' class='btn btn-sm btn-danger btn-just-icon '><i class='material-icons'>close</i></a></form>

                                        </td>";

                                            

                                    $html .=$th;

                                    if($controle==3){ 

                                        $html .="

                                                </tr>

                                            </thead>";

                                        $controle++;

                                    }

                                    if($controle==4){

                                        $html .="<tfoot>

                                                        <tr>";

                                        $controle++;

                                    }

                                    $html .=$th;



                                     if($controle==5){

                                        $html .=" 

                                                </tr>

                                            </tfoot>

                                            <tbody>

                                            <?php foreach ($"."view_listar_{$tb1} AS $"."listar_{$tb1}):  ?>

                                                <tr>";

                                        $controle++;

                                    }

                                   $html .=$td; 

                                }



               $html.= "                    </tr>

                                        <?php endforeach; ?> 

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>" ;

    }

    if($tipo=="form_"){

        $header.= "<?php 

            require_once 'inicio.phtml';          

            if(empty($"."view_id)){ ?>";

    $form.= "

        <div class='content'>

                <div class='container-fluid'>

                    <div class='row'>

                        <div class='col-md-12'>

                            <div class='card '>

                                <div class='card-header card-header-rose card-header-icon'>

                                    <div class='card-icon'>

                                        <i class='material-icons'>work_outline</i>

                                    </div>

                                    <h4 class='card-title'>Cadastro {$tb}</h4>

                                </div>

                               <form form enctype='multipart/form-data' method='post' action='/{$tb}/SEM AÇÃO/'> <!-- PRECISA MEXER -->

                                    <div class='card-body '>

                                        <div class='row'>";



                                        while ( ( $row = $res->fetch_row() ) ){

                                            $name = $row[ 0 ];

                                            $type = empty( $row[ 3 ] ) ? "text" : "hidden";

                                            $size = 20;

                                            $mtc  = array();



                                            if ( preg_match( "/((d+))/" , $row[ 1 ] , $mtc ) ){

                                                $size = (float) $mtc[ 1 ];

                                            }

 

                                            if (substr($name, 0,3) == "id_" ){

                                                $nome1=substr($name, 3);



                                                $form.= "<div class='col-md-6'> 

                                                            <div class='form-group '>

                                                               <label  class='bmd-label-floating'>{$name}</label>

                                                               <select id='{$name}' name='{$name}' class='form-control' required='true' autocorrect='off' autocomplete='off'>

                                                                    <?php foreach ($"."view_listar_{$nome1} AS $"."listar_{$nome1}):                        

                                                                        if( $"."listar_{$nome1}['id'] == $"."listar_{$tb1}['{$name}']){ ?>

                                                                              <option selected='true' value=' <?php echo $"."listar_{$nome1}['id'] ; ?>' ><?php echo $"."listar_{$nome1}['NOME_CAMPO'] ; ?></option>  <!--  PRECISA MEXER -->

                                                                          <?php }else{ ?>

                                                                              <option value=' <?php echo $"."listar_{$nome1}['id'] ; ?> '><?php echo $"."listar_{$nome1}['NOME_CAMPO'] ; ?></option>  <!-- PRECISA MEXER -->

                                                                    <?php  } endforeach; ?>

                                                               </select> 

                                                            </div>

                                                         </div>";                   

                                            } elseif(substr($name, 0,4)=="data") {

                                                $type = empty( $row[ 3 ] ) ? "date" : "hidden";

                                                $form.="<div class='col-md-6'> 

                                                            <div class='form-group '>

                                                                <label  class='bmd-label-floating'>{$name}</label>

                                                                <input id='{$nome}' type='{$type}'name='{$name}' class='form-control' required='true'value='<?php echo $"."listar_{$tb1}['{$name}'] ?>'>

                                                            </div>

                                                       </div>"; 

                                            }elseif(substr($name, 0,2)=="id") {

                                                $type = empty( $row[ 3 ] ) ? "text" : "hidden";

                                                $form.="

                                                        <input id='{$nome}' type='{$type}'name='{$name}'  required='true'value='<?php echo $"."listar_{$tb1}['{$name}'] ?>'>

                                                     "; 

                                            }else{ 

                                                $type = empty( $row[ 3 ] ) ? "text" : "hidden";

                                                $form.="<div class='col-md-6'> 

                                                            <div class='form-group '>

                                                                <label  class='bmd-label-floating'>{$name}</label>

                                                                <input id='{$nome}' type='{$type}'name='{$name}' class='form-control' required='true' value='<?php echo $"."listar_{$tb1}['{$name}'] ?>'>

                                                            </div>

                                                        </div>";    

                                            }

                                        }



                                $form.= "<div class='card-footer text-right'>

                                           <button type='submit' name='' class='btn btn-rose' required=''>Salvar</button>

                                       </div>

                                    </div>

                                </div>

                           </form>

                       </div>

                   </div>

                </div>

            </div>

        </div>

<br><br>";

$header2.="                                      

    <?php

        }else{

            foreach ($"."view_listar_$tb1 AS $"."listar_$tb1):

     ?>

    " ;   

                                

                               

$header3.= " <?php endforeach; } ?>";

   

  $html.=$header.$form.$header2.$form.$header3;

 

    }

             $res->free_result();

        }

   

    }

    $conn->close();

    if($tipo=="Controller" ){

        $handle = fopen("./app/criador/".$tb.$nome_form.'.php','w+');

        fwrite($handle,$html);

        fclose($handle);

        

    }

    if($tipo=="Model"){

        $handle = fopen("./app/criador/".$tb.$nome_form.'.php','w+');

        fwrite($handle,$html);

        fclose($handle);

        

    }

      if($tipo<>"Controller" && $tipo<>"Model"){

        $handle = fopen("./app/criador/".$nome_form.strtolower($tb).'.phtml','w+');

        fwrite($handle,$html);

        fclose($handle);

    }



}

}