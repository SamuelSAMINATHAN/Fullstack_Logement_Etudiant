<?php

namespace App\Core;

// PHPMailer classes (Importation manuelle sans Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Service de gestion des emails utilisant PHPMailer en local
 */
class MailService
{
    /**
     * Envoie un code de réinitialisation de mot de passe
     */
    public static function sendPasswordResetCode($toEmail, $code)
    {
        // Chargement manuel des fichiers PHPMailer
        $libPath = dirname(APPROOT) . '/lib/PHPMailer-master/src/';
        
        if (file_exists($libPath . 'Exception.php')) {
            require_once $libPath . 'Exception.php';
            require_once $libPath . 'PHPMailer.php';
            require_once $libPath . 'SMTP.php';
        }

        $subject = "Votre code de réinitialisation - Dorocho";
        
        $body = "
        <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
          <h2 style='color: #007bff; text-align: center;'>Bonjour,</h2>
          <p>Vous avez demandé la réinitialisation de votre mot de passe sur la plateforme <strong>Dorocho</strong>.</p>
          <p>Voici votre code de vérification unique :</p>
          <div style='text-align: center; margin: 30px 0;'>
            <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #007bff; background: #f8f9fa; padding: 15px 30px; border-radius: 5px; border: 1px dashed #007bff;'>{$code}</span>
          </div>
          <p>Ce code est valable pendant <strong>1 heure</strong>. Saisissez-le sur la page de vérification pour continuer.</p>
          <p>Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.</p>
          <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
          <p style='font-size: 0.8em; color: #777; text-align: center;'>Ceci est un email automatique de Dorocho.</p>
        </div>
        ";

        // Tentative d'envoi avec PHPMailer
        if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
            $mail = new PHPMailer(true);

            try {
                // Configuration Serveur SMTP
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;
                $mail->CharSet    = 'UTF-8';

                // Destinataires
                $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
                $mail->addAddress($toEmail);

                // Contenu
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = "Votre code de réinitialisation Dorocho est : {$code}. Valable 1 heure.";

                $mail->send();
                error_log("[MailService] Code envoyé avec succès à $toEmail");
                return true;
            } catch (Exception $e) {
                error_log("[MailService] Échec PHPMailer : " . $mail->ErrorInfo);
            } catch (\Exception $e) {
                error_log("[MailService] Échec général : " . $e->getMessage());
            }
        }

        // Fallback sur mail() natif
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_USER . ">" . "\r\n";

        if (mail($toEmail, $subject, $body, $headers)) {
            return true;
        }

        return false;
    }
}
