<?php
// validacao.php
// Funções de validação para login e cadastro

function validarApelido($apelido) {
    $apelido = trim($apelido);
    return strlen($apelido) >= 2 && strlen($apelido) <= 30;
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarSenha($senha) {
    return strlen($senha) >= 3;
}

function validarNome($nome) {
    $nome = trim($nome);
    return strlen($nome) >= 2 && strlen($nome) <= 100;
}

function validarTelefone($telefone) {
    // Aceita formatos simples, pode ser melhorado conforme necessidade
    $telefone = preg_replace('/\D/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 15;
}

function validarCadastro($nome, $apelido, $email, $senha, $confirmSenha, $telefone = '') {
    $erros = [];
    if (!validarNome($nome)) {
        $erros[] = 'Nome deve ter entre 2 e 100 caracteres.';
    }
    if (!validarApelido($apelido)) {
        $erros[] = 'Apelido deve ter entre 2 e 30 caracteres.';
    }
    if (!validarEmail($email)) {
        $erros[] = 'E-mail inválido.';
    }
    if (!validarSenha($senha)) {
        $erros[] = 'A senha deve ter pelo menos 3 caracteres.';
    }
    if ($senha !== $confirmSenha) {
        $erros[] = 'As senhas não coincidem!';
    }
    if (!validarTelefone($telefone)) {
        $erros[] = 'Telefone inválido.';
    }
    return $erros;
}

function sanitizarEntrada($dados) {
    if (is_array($dados)) {
        return array_map('sanitizarEntrada', $dados);
    }
    return htmlspecialchars(trim($dados), ENT_QUOTES, 'UTF-8');
}
?>