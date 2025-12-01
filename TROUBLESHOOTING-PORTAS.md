# 🔧 Fix: Port Already Allocated Error

## Error
```
Bind for 0.0.0.0:8081 failed: port is already allocated
```

## Solution

### Option 1: Create `.env.docker`
```bash
cat > .env.docker << EOF
WEB_PORT=8080
MYSQL_PORT=3306
PHPMYADMIN_PORT=8082
EOF

./start.sh
```

### Option 2: Inline variables
```bash
PHPMYADMIN_PORT=8082 docker compose up -d
```

### Option 3: Edit `docker-compose.yml`
```yaml
phpmyadmin:
  ports:
    - "8082:80"  # Change from 8081
```

## Port Variables

| Variable | Default | Service |
|----------|---------|---------|
| `WEB_PORT` | 8080 | Website |
| `MYSQL_PORT` | 3306 | Database |
| `PHPMYADMIN_PORT` | 8081 | phpMyAdmin |
