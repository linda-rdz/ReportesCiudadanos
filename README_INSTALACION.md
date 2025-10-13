# Sistema de Registro de Solicitudes de Mantenimiento Urbano

## Descripción
Sistema web desarrollado en Laravel que permite a los ciudadanos reportar problemas urbanos (baches, fugas de agua, alumbrado público dañado, etc.) y a los funcionarios municipales gestionar y actualizar el estado de estas solicitudes.

## Tecnologías Utilizadas
- **Backend**: Laravel 9
- **Base de Datos**: MySQL
- **Frontend**: Bootstrap 5 + Blade Templates
- **Autenticación**: Sistema personalizado con roles

## Requisitos del Sistema
- PHP 8.0 o superior
- Composer
- MySQL
- XAMPP (recomendado para desarrollo local)

## Instalación

### 1. Instalar Composer (si no lo tienes)
Descarga e instala Composer desde: https://getcomposer.org/download/

### 2. Instalar dependencias de Laravel
```bash
cd C:\xampp\htdocs\ESTADIA\estadia
composer install
```

### 3. Configurar archivo .env
Copia el archivo `.env.example` a `.env` y configura tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estadia
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generar clave de aplicación
```bash
php artisan key:generate
```

### 5. Crear base de datos
Crea una base de datos llamada `estadia` en MySQL.

### 6. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Crear enlace simbólico para almacenamiento
```bash
php artisan storage:link
```

### 8. Iniciar servidor de desarrollo
```bash
php artisan serve
```

## Estructura de la Base de Datos

### Tablas principales:
- **users**: Usuarios del sistema (ciudadanos y funcionarios)
- **solicitudes**: Solicitudes de mantenimiento urbano
- **categorias**: Tipos de problemas (baches, alumbrado, etc.)
- **colonias**: Ubicaciones geográficas
- **evidencias**: Archivos adjuntos a las solicitudes

### Campos importantes:
- **users.role**: 'ciudadano' o 'funcionario'
- **solicitudes.estado**: 'Pendiente', 'En proceso', 'Resuelto', 'Rechazado'

## Usuarios de Prueba

Después de ejecutar el seeder, tendrás estos usuarios:

### Ciudadano
- **Email**: ciudadano@test.com
- **Contraseña**: password
- **Funciones**: Crear solicitudes, ver sus propias solicitudes

### Funcionario
- **Email**: funcionario@test.com
- **Contraseña**: password
- **Funciones**: Ver todas las solicitudes, cambiar estados

## Funcionalidades

### Para Ciudadanos:
- Registro e inicio de sesión
- Crear nuevas solicitudes de mantenimiento
- Subir evidencias (imágenes)
- Ver el estado de sus solicitudes
- Filtrar solicitudes por estado

### Para Funcionarios:
- Acceso al panel de administración
- Ver todas las solicitudes del sistema
- Cambiar el estado de las solicitudes
- Ver estadísticas rápidas
- Filtrar solicitudes por estado

## Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── SolicitudController.php
│   └── Auth/AuthController.php
├── Models/
│   ├── Solicitud.php
│   ├── Categoria.php
│   ├── Colonia.php
│   ├── Evidencia.php
│   └── User.php
├── Enums/
│   └── EstadoSolicitud.php
└── Http/Middleware/
    └── EnsureRole.php

resources/views/
├── layouts/app.blade.php
├── solicitudes/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── show.blade.php
├── admin/solicitudes/
│   └── index.blade.php
└── auth/
    └── register.blade.php
```

## Rutas Principales

- `/` - Redirige al listado de solicitudes
- `/login` - Iniciar sesión
- `/register` - Registrarse
- `/solicitudes` - Listado de solicitudes (ciudadanos ven solo las suyas)
- `/solicitudes/crear` - Crear nueva solicitud (solo ciudadanos)
- `/admin/solicitudes` - Panel de administración (solo funcionarios)

## Personalización

### Agregar nuevas categorías:
```php
Categoria::create(['nombre' => 'Nueva Categoría']);
```

### Agregar nuevas colonias:
```php
Colonia::create(['nombre' => 'Nueva Colonia']);
```

### Modificar estados:
Edita el enum `EstadoSolicitud.php` para agregar o cambiar estados.

## Solución de Problemas

### Error de permisos en Windows:
Si tienes problemas con permisos de archivos, ejecuta como administrador.

### Error de conexión a base de datos:
Verifica que MySQL esté ejecutándose en XAMPP y que la configuración en `.env` sea correcta.

### Error de almacenamiento:
Asegúrate de ejecutar `php artisan storage:link` para crear el enlace simbólico.

## Desarrollo Futuro

Posibles mejoras:
- API REST para aplicación móvil
- Notificaciones por email
- Geolocalización con mapas
- Reportes en PDF/Excel
- Sistema de comentarios en solicitudes
- Dashboard con gráficos estadísticos

## Soporte

Para reportar problemas o solicitar nuevas funcionalidades, contacta al equipo de desarrollo.
