# Prueba técnica - Backend PHP
### por Fernando Frontera para LVP

#### Requisitos

- Docker
- Docker Compose
- Base de datos MySQL. El Docker Compose está configurado para levantar un contenedor de MySQL y tomar esta conexión por defecto.

#### Instrucciones de ejecución:

- Construya la imagen de Docker: `docker-compose build`. El tag por defecto de la imagen es `lvp:latest`.
- Configure las variables de entorno del contenedor con la información de acceso a la base de datos:
  - `DB_HOST`: Host de la base de datos
  - `DB_PORT`: Puerto de conexión
  - `DB_NAME`: Nombre de la base de datos
  - `DB_USER`: Usuario de acceso
  - `DB_PASSWORD`: Contraseña
- Levante el contenedor: `docker-compose up -d`. La aplicación se levantará en http://localhost:80
- Ejecute la migración de la base de datos: `docker-compose exec lvp migrate`
- Si desea ejecutar las pruebas de la aplicación puede ejecutar el siguiente comando: `docker-compose exec lvp test`

#### Referencia de la API:

- **Crear apuesta:**
  - Endpoint: `/apuestas`
  - Method: POST
  - Request body:
  ```
  {
    "email": "fernandodf91@gmail.com",
    "nombre": "Fernando Frontera",
    "kills": 31
  }
  ```
    
- **Consultar apuestas:**
  - Endpoint: `/apuestas`
  - Method: GET
  
- **Ver estadísticas:**
  - Endpoint: `/apuestas/stats`
  - Method: GET

- **Consultar ganador:**
  - Endpoint: `/apostadores/ganador/{killsTotales}`
  - Method: GET
  - Query params:
    - killsTotales: número positivo