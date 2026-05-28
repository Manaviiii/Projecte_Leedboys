# 🤖 Ledboys — Plataforma de Reservas de Animación LED

Proyecto de fin de ciclo desarrollado por estudiantes de DAW y DAM.  
Plataforma web para contratar animadores con trajes LED, accesorios y packs de Hora Loca para eventos y celebraciones.

---

## 👥 Equipo

| Nombre | Ciclo | Responsabilidad |
|--------|-------|-----------------|
| Víctor | DAW   | Frontend — Aplicación React (catálogo, carrito, checkout, reservas) |
| Iker   | DAW   | Backend — API REST Laravel, panel de administración Filament, sistema de pagos Stripe |

---

## 🧱 Arquitectura

El proyecto sigue una arquitectura **cliente-servidor desacoplada**:

- **Frontend** (React) — consume la API REST y gestiona la experiencia del usuario
- **Backend** (Laravel) — expone la API, gestiona la base de datos y el panel de administración
- **Base de datos** (MySQL) — almacena toda la información del sistema incluyendo fotos en formato BLOB

---

## ⚙️ Tecnologías

### Backend (DAW)
- **Laravel 9** — framework PHP para la API REST
- **Filament v2** — panel de administración
- **Stripe** — pasarela de pagos con soporte de webhooks
- **MySQL** — base de datos relacional
- **Laravel Sanctum** — autenticación mediante tokens
- **DomPDF** — generación de facturas en PDF
- **SMTP / Gmail** — envío de emails con factura adjunta

### Frontend (DAM)
- **React** — librería JavaScript para la interfaz de usuario

---

## 🗄️ Estructura de la base de datos

```
users           — cuentas de acceso (admin / user)
clientes        — perfil comercial vinculado al usuario
items           — catálogo general (trajes, accesorios, packs)
item_trajes     — datos específicos de trajes (tipo, género, stock)
item_accesorios — datos específicos de accesorios (stock, foto BLOB)
item_packs      — datos específicos de packs (número de zancudos)
fotos           — fotos de trajes en formato LONGBLOB
eventos         — eventos contratados (fecha, hora, ubicación, estado)
evento_items    — items contratados por evento (tabla pivote)
pagos           — pagos con datos de facturación y estado Stripe
residencias     — contratos de residencia periódica
```

---

## 🚀 Instalación local

### Requisitos
- PHP 8.2+
- Composer
- MySQL (XAMPP recomendado)
- Node.js y npm

### Backend

```bash
# Clonar el repositorio
git clone <url-del-repo>
cd Ledboys

# Instalar dependencias PHP
composer install

# Copiar el archivo de entorno
cp .env.example .env

# Generar la clave de aplicación
php artisan key:generate

# Configurar la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ledboys
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Subir las fotos de trajes (opcional)
# Colocar archivos en storage/app/Fotos/ con formato traje_XX_foto_Y.jpg
php artisan db:seed --class=FotosSeeder

# Subir fotos de accesorios (opcional)
# Colocar archivos en storage/app/Accesorios/
php artisan db:seed --class=ItemAccesoriosSeeder

# Enlace de almacenamiento
php artisan storage:link

# Arrancar el servidor
php artisan serve
```

### Configuración del .env

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ledboys
DB_USERNAME=root
DB_PASSWORD=

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Email (Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=infoledboys@gmail.com
MAIL_PASSWORD=xxxx_xxxx_xxxx_xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=infoledboys@gmail.com
MAIL_FROM_NAME="LEDBOYSS Performance"

# Filament
FILAMENT_PATH=admin
```

### MySQL — max_allowed_packet

Para poder subir fotos como BLOB es necesario aumentar el límite en `my.ini`:

```ini
[mysqld]
max_allowed_packet=64M
```

---

## 🔑 Acceso al panel de administración

URL: `http://localhost:8000/admin`

Crear un usuario admin desde tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@ledboys.com',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

---

## API REST — Endpoints principales

### Autenticación
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/login` | Iniciar sesión |
| POST | `/api/registro` | Registrar usuario |
| POST | `/api/logout` | Cerrar sesión |
| GET  | `/api/me` | Datos del usuario autenticado |

### Catálogo (público)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/trajes` | Listado de trajes |
| GET | `/api/trajes/{id}` | Detalle de un traje |
| GET | `/api/accesorios` | Listado de accesorios |
| GET | `/api/packs` | Listado de packs |
| GET | `/api/fotos` | Fotos principales del catálogo |
| GET | `/api/fotos/traje/{id}` | Todas las fotos de un traje |

### Pagos (autenticado)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/pagos/crear-intento` | Crear intento de pago en Stripe |
| POST | `/api/pagos/{id}/confirmar` | Confirmar pago y enviar factura |
| GET  | `/api/pagos` | Historial de pagos |
| POST | `/api/pagos/{id}/reembolso` | Solicitar reembolso |

### Reservas (autenticado)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/reservas` | Próximas reservas del usuario |
| GET | `/api/reservas?historial=true` | Reservas pasadas |
| GET | `/api/reservas/{id}` | Detalle de una reserva |

### Perfil (autenticado)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET    | `/api/perfil` | Ver perfil |
| PUT    | `/api/perfil` | Actualizar nombre y teléfono |
| PUT    | `/api/perfil/email` | Cambiar email |
| PUT    | `/api/perfil/password` | Cambiar contraseña |
| DELETE | `/api/perfil` | Eliminar cuenta |

---

## Funcionalidades principales

- Catálogo de trajes LED con fotos en BLOB, filtros por género y tipo
- Accesorios y packs de Hora Loca con descripciones detalladas
- Sistema de pagos con Stripe y desglose de IVA (21%)
- Generación y envío de factura en PDF por email al confirmar el pago
- Control de stock de trajes por fecha — evita dobles reservas
- Gestión de reservas futuras y historial de eventos pasados
- Panel de administración con dashboard, estadísticas y gestión del catálogo
- Registro y autenticación con tokens Sanctum
- Webhooks de Stripe para sincronización de estados de pago

---

##  Estructura del proyecto

```
app/
├── Filament/
│   ├── Resources/          — Resources del panel admin
│   └── Widgets/            — Widgets del dashboard
├── Http/Controllers/       — Controladores de la API
├── Mail/                   — Mailables (FacturaMail)
├── Models/                 — Modelos Eloquent
database/
├── migrations/             — Migraciones de la BD
├── seeders/                — Seeders con datos de prueba
resources/views/emails/     — Vistas de emails (factura PDF)
storage/app/
├── Fotos/                  — Fotos de trajes para el seeder
└── Accesorios/             — Fotos de accesorios para el seeder
```
