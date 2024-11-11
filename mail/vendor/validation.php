<?php


function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateFormData($data) {
    $errors = [];

    // Limpa e valida cada campo do formulário
    $name = isset($data['name']) ? cleanInput($data['name']) : '';
    $estado = isset($data['estado']) ? cleanInput($data['estado']) : '';
    $telefone = isset($data['telefone']) ? cleanInput($data['telefone']) : '';
    $email = isset($data['email']) ? cleanInput($data['email']) : '';
    $precatorio = isset($data['precatorio']) ? cleanInput($data['precatorio']) : '';
    $processo = isset($data['processo']) ? cleanInput($data['processo']) : '';
    $termos = isset($data['termos']) ? cleanInput($data['termos']) : '';

    // Validações
    if (empty($name)) $errors[] = 'O nome é obrigatório.';
    if (empty($estado)) $errors[] = 'O estado é obrigatório.';
    if (empty($telefone) || !preg_match('/^[0-9]{10,11}$/', $telefone)) $errors[] = 'Telefone inválido.';
    if (empty($email) || !validateEmail($email)) $errors[] = 'E-mail inválido.';
    if (empty($precatorio) || !preg_match('/^[0-9,.]+$/', $precatorio)) $errors[] = 'Valor do precatório inválido.';
    if (empty($processo)) $errors[] = 'Número do processo é obrigatório.';
    if (empty($termos)) $errors[] = 'Você deve aceitar a política de privacidade.';

    return [$errors, [
        'name' => $name,
        'estado' => $estado,
        'telefone' => $telefone,
        'email' => $email,
        'precatorio' => $precatorio,
        'processo' => $processo
    ]];
}
?>
