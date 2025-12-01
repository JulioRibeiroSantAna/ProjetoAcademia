# Script para verificar portas em uso no Windows

Write-Host "🔍 Verificando portas utilizadas pelo sistema..." -ForegroundColor Cyan
Write-Host ""

$ports = @(
    @{Port=8080; Name="Web (Site)"; Env="WEB_PORT"},
    @{Port=3306; Name="MySQL"; Env="MYSQL_PORT"},
    @{Port=8081; Name="phpMyAdmin"; Env="PHPMYADMIN_PORT"}
)

$occupied = @()

foreach ($p in $ports) {
    $port = $p.Port
    $name = $p.Name
    $env = $p.Env
    
    # Verifica se a porta está em uso
    $connection = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
    
    if ($connection) {
        Write-Host "❌ Porta $port ($name) está OCUPADA" -ForegroundColor Red
        $occupied += @{Env=$env; Port=$port}
        
        # Sugere porta alternativa
        $suggested = $port + 1
        while (Get-NetTCPConnection -LocalPort $suggested -State Listen -ErrorAction SilentlyContinue) {
            $suggested++
        }
        Write-Host "   💡 Sugestão: Use porta $suggested" -ForegroundColor Yellow
        Write-Host "   📝 Comando: Add-Content .env.docker '$env=$suggested'" -ForegroundColor Gray
        Write-Host ""
    } else {
        Write-Host "✅ Porta $port ($name) está LIVRE" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

if ($occupied.Count -eq 0) {
    Write-Host "✅ Todas as portas estão livres!" -ForegroundColor Green
    Write-Host "Pode executar: .\start.ps1" -ForegroundColor White
} else {
    Write-Host "⚠️  $($occupied.Count) porta(s) ocupada(s)" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "SOLUÇÃO RÁPIDA:" -ForegroundColor White
    Write-Host "───────────────" -ForegroundColor Gray
    Write-Host "1. Criar arquivo de configuração:" -ForegroundColor White
    Write-Host "   Copy-Item .env.example .env.docker" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. Adicionar no .env.docker:" -ForegroundColor White
    foreach ($occ in $occupied) {
        $suggested = $occ.Port + 1
        while (Get-NetTCPConnection -LocalPort $suggested -State Listen -ErrorAction SilentlyContinue) {
            $suggested++
        }
        Write-Host "   $($occ.Env)=$suggested" -ForegroundColor Yellow
    }
    Write-Host ""
    Write-Host "3. Executar novamente:" -ForegroundColor White
    Write-Host "   .\start.ps1" -ForegroundColor Gray
}

Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
