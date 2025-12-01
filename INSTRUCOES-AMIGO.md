# 📋 Instruções para Rodar no Linux/WSL

## ⚡ Método Rápido (Recomendado)

```bash
# 1. Clone ou atualize o repositório
git pull origin main

# 2. Execute o script de teste automatizado
chmod +x test-linux.sh
./test-linux.sh
```

O script vai:
- ✅ Verificar se Docker está instalado e rodando
- ✅ Limpar containers antigos
- ✅ Fazer rebuild completo sem cache
- ✅ Iniciar os containers
- ✅ Aguardar MySQL ficar pronto
- ✅ Testar a conexão automaticamente

---

## 🔧 Método Manual

### Passo 1: Verificar Docker
```bash
docker info
```

Se der erro, inicie o Docker:
```bash
sudo systemctl start docker
```

### Passo 2: Limpar ambiente
```bash
cd /caminho/do/projeto
docker-compose down -v
docker system prune -f
```

### Passo 3: Rebuild e start
```bash
docker-compose build --no-cache
docker-compose up -d
```

### Passo 4: Verificar status
```bash
docker-compose ps
```

Deve mostrar:
```
NAME                      STATUS
siteacademia_db          Up (healthy)
siteacademia_web         Up
siteacademia_phpmyadmin  Up
```

### Passo 5: Testar conexão
Abra no navegador:
- **Debug:** http://localhost:8080/debug_config.php
- **Site:** http://localhost:8080

Se aparecer **✅ CONEXÃO ESTABELECIDA**, está funcionando!

---

## ❌ Ainda dá erro?

### Ver logs em tempo real
```bash
# Ver todos os logs
docker-compose logs -f

# Apenas do banco
docker-compose logs -f db

# Apenas do PHP
docker-compose logs -f web
```

### Entrar no container e testar manualmente
```bash
# Entrar no container web
docker exec -it siteacademia_web bash

# Dentro do container, testar conexão com banco
ping db
php -r "echo 'Testando...\n'; \$pdo = new PDO('mysql:host=db;dbname=sistema_nutricao', 'user', 'password'); echo 'Conectado!\n';"
exit
```

### Verificar rede do Docker
```bash
docker network ls
docker network inspect siteacademia_siteacademia_network
```

### Portas em uso
```bash
sudo netstat -tulpn | grep -E '8080|3306|8081'
```

---

## 📖 Documentação Completa

Veja `TROUBLESHOOTING-LINUX.md` para guia completo com todas as soluções possíveis.

---

## 🔑 Credenciais para Testar

**Admin:**
- Email: admin@mef.com
- Senha: admin123

**Usuário:**
- Email: teste1@gmail.com  
- Senha: 12345678

**phpMyAdmin (http://localhost:8081):**
- Usuário: root
- Senha: root

---

## ⚙️ Configuração do Banco (Dentro do Docker)

O PHP conecta usando:
- Host: `db` (não use localhost!)
- Database: `sistema_nutricao`
- User: `user`
- Password: `password`

**IMPORTANTE:** O código PHP roda DENTRO do container, então ele usa `db` como hostname (nome do serviço no docker-compose), não `localhost`.

---

## 🆘 Precisa de Ajuda?

1. Execute o script de teste: `./test-linux.sh`
2. Acesse o debug: http://localhost:8080/debug_config.php
3. Copie os logs: `docker-compose logs > logs.txt`
4. Envie o arquivo logs.txt

---

## ✅ Checklist Final

- [ ] Docker está instalado: `docker --version`
- [ ] Docker está rodando: `docker info`
- [ ] Repositório atualizado: `git pull origin main`
- [ ] Containers foram recriados: `docker-compose down -v && docker-compose up -d`
- [ ] Containers estão healthy: `docker-compose ps`
- [ ] Debug mostra sucesso: http://localhost:8080/debug_config.php
- [ ] Site carrega: http://localhost:8080
- [ ] Consegue fazer login: admin@mef.com / admin123
