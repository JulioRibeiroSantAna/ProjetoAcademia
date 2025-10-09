<?php
// validacao.php
// Funções de validação para login e cadastro

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

function validarCadastro($nome, $email, $senha, $confirmSenha) {
    $erros = [];
    
    if (!validarNome($nome)) {
        $erros[] = 'Nome deve ter entre 2 e 100 caracteres.';
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
    
    return $erros;
}

function sanitizarEntrada($dados) {
    if (is_array($dados)) {
        return array_map('sanitizarEntrada', $dados);
    }
    return htmlspecialchars(trim($dados), ENT_QUOTES, 'UTF-8');
}
?>