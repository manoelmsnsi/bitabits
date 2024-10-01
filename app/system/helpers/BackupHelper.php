<?php

class BackupHelper{

    

 public function backup($host,$user,$pass,$name,$tables,$caminho){        
        try {
            $link = mysqli_connect($host,$user,$pass,$name);
            mysqli_select_db($link,$name); 
           //get all of the tables
            if($tables == '*'){
                $tables = array();
                $result = mysqli_query($link,'SHOW TABLES');
                while($row = mysqli_fetch_row($result)){
                    $tables[] = $row[0];
                }
            }else{
                $tables = is_array($tables) ? $tables : explode(',',$tables);

            }



               //cycle through each table and format the data

            foreach($tables as $table){

                $result = mysqli_query($link,'SELECT * FROM '.$table);

                $num_fields = mysqli_num_fields($result);

                $return.= 'DROP TABLE '.$table.';';

                $row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));

                $return.= "\n\n".$row2[1].";\n\n";

 

                for ($i = 0; $i < $num_fields; $i++){ 

                    while($row = mysqli_fetch_row($result)){

                        $return.= 'INSERT INTO '.$table.' VALUES(';

                        for($j=0; $j<$num_fields; $j++){

                            $row[$j] = addslashes($row[$j]);

                            $row[$j] = $row[$j];

                            if (isset($row[$j])) { 

                                $return.= '"'.$row[$j].'"' ; 

                            } else { 

                                $return.= '""'; 

                            }

                            if ($j<($num_fields-1)) { 

                                $return.= ','; 

                            }  

                        }

                        $return.= ");\n";

                    }

                }

                $return.="\n\n\n";

            }



            //save the file

            $handle = fopen($caminho.date("d").' db-backup-'.$name.'.sql','w+');

            fwrite($handle,$return);

            fclose($handle);
        return $caminho.date("d").' db-backup-'.$name.'.sql';
            
        } catch (Exception $ex) {

            $redirect = new RedirectHelper();

            $redirect->goToUrl("/Errors/error_404/erro/CB001/"); 

        }

       

    }

}