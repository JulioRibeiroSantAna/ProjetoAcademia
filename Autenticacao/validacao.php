<?php
// Autenticacao/validacao.php
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
    // Remove tudo que não é dígito
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
    
    if ($telefone && !validarTelefone($telefone)) {
        $erros[] = 'Telefone inválido.';
    }
    
    return $erros;
}

function sanitizarEntrada($dados) {
    if (is_array($dados)) {
        $resultado = [];
        foreach ($dados as $chave => $valor) {
            $resultado[$chave] = sanitizarEntrada($valor);
        }
        return $resultado;
    }
    
    return htmlspecialchars(trim((string)$dados), ENT_QUOTES, 'UTF-8');
}
?>