<?php

    $return = "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body>
        <table style='width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; border-collapse: collapse;'>
            <tr>
                <td style='background-color: #f8f8f8; text-align: center; padding: 20px;'>
                    <h1 style='color: #333;'>{$lang->simple['Hello']} {$name}! </h1>
                </td>
            </tr>
            <tr>
                <td style='padding: 20px; text-align:center'>
                    <p style='font-size: 1em; color: #666; line-height:1.5em'>{$lang->ForgotPass['MailRecoverMsg1']}</p>
                    <p style='font-size: 1em; color: #666; line-height:1.5em'>{$lang->ForgotPass['MailRecoverMsg2']} <strong>$pass</strong></p>
                    <p style='font-size: 1em; color: #666; line-height:1.5em'>{$lang->ForgotPass['MailRecoverMsg3']}</p>
                    <p style='font-size: 1em; color: #666; line-height:1.5em'>{$lang->ForgotPass['MailRecoverMsg4']}</p>
                </td>
            </tr>
            <tr>
                <td style='background-color: #333; color: #fff; text-align: center; padding: 10px; font-size: 14px;'>
                    &copy; " . date('Y') . " " . APP['app_company'] . ". Todos os direitos reservados.
                </td>
            </tr>
        </table>
    </body>
    </html>";