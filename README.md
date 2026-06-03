# Manage API Keys
## Requisitos

- Docker
- Docker Compose

## Configuracion local

1. Copia el archivo de ejemplo:

cp .env.example .env (el usr y pass deben coincidir con los de docker-compose.yaml)

2. Completa los valores de `.env` con tus datos locales (dejar el host y nombre de la bd que esta por defecto)

## Levantar con Docker

1. Construye y levanta los contenedores:

docker compose up --build (en caso de que marque error de permisos corre como sudo)


3. La API queda disponible en:

```bash
http://localhost:8000
```

## Endpoints

### Obtener keys

```bash
curl -X GET http://localhost:8000/api/keys
```

### Crear key

```bash
curl -X POST http://localhost:8000/api/keys \
  -H "Content-Type: application/json" \
  -d '{"name":"mi_api_key"}'
```

### Revocar key

```bash
curl -X POST http://localhost:8000/api/keys/1/revoke
```

### Validar key

```bash
curl -X POST http://localhost:8000/api/keys/validate \
  -H "Content-Type: application/json" \
  -d '{"key":"ak_tu_clave_real"}'

