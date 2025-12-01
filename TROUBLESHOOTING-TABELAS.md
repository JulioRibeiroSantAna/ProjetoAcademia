# 🔧 Solução: Tabelas não estão sendo criadas

## ❌ Problema

Banco aparece como **healthy** mas as tabelas não existem ou estão vazias.

```bash
docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "SHOW TABLES;"
# Resultado: vazio ou erro
```

---

## 🎯 Causa

O MySQL só executa scripts em `/docker-entrypoint-initdb.d/` na **PRIMEIRA VEZ** que o volume é criado.

Se você já rodou `docker compose up` antes, o volume antigo é mantido e o SQL **não roda novamente**.

---

## ✅ Solução Definitiva

### 1. Remover TUDO e recomeçar

```bash
# Linux/Mac/WSL
docker compose down -v
docker compose up -d

# Windows PowerShell
docker-compose down -v
docker-compose up -d
```

**⚠️ O `-v` é OBRIGATÓRIO!** Remove os volumes antigos.

---

### 2. Aguardar inicialização completa

```bash
# Verificar status
docker compose ps

# Aguarde até ver:
# siteacademia_db  Up (healthy)
```

Pode levar **30-60 segundos**.

---

### 3. Verificar se funcionou

```bash
# Verificar tabelas
docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "SHOW TABLES;"

# Deve mostrar 7 tabelas:
# - usuarios
# - profissionais
# - topicos
# - videos
# - videos_topicos
# - agendamentos
# - horarios_profissionais
```

---

## 🔍 Debug Avançado

### Ver logs da criação do banco

```bash
docker compose logs db | grep "sistema_nutricao.sql"

# Deve mostrar:
# running /docker-entrypoint-initdb.d/sistema_nutricao.sql
```

### Ver se há erros

```bash
docker compose logs db | grep ERROR
```

### Verificar dados inseridos

```bash
docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "
  SELECT 'Usuarios' as tabela, COUNT(*) as total FROM usuarios
  UNION ALL
  SELECT 'Profissionais', COUNT(*) FROM profissionais
  UNION ALL
  SELECT 'Videos', COUNT(*) FROM videos;
"

# Deve mostrar:
# Usuarios: 3
# Profissionais: 4
# Videos: 6
```

---

## 🚨 Se AINDA não funcionar

### Verificar se o SQL está correto

```bash
# Ver conteúdo do arquivo SQL
cat docker-entrypoint-initdb.d/sistema_nutricao.sql | head -20

# Deve começar com:
# USE sistema_nutricao;
# CREATE TABLE IF NOT EXISTS `usuarios` (
```

### Executar SQL manualmente

```bash
# Copiar SQL para dentro do container
docker cp docker-entrypoint-initdb.d/sistema_nutricao.sql siteacademia_db:/tmp/

# Executar manualmente
docker exec siteacademia_db mysql -uroot -proot < /tmp/sistema_nutricao.sql

# Verificar resultado
docker exec siteacademia_db mysql -uroot -proot sistema_nutricao -e "SHOW TABLES;"
```

---

## 📋 Checklist Final

Antes de reportar problema, verifique:

- [ ] Executou `docker compose down -v` (com `-v`!)
- [ ] Container `db` está **healthy** (`docker compose ps`)
- [ ] Aguardou 30-60 segundos após `up -d`
- [ ] Arquivo `sistema_nutricao.sql` existe em `docker-entrypoint-initdb.d/`
- [ ] Sem erros nos logs (`docker compose logs db | grep ERROR`)

---

## 💡 Explicação Técnica

**Por que `-v` é necessário?**

```bash
docker compose down     # Para containers, MANTÉM volumes
docker compose down -v  # Para containers E REMOVE volumes
```

Volumes do Docker persistem dados entre reinicializações. Isso é **ótimo** para produção, mas **ruim** quando você quer recriar o banco do zero.

Scripts em `/docker-entrypoint-initdb.d/` só rodam se o diretório `/var/lib/mysql` estiver **vazio**. Com volumes antigos, ele já tem dados e pula a inicialização.

---

**Última atualização:** 30/11/2025
