# Sistema de Reportes Ciudadanos - Instrucciones

## 🎉 Sistema Implementado Exitosamente

Se ha implementado un sistema completo de autenticación con roles para administradores y ciudadanos.

## 👥 Credenciales de Acceso

### Administrador
- **Email:** admin@admin.com
- **Contraseña:** password
- **Permisos:** Ver todas las solicitudes, cambiar estados

### Ciudadano de Prueba
- **Email:** ciudadano@test.com
- **Contraseña:** password
- **Permisos:** Crear y ver solo sus propias solicitudes

## 🚀 Cómo Usar el Sistema

### Para Ciudadanos:
1. Regístrate en `/register` o inicia sesión en `/login`
2. Una vez autenticado, podrás:
   - Ver solo TUS solicitudes en el panel
   - Crear nuevas solicitudes
   - Ver el estado de tus solicitudes (no puedes modificarlo)
   - Ver las evidencias que subiste

### Para Administradores:
1. Inicia sesión con las credenciales de administrador
2. Accede automáticamente al panel de administración
3. Podrás:
   - Ver TODAS las solicitudes de todos los ciudadanos
   - Cambiar el estado de cualquier solicitud (Pendiente, En proceso, Resuelto, Rechazado)
   - Filtrar por estado, categoría o buscar por texto
   - Ver estadísticas generales

## 📁 Estructura del Sistema

### Rutas Implementadas:

#### Públicas:
- `/` - Página de inicio
- `/login` - Iniciar sesión
- `/register` - Registro de nuevos ciudadanos

#### Ciudadanos (requiere autenticación):
- `/ciudadano/solicitudes` - Ver mis solicitudes
- `/ciudadano/solicitudes/crear` - Crear nueva solicitud
- `/ciudadano/solicitudes/{id}` - Ver detalles de mi solicitud

#### Administradores (requiere autenticación y rol admin):
- `/admin/solicitudes` - Panel de administración
- `/admin/solicitudes/{id}` - Ver detalles de cualquier solicitud
- `/admin/solicitudes/{id}/estado` - Actualizar estado (PATCH)

## 🔐 Seguridad Implementada

1. **Middleware de Autenticación:** Protege todas las rutas que requieren login
2. **Middleware de Roles:** Separa acceso de administradores y ciudadanos
3. **Protección de Datos:** Los ciudadanos solo pueden ver sus propias solicitudes
4. **Contraseñas Encriptadas:** Todas las contraseñas se guardan con Hash

## 🎨 Características Visuales

### Panel de Administrador:
- Tabla completa con todas las solicitudes
- Selector de estado en cada fila para cambio rápido
- Filtros por estado, categoría y búsqueda
- Estadísticas en tarjetas (Total, Pendientes, En proceso, Resueltas)
- Vista detallada de cada solicitud con datos del ciudadano

### Panel de Ciudadano:
- Tarjetas con sus solicitudes
- Badge de estado con colores
- Previsualizaciones de evidencias
- Vista detallada de solo lectura
- Botón destacado para crear nueva solicitud

## 🔧 Cómo Crear un Nuevo Administrador

Para crear más usuarios administradores, usa el siguiente comando en terminal:

```bash
php artisan tinker
```

Luego ejecuta:

```php
\App\Models\User::create([
    'name' => 'Nombre del Admin',
    'email' => 'email@ejemplo.com',
    'password' => \Hash::make('contraseña'),
    'role' => 'admin'
]);
```

## 📝 Notas Importantes

1. Los ciudadanos se registran automáticamente con el rol "ciudadano"
2. Los administradores deben crearse manualmente en la base de datos
3. El estado de las solicitudes solo puede ser modificado por administradores
4. Cada solicitud queda vinculada al ciudadano que la creó
5. Los administradores ven el nombre completo del ciudadano en cada solicitud

## 🌐 Acceder al Sistema

Abre tu navegador y ve a:
```
http://localhost:8000
```

O si usas el servidor de XAMPP:
```
http://localhost/ReportesCiudadanos/public
```

## ✅ Sistema Completamente Funcional

El sistema ahora tiene:
- ✅ Autenticación completa (Login/Registro/Logout)
- ✅ Sistema de roles (Admin/Ciudadano)
- ✅ Panel de administración con todas las solicitudes
- ✅ Panel de ciudadano con solo sus solicitudes
- ✅ Protección de rutas con middleware
- ✅ Estados modificables solo por administradores
- ✅ Interfaz moderna y responsiva
- ✅ Filtros y búsqueda en panel de admin
- ✅ Estadísticas para administradores

¡Disfruta tu nuevo sistema de reportes ciudadanos!

