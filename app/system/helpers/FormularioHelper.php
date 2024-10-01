<?php
 class FormularioHelper {
    private $script;
    function __construct () {
      echo '<div class="content ">
                    <div class="container-fluid">
                      <div class="row">                      
                      ';

    }



    function __destruct () {

        echo '</div></div></div></form></body></html>
           
<script>
    function maska($nome_classe,$mascara){
        $("."+$nome_classe).mask($mascara);         
    }
    </script>
 <script>       
function somarParcelas(){
    var soma = 0;
          numeros = document.querySelectorAll(".numero")
          .forEach((elemento) => {                                
          soma += Number.parseFloat(elemento.value);
          });
    var valor_total = $("#valor_total").val();
    valor_total=soma-valor_total;
    document.getElementById("valor_sobra_falso").value = ""+valor_total.toLocaleString("us", {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById("valor_sobra").value = ""+soma;
  }
                          
                        

        $("#valor_pago").keyup(function(){
        var valor_pago = $("#valor_pago").val();
        var valor_parcela = $("#valor_parcela").val();
        var valor_parcela_juros = $("#valor_parcela_juros").val();
        var valor_troco = valor_pago-valor_parcela_juros;      
        $("#valor_troco_falso").val(valor_troco.toLocaleString("us", {minimumFractionDigits: 2, maximumFractionDigits: 2}));
              $("#valor_troco").val(valor_troco);
      });
      
      $("#valor_juros").keyup(function(){
        var juros = $("#valor_juros").val();
        var juros_mes = ((juros*100)*30)
        var valor_parcela = $("#valor_parcela").val();
        var dias_atraso = $("#dias_atraso").val();
        var valor_troco = Number.parseFloat(valor_parcela)+Number.parseFloat(((valor_parcela)*(juros*dias_atraso)));      
        $("#valor_parcela_juros").val(valor_troco);
        $("#valor_juros_mes").val(juros_mes.toLocaleString("us", {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $("#valor_troco").val(valor_troco);
      });
     
</script>'.$this->script;

    }

    function card($titulo="Bit a Bits",$inputs=null,$classe=null,$acao_formulario=null,$metodo_envio=null,$icone=null){
      if(!empty($icone)){
            $icon=' <div class="card-icon">
                        <i class="material-icons">'.$icone.'</i>
                    </div>'; 
          }
         
          if(!empty($acao_formulario)){
          //  echo $_SERVER["REQUEST_URI"];
          $func= explode("/",$_SERVER["REQUEST_URI"]);
          
            
           // print_r($func);
            if($func[2]=="visualizar"){ 
              
              $form1='<form class="form-check-inline  d-flex justify-content-end pull-right" >
                          <a href="'.$acao_formulario.'" type="submit" class="btn btn-sm btn-warning btn-just-icon  ">
                          <i class="material-icons">edit</i></a>
                          </form>
                       ';
            }else{
               
                $form= '<form enctype="multipart/form-data" action="'.$acao_formulario.'" method="'.$metodo_envio.'"><div class="row"></form>';
            }
              
            
          }
         
          
          echo '<div class="card  '.$classe.'">
                  <div class="card-header card-header-rose card-header-icon">
                    '.$icon.'
                      <h4 class="card-title">'.$titulo.'</h4>
                      
                      '.$form1.'
                      </div>                              
                   '.$form.
                    $inputs.'
                  </div>
                </div>';
      
    }
     

    function Tag ( $Tag, $Classe,$Dados) {
        return '<'.$Tag.'  class="'.$Classe.'">'.$Dados.'</'.$Tag.'>';
    }

    function Label ( $Nome=null, $Classe=null) {
        return '<label  class="'.$Classe.'">'.$Nome.'</label>';
    }

    function Input ($Tipo=null, $Nome=null, $Classe=null, $Value=null,$Required=null,$Label=null,$disable=null,$id=null) {
        if(!empty($Label)){
            $Label="<label class='bmd-label-floating'>".$Label."</label>";
        }
        if(empty($id)){
            $id=$Nome;
        }

    return '<div class="'.$Classe.'"> 
                '.$Label.'
                <div class="form-group">
                    <input id="'.$id.'" type="'.$Tipo.'" name="'.$Nome.'" class="form-control '.$id.'" '.$Required.' '.$disable.' value="'.$Value.'">                       
                </div>
            </div>';
    } 
    
    function Check ($checked=null, $Nome=null, $Classe=null, $Value=null,$Required=null,$Label=null,$disable=null,$id=null) {
       if(!empty($checked)){
           $checked="checked";
       }else{
           $checked="";
       }
        if(empty($id)){
            $id=$Nome;
        }

    return '<div class="togglebutton '.$Classe.'">
                <label style="color:black;">
                    <input id="'.$id.'" type="checkbox" '.$checked.' name="'.$Nome.'" value="'.$Value.'">
                    <span class="toggle"></span>
                    '.$Label.'
                </label>
            </div>';
    } 
    
    
    function Text ($Tipo=null, $Nome=null, $Classe=null, $Value=null,$Required=null,$Label=null,$disable=null,$id=null) {
               if(empty($id)){
            $id=$Nome;
        }

    return '
            <div class="'.$Classe.'">
              <div class="card-body">
                <div class="form-group ">
                <label for="comment">'.$Label.'</label>
                <textarea id="'.$id.'" autofocus '.$Required.'  class="form-control" name="'.$Nome.'" rows="10">'.$Value.'</textarea>
                <script>
                  CKEDITOR.replace( "'.$id.'" );
                </script>
                  </div>
              </div>
            </div';
    } 
    function MiniCard($dados){ 

        foreach ($dados as $dado):
            $card.= '<div class="'.$dado["css"].'">
                        <div class="card card-stats">
                            <div class="card-header card-header-icon " >
                                <div class="card-icon"Style="background:'.$dado["cor"].'">
                                  <i class="material-icons">'.$dado["icone"].'</i>
                                </div>
                                <p class="card-category">'.$dado["nome"].'</p>
                                <h3 class="card-title">'.$dado["valor"].'</h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                  <i class="material-icons">date_range</i> '.$dado["footer"].'
                                </div>
                            </div>
                        </div>    
                    </div>';     
        endforeach;
        return' <div class="row">
                    '.$card.'         
                </div>';
    }

    function upload($classe=null){
 //<script // src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        return '
       
<style>
  .imageThumb {
     max-height: 300px;
     border: none;
     margin: 10px 10px 0 0;
     padding: 1px;
  }
</style>
<script>

$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML("'."<img class=imageThumb>".'")).attr("'."src".'", event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $("'."#gallery-photo-add".'").on("'."change".'", function() {
        imagesPreview(this, "div.gallery");
    });
});

</script>"   
                <div class="'.$classe.'"> 
                        <div>
                           <span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Selecione a Imagem</span>
                                <input type="file" name="arquivo[]" multiple="multiple" id="gallery-photo-add"/> 

                            </span>                             
                        </div>
                    </div>
                    <div class="col-md-12  gallery ">
                </div>';

    }

    function Abas ($Tipo=null, $Nome=null, $Classe=null, $dados=null,$inf=null,$Classe_aba="card-header-rose") {

        

        foreach ($dados AS $dado): 

            $abas.= '               
                    <li class="nav-item ">
                        <a class="nav-link '.$dado["classe"].'" href="#'.$dado["id"].'" data-toggle="tab">
                            <i class="material-icons">'.$dado["icone"].'</i>'.$dado["descricao"].'
                            <div class="ripple-container"></div>
                        </a>
                    </li> ';

                                    

        endforeach; 

        foreach ($inf AS $info):

            $sub_aba.= '<div class="tab-pane '.$info["classe"].'" id="'.$info["id"].'">
                            <div>
                                <div class="card-body">
                                    <div class="col-md-12 row">
                                       '.$info["dados"].'
                                    </div>                  
                                </div>                  
                            </div>                  
                        </div>';

                                    

        endforeach; 

        return '<div class=" '.$Classe.'">
            <br><br><br>
                                         
                            <div class="card-header card-header-tabs  '.$Classe_aba.'">
                                <div class="nav-tabs-navigation">
                                    <div class="nav-tabs-wrapper">
                                        <span class="nav-tabs-title"></span>
                                        <ul class="nav nav-tabs" data-tabs="tabs">                                                        
                                            '.$abas.'                                                     
                                        </ul>                             
                                    </div>
                                </div>
                            </div>  
                            
                            <div>
                                <div class="tab-content">                          
                                    '.$sub_aba.'
                                </div>  
                            </div>                             
                   
                </div>   ';                

                      

    }   
 
    

    function select ( $label,$Nome, $Classe, $dados,$Option,$Value=null,$Required="required") { 

        $Option= explode(",", $Option);
        $dados2=$dados;
     //       if(empty($Required)){ $Required="required"; }
     
        foreach ($dados2 AS $dado ):
            $dado_select="";
              foreach ($Option AS $Opt):
                   $dado_select .= $dado["$Opt"]." ";
               endforeach;
            if($dado["id"]==$Value){
                 $select.= '<option class="form-control '.$Classe.' " selected="true" value="'.$dado["id"].'"> '.$dado_select.'</option>';

            }else{
                 $select.= '<option class="form-control '.$Classe.' " value="'.$dado["id"].'"> '.$dado_select.'</option>';

            } 
        endforeach;  
   $select.= '<option class="form-control '.$Classe.' " value=""> </option>';
        
   return '<div class="'.$Classe.'">       
            <label  class="bmd-label-floating">'.$label.'</label>
                <div class="form-group">  
                    <select autocorrect="off" style="width: 100%" autocomplete="off" name="'.$Nome.'"'.$Required.'    class="form-control select">'.$select.'</select>
                </div>
            </div>';

    }

    function select_imagem ( $label,$Nome, $Classe, $dados,$Option) {
        foreach ($dados AS $dado):
            $select.= '<option class=" '.$Classe.' " value="'.$dado["id"].'"><i class="material-icons"> '.$dado["$Option"].'</i> </option>';
        endforeach;  
        return '<div class="'.$Classe.'">       
                <label  class="bmd-label-floating">'.$label.'</label>
                    <div class="form-group">  
                        <select autocorrect="off" autocomplete="off" name="'.$Nome.'" required class="form-control select ">'.$select.'</select>
                    </div>
                </div>';

    }

    function Button ($class, $Valor, $class_grupo="col-md-12") {
         return '<div class="form-group '.$class_grupo.'"> 
                    <div class="card-footer d-flex justify-content-end pull-right text-right">
                        <button type="submit" class="'.$class.'" >'.$Valor.'</button>
                    </div>
                </div>';
    }
    
public function Pesquisa($class) {
        return "
            <div class='content'>
                <div class='container-fluid'> 
                    <div class='row col-md-12'>
                        <form method='POST'  action=''>
                           <div class='card $class'>           
                               <div class='row'>
                                  <div class='col-md-10'>
                                       <label  class='bmd-label-floating'>Pesquisa</label>
                                           <input id='' type='text' name='pesquisa' class='form-control' required='true' >                                          
                                   </div>
                                   
                                   <div class='col-md-2'>
                                       <input type='submit' name='Pesquisar' class='btn btn-rose' value='Pesquisar'/>
                                   </div>    
                               </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        ";
}

    public function Listar($classe=null,$titulo=null,$action=null,$icone=null,$dados=null,$id=null,$acao=null,$pesquisa=null){      
         if(!empty($icone)){
            $icon=' <div class="card-icon">
                        <i class="material-icons">'.$icone.'</i>
                    </div>';
        }

        


        if(!empty($action)){
            $act= "<form class=' d-flex justify-content-end pull-right'action='$action'><input type='submit' class='btn btn-md btn-rose pull-right' value='+' required=''></form>";
         //  href="$_SERVER['."'HTTP_REFERER'".']"
           // $act="<form action='$action' method='POST'><input type='submit' class='btn btn-md btn-rose pull-right' value='+' required=''></form>";

        }
     
       if(!empty($dados)){ 
           $key = explode(",",implode(",",array_keys($dados[0])));        
            $th="<tr>";
            foreach ($key as $k):             
                if(substr($k, 0,3)=="cor" OR substr($k, 0,7)=="comando" OR substr($k, 0,2)=="id"){
                }elseif(substr($k, 0,3)=="src"){
                    $th.= "<th style='font-size:0.9rem;  font-weight: bold;'scope='col'>".substr($k, 3)."</th>";
                }else{
                    $th.= "<th style='font-size:0.9rem;  font-weight: bold;'scope='col'>".$k."</th>";  
                }  
            endforeach;
        }     

        if(!empty($acao)){
            $th.= "<th style='font-size:0.9rem;  font-weight: bold;'scope='col'>Ação</th>";
        }     

        $th.="</tr>"; 
        $td="<tr>";
        $cont= count($key);
        
        if(empty($dados)){
            
        }else{
            foreach($dados AS $dado):
                $comando="";
                $cor="";
                for($c=0;$c<$cont;$c++){
                    if(!empty($dado[$key[$c]])){
                        if($key[$c]=="comando"){
                            $relatorio=$dado[$key[$c]];
                        } 
                        $comando.=$key[$c]."/".preg_replace("/[^0-9 A-Za-z]/", "",$dado[$key[$c]]) ."/";  
                    }
                    
                    if(substr($key[$c],0,3)=="cor"){ $cor=$key[$c];}else{
                        if($key[$c]== substr($cor, 4)){                             
                            $td.="<td><button type='button' class='btn btn-sm btn-default'  style='background:".$dado[$cor]." ;width: 160px'>".$dado[$key[$c]]."</button></td>" ;      
                            $cor="";
                        }elseif($key[$c]=="comando" or substr($key[$c],0,2)=="id"){                            
                        }elseif($key[$c]=="Cliente" or $key[$c]=="Colaborador"){     
                          $td.="<td><a href='/Pessoa/visualizar/$comando'>".$dado[$key[$c]]."</a></td>";
                        }elseif( substr($key[$c],0,4)=="data" or substr($key[$c],0,4)=="Data"){                          
                            $data_convertida = date_create($dado[$key[$c]]);         
                            if($dado[$key[$c]]<date("Y-m-d") ){                                
                                $td.="<td style='color: red'>".date_format($data_convertida,'d/m/Y')."</td>";
                            }else{                                 
                                $td.="<td>".date_format($data_convertida,'d/m/Y')."</td>";
                            }                            
                        }elseif( substr($key[$c],0,3)=="src"){       
                            if(substr($dado[$key[$c]], -4)==".pdf" OR substr($dado[$key[$c]], -5)==".docx"){
                                $td.="<td><a target='_blank' href=".$dado[$key[$c]].">Baixar Arquivo</a></td>";
                            }else{
                                $td.="<td><a target='_blank' href=".$dado[$key[$c]]."><img width='80em' src=".$dado[$key[$c]]."></a></td>";
                            }
                            
                        }elseif( substr($key[$c],0,5)=="icone" OR substr($key[$c],0,5)=="Icone"){ 
                            
                            $td.="<td><form class='form-check-inline' ><a href='#' type='submit' class='btn  btn-just-icon btn-rose'><i class='material-icons'>".$dado[$key[$c]]."</i></a></form></td>";
                            
                        }elseif( substr($key[$c],-10)=="Vencimento" OR substr($key[$c],0,10)=="vencimento"){       
                            $td.="<td><img width='80em' src=".$dado[$key[$c]]."></td>";
                        }elseif( substr($key[$c],0,5)=="Valor" ){       
                            $td.="<td>".money_format('%.2n', ($dado[$key[$c]]))."</td>";
                        }else{
                            $td.="<td>".$dado[$key[$c]]."</td>";  
                        }
                    }
                  
                } 

                if(!empty($acao)){
                $acao_botao="<td>";
                foreach ($acao as $botao) :
                    if(substr($botao["acao"], -9)=="/excluir/"){
                         $acao_botao.="<form class='form-check-inline' ><a  href='".$botao["acao"].$comando."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }elseif(substr($botao["acao"], -11)=="/relatorio/"){
                        $acao_botao.="<form class='form-check-inline' ><a href='".$relatorio."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }else{
                        $acao_botao.="<form class='form-check-inline' ><a href='".$botao["acao"].$comando."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }
                endforeach;                     
                $acao_botao.="</td>";
            }
                $td.=$acao_botao;
                $td.="</tr>";
            endforeach;
        }

        
        return "       
                <div  class='$classe '>           
                    <div class=' card'>
                       <div class='card-header card-header-primary card-header-icon'>                         
                            ".$icon."                                
                           <h4 class='card-title'><b>$titulo</b></h4>
                            ".$act."
                       </div>                    
                       <div class=' card-body'>
                            <div class='material-datatables'>
                               <table id='$id' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
                                   <thead>
                                        ".$th."
                                   </thead>
                                   <tbody>                                                  
                                        ".$td."
                                   </tbody>
                                   <tfoot>
                                        ".$th."
                                   </tfoot>
                               </table>
                            </div>                            
                        </div>
                    </div>
                </div>   
                
 <script>
            $(document).ready(function() {
              $('#$id').DataTable({
                    dom: 'Bfrtip',               
                buttons: [
                    'print','pdf','csv'
                ],
                'paging': false,
                'lengthMenu': [
                  [10, 25, 50, -1],
                  [10, 25, 50, 'ALL']
                ],
                responsive: true,
                language: {
                  search: '_INPUT_',
                  searchPlaceholder: 'Pesquisa',
                }
              });
            
            });
          </script>
          <script src='/web-files/sistema/js/jquery.dataTables.min.js'></script>
                ";                              

    }
    
    public function ListarId($classe=null,$titulo=null,$action=null,$icone=null,$dados=null,$id=null,$acao=null,$pesquisa=null){ 
      
         if(!empty($icone)){

            $icon=' <div class="card-icon">

                        <i class="material-icons">'.$icone.'</i>

                    </div>';

        }

        


        if(!empty($action)){

            $act="<form action='$action' method='POST'><input type='submit' class='btn btn-md btn-rose pull-right' value='+' required=''></form>";

        }
     
       if(!empty($dados)){ 
           $key = explode(",",implode(",",array_keys($dados[0]))); 
       
        $th="<tr>";
 
        foreach ($key as $k):             
            if(substr($k, 0,3)=="cor" OR substr($k, 0,7)=="comando"){
                
            }else{
                $th.= "<th style='font-size:0.9rem;  font-weight: bold;'scope='col'>".$k."</th>";  
            }  
        endforeach;
       
       }
       

       

        if(!empty($acao)){

            $th.= "<th style='font-size:0.9rem;  font-weight: bold;'scope='col'>Ação</th>";

        }

        

        $th.="</tr>"; 

        $td="<tr>";

        $cont= count($key);

        

        if(empty($dados)){
            
        }else{
            foreach($dados AS $dado):
                $comando="";
                 $cor="";
                for($c=0;$c<$cont;$c++){    
                    if(substr($key[$c],0,3)=="cor"){ $cor=$key[$c];}else{
                        if($key[$c]== substr($cor, 4)){                             
                            $td.="<td><button type='button' class='btn btn-sm btn-default'  style='background:".$dado[$cor]." ;width: 160px'>".$dado[$key[$c]]."</button></td>" ;      
                            $cor="";
                        }elseif($key[$c]=="comando"){
                            
                        }else{
                            $td.="<td>".$dado[$key[$c]]."</td>";  
                        }
                    }



                    if(!empty($dado[$key[$c]])){
                        if($key[$c]=="comando"){
                            $relatorio=$dado[$key[$c]];
                        } 
                        $comando.=$key[$c]."/".preg_replace("/[^0-9 A-Za-z]/", "",$dado[$key[$c]]) ."/";   

                    }




                } 

                if(!empty($acao)){

                $acao_botao="<td>";

                foreach ($acao as $botao) :
                    if(substr($botao["acao"], -9)=="/excluir/"){
                        $excluir_alert="Voce tem certeza que deseja Excluir este registro ?";
                        $acao_botao.="<form class='form-check-inline' ><a onclick='return confirm();'  href='".$botao["acao"].$comando."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }elseif(substr($botao["acao"], -11)=="/relatorio/"){
                        $acao_botao.="<form class='form-check-inline' ><a href='".$relatorio."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }else{
                        $acao_botao.="<form class='form-check-inline' ><a href='".$botao["acao"].$comando."' type='submit' class='btn  btn-just-icon ".$botao["classe"]."'><i class='material-icons'>".$botao["icone"]."</i></a></form>";
                    }

                endforeach;                        

                $acao_botao.="</td>";

            }

                $td.=$acao_botao;

                $td.="</tr>";

            endforeach;
        }

        
        return "
       
        <div  class=' row $classe '>
           
                    <div class=' card'>

                       <div class='card-header card-header-primary card-header-icon'>                          

                            ".$icon."                                

                           <h4 class='card-title'><b>$titulo</b></h4>

                            ".$act."

                       </div>
                      

                       <div class=' card-body'>

                            <div class='material-datatables'>

                               <table id='$id' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>

                                   <thead>

                                        ".$th."

                                   </thead>

                                   <tbody>                                                  

                                        ".$td."

                                   </tbody>

                                   <tfoot>

                                        ".$th."

                                   </tfoot>

                               </table>
                               
                            </div>
                            
                        </div>

                    </div>   
                </div>    
  
 <script>
            $(document).ready(function() {
              $('#$id').DataTable({
                    dom: 'Bfrtip',               
                buttons: [
                    'print','pdf','csv'
                ],
                'paging': false,
                'lengthMenu': [
                  [10, 25, 50, -1],
                  [10, 25, 50, 'ALL']
                ],
                responsive: true,
                language: {
                  search: '_INPUT_', 
                  searchPlaceholder: 'Pesquisa',
                }
              });
            
            });
          </script>
          <script src='/web-files/sistema/js/jquery.dataTables.min.js'></script>
                  ";                              

    }

    function Script ($script) {

        $this->script=$script;

    }

}
