# Script de inicialização para Windows PowerShell
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "INICIANDO SISTEMA - WINDOWS" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan

# 1. Verificar Docker
Write-Host "`nVerificando Docker..." -ForegroundColor Yellow
try {
    docker info | Out-Null
    Write-Host "✅ Docker está rodando" -ForegroundColor Green
} catch {
    Write-Host "❌ ERRO: Docker não está rodando!" -ForegroundColor Red
    Write-Host "Inicie o Docker Desktop e tente novamente" -ForegroundColor Yellow
    exit 1
}

# 2. Verificar docker-compose
Write-Host "`nVerificando docker-compose..." -ForegroundColor Yellow
$composeCmd = "docker-compose"
try {
    & $composeCmd version | Out-Null
    Write-Host "✅ docker-compose encontrado" -ForegroundColor Green
} catch {
    try {
        docker compose version | Out-Null
        $composeCmd = "docker compose"
        Write-Host "✅ docker compose (plugin) encontrado" -ForegroundColor Green
    } catch {
        Write-Host "❌ ERRO: docker-compose não encontrado!" -ForegroundColor Red
        exit 1
    }
}

# 3. Parar containers antigos
Write-Host "`nParando containers antigos..." -ForegroundColor Yellow
if ($composeCmd -eq "docker-compose") {
    docker-compose down -v 2>$null
} else {
    docker compose down -v 2>$null
}

# 4. Limpar volumes
Write-Host "`nLimpando volumes órfãos..." -ForegroundColor Yellow
docker volume prune -f

# 5. Build
Write-Host "`nFazendo build da imagem (pode demorar alguns minutos)..." -ForegroundColor Yellow
if ($composeCmd -eq "docker-compose") {
    docker-compose build --no-cache
} else {
    docker compose build --no-cache
}

# 6. Iniciar
Write-Host "`nIniciando containers..." -ForegroundColor Yellow
if ($composeCmd -eq "docker-compose") {
    docker-compose up -d
} else {
    docker compose up -d
}

# 7. Aguardar MySQL
Write-Host "`nAguardando MySQL inicializar..." -ForegroundColor Yellow
$maxAttempts = 30
$attempt = 0

while ($attempt -lt $maxAttempts) {
    try {
        docker exec siteacademia_db mysqladmin ping -h localhost -uroot -proot --silent 2>$null
        Write-Host "✅ MySQL está pronto!" -ForegroundColor Green
        break
    } catch {
        $attempt++
        Write-Host "Tentativa $attempt/$maxAttempts..." -ForegroundColor Gray
        Start-Sleep -Seconds 3
    }
}

if ($attempt -eq $maxAttempts) {
    Write-Host "⚠️  Timeout aguardando MySQL, mas containers estão rodando" -ForegroundColor Yellow
}

# 8. Status
Write-Host "`nStatus dos containers:" -ForegroundColor Yellow
if ($composeCmd -eq "docker-compose") {
    docker-compose ps
} else {
    docker compose ps
}

# 9. Verificar banco configurado
Write-Host "`nVerificando configuração do banco..." -ForegroundColor Yellow
Start-Sleep -Seconds 5

$maxAttempts = 30
$attempt = 0

while ($attempt -lt $maxAttempts) {
    try {
        docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "SELECT 1 FROM usuarios LIMIT 1;" 2>$null | Out-Null
        Write-Host "✅ Banco de dados configurado e pronto!" -ForegroundColor Green
        break
    } catch {
        $attempt++
        Write-Host "Tentativa $attempt/$maxAttempts - Aguardando banco inicializar..." -ForegroundColor Gray
        Start-Sleep -Seconds 3
    }
}

if ($attempt -eq $maxAttempts) {
    Write-Host "⚠️  Timeout aguardando banco, mas containers estão rodando" -ForegroundColor Yellow
    Write-Host "Execute: .\verify-database.sh para verificar manualmente" -ForegroundColor Yellow
}
# 10. Testar conexão
Write-Host "`nTestando conexão com o site..." -ForegroundColor Yellow
Start-Sleep -Seconds 2

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8080/debug_config.php" -UseBasicParsing
    if ($response.Content -match "CONEXÃO ESTABELECIDA") {
        Write-Host "✅ CONEXÃO COM BANCO DE DADOS OK!" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Site acessível, mas verifique a conexão" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  Não foi possível testar automaticamente" -ForegroundColor Yellow
}
# 11. Informações finais
Write-Host "`n================================================" -ForegroundColor Cyan
Write-Host "✅ SISTEMA INICIADO COM SUCESSO!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "`n🌐 Acessos:" -ForegroundColor White
Write-Host "   Site principal: http://localhost:8080" -ForegroundColor Gray
Write-Host "   Debug/Teste:    http://localhost:8080/debug_config.php" -ForegroundColor Gray
Write-Host "   phpMyAdmin:     http://localhost:8081" -ForegroundColor Gray
Write-Host "`n🔑 Credenciais:" -ForegroundColor White
Write-Host "   Admin: admin@mef.com / admin123" -ForegroundColor Gray
Write-Host "   User:  teste1@gmail.com / 12345678" -ForegroundColor Gray
Write-Host "`n📋 Comandos úteis:" -ForegroundColor White
Write-Host "   Ver logs:           docker-compose logs -f" -ForegroundColor Gray
Write-Host "   Parar:              docker-compose down" -ForegroundColor Gray
Write-Host "   Reiniciar:          docker-compose restart" -ForegroundColor Gray
Write-Host "   Verificar banco:    bash verify-database.sh" -ForegroundColor Gray
Write-Host "`n================================================" -ForegroundColor Cyan
Write-Host "`n================================================" -ForegroundColor Cyan
