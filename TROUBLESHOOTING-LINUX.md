# 🐧 Troubleshooting - Linux/WSL

## Problema: "Connection refused" ou erro ao conectar no banco

### Solução Rápida (Automatizada)

```bash
# Dar permissão de execução
chmod +x test-linux.sh

# Executar teste completo
./test-linux.sh
```

---

## Solução Manual (Passo a Passo)

### 1. Verificar se Docker está rodando

```bash
# Verificar status
docker info

# Se não estiver rodando, inicie:
sudo systemctl start docker

# Adicionar usuário ao grupo docker (evita usar sudo)
sudo usermod -aG docker $USER
newgrp docker
```

### 2. Limpar ambiente completamente

```bash
# Parar todos os containers
docker-compose down -v

# Remover volumes órfãos
docker volume prune -f

# Limpar cache do Docker
docker system prune -f
```

### 3. Verificar permissões de arquivos

```bash
# No WSL, às vezes arquivos do Windows têm permissões erradas
# Verificar se você tem permissão de leitura/escrita
ls -la

# Se necessário, ajustar permissões
chmod -R 755 .
```

### 4. Rebuild completo (sem cache)

```bash
# Build do zero
docker-compose build --no-cache

# Iniciar containers
docker-compose up -d
```

### 5. Verificar se containers estão rodando

```bash
# Ver status
docker-compose ps

# Deve mostrar:
# - siteacademia_db (healthy)
# - siteacademia_web (running)
# - siteacademia_phpmyadmin (running)
```

### 6. Verificar logs em tempo real

```bash
# Logs do banco de dados
docker-compose logs -f db

# Logs do PHP
docker-compose logs -f web

# Todos os logs
docker-compose logs -f
```

### 7. Testar conexão manualmente

```bash
# Acessar container do MySQL
docker exec -it siteacademia_db mysql -uroot -proot

# Dentro do MySQL, execute:
SHOW DATABASES;
USE sistema_nutricao;
SHOW TABLES;
SELECT * FROM usuarios;
exit
```

### 8. Testar conexão do PHP

```bash
# Entrar no container web
docker exec -it siteacademia_web bash

# Dentro do container, teste:
php -r "echo 'Host: db\n';"
ping -c 3 db
exit
```

### 9. Acessar página de debug

Abra no navegador:
- http://localhost:8080/debug_config.php

Deve mostrar:
- ✅ config.php carregado
- ✅ Todas as constantes definidas
- ✅ DSN correto: `mysql:host=db;dbname=sistema_nutricao;charset=utf8mb4`
- ✅ CONEXÃO ESTABELECIDA COM SUCESSO!

---

## Problemas Específicos do WSL

### Problema: Porta 8080 já em uso

```bash
# Ver o que está usando a porta
sudo lsof -i :8080

# Matar processo se necessário
sudo kill -9 <PID>

# Ou mudar porta no docker-compose.yml:
# ports:
#   - "8081:80"  # Usar 8081 em vez de 8080
```

### Problema: Docker daemon não inicia

```bash
# No WSL2, certifique-se que Docker Desktop está rodando no Windows

# Ou instale Docker direto no WSL:
sudo apt update
sudo apt install docker.io docker-compose
sudo systemctl start docker
sudo systemctl enable docker
```

### Problema: Rede não funciona

```bash
# Reiniciar rede do Docker
docker network prune
docker-compose down
docker-compose up -d
```

### Problema: Volumes não montam corretamente

```bash
# No WSL, use caminho absoluto Linux
# Edite docker-compose.yml:
volumes:
  - /mnt/c/xampp/htdocs/SiteAcademia:/var/www/html
```

---

## Verificação Final

Execute este comando para ver se tudo está OK:

```bash
echo "=== STATUS DOS CONTAINERS ==="
docker-compose ps
echo ""
echo "=== TESTE DE CONEXÃO ==="
curl -s http://localhost:8080/debug_config.php | grep -A5 "Teste de conexão"
echo ""
echo "=== ACESSOS ==="
echo "Site: http://localhost:8080"
echo "phpMyAdmin: http://localhost:8081"
echo "Debug: http://localhost:8080/debug_config.php"
```

---

## Ainda não funciona?

1. **Compartilhe os logs:**
   ```bash
   docker-compose logs > logs.txt
   ```

2. **Verifique versão do Docker:**
   ```bash
   docker --version
   docker-compose --version
   ```

3. **Teste conexão de rede:**
   ```bash
   docker network inspect siteacademia_siteacademia_network
   ```

4. **Entre no container e teste manualmente:**
   ```bash
   docker exec -it siteacademia_web bash
   apt update && apt install -y iputils-ping
   ping db
   ```

---

## Credenciais do Sistema

**Banco de Dados (dentro do Docker):**
- Host: `db`
- Database: `sistema_nutricao`
- User: `user`
- Password: `password`

**phpMyAdmin:**
- URL: http://localhost:8081
- User: `root`
- Password: `root`

**Login no site:**
- Admin: `admin@mef.com` / `admin123`
- User: `teste@teste.com` / `teste123`
