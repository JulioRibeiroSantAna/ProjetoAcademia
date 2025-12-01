@echo off
REM Script de inicialização para Windows CMD
echo ================================================
echo INICIANDO SISTEMA - WINDOWS
echo ================================================

REM Verificar Docker
echo.
echo Verificando Docker...
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERRO] Docker nao esta rodando!
    echo Inicie o Docker Desktop e tente novamente
    exit /b 1
)
echo [OK] Docker esta rodando

REM Parar containers antigos
echo.
echo Parando containers antigos...
docker-compose down -v >nul 2>&1

REM Limpar volumes
echo.
echo Limpando volumes orfaos...
docker volume prune -f

REM Build
echo.
echo Fazendo build da imagem (pode demorar alguns minutos)...
docker-compose build --no-cache

REM Iniciar
echo.
echo Iniciando containers...
docker-compose up -d

REM Aguardar MySQL
echo.
echo Aguardando MySQL inicializar (ate 90 segundos)...
timeout /t 15 /nobreak >nul

REM Status
echo.
echo Status dos containers:
docker-compose ps

REM Informações
echo.
echo ================================================
echo SISTEMA INICIADO!
echo ================================================
echo.
echo Acessos:
echo   Site: http://localhost:8080
echo   Debug: http://localhost:8080/debug_config.php
echo   phpMyAdmin: http://localhost:8081
echo.
echo Credenciais:
echo   Admin: admin@mef.com / admin123
echo   User: teste1@gmail.com / 12345678
echo.
echo ================================================
pause
