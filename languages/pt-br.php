<?php

namespace Languages;

class Language
{
    public $MbLogin = [
        "StartYourSession" => "Inicie sua sessão",
        "EmailAddress" => "Seu nome de usuário ou e-mail",
        "Password" => "Senha",
        "RememberMe" => "Lembrar-me",
        "SignIn" => "Entrar",
        "ForgotPassword" => "Esqueceu a senha?",
        "DoNotHaveAccount" => "Não tem uma conta?",
        "CreateAccount" => "Crie a sua conta aqui!"
    ];

    public $CreateAccount = [
        "Title" => "Cadastro de Usuário",
        "Name" => "Nome",
        "UserName" => "User Name",
        "EmailAddess" => "Seu e-mail",
        "Password" => "Senha",
        "PasswordConfirm" => "Confirme a Senha",
        "Register" => "Cadastrar",
        "ModalTitle" => "Registro Concluído com Êxito",
        "ModalContent1" => "Sua conta foi criada com sucesso. Agora você pode realizar o login e acessar seu painel de controle.",
        "ModalContent2" => "Sua conta foi criada com sucesso. Um e-mail de confirmação foi enviado para validar o seu primeiro acesso.",
        "messageAlert1" => "O endereço de e-mail deve pertencer ao(s) domínio(s) ",
        "goToLogin" => "Fazer Login"
    ];

    public $ForgotPass = [
        "Title" => "Esqueceu sua senha",
        "Content" => "Digite seu endereço de e-mail e uma senha temporária será redefinida e enviada para você por e-mail.",
        "Email" => "Endereço de e-mail",
        "ForgotPass" => "Enviar nova Senha",
        "BackToLogin" => "Voltar para o Login",
        "Message" => "Este endereço de e-mail não foi encontrado em nossa base de dados.",
        "MessageError" => "Ocorreu um erro inesperado. Tente novamente mais tarde ou entre em contato com o administrador do sistema.",
        "ModalTitle" => "E-mail encontrado!",
        "ModalContent" => "Acabamos de enviar uma mensagem contendo o link para a recuperação de senha para o seu endereço de e-mail",
        "goToLogin" => "Fazer Login",
        "MailRecoverTitle" => "Sua nova senha!",
        "MailRecoverMsg1" => "Recebemos uma solicitação para redefinir a senha da sua conta. Aqui está a sua nova senha:",
        "MailRecoverMsg2" => "Nova Senha: ",
        "MailRecoverMsg3" => "Por favor, acesse o sistema utilizando esta nova senha. Recomendamos que altere sua senha assim que possível após o login.",
        "MailRecoverMsg4" => "Se você não solicitou esta alteração, entre em contato conosco imediatamente.",
    ];

    public $panelAdmin = [
        "Profile" => "Perfil",
        "Settings" => "Configurações",
        "Exit" => "Sair",
        "CloseTitle" => "Desconectar?",
        "CloseContent" => "Selecione 'Sair' abaixo para encerrar sua sessão atual.",
        "MsgSuccess" => "Cadastro realizado com sucesso!",
        "MsgError" => "Houve um problema ao tentar realizar este cadastro. Por favor, tente novamente."
    ];

    public $profile = [
        "Title" => "Perfil do Usuário",
        "Avatar" => "Foto de Perfil",
        "UserName" => "Nome",
        "CurrentPassword" => "Senha Atual",
        "CurrentPasswordPlaceholder" => "Digite sua senha atual",
        "NewPassword" => "Nova Senha",
        "NewPasswordPlaceholder" => "Digite sua nova senha",
        "ConfirmPassword" => "Confirmar Senha",
        "ConfirmPasswordPlaceholder" => "Confirme sua senha"
    ];

    public $tmpeNewAccount = [
        "message" => "Ative seu cadastro",
        "message0" => "Olá",
        "message1" => "Obrigado por se juntar a nós. Sua conta foi criada com sucesso.",
        "message2" => "Para concluir seu cadastro, clique no botão abaixo e ative a sua conta em nossa plataforma.",
        "message3" => "Ativar Conta",
        "message4" => "Link de ativação"
    ];

    public $simple = [
        "Hello" => "Olá",
        "Exit" => "Sair",
        "Cancel" => "Cancelar",
        "Save" => "Salvar",
        "Email" => "E-mail",
        "Sending" => "Enviando"
    ];

}