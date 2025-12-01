# 🔧 Solução: Erro "port is already allocated"

## ❌ Problema

```bash
Error response from daemon: failed to set up container networking: 
driver failed programming external connectivity on endpoint siteacademia_phpmyadmin: 
Bind for 0.0.0.0:8081 failed: port is already allocated
```

**Causa:** Alguma porta (8080, 3306 ou 8081) já está sendo usada por outro programa no seu sistema.

---

## ✅ Solução Automática (Recomendada)

### 1️⃣ Verificar quais portas estão ocupadas

```bash
# Linux/Mac
chmod +x check-ports.sh
./check-ports.sh

# Windows PowerShell
.\check-ports.ps1
```

O script mostrará:
- ✅ Portas livres
- ❌ Portas ocupadas
- 💡 Sugestões de portas alternativas

---

### 2️⃣ Criar arquivo de configuração

```bash
cp .env.example .env.docker
```

---

### 3️⃣ Editar o arquivo `.env.docker`

```bash
# Linux/Mac
nano .env.docker

# Ou use qualquer editor:
vim .env.docker
code .env.docker
gedit .env.docker
```

**Exemplo:** Se a porta 8081 está ocupada, mude para 8082:

```dotenv
# Configurações de Portas do Docker
WEB_PORT=8080
MYSQL_PORT=3306
PHPMYADMIN_PORT=8082  # ← MUDOU de 8081 para 8082
```

---

### 4️⃣ Executar novamente

```bash
# Linux/Mac
./start.sh

# Windows
.\start.ps1
```

---

## 🛠️ Solução Manual

### Descobrir qual processo está usando a porta

**Linux:**
```bash
# Verificar porta 8081
sudo lsof -i :8081

# Ou
sudo netstat -tulpn | grep 8081
```

**Mac:**
```bash
lsof -i :8081
```

**Windows PowerShell:**
```powershell
Get-NetTCPConnection -LocalPort 8081 -State Listen
```

---

### Opções:

#### A) Parar o processo que está usando a porta

**Linux/Mac:**
```bash
# Exemplo: matar processo na porta 8081
sudo kill $(lsof -t -i:8081)
```

**Windows:**
```powershell
# Ver processo
Get-Process -Id (Get-NetTCPConnection -LocalPort 8081).OwningProcess

# Parar processo (substitua <PID> pelo número)
Stop-Process -Id <PID> -Force
```

#### B) Usar porta alternativa (Recomendado)

Siga os passos da **Solução Automática** acima.

---

## 📋 Portas Padrão do Sistema

| Serviço | Porta Padrão | Variável | Descrição |
|---------|-------------|----------|-----------|
| **Site** | 8080 | `WEB_PORT` | Interface principal |
| **MySQL** | 3306 | `MYSQL_PORT` | Banco de dados |
| **phpMyAdmin** | 8081 | `PHPMYADMIN_PORT` | Gerenciador de BD |

---

## 🎯 Exemplos de Configuração

### Caso 1: Porta 8081 ocupada
```dotenv
WEB_PORT=8080
MYSQL_PORT=3306
PHPMYADMIN_PORT=8082  # Mudou para 8082
```

**Acesso:**
- Site: http://localhost:8080
- phpMyAdmin: http://localhost:8082

---

### Caso 2: Portas 8080 e 8081 ocupadas
```dotenv
WEB_PORT=9090  # Mudou para 9090
MYSQL_PORT=3306
PHPMYADMIN_PORT=9091  # Mudou para 9091
```

**Acesso:**
- Site: http://localhost:9090
- phpMyAdmin: http://localhost:9091

---

### Caso 3: Todas as portas ocupadas
```dotenv
WEB_PORT=7000
MYSQL_PORT=7001
PHPMYADMIN_PORT=7002
```

**Acesso:**
- Site: http://localhost:7000
- phpMyAdmin: http://localhost:7002

---

## 🚀 Após Configurar

1. **Limpar containers antigos:**
```bash
docker-compose down -v
```

2. **Iniciar com novas configurações:**
```bash
./start.sh  # Linux/Mac
.\start.ps1  # Windows
```

3. **Verificar se funcionou:**
```bash
docker-compose ps
```

Todos os containers devem estar **Up** ou **Healthy**.

---

## ❓ Perguntas Frequentes

### "Como saber se funcionou?"
```bash
docker-compose ps

# Deve mostrar:
# siteacademia_db         Up (healthy)
# siteacademia_web        Up
# siteacademia_phpmyadmin Up
```

### "Mudei a porta, mas ainda não funciona"
1. Parar tudo: `docker-compose down -v`
2. Verificar se o arquivo `.env.docker` está na raiz do projeto
3. Verificar se as portas no `.env.docker` estão realmente livres: `./check-ports.sh`
4. Tentar novamente: `./start.sh`

### "Qual porta escolher?"
Qualquer porta entre **1024-65535** que não esteja em uso. 
Use o script `check-ports.sh` para sugestões automáticas.

### "Posso usar porta 80?"
Sim, mas precisa de **permissões de administrador** (sudo no Linux, admin no Windows).
Recomendado usar portas acima de 1024.

---

## 📞 Ainda com Problemas?

Execute o diagnóstico completo:
```bash
./verify-database.sh
```

Ou envie a saída de:
```bash
docker-compose ps
docker-compose logs --tail=50
cat .env.docker  # se existir
```

---

**Última atualização:** 30/11/2025
