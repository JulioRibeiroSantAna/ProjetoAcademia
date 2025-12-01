# 🏥 MEF - Sistema de Gestão de Saúde e Nutrição

Sistema web completo para gerenciamento de consultas nutricionais com upload de vídeos educativos.

## 🚀 Instalação Rápida

### Windows (PowerShell)
```powershell
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
.\start.ps1
```

### Linux/WSL/Mac
```bash
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
chmod +x start.sh
./start.sh
```

### ⚠️ Se der erro "port is already allocated"
**Solução:** Alguma porta já está em uso no seu sistema.

```bash
# Copie o arquivo de exemplo
cp .env.example .env.docker

# Edite e mude a porta que está ocupada:
# Exemplo: Se porta 8081 está ocupada, mude para 8082
nano .env.docker  # ou vim, code, gedit, etc
```

Depois execute novamente:
```bash
./start.sh  # ou .\start.ps1 no Windows
```

## 🔑 Credenciais

**Admin:** admin@mef.com / admin123  
**Usuário:** teste1@gmail.com / 12345678  
**phpMyAdmin:** http://localhost:8081 (root / root)

---

## ⚡ Funcionalidades

### Usuários
- Agendamento de consultas
- Vídeos educativos com filtros
- Gerenciamento de perfil
- Upload de foto

### Administradores
- Gerenciar profissionais (CRUD completo)
- Upload de vídeos (500MB) ou links YouTube
- Thumbnails personalizados
- Visualizar todos agendamentos
- Sistema de categorias múltiplas

---

## 🛠️ Tecnologias

- PHP 8.2 + Apache
- MySQL 8.0
- Docker + Docker Compose
- Bootstrap 5
- JavaScript ES6+

---

## 📦 Estrutura

```
web (PHP 8.2 + Apache) → porta 8080
db (MySQL 8.0) → porta 3306
phpmyadmin → porta 8081
```

Sistema com **retry automático** e **healthcheck** - garante conexão em qualquer ambiente!


**Debug:**
- http://localhost:8080/debug_config.php
- Veja `TROUBLESHOOTING-LINUX.md`

---

## 📝 Banco de Dados

Populado automaticamente com:
- ✅ 4 profissionais
- ✅ 3 usuários (1 admin + 2 comuns)
- ✅ Agendamentos de exemplo
- ✅ Vídeos educativos

