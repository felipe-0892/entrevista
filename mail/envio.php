<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php'; // Ajuste o caminho para o autoload do PHPMailer

// Funções de validação
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePhone($phone) {
    return preg_match('/^\d{10,11}$/', $phone);
}

// Checagem do formulário se será enviado corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $estado = isset($_POST['estado']) ? htmlspecialchars(trim($_POST['estado'])) : '';
    $telefone = isset($_POST['telefone']) ? htmlspecialchars(trim($_POST['telefone'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $precatorio = isset($_POST['precatorio']) ? htmlspecialchars(trim($_POST['precatorio'])) : '';
    $processo = isset($_POST['processo']) ? htmlspecialchars(trim($_POST['processo'])) : '';
    $termos = isset($_POST['termos']) ? htmlspecialchars(trim($_POST['termos'])) : '';


    // Validação dos dados
    $errors = [];
    if (empty($name)) $errors[] = 'O nome é obrigatório.';
    if (empty($estado)) $errors[] = 'O estado é obrigatório.';
    if (empty($telefone) || !validatePhone($telefone)) $errors[] = 'Telefone inválido. Deve ter 10 ou 11 dígitos.';
    if (empty($email) || !validateEmail($email)) $errors[] = 'E-mail inválido.';
    if (empty($precatorio)) $errors[] = 'O valor do precatório é obrigatório.';
    if (empty($processo)) $errors[] = 'Número do processo é obrigatório.';
    if (empty($termos)) $errors[] = 'O campo de consentimento é obrigatório.';


    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
        exit;
    }

    // Configuração do PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Configurações do servidor SMTP
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Ativar saída de depuração detalhada
        $mail->isSMTP(); // Definir para usar SMTP
        $mail->Host = 'smtp.gmail.com'; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Habilitar autenticação SMTP
        $mail->Username = 'felipesilvacosta892@gmail.com'; // Usuário SMTP
        $mail->Password = '1q2w3e!Q@W#E'; // Senha SMTP
        $mail->Port = 587; // Porta TCP para conexão
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS

        // Configurações do e-mail
        $mail->setFrom($email, $name); // Endereço e nome do remetente
        $mail->addAddress('marcelw@alfamaweb.com.br', 'Marcel'); // Endereço e nome do destinatário
        $mail->addAddress('cassiob@alfamaweb.com.br', 'Cassio'); // Endereço e nome do destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Novo Contato';
        $mail->Body = "
            <html>
            <head>
                <title>Formulário de Contato</title>
            </head>
            <body>
                <h2>Formulário de Contato</h2>
                <p><strong>Nome:</strong> $name</p>
                <p><strong>Estado:</strong> $estado</p>
                <p><strong>Telefone:</strong> $telefone</p>
                <p><strong>E-mail:</strong> $email</p>
                <p><strong>Valor do Precatório:</strong> $precatorio</p>
                <p><strong>Número do Processo:</strong> $processo</p>
                <p><strong>Política de Privacidade:</strong> " . ($termos ? 'Aceito' : 'Não aceito') . "</p>
            </body>
            </html>";

        // Envia o e-mail
        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Formulário enviado com sucesso para o destinatário']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Ocorreu um erro ao enviar o seu formulário: {$mail->ErrorInfo}"]);
    }
}
?>
