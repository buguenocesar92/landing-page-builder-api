# 🚀 Landing Page Builder - Backend API

API backend construida con **Laravel 11** para el sistema de Landing Page Builder. Proporciona autenticación JWT, CRUD completo para templates, landing pages y leads, más analytics avanzados.

## 📋 **Tabla de Contenidos**

- [Instalación](#instalación)
- [Configuración](#configuración)
- [Arquitectura](#arquitectura)
- [Base de Datos](#base-de-datos)
- [API Endpoints](#api-endpoints)
- [Autenticación](#autenticación)
- [Modelos](#modelos)
- [Funcionalidades](#funcionalidades)
- [Próximos Pasos](#próximos-pasos)

## 🛠️ **Instalación**

### Prerrequisitos
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ (para assets)

### Pasos de instalación

```bash
# 1. Clonar repositorio
cd landing-page-builder-api

# 2. Instalar dependencias PHP
composer install

# 3. Configurar environment
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env
DB_DATABASE=landing_page_builder
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# 5. Ejecutar migraciones
php artisan migrate

# 6. Sembrar datos de prueba
php artisan db:seed

# 7. Generar JWT secret
php artisan jwt:secret

# 8. Instalar dependencias JS (opcional)
npm install
npm run build

# 9. Iniciar servidor
php artisan serve
```

## ⚙️ **Configuración**

### Variables de entorno clave (.env)

```env
# Aplicación
APP_NAME="Landing Page Builder API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=landing_page_builder
DB_USERNAME=root
DB_PASSWORD=

# JWT Configuration
JWT_SECRET=tu_jwt_secret_aqui
JWT_TTL=1440

# Mail (opcional para notificaciones)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password

# Frontend URL (para CORS)
FRONTEND_URL=http://localhost:3000
```

### Configuración CORS

El archivo `bootstrap/app.php` ya está configurado para permitir requests desde el frontend:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        HandleCors::class,
    ]);
})
```

## 🏗️ **Arquitectura**

### Estructura del proyecto

```
landing-page-builder-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/
│   │   │   │   ├── LandingController.php      # CRUD landing pages
│   │   │   │   ├── LeadController.php         # CRUD leads
│   │   │   │   └── TemplateController.php     # CRUD templates
│   │   │   └── AuthController.php             # JWT Authentication
│   │   ├── Middleware/
│   │   └── Requests/
│   │       ├── BaseFormRequest.php
│   │       ├── LoginRequest.php
│   │       └── UserRequest.php
│   ├── Models/
│   │   ├── User.php                           # Usuarios del sistema
│   │   ├── Template.php                       # Templates disponibles
│   │   ├── Landing.php                        # Landing pages creadas
│   │   └── Lead.php                          # Leads capturados
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── 2025_07_11_000001_create_templates_table.php
│   │   ├── 2025_07_11_000002_create_landings_table.php
│   │   └── 2025_07_11_000003_create_leads_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── TemplateSeeder.php
├── routes/
│   ├── api.php                               # Todas las rutas API
│   └── web.php
└── config/
    ├── jwt.php                               # Configuración JWT
    └── cors.php                              # Configuración CORS
```

## 🗄️ **Base de Datos**

### Esquema de tablas

#### Users (Usuarios)
```sql
- id (bigint, PK)
- name (varchar 255)
- email (varchar 255, unique)
- email_verified_at (timestamp)
- password (varchar 255)
- created_at, updated_at (timestamps)
```

#### Templates (Plantillas)
```sql
- id (bigint, PK)
- name (varchar 255)
- description (text)
- content (json)                    # Estructura del template
- preview_image (varchar 255)
- is_active (boolean, default true)
- is_premium (boolean, default false)
- created_at, updated_at (timestamps)
```

#### Landings (Landing Pages)
```sql
- id (bigint, PK)
- user_id (bigint, FK -> users.id)
- template_id (bigint, FK -> templates.id)
- title (varchar 255)
- slug (varchar 255, unique)
- description (text, nullable)
- content (json)                    # Contenido personalizado
- custom_domain (varchar 255, nullable)
- is_active (boolean, default true)
- views_count (int, default 0)
- leads_count (int, default 0)
- created_at, updated_at (timestamps)
```

#### Leads (Contactos capturados)
```sql
- id (bigint, PK)
- landing_id (bigint, FK -> landings.id)
- name (varchar 255)
- email (varchar 255)
- phone (varchar 20, nullable)
- message (text, nullable)
- source (varchar 100, nullable)    # Fuente del lead
- ip_address (varchar 45, nullable)
- user_agent (text, nullable)
- created_at, updated_at (timestamps)
```

### Relaciones
- `User` hasMany `Landing`
- `Template` hasMany `Landing`
- `Landing` hasMany `Lead` y belongsTo `User`, `Template`
- `Lead` belongsTo `Landing`

## 🔗 **API Endpoints**

### Base URL: `http://localhost:8000/api`

#### Autenticación
```http
POST   /register                    # Registro de usuario
POST   /login                       # Login con JWT
POST   /logout                      # Logout
POST   /refresh                     # Refresh token
GET    /who                         # Usuario actual
```

#### Templates
```http
GET    /templates                   # Listar todos los templates
GET    /templates/free              # Templates gratuitos
GET    /templates/premium           # Templates premium
GET    /templates/{id}              # Obtener template específico
```

#### Landing Pages (requiere auth)
```http
GET    /landings                    # Listar landing pages del usuario
POST   /landings                    # Crear nueva landing page
GET    /landings/{id}               # Obtener landing page específica
PUT    /landings/{id}               # Actualizar landing page
DELETE /landings/{id}               # Eliminar landing page
POST   /landings/{id}/duplicate     # Duplicar landing page
GET    /landings/{id}/analytics     # Analytics de la landing page
```

#### Landing Pages Públicas (sin auth)
```http
GET    /l/{slug}                    # Ver landing page pública por slug
POST   /submit-lead                 # Enviar lead desde formulario público
```

#### Leads (requiere auth)
```http
GET    /leads                       # Listar leads del usuario
GET    /leads/{id}                  # Obtener lead específico
PUT    /leads/{id}                  # Actualizar lead
DELETE /leads/{id}                  # Eliminar lead
GET    /leads/stats                 # Estadísticas de leads
GET    /leads/export                # Exportar leads en CSV
```

#### Dashboard (requiere auth)
```http
GET    /dashboard/stats             # Estadísticas del dashboard
```

#### Utilidades
```http
GET    /utils/check-slug/{slug}     # Verificar disponibilidad de slug
POST   /utils/generate-slug         # Generar slug desde título
```

## 🔐 **Autenticación**

### JWT (JSON Web Tokens)

El sistema usa **tymon/jwt-auth** para autenticación:

```php
// AuthController.php
public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');
    
    if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    return $this->respondWithToken($token);
}
```

### Headers requeridos para rutas protegidas
```http
Authorization: Bearer {jwt_token}
Content-Type: application/json
Accept: application/json
```

### Middleware aplicado
- `auth:api` para rutas protegidas
- `cors` para requests cross-origin

## 📝 **Modelos**

### User Model
```php
class User extends Authenticatable implements JWTSubject
{
    // Relaciones
    public function landings() // hasMany
    
    // JWT Implementation
    public function getJWTIdentifier()
    public function getJWTCustomClaims()
}
```

### Template Model
```php
class Template extends Model
{
    protected $fillable = [
        'name', 'description', 'content', 'preview_image', 
        'is_active', 'is_premium'
    ];
    
    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'is_premium' => 'boolean'
    ];
    
    // Relaciones
    public function landings() // hasMany
    
    // Scopes
    public function scopeActive($query)
    public function scopeFree($query)
    public function scopePremium($query)
}
```

### Landing Model
```php
class Landing extends Model
{
    protected $fillable = [
        'user_id', 'template_id', 'title', 'slug', 
        'description', 'content', 'custom_domain', 'is_active'
    ];
    
    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean'
    ];
    
    // Relaciones
    public function user() // belongsTo
    public function template() // belongsTo
    public function leads() // hasMany
    
    // Métodos
    public function incrementViews()
    public function updateLeadsCount()
    public function getPublicUrl()
}
```

### Lead Model
```php
class Lead extends Model
{
    protected $fillable = [
        'landing_id', 'name', 'email', 'phone', 
        'message', 'source', 'ip_address', 'user_agent'
    ];
    
    // Relaciones
    public function landing() // belongsTo
    
    // Scopes
    public function scopeRecent($query)
    public function scopeByLanding($query, $landingId)
}
```

## ✅ **Funcionalidades Implementadas**

### ✅ Sistema de Autenticación
- [x] Registro de usuarios
- [x] Login con JWT
- [x] Refresh tokens
- [x] Logout
- [x] Middleware de protección

### ✅ Gestión de Templates
- [x] CRUD completo de templates
- [x] Templates gratuitos vs premium
- [x] Contenido JSON flexible
- [x] Sistema de activación

### ✅ Landing Pages
- [x] CRUD completo para usuarios autenticados
- [x] Slugs únicos automáticos
- [x] Contenido personalizable basado en templates
- [x] Sistema de activación/desactivación
- [x] Duplicación de landing pages
- [x] Vista pública sin autenticación

### ✅ Gestión de Leads
- [x] Captura desde formularios públicos
- [x] CRUD para usuarios autenticados
- [x] Tracking de fuente y metadata
- [x] Contadores automáticos
- [x] Estadísticas básicas

### ✅ Analytics Básicos
- [x] Contador de visitas por landing
- [x] Contador de leads por landing
- [x] Estadísticas del dashboard
- [x] Métricas de conversión

### ✅ API RESTful
- [x] 34 endpoints funcionando
- [x] Respuestas JSON consistentes
- [x] Validación de requests
- [x] Manejo de errores
- [x] CORS configurado

## 🚧 **Próximos Pasos Sugeridos**

### 🎯 Prioridad Alta
1. **Notificaciones por Email**
   - Envío automático cuando llega nuevo lead
   - Templates de emails personalizables
   - Configuración SMTP

2. **Analytics Avanzados**
   - Tracking de vistas por día/hora
   - Geolocalización de visitantes
   - Fuentes de tráfico
   - Tiempo en página

3. **SEO y Performance**
   - Meta tags automáticos
   - Sitemap XML
   - Compresión de imágenes
   - Cache de respuestas

### 🔧 Prioridad Media
1. **Integraciones**
   - Webhooks configurables
   - API de Mailchimp
   - Google Analytics
   - Facebook Pixel

2. **Sistema de Planes**
   - Límites por plan
   - Integración con Stripe
   - Gestión de suscripciones

3. **Dominios Personalizados**
   - Validación de dominios
   - SSL automático
   - DNS management

### 🚀 Prioridad Baja
1. **A/B Testing**
   - Múltiples variantes
   - División de tráfico
   - Métricas comparativas

2. **Equipos y Colaboración**
   - Múltiples usuarios por cuenta
   - Roles y permisos
   - Gestión de equipos

3. **API v2**
   - GraphQL endpoint
   - Subscriptions en tiempo real
   - Rate limiting avanzado

## 🔧 **Comandos Útiles**

```bash
# Migrar y sembrar datos
php artisan migrate:fresh --seed

# Crear nuevo controller
php artisan make:controller API/NuevoController --api

# Crear nueva migración
php artisan make:migration create_nueva_tabla

# Crear nuevo modelo con migración
php artisan make:model NuevoModelo -m

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Generar documentación API (opcional)
php artisan l5-swagger:generate
```

## 📊 **Monitoring y Logs**

- **Logs**: `storage/logs/laravel.log`
- **Debug**: Activar `APP_DEBUG=true` en development
- **Performance**: Usar `php artisan debugbar:publish` para debug bar

## 🤝 **Contribución**

Para continuar el desarrollo:

1. **Fork** el repositorio
2. **Crear branch** feature (`git checkout -b feature/nueva-funcionalidad`)
3. **Commit** cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. **Push** al branch (`git push origin feature/nueva-funcionalidad`)
5. **Crear Pull Request**

---

## 📞 **Soporte**

Para dudas sobre la API backend:
- Revisar logs en `storage/logs/`
- Verificar configuración en `.env`
- Comprobar migraciones con `php artisan migrate:status`
