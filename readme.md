# budowanie kontenerów:
```bash
docker compose -f docker-compose.yaml build --no-cache
```

### usuwanie kontenerów 
```bash
docker compose -f docker-compose.yaml down
```

# uruchomienie kontenerów:
```bash
docker compose -f docker-compose.yaml -p docker up -d
```

### zatrzymanie kontenerów
```bash
docker compose -f docker-compose.yaml stop
```

# php83
### wejście do bash contenera php83 jako zwykły user:
```bash
docker compose -f docker-compose.yaml exec -w /var/www e4y_php83 bash
```

### wejście do bash contenera php83 jako root:
```bash
docker compose -f docker-compose.yaml exec -w /var/www -u root e4y_php83 bash
```
