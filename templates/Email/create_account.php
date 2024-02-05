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
                    <h1 style='color: #333;'>{$lang->tmpeNewAccount['message0']} [Nome do Usuário]! </h1>
                    <p style='font-size: 16px; color: #666;'>{$lang->tmpeNewAccount['message1']}</p>
                </td>
            </tr>
            <tr>
                <td style='padding: 20px; text-align:center'>
                    <p style='font-size: 1em; color: #666; line-height:1.5em'>{$lang->tmpeNewAccount['message2']}</p>
                    <a href='$activationLink' style='display: inline-block; background-color: #4caf50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>{$lang->tmpeNewAccount['message3']}</a>
                    <p style='font-size: .8em; color: #666; line-height:1.5em; text-align:left; font-style: italic;'>{$lang->tmpeNewAccount['message4']}: $activationLink</p>
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