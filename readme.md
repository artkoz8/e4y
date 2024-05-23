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
# baza
### twrozenie bazy 
```bash
bin/console doctrine:database:create -f
```

### twrozenie schemy
```bash
bin/console doctrine:schema:create -f
```

### ładowanie przygotowanych danych do bazy
```bash
bin/console doctrine:fixtures:load -n --group=dev
```