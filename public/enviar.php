<?php
header("Access-Control-Allow-Origin: https://grupoajaw.ajaw.com.mx");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nombre = filter_var($input['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $empresa = filter_var($input['empresa'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefono = filter_var($input['telefono'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $correo = filter_var($input['correo'] ?? '', FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($input['mensaje'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    
    

    if (!$nombre || !$correo || !$mensaje) {
        echo json_encode(["status" => "error", "message" => "Por favor llena los campos obligatorios (Nombre, Correo y Mensaje)."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.ajaw.com.mx';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ncarrillo@ajaw.com.mx'; 
        $mail->Password   = '$zys$sL[WdKa.U!n';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        $mail->setFrom('ncarrillo@ajaw.com.mx', 'Pagina Web Contacto');
        $mail->addAddress('noelizardi7@gmail.com'); 
        $mail->addReplyTo($correo, $nombre);         

        $mail->isHTML(true);
        $mail->Subject = "Lead - $nombre";
        
        $mail->Body = "
            <div style='background-color: #f6f9fc; padding: 40px 10px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
                <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;'>
                    
                    <tr>
                        <td style='background: linear-gradient(135deg, #01102A 0%, #022561 100%); padding: 30px; text-align: center;'>
                            <h1 style='color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;'>
                                Nuevo Mensaje de Contacto
                            </h1>
                            <p style='color: #ffffff; margin: 5px 0 0 0; font-size: 14px;'>
                                Pagina Web • Lead Recibido
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style='padding: 40px 30px;'>
                            <p style='margin: 0 0 25px 0; font-size: 16px; line-height: 1.5; color: #01102A;'>
                                Se ha registrado una nueva consulta a través del formulario de contacto de la página web. A continuación se detallan los datos proporcionados por el usuario:
                            </p>

                            <table border='0' cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 30px;'>
                                <tr>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; width: 35%; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;'>Nombre:</td>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;'>{$nombre}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;'>Empresa:</td>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;'>" . ($empresa ? $empresa : '<em style="color:#94a3b8;">No especificada</em>') . "</td>
                                </tr>
                                <tr>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;'>Teléfono:</td>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #0f172a;'>" . ($telefono ? $telefono : '<em style="color:#94a3b8;">No proporcionado</em>') . "</td>
                                </tr>
                                <tr>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;'>Correo:</td>
                                    <td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; color: #4F119F; font-weight: 500;'>{$correo}</td>
                                </tr>
                            </table>

                            <div style='background-color: #f8fafc; border-left: 4px solid #4F119F; padding: 20px; border-radius: 0 4px 4px 0;'>
                                <h4 style='margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;'>Mensaje o Proyecto:</h4>
                                <p style='margin: 0; font-size: 15px; line-height: 1.6; color: #1e293b; white-space: pre-line;'>" . nl2br($mensaje) . "</p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style='background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #f1f5f9;'>
                            <p style='margin: 0; font-size: 12px; color: #94a3b8;'>
                                Este es un correo automático generado por el sistema de contacto. Por favor, no respondas directamente a este correo remitente.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        ";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Tu información ha sido enviada con éxito."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "El servidor de correos falló. Detalles: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Acceso no autorizado."]);
}