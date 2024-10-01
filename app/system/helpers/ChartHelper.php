<?php

class ChartHelper{

    public function chart($titulo,$icone,$nome,$tipo,array $dados ,array $cdados,$class=null ){

    $label="'";
    $label.= implode("' , ' ", array_keys($dados));
    $label.="'";
    $valor= implode(" , ", array_values($dados));
    $color = "'";
    $color .= implode("' , '", array_values($cdados));
    $color .= "'";
   if(!empty($icone)){
            $icon="<div class='card-icon'>
                        <i class='material-icons'>$icone</i>
                    </div>"; 
          }

       $chart=" 
            <div class='$class'>
                <div class='card '>     
                 <div class='card-header card-header-rose card-header-icon'>
                    $icon
                      <h4 class='card-title'>$titulo</h4>
                      </div>
                    <div class='card-body '>
                        <div class='row'>
                            <canvas id='".$nome."'></canvas>
                        </div>
                    </div>
                </div>
            </div>

       <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js'></script>         

               

    <script> chart = new Chart(".$nome.", {

        type: '".$tipo."',

                            

        data: {

            labels: [".$label."],

                

            

                        

            datasets: [

                {

                    label: '".$nome."',

                       backgroundColor: [".$color."], 

                    data: [".$valor."]

                        

                }

            ]

        }

    });</script>";

       return $chart;

    }

    

}



//bar = barra

//pie = pizza

//doughnut = redondo

//line= linha