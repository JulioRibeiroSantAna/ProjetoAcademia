#!/bin/bash
# Script de inicialização universal para qualquer ambiente

echo "================================================"
echo "INICIANDO SISTEMA - MODO UNIVERSAL"
echo "================================================"

# 0. Carregar variáveis de ambiente personalizadas (se existir)
if [ -f ".env.docker" ]; then
    echo "✅ Carregando configurações personalizadas de .env.docker"
    export $(cat .env.docker | grep -v '^#' | xargs)
    echo ""
fi

# 1. Detectar sistema operacional
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    echo "Sistema detectado: Linux"
    OS="linux"
elif [[ "$OSTYPE" == "darwin"* ]]; then
    echo "Sistema detectado: macOS"
    OS="mac"
elif [[ "$OSTYPE" == "msys" ]] || [[ "$OSTYPE" == "cygwin" ]]; then
    echo "Sistema detectado: Windows (Git Bash/Cygwin)"
    OS="windows"
else
    echo "Sistema detectado: Desconhecido (assumindo Linux)"
    OS="linux"
fi

# 2. Verificar Docker
echo ""
echo "Verificando Docker..."
if ! docker info > /dev/null 2>&1; then
    echo "❌ ERRO: Docker não está rodando!"
    echo ""
    if [[ "$OS" == "linux" ]]; then
        echo "Tente: sudo systemctl start docker"
    elif [[ "$OS" == "windows" ]]; then
        echo "Inicie o Docker Desktop no Windows"
    fi
    exit 1
fi
echo "✅ Docker está rodando"

# 3. Verificar docker-compose
echo ""
echo "Verificando docker-compose..."
if command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
    echo "✅ docker-compose encontrado"
elif docker compose version &> /dev/null; then
    COMPOSE_CMD="docker compose"
    echo "✅ docker compose (plugin) encontrado"
else
    echo "❌ ERRO: docker-compose não encontrado!"
    exit 1
fi

# 4. Parar containers antigos
echo ""
echo "Parando containers antigos..."
$COMPOSE_CMD down -v 2>/dev/null || true

# 5. Limpar volumes órfãos (opcional, mas recomendado)
echo ""
echo "Limpando volumes órfãos..."
docker volume prune -f

# 6. Build da imagem (sem cache para garantir atualização)
echo ""
echo "Fazendo build da imagem (pode demorar alguns minutos)..."
$COMPOSE_CMD build --no-cache

# 7. Iniciar containers
echo ""
echo "Iniciando containers..."
$COMPOSE_CMD up -d

# 8. Aguardar MySQL ficar pronto
echo ""
echo "Aguardando MySQL inicializar (isso pode levar até 30 segundos)..."
echo "Verificando a cada 3 segundos..."

MAX_ATTEMPTS=30
ATTEMPT=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if docker exec siteacademia_db mysqladmin ping -h localhost -uroot -proot --silent 2>/dev/null; then
        echo "✅ MySQL está pronto!"
        break
    fi
    
    ATTEMPT=$((ATTEMPT + 1))
    echo "Tentativa $ATTEMPT/$MAX_ATTEMPTS..."
    sleep 3
done

if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
    echo "⚠️  Timeout aguardando MySQL, mas containers estão rodando"
    echo "Você pode verificar manualmente: $COMPOSE_CMD logs db"
fi

# 9. Verificar status dos containers
echo ""
echo "Status dos containers:"
$COMPOSE_CMD ps

# 10. Aguardar banco estar 100% pronto
echo ""
echo "Verificando se banco está configurado..."
sleep 5

MAX_ATTEMPTS=30
ATTEMPT=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "SELECT 1 FROM usuarios LIMIT 1;" 2>/dev/null; then
        echo "✅ Banco de dados configurado e pronto!"
        break
    fi
    
    ATTEMPT=$((ATTEMPT + 1))
    echo "Tentativa $ATTEMPT/$MAX_ATTEMPTS - Aguardando banco inicializar..."
    sleep 3
done

if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
    echo "⚠️  Timeout aguardando banco, mas containers estão rodando"
    echo "Execute: ./verify-database.sh para verificar manualmente"
fi
# 11. Testar conexão via curl
echo ""
echo "Testando conexão com o site..."
sleep 2

if command -v curl &> /dev/null; then
    if curl -s http://localhost:8080/debug_config.php | grep -q "CONEXÃO ESTABELECIDA"; then
        echo "✅ CONEXÃO COM BANCO DE DADOS OK!"
    else
        echo "⚠️  Site acessível, mas verifique a conexão"
    fi
elif command -v wget &> /dev/null; then
    if wget -q -O - http://localhost:8080/debug_config.php | grep -q "CONEXÃO ESTABELECIDA"; then
        echo "✅ CONEXÃO COM BANCO DE DADOS OK!"
    else
        echo "⚠️  Site acessível, mas verifique a conexão"
    fi
else
    echo "ℹ️  curl/wget não disponível, pulando teste automático"
fi
# 12. Informações finais
echo ""
echo "================================================"
echo "✅ SISTEMA INICIADO COM SUCESSO!"
echo "================================================"
echo ""
echo "🌐 Acessos:"
echo "   Site principal: http://localhost:8080"
echo "   Debug/Teste:    http://localhost:8080/debug_config.php"
echo "   phpMyAdmin:     http://localhost:8081"
echo ""
echo "🔑 Credenciais:"
echo "   Admin: admin@mef.com / admin123"
echo "   User:  teste1@gmail.com / 12345678"
echo ""
echo "📋 Comandos úteis:"
echo "   Ver logs:           $COMPOSE_CMD logs -f"
echo "   Parar:              $COMPOSE_CMD down"
echo "   Reiniciar:          $COMPOSE_CMD restart"
echo "   Verificar banco:    ./verify-database.sh"
echo ""
echo "================================================"
echo "================================================"
