#!/bin/bash
# Script para aguardar MySQL ficar pronto

set -e

host="$1"
shift
cmd="$@"

echo "Aguardando MySQL em $host ficar disponível..."

until mysql -h"$host" -u"root" -p"root" -e "SELECT 1" >/dev/null 2>&1; do
  echo "MySQL ainda não está pronto - aguardando..."
  sleep 2
done

echo "MySQL está pronto! Executando comando..."
exec $cmd
