<?php
/**
 * ARQUIVO: validacao.php
 * Funções para validar dados dos formulários
 */

// Valida apelido (2 a 30 caracteres)
function validarApelido($apelido) {
    $apelido = trim($apelido);
    return strlen($apelido) >= 2 && strlen($apelido) <= 30;
}

// Valida formato de email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Valida senha (mínimo 3 caracteres)
function validarSenha($senha) {
    return strlen($senha) >= 3;
}

// Valida nome completo (2 a 100 caracteres)
function validarNome($nome) {
    $nome = trim($nome);
    return strlen($nome) >= 2 && strlen($nome) <= 100;
}

// Valida telefone (10 a 15 dígitos)
function validarTelefone($telefone) {
    // Remove tudo que não é número
    $telefone = preg_replace('/\D/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 15;
}

// Valida todos os campos do cadastro
// Retorna array com erros ou array vazio se tudo ok
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

// Limpa dados do formulário (proteção contra XSS)
// XSS = ataque com código JavaScript malicioso
function sanitizarEntrada($dados) {
    if (is_array($dados)) {
        $resultado = [];
        foreach ($dados as $chave => $valor) {
            $resultado[$chave] = sanitizarEntrada($valor);
        }
        return $resultado;
    }
    
    // Converte caracteres especiais: < vira &lt;
    return htmlspecialchars(trim((string)$dados), ENT_QUOTES, 'UTF-8');
}
?>