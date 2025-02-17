<?php 
require 'conexion_db.php';
require 'secret.php';

// Importar PHPMailer
/*
require "../PHPMailer/src/Exception.php";
require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
*/

if(isset($_GET['register']) && isset($_GET['email'])){

    $email = htmlspecialchars($_GET['email']);

    // Generar token aleatorio de 5 digitos.
    $token = rand(10000, 99999);
    $enlace = "https://dpladia.freeddns.org/token.php?email=$email&token=$token";

    $msg = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Confirma tu email</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f3e8ff;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }
                .logo {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .logo svg {
                    width: 48px;
                    height: 48px;
                    fill: #9333ea;
                }
                h1 {
                    color: #111827;
                    font-size: 24px;
                    text-align: center;
                    margin-bottom: 20px;
                }
                p {
                    margin-bottom: 20px;
                }
                .button {
                    display: inline-block;
                    background-color: #9333ea;
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    text-align: center;
                }
                .footer {
                    margin-top: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #6b7280;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='logo'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                        <path d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'></path>
                        <polyline points='22,6 12,13 2,6'></polyline>
                    </svg>
                </div>
                <h1>Confirma tu correo</h1>
                <p>Gracias por registrarte en Dpladia. Para completar el registro, haga click en el siguiente botón:</p>
                <p style='text-align: center;'>
                    <a href='$enlace' class='button'>Confirmar Correo</a>
                </p>
                <p>Si no has pedido una confirmación de cuenta puedes ignorar este email.</p>
                <div class='footer'>
                    <p>© 2025 Dpladia. Todos los derechos reservados.</p>
                    <p>
                        <a href='#'>Terminos</a>
                        <a href='#'>Privacidad</a>
                        <a href='#'>Ayuda</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";

    
    // Comentamos el envio del email porque lo tenemos en local actualmente.
    /*
    $mail= new PHPMailer();
    $mail->isSMTP();
    $mail->Host="smtp.gmail.com";
    $mail->Port=587;
    $mail->SMTPDebug = "0";
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth=true;
    $mail->Username="$mail_email";
    $mail->Password="$mail_password";
    $mail->setFrom("$mail_email","$mail_name");
    $mail->addAddress("$email");
    $mail->Subject="Dpladia - Confirmación de email.";
    $mail->isHTML(true);
    $mail->Body=$msg;
    */

    if (!empty($email)) {
        // Guardar en la base de datos el token
        $stmt = $pdo->prepare('INSERT INTO tokens (email, token, creado_en) VALUES (:email, :token, NOW()) ON DUPLICATE KEY UPDATE token=:token, creado_en=NOW()');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_INT);
        $stmt->execute();

        echo $msg;

        // Envio del correo de confirmación
        /*
        if(!$mail->send()){echo $mail->ErrorInfo;}
        else {
            header("Location:../register.php");
        }*/

    } else{
        header('Location: ../register.php');
    }
}else{
    header('Location: ../index.php');
}
?>
