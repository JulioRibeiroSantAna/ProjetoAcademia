## Credenciais de Acesso

### Administrador
- **Email:** admin@mef.com
- **Senha:** admin123
- **Perfil:** Acesso total ao sistema (gerenciar profissionais, vídeos, usuários)

### Usuário Comum
- **Email:** teste1@gmail.com
- **Senha:** 12345678
- **Perfil:** Acesso a agendamentos, vídeos de apoio e perfil pessoal

## Como Navegar no Sistema

### 1. Acesso Inicial
- Acesse `http://localhost:8080` no navegador

### 2. Como Usuário Comum (teste1@gmail.com)
Após fazer login, você terá acesso a:
- **Início:** Página principal com informações do sistema
- **Profissionais:** Visualize a equipe de profissionais da saúde
- **Vídeos de Apoio:** Assista vídeos educativos filtrados por categoria
- **Agendar Consulta:** Marque horários com profissionais
- **Meus Agendamentos:** Visualize suas consultas marcadas
- **Perfil:** Edite suas informações pessoais e foto

### 3. Como Administrador (admin@mef.com)
Além das funcionalidades do usuário, o admin pode:
- **Gerenciar Profissionais:** Adicionar, editar ou excluir profissionais
  - Cadastro com foto, especialidade, telefone e biografia
  - Validação de telefone único
- **Gerenciar Vídeos:** Upload de vídeos (até 500MB) ou links do YouTube
  - Adicionar capa/thumbnail para vídeos locais
  - Organizar por múltiplas categorias
  - Sistema de filtros estilo YouTube
- **Visualizar Todos os Agendamentos:** Acompanhe consultas de todos os usuários

### 4. Funcionalidades Principais
- **Upload de Vídeos:** Suporta arquivos até 500MB com capa personalizada
- **Filtros Múltiplos:** Selecione várias categorias ao mesmo tempo
- **Validação de Telefone:** Sistema impede cadastros duplicados
- **Máscara de Telefone:** Formato automático (51) 99999-9999
- **Perfis Personalizados:** Upload de foto de perfil para usuários e profissionais
- **Sistema Responsivo:** Funciona em desktop, tablet e celular

### 5. Dicas de Uso
- Para adicionar um vídeo local, selecione "Arquivo do PC" e faça upload da capa também
- Use os filtros por categoria para encontrar vídeos específicos rapidamente
- Edite seu perfil clicando no ícone do usuário no menu superior
- Administradores veem botões extras de edição e exclusão nos vídeos e profissionais

---

## 🐧 Instalação no WSL/Linux

### Pré-requisitos
- Docker e Docker Compose instalados
- WSL 2 (para Windows)

### Passos para Instalação

1. **Clone o repositório:**
```bash
git clone https://github.com/JulioRibeiroSantAna/ProjetoAcademia.git
cd ProjetoAcademia
```

2. **Inicie os containers Docker:**
```bash
docker-compose up -d
```

3. **Verifique se os containers estão rodando:**
```bash
docker-compose ps
```

Você deve ver 3 containers ativos:
- `siteacademia_web` (porta 8080)
- `siteacademia_db` (porta 3306)
- `siteacademia_phpmyadmin` (porta 8081)

4. **Acesse o sistema:**
- **Site:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081
  - Usuário: `root`
  - Senha: `root`

### Troubleshooting WSL

**Erro "Connection refused" ao acessar o site:**

1. **Verifique se todos os containers estão rodando:**
```bash
docker-compose ps
```

Todos devem estar "Up" e "healthy".

2. **Teste a conexão com banco de dados:**
- Acesse: http://localhost:8080/test_connection.php
- Este script testa todas as configurações possíveis

3. **Aguarde o banco inicializar completamente:**
O MySQL pode levar 30-60 segundos para iniciar na primeira vez.
```bash
# Aguarde até ver "ready for connections"
docker-compose logs -f db
```

4. **Reconstrua os containers:**
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

5. **Verifique conectividade entre containers:**
```bash
# De dentro do container web, tenta pingar o banco
docker exec -it siteacademia_web ping -c 3 db
```

**Erro de conexão com banco de dados:**
- Certifique-se que o Docker Desktop está rodando
- Verifique se as portas 8080, 3306 e 8081 não estão em uso:
```bash
sudo netstat -tulpn | grep -E '8080|3306|8081'
```

**Recriar do zero:**
```bash
docker-compose down -v
docker-compose up -d
```

**Ver logs de erro:**
```bash
docker-compose logs -f web
docker-compose logs -f db
```

### Estrutura Docker

O projeto usa 3 containers:
- **web:** Apache + PHP 8.2
- **db:** MySQL 8.0 (banco já povoado automaticamente)
- **phpmyadmin:** Interface web para gerenciar banco

O banco de dados é automaticamente populado com:
- ✅ 4 profissionais cadastrados
- ✅ 3 usuários (1 admin + 2 usuários)
- ✅ Horários e agendamentos de exemplo
- ✅ Vídeos educativos

---

### Infos Antigas

index: Página de quando o usúario não está logado, ele poderá futuramnte fazer login ou se cadastrar e acessar opções da menubar.

Pasta UsuarioLogado / logado: Nesta página é ondeo usuário Logado poderá acessar o menu que contém também o submenu na opção profissionais, navegando em diversas telas.

Pasta AdmLogado  / logado-Adm: Esta página é do Adm, ao invés de adicionar novas telas eu adicionei as telas existentes novas opções seguindo a ideia original do projeto, você pode visualizar isso em videos de apoio e bate-papo.

