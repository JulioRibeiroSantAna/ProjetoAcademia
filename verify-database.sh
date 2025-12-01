#!/bin/bash
# Script para verificar e corrigir configuração do banco

echo "========================================="
echo "VERIFICAÇÃO DO BANCO DE DADOS"
echo "========================================="

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

check_mysql() {
    docker exec siteacademia_db mysqladmin ping -uroot -proot --silent 2>/dev/null
    return $?
}

check_database() {
    docker exec siteacademia_db mysql -uroot -proot -e "USE sistema_nutricao; SELECT 1;" 2>/dev/null
    return $?
}

check_tables() {
    TABLES=$(docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -sN -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'sistema_nutricao';")
    echo $TABLES
}

check_data() {
    USERS=$(docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -sN -e "SELECT COUNT(*) FROM usuarios;" 2>/dev/null)
    echo $USERS
}

echo -e "${YELLOW}1. Verificando se MySQL está rodando...${NC}"
if check_mysql; then
    echo -e "${GREEN}✅ MySQL está rodando${NC}"
else
    echo -e "${RED}❌ MySQL não está respondendo${NC}"
    exit 1
fi

echo -e "\n${YELLOW}2. Verificando banco de dados...${NC}"
if check_database; then
    echo -e "${GREEN}✅ Banco 'sistema_nutricao' existe${NC}"
else
    echo -e "${RED}❌ Banco 'sistema_nutricao' não existe${NC}"
    echo "Criando banco..."
    docker exec siteacademia_db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS sistema_nutricao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
fi

echo -e "\n${YELLOW}3. Verificando tabelas...${NC}"
TABLE_COUNT=$(check_tables)
if [ "$TABLE_COUNT" -gt 0 ]; then
    echo -e "${GREEN}✅ $TABLE_COUNT tabelas encontradas${NC}"
else
    echo -e "${RED}❌ Nenhuma tabela encontrada${NC}"
    echo "Importando estrutura..."
    docker exec -i siteacademia_db mysql -uroot -proot sistema_nutricao < docker-entrypoint-initdb.d/sistema_nutricao.sql
fi

echo -e "\n${YELLOW}4. Verificando dados...${NC}"
USER_COUNT=$(check_data)
if [ "$USER_COUNT" -gt 0 ]; then
    echo -e "${GREEN}✅ $USER_COUNT usuários encontrados${NC}"
else
    echo -e "${YELLOW}⚠️  Nenhum usuário encontrado (isso pode ser normal se for primeira inicialização)${NC}"
fi

echo -e "\n${YELLOW}5. Testando conexão PHP...${NC}"
sleep 2
RESPONSE=$(curl -s http://localhost:8080/debug_config.php | grep -o "CONEXÃO ESTABELECIDA")
if [ ! -z "$RESPONSE" ]; then
    echo -e "${GREEN}✅ Conexão PHP funcionando${NC}"
else
    echo -e "${RED}❌ Conexão PHP com problemas${NC}"
fi

echo -e "\n========================================="
echo -e "${GREEN}VERIFICAÇÃO CONCLUÍDA!${NC}"
echo -e "========================================="
echo ""
echo "📊 Resumo:"
echo "  - MySQL: Rodando"
echo "  - Banco: sistema_nutricao"
echo "  - Tabelas: $TABLE_COUNT"
echo "  - Usuários: $USER_COUNT"
echo ""
echo "🌐 Acessos:"
echo "  - Site: http://localhost:8080"
echo "  - Debug: http://localhost:8080/debug_config.php"
echo "  - phpMyAdmin: http://localhost:8081 (root/root)"
echo ""
