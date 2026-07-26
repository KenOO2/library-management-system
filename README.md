# 📚 Sistema de Gestión de Biblioteca

Mini-aplicación en PHP (POO) + MySQL para gestionar libros, usuarios y préstamos, dockerizada para levantarse con un solo comando.

## Requisitos previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y corriendo.

No necesitas instalar PHP, MySQL, XAMPP ni nada más en tu máquina — todo corre dentro de los contenedores.

## Cómo ejecutar el proyecto

1. Clona o descarga este repositorio.

2. Abre una terminal (PowerShell, CMD o similar) en la carpeta raíz del proyecto (donde está el archivo `docker-compose.yml`).

3. Levanta los contenedores:

   ```bash
   docker compose up -d --build
   ```

   Esto va a:
   - Construir la imagen de PHP con la extensión `pdo_mysql`.
   - Descargar y levantar MySQL 8.0.
   - Crear automáticamente la base de datos `biblioteca` y sus tablas a partir del script en `db-init/biblioteca.sql`.

4. Espera unos 15-20 segundos la primera vez (MySQL necesita inicializar).

5. Abre el navegador en:

   ```
   http://localhost:8080
   ```

## Comandos útiles

| Acción | Comando |
|---|---|
| Levantar los contenedores | `docker compose up -d --build` |
| Ver logs del backend PHP | `docker compose logs -f php` |
| Ver logs de MySQL | `docker compose logs -f mysql` |
| Detener los contenedores | `docker compose down` |
| Detener y borrar los datos de la BD | `docker compose down -v` |

> Usa `docker compose down -v` si necesitas que la base de datos se reinicialice desde cero con el script `biblioteca.sql` (por ejemplo, si lo modificaste).

## Estructura del proyecto

```
├── docker-compose.yml     # Orquesta los servicios de PHP y MySQL
├── Dockerfile              # Imagen del backend PHP
├── index.php                # Vista principal
├── src/                        # Clases PHP (modelos y lógica de negocio)
└── db-init/                  # Script SQL que inicializa la base de datos
```

## Notas

- El puerto `8080` es el de la app (PHP). El puerto `3306` es el de MySQL, expuesto por si quieres conectarte con un cliente externo (ej. MySQL Workbench, DBeaver).
- Las credenciales de la base de datos se configuran como variables de entorno dentro de `docker-compose.yml`.