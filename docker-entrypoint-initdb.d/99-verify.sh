#!/bin/bash
set -e

echo "======================================"
echo "Verificando estado final do banco"
echo "======================================"

# Aguarda MySQL
until mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
    sleep 1
done

# Conta tabelas
TABLE_COUNT=$(mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sistema_nutricao -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='sistema_nutricao';" -s -N 2>/dev/null || echo "0")

# Conta registros
USER_COUNT=$(mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sistema_nutricao -e "SELECT COUNT(*) FROM usuarios;" -s -N 2>/dev/null || echo "0")
PROF_COUNT=$(mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sistema_nutricao -e "SELECT COUNT(*) FROM profissionais;" -s -N 2>/dev/null || echo "0")

echo ""
echo "📊 RESULTADO:"
echo "   Tabelas criadas: $TABLE_COUNT/7"
echo "   Usuários: $USER_COUNT"
echo "   Profissionais: $PROF_COUNT"
echo ""

if [ "$TABLE_COUNT" -eq 7 ] && [ "$USER_COUNT" -gt 0 ]; then
    echo "✅ BANCO CONFIGURADO COM SUCESSO!"
else
    echo "❌ PROBLEMA! Tabelas ou dados faltando"
    echo ""
    echo "Solução:"
    echo "  docker-compose down -v"
    echo "  docker-compose up -d"
fi

echo "======================================"
