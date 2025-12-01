#!/bin/bash

echo "========================================="
echo "TESTE DE CONEXÃO - AMBIENTE LINUX/WSL"
echo "========================================="
echo ""

# 1. Verificar se Docker está rodando
echo "1. Verificando Docker..."
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando!"
    echo "Execute: sudo systemctl start docker"
    exit 1
fi
echo "✅ Docker está rodando"
echo ""

# 2. Verificar se docker-compose está instalado
echo "2. Verificando docker-compose..."
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose não encontrado!"
    echo "Instale com: sudo apt install docker-compose"
    exit 1
fi
echo "✅ docker-compose instalado"
echo ""

# 3. Parar containers antigos
echo "3. Parando containers antigos..."
docker-compose down -v
echo ""

# 4. Limpar cache do Docker
echo "4. Limpando cache..."
docker system prune -f
echo ""

# 5. Rebuild completo
echo "5. Fazendo rebuild completo..."
docker-compose build --no-cache
echo ""

# 6. Iniciar containers
echo "6. Iniciando containers..."
docker-compose up -d
echo ""

# 7. Aguardar MySQL estar pronto
echo "7. Aguardando MySQL ficar pronto..."
sleep 10
echo ""

# 8. Verificar status dos containers
echo "8. Status dos containers:"
docker-compose ps
echo ""

# 9. Verificar logs do banco
echo "9. Últimas linhas do log do MySQL:"
docker-compose logs --tail=20 db
echo ""

# 10. Testar conexão
echo "10. Testando conexão via curl..."
curl -s http://localhost:8080/debug_config.php | grep -E "(✅|❌)"
echo ""

echo "========================================="
echo "Se viu '✅ CONEXÃO ESTABELECIDA', está OK!"
echo "Senão, execute: docker-compose logs web"
echo "========================================="
