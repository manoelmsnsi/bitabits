<?php 

class EmailHelper {

    public function enviar($email_destinatario,$email_remetente,$nome_remetente,$asssunto,$menssagem,$arquivo,$nome_arquivo,$notificacao){ 
   

        $email = "$email_remetente";

        $host = "smtp.bitabits.com.br";

        $usuario = "$email_remetente";

        $senha = "Sprt@bit#25207";

        $criptografia = "";

        $porta = "587";

        $destinatario ="$email_destinatario";

        $assunto = "$asssunto";

        $mensagem = "$menssagem";

        $arquivo = "$arquivo";



         $mailer = new PHPMailer();



        $mailer->isSMTP();                                      // Set mailer to use SMTP



        $mailer->SMTPOptions = array(

            'ssl' => array(

                'verify_peer' => false,

                'verify_peer_name' => false,

                'allow_self_signed' => true

            )

        );





       

        $mailer->Host = $host;  // Servidor que realiza o envio

        $mailer->SMTPAuth = true; // Enable SMTP authentication

        $mailer->isHTML(true);  // Set email format to HTML

        $mailer->CharSet = 'utf-8';

        $mailer->Port = "$porta";   // Porta de Envio

      //  $mail->addStringAttachment(file_get_contents($url), 'myfile.pdf');

        if(isset($criptografia)){



            $mailer->SMTPSecure = "$criptografia";



        }



        $mailer->Username = "$usuario";  // SMTP username

        $mailer->Password = "$senha";  // SMTP password



        $mailer->SMTPDebug = 0;

        $mailer->Debugoutput = 'html';

        $mailer->setLanguage('pt');



        //setando formato HTML da mensagem, para envio ser aceito corretamente.



        $corpoMSG = "<!DOCTYPE html>";

        $corpoMSG .= "<html>";

        $corpoMSG .= "<head>";

        $corpoMSG .= "</head>";

        $corpoMSG .= "<body>";

        $corpoMSG .= "$mensagem";

        $corpoMSG .= "</body>";

        $corpoMSG .= "</html>";





        $mailer->AddAddress($destinatario);

        $mailer->From = $email;  // E-mail que estÃ¡ enviando

        $mailer->FromName = $email; //Nome que serÃ¡ exibido

        $mailer->Sender = $email;  // E-mail que estÃ¡ enviando

        $mailer->Subject = $assunto; // assunto da mensagem

        $mailer->MsgHTML($corpoMSG); // corpo da mensagem

       // $mailer->addAttachment($arquivo, $name, $encoding, substr($arquivo, -4));

        if(!empty("$arquivo")) {

            $mailer->addStringAttachment(file_get_contents($arquivo), $nome_arquivo);

        }

        

        if ($arquivo['error'] == 0){

        $mailer->AddAttachment($arquivo['tmp_name'], $arquivo['name']);



        }


        if(!$mailer->Send()) {

           echo "Erro: " . $mailer->ErrorInfo;

          } else {

           echo "<div class='alert alert-info'>

                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>

                      <i class='material-icons'>close</i>

                    </button>

                    <span>$notificacao</span>

                  </div>";

          }

        }

    

}

 