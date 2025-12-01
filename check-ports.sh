#!/bin/bash

# Script para verificar portas em uso e sugerir alternativas

echo "🔍 Verificando portas utilizadas pelo sistema..."
echo ""

PORTS=(8080 3306 8081)
PORT_NAMES=("Web (Site)" "MySQL" "phpMyAdmin")
ENV_VARS=("WEB_PORT" "MYSQL_PORT" "PHPMYADMIN_PORT")

OCCUPIED=()

for i in "${!PORTS[@]}"; do
    PORT="${PORTS[$i]}"
    NAME="${PORT_NAMES[$i]}"
    ENV_VAR="${ENV_VARS[$i]}"
    
    # Verifica se a porta está em uso
    if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null 2>&1 || netstat -tuln 2>/dev/null | grep -q ":$PORT "; then
        echo "❌ Porta $PORT ($NAME) está OCUPADA"
        OCCUPIED+=("$ENV_VAR=$PORT")
        
        # Sugere porta alternativa
        SUGGESTED=$((PORT + 1))
        while lsof -Pi :$SUGGESTED -sTCP:LISTEN -t >/dev/null 2>&1 || netstat -tuln 2>/dev/null | grep -q ":$SUGGESTED "; do
            SUGGESTED=$((SUGGESTED + 1))
        done
        echo "   💡 Sugestão: Use porta $SUGGESTED"
        echo "   📝 Comando: echo '$ENV_VAR=$SUGGESTED' >> .env.docker"
        echo ""
    else
        echo "✅ Porta $PORT ($NAME) está LIVRE"
    fi
done

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ ${#OCCUPIED[@]} -eq 0 ]; then
    echo "✅ Todas as portas estão livres!"
    echo "Pode executar: ./start.sh"
else
    echo "⚠️  ${#OCCUPIED[@]} porta(s) ocupada(s)"
    echo ""
    echo "SOLUÇÃO RÁPIDA:"
    echo "───────────────"
    echo "1. Criar arquivo de configuração:"
    echo "   cp .env.example .env.docker"
    echo ""
    echo "2. Adicionar no .env.docker:"
    for var in "${OCCUPIED[@]}"; do
        PORT="${var#*=}"
        SUGGESTED=$((PORT + 1))
        while lsof -Pi :$SUGGESTED -sTCP:LISTEN -t >/dev/null 2>&1 || netstat -tuln 2>/dev/null | grep -q ":$SUGGESTED "; do
            SUGGESTED=$((SUGGESTED + 1))
        done
        ENV_NAME="${var%=*}"
        echo "   $ENV_NAME=$SUGGESTED"
    done
    echo ""
    echo "3. Executar novamente:"
    echo "   ./start.sh"
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
