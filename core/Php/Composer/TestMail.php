<?php

require 'vendor/autoload.php'; // Inclui o autoloader do Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "\n---------------------------------------------------------\n";

$error = false;

// Verifica se foi fornecido um endereço de e-mail como argumento
if ($argc > 1) {
    $destinatario = $argv[1];
    // Testa se é um endereço de e-mail válido
    if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
        $error = true;
    }
} else {
    $error = true;
}

if ($error) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Informe um endereço de e-mail válido para realizar o teste.\n";
    echo $mensagemAtencao;
    exit;
}

// Função para enviar e-mail utilizando as configurações fornecidas
function enviarEmail($para, $assunto, $mensagem) {
    $configuracoes = require 'config/PhpMailer.php';

    $host = $configuracoes['host'];
    $port = $configuracoes['port'];
    $username = $configuracoes['username'];
    $password = $configuracoes['password'];
    $smtpSecure = $configuracoes['smtp_secure'];
    $fromEmail = $configuracoes['from_email'];
    $fromName = $configuracoes['from_name'];
    $isHtml = $configuracoes['is_html'];
    $charset = $configuracoes['charset'];

    // Instancia um objeto PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        $mail->SMTPSecure = $smtpSecure;

        // Remetente e destinatário
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($para);

        // Conteúdo do e-mail
        $mail->isHTML($isHtml);
        $mail->Subject = $assunto;
        $mail->Body    = $mensagem;

        // Envia o e-mail
        $mail->send();
        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}

// Teste do envio de e-mail
echo "Iniciando teste de envio de e-mail...\n";
echo "Isso pode demorar alguns minutos\n";

// Assunto e mensagem do e-mail de teste
$assunto = 'Assunto do Teste';
$mensagem = 'Este é um e-mail de teste enviado via PHPMailer através do MOB.';

// Envio do e-mail de teste
echo "Enviando e-mail para: $destinatario...\n";

enviarEmail($destinatario, $assunto, $mensagem);
