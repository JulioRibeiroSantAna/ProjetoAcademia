# 🚀 GUIA DE INSTALAÇÃO UNIVERSAL

## ✅ Funciona em QUALQUER sistema operacional!

Este sistema agora está configurado para funcionar em:
- ✅ Windows (PowerShell, CMD, Git Bash)
- ✅ Linux (Ubuntu, Debian, Fedora, etc.)
- ✅ WSL (Windows Subsystem for Linux)
- ✅ macOS

---

## 📦 Pré-requisitos

Apenas **Docker** e **Docker Compose** precisam estar instalados:

### Windows
1. Instale [Docker Desktop](https://www.docker.com/products/docker-desktop)
2. Certifique-se que o Docker Desktop está rodando

### Linux/WSL
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install docker.io docker-compose
sudo systemctl start docker
sudo usermod -aG docker $USER
newgrp docker

# Fedora
sudo dnf install docker docker-compose
sudo systemctl start docker
sudo usermod -aG docker $USER
```

### macOS
1. Instale [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop)
2. Certifique-se que o Docker Desktop está rodando

---

## 🚀 Instalação Automática (RECOMENDADO)

### Windows (PowerShell)
```powershell
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
.\start.ps1
```

### Windows (CMD)
```cmd
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
start.bat
```

### Linux/WSL/Mac
```bash
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
chmod +x start.sh
./start.sh
```

O script automático faz TUDO:
- ✅ Verifica se Docker está rodando
- ✅ Para containers antigos
- ✅ Limpa cache e volumes
- ✅ Faz rebuild completo sem cache
- ✅ Inicia os containers
- ✅ Aguarda MySQL ficar 100% pronto
- ✅ Testa a conexão automaticamente
- ✅ Mostra os acessos e credenciais

---

## ⚙️ Instalação Manual

Se preferir fazer manualmente:

```bash
# 1. Clone o repositório
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia

# 2. Limpe ambiente anterior (se existir)
docker-compose down -v
docker volume prune -f

# 3. Build sem cache
docker-compose build --no-cache

# 4. Inicie os containers
docker-compose up -d

# 5. Aguarde 20-30 segundos para MySQL inicializar

# 6. Verifique se está rodando
docker-compose ps
```

---

## 🌐 Acessos

Após a instalação, acesse:

- **🏠 Site:** http://localhost:8080
- **🔧 Debug/Diagnóstico:** http://localhost:8080/debug_config.php
- **💾 phpMyAdmin:** http://localhost:8081

---

## 🔑 Credenciais

### Login no Site
**Administrador:**
- Email: `admin@mef.com`
- Senha: `admin123`

**Usuário:**
- Email: `teste1@gmail.com`
- Senha: `12345678`

### phpMyAdmin
- Usuário: `root`
- Senha: `root`

---

## 🔍 Verificação

Para garantir que tudo está funcionando:

1. **Verificar containers rodando:**
```bash
docker-compose ps
```
Deve mostrar 3 containers UP (db deve estar "healthy")

2. **Teste de conexão:**
Acesse http://localhost:8080/debug_config.php
Deve mostrar: **✅ CONEXÃO ESTABELECIDA COM SUCESSO!**

3. **Ver logs:**
```bash
# Todos os logs
docker-compose logs -f

# Apenas MySQL
docker-compose logs -f db

# Apenas PHP/Apache
docker-compose logs -f web
```

---

## 🛠️ Comandos Úteis

```bash
# Ver status dos containers
docker-compose ps

# Ver logs em tempo real
docker-compose logs -f

# Parar containers
docker-compose down

# Parar e remover volumes (reset completo)
docker-compose down -v

# Reiniciar containers
docker-compose restart

# Entrar no container do banco
docker exec -it siteacademia_db bash

# Entrar no container web
docker exec -it siteacademia_web bash

# Executar SQL diretamente
docker exec -it siteacademia_db mysql -uroot -proot sistema_nutricao
```

---

## ❌ Problemas Comuns

### Porta 8080 já em uso
```bash
# Ver o que está usando a porta
sudo lsof -i :8080  # Linux/Mac
netstat -ano | findstr :8080  # Windows

# Matar processo ou mudar porta no docker-compose.yml:
# ports:
#   - "8081:80"  # Usar 8081 em vez de 8080
```

### Docker não está rodando
```bash
# Linux
sudo systemctl start docker

# Windows/Mac
# Inicie o Docker Desktop
```

### Containers não iniciam
```bash
# Reset completo
docker-compose down -v
docker system prune -a -f
docker volume prune -f

# Rebuild do zero
docker-compose build --no-cache
docker-compose up -d
```

### "Connection refused" no navegador
```bash
# Aguarde 30 segundos após docker-compose up
# Verifique logs
docker-compose logs web
docker-compose logs db

# Teste de conexão manual
docker exec -it siteacademia_web curl http://localhost:8080/debug_config.php
```

### MySQL não fica "healthy"
```bash
# Ver logs do MySQL
docker-compose logs db

# Remover volumes e recriar
docker-compose down -v
docker volume rm siteacademia_db_data 2>/dev/null || true
docker-compose up -d
```

---

## 🔄 Atualizar o Sistema

Para pegar as últimas mudanças do GitHub:

```bash
# Parar containers
docker-compose down

# Atualizar código
git pull origin main

# Rebuild e reiniciar
docker-compose build --no-cache
docker-compose up -d
```

---

## 🏗️ Arquitetura

O sistema usa 3 containers Docker:

1. **web** (PHP 8.2 + Apache)
   - Porta: 8080
   - Aguarda automaticamente MySQL ficar pronto
   - Retry automático de conexão

2. **db** (MySQL 8.0)
   - Porta: 3306
   - Healthcheck integrado
   - Banco populado automaticamente

3. **phpmyadmin**
   - Porta: 8081
   - Interface web para gerenciar banco

**Rede:** `siteacademia_network` (bridge)
**Volumes:** `db_data` (persistência do banco)

---

## ✨ Melhorias Implementadas

Este sistema tem proteções contra falhas:

1. ✅ **Retry automático de conexão** - Se MySQL não estiver pronto, PHP aguarda
2. ✅ **Healthcheck no MySQL** - Container web só sobe quando banco está OK
3. ✅ **Script de espera no container** - Usa netcat para testar porta 3306
4. ✅ **Scripts de instalação para cada OS** - Windows, Linux, Mac
5. ✅ **Configuração robusta do MySQL** - Parâmetros otimizados
6. ✅ **Logs detalhados** - Fácil diagnóstico de problemas

---

## 📞 Suporte

Se ainda assim não funcionar:

1. Execute o script de diagnóstico automático:
   - Windows: `.\start.ps1`
   - Linux/Mac: `./start.sh`

2. Copie os logs:
```bash
docker-compose logs > logs.txt
```

3. Envie os logs e descrição do problema

---

## 📝 Notas Importantes

- ⚠️ Primeira inicialização demora ~2 minutos (download de imagens)
- ⚠️ MySQL precisa de 20-30 segundos para inicializar completamente
- ⚠️ Use sempre `docker-compose down -v` para limpar completamente
- ✅ Sistema testado em Windows 10/11, Ubuntu 20.04/22.04, macOS
- ✅ Funciona com Docker versão 20+ e Docker Compose 1.27+

---

## 🎉 Pronto para Usar!

Agora é só executar o script de instalação do seu sistema operacional e começar a usar! 🚀
