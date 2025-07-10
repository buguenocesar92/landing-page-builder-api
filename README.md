# Link Bio - Backend API

Backend API para la aplicación Link Bio construido con Laravel y autenticación JWT.

## Características

- **Autenticación JWT**: Sistema completo de autenticación con tokens JWT
- **Registro de usuarios**: Endpoint para crear nuevas cuentas
- **Login/Logout**: Gestión de sesiones
- **Refresh token**: Renovación automática de tokens
- **Middleware de autenticación**: Protección de rutas

## Estructura del Proyecto

Este backend ha sido limpiado y contiene únicamente:

- **Controladores**: `AuthController` - Manejo de autenticación
- **Modelos**: `User` - Modelo básico de usuario
- **Rutas**: Solo rutas de autenticación en `/api`
- **Migraciones**: Tablas básicas de usuarios y sistema
- **Middleware**: Autenticación JWT

## Endpoints de API

### Autenticación
- `POST /api/register` - Registro de nuevos usuarios
- `POST /api/login` - Inicio de sesión
- `GET /api/who` - Información del usuario autenticado (requiere auth)
- `POST /api/logout` - Cerrar sesión (requiere auth)
- `POST /api/refresh` - Renovar token (requiere auth)

### Test
- `GET /api/test` - Verificar que el backend funciona

## Instalación

```bash
# Instalar dependencias
composer install

# Configurar base de datos en .env
cp .env.example .env

# Generar key de aplicación
php artisan key:generate

# Generar secret JWT
php artisan jwt:secret

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (opcional)
php artisan db:seed

# Iniciar servidor
php artisan serve
```

## Tecnologías

- Laravel 11
- JWT Auth (tymon/jwt-auth)
- MySQL/PostgreSQL/SQLite
- PHP 8.2+
