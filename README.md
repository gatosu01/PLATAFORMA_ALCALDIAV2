# Portal Web de la Alcaldía

## Introducción

Este proyecto es una aplicación web integral diseñada para funcionar como el portal digital oficial de una municipalidad. Su objetivo principal es modernizar y optimizar la interacción entre la administración de la ciudad y sus ciudadanos. Al centralizar la información, los servicios y los canales de comunicación, la plataforma busca fomentar un gobierno local más transparente, accesible y eficiente.

La aplicación ofrece una interfaz intuitiva donde los residentes pueden acceder a información esencial, realizar trámites administrativos de forma remota y comunicar sus necesidades o inquietudes directamente a los departamentos municipales correspondientes. Esto no solo reduce la necesidad de visitas presenciales, sino que también simplifica los procesos burocráticos y mejora los tiempos de respuesta.

## Cambios Recientes y Refactorización

- El proyecto ha sido migrado a una arquitectura **MVC moderna** siguiendo buenas prácticas y la metodología XP.
- Todos los **controladores**, **modelos** y **vistas** han sido reorganizados en la carpeta `app/`.
- Los **assets** (CSS, JS, IMG) ahora se encuentran en la carpeta `public/` para facilitar el acceso y la gestión.
- El archivo de entrada principal es `public/index.php`, que carga el router y el autoload.
- La **configuración de la base de datos** está en `config/database.php` y la conexión se gestiona desde `app/Models/Conexion.php`.
- El sistema de **registro** y **inicio de sesión** ha sido adaptado para mayor seguridad y mantenibilidad.
- Se ha implementado control de intentos de login por sesión, IP y usuario, usando archivos JSON (`DATA/login_attempts.json`).
- Las rutas están definidas en `routes/web.php`.
- Se recomienda revisar el archivo `.htaccess` para asegurar el correcto enrutamiento en Apache.

## Características Principales

El portal está equipado con un conjunto de funcionalidades pensadas para cubrir las necesidades más comunes de los ciudadanos:

*   **Centro de Información (FAQs):** Una sección de Preguntas Frecuentes, gestionada a través de un archivo `faqs.json`, que proporciona respuestas claras y directas a consultas comunes sobre pagos de impuestos, horarios de atención, ubicación de oficinas y más.

*   **Sistema de Quejas y Denuncias Ciudadanas:** Permite a los usuarios presentar quejas y reportes sobre diversos problemas, como luminarias públicas dañadas, inconvenientes con la recolección de basura u otras incidencias en la comunidad.

*   **Seguimiento de Solicitudes:** Los usuarios registrados pueden iniciar sesión y acceder a una sección personal ("Mis Denuncias") para dar seguimiento al estado de sus quejas y denuncias, consultando el historial y los códigos de seguimiento asignados.

*   **Guía de Trámites Municipales:** Ofrece información detallada sobre los requisitos y pasos a seguir para realizar trámites importantes, como el registro de un nuevo negocio o la solicitud de permisos de construcción.

*   **Comunicación por Correo Electrónico:** Integra la robusta librería **PHPMailer** para gestionar todas las comunicaciones por correo electrónico de manera segura y fiable. Se utiliza para notificaciones, confirmaciones de registro y respuestas automáticas a las solicitudes de los ciudadanos.

## Funcionamiento General del Sistema MVC

El sistema está basado en el patrón **Modelo-Vista-Controlador (MVC)**, lo que permite separar la lógica de negocio, la presentación y el acceso a datos para facilitar el mantenimiento y la escalabilidad.

### Flujo de Peticiones

1. **Entrada por Apache y .htaccess**
   - Todas las peticiones HTTP pasan por el archivo `public/.htaccess`, que redirige cualquier URL (excepto archivos/carpetas existentes) a `public/index.php`.
   - Ejemplo de regla:
     ```
    # Documentación de la Estructura y Funcionalidad MVC

    ## Estructura General

    - `app/Controllers/`: Controladores para cada módulo (quejas, postulaciones, etc.)
    - `app/Models/`: Modelos para acceso a datos y lógica de negocio
    - `app/Views/`: Vistas PHP para la interfaz de usuario
    - `public/`: Archivos estáticos (CSS, JS, imágenes) y punto de entrada (`index.php`)
    - `routes/web.php`: Definición de rutas MVC
    - `config/database.php`: Configuración de la base de datos
    - `UPLOADS/`: Archivos subidos por usuarios (CV, imágenes)
     RewriteEngine On
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteCond %{REQUEST_FILENAME} !-d
     RewriteRule ^(.*)$ index.php [QSA,L]
     require_once __DIR__ . '/../config/bootstrap.php';
     require_once __DIR__ . '/../routes/web.php';
3. **Router (`app/Core/Router.php`)**
   - El router recibe la petición, normaliza la URI (por ejemplo, `/Alcaldia/public/` se convierte en `/`), y busca la ruta en el archivo `routes/web.php`.
     $routes = require __DIR__ . '/../../routes/web.php';
     if (isset($routes[$method][$uri])) {
         [$controller, $action] = $routes[$method][$uri];
         (new $controller)->$action();
     } else {
         http_response_code(404);
         echo 'Página no encontrada';
     }
     ```

4. **Definición de Rutas (`routes/web.php`)**
   - Las rutas se definen en un array asociativo por método HTTP (`GET`, `POST`).
   - Ejemplo:
     ```php
     return [
         'GET' => [
             '/' => [HomeController::class, 'index'],
             '/login' => [SignInController::class, 'showForm'],
             // ...
         ],
         'POST' => [
             '/login' => [SignInController::class, 'login'],

   - Cada controlador gestiona una funcionalidad específica. Por ejemplo, `HomeController` carga la vista principal:
     ```php
     class HomeController {
         public function index() {
             require __DIR__ . '/../Views/home.php';
         }
     }
     ```

6. **Vistas (`app/Views/`)**
   - Las vistas son archivos PHP que generan la interfaz HTML. Reciben datos desde el controlador y muestran la información al usuario.

7. **Modelos (`app/Models/`)**
   - Los modelos gestionan el acceso a la base de datos y la lógica de negocio relacionada con los datos.
   - Ejemplo: `Conexion.php` para la conexión a la base de datos.

8. **Assets públicos (`public/`)**
   - CSS, JS e imágenes se encuentran en la carpeta `public/` y se referencian desde las vistas.

### Ejemplo de Navegación
- Acceder a `http://localhost/Alcaldia/public/` carga la página principal (`/`), que es gestionada por `HomeController` y muestra la vista `home.php`.
- Acceder a `http://localhost/Alcaldia/public/login` carga el formulario de inicio de sesión.
- Acceder a cualquier otra ruta definida en `routes/web.php` ejecuta el controlador y vista correspondiente.

### Seguridad y Buenas Prácticas
- El sistema implementa control de intentos de login por sesión, IP y usuario usando archivos JSON.
- La configuración de la base de datos está separada en `config/database.php`.
- El autoload y la estructura modular facilitan la extensión y el mantenimiento.

## Migración y Uso de Módulos en la Estructura MVC

### Migración de Vistas Legacy

- Todas las vistas legacy (por ejemplo, `VIEWS/complaints_reports_view.php`) han sido migradas a la carpeta `app/Views/`.
- Las rutas de assets (CSS, JS, imágenes) ahora son absolutas y apuntan a la carpeta `public/`.
- Los includes de header y footer se realizan con `include __DIR__ . '/header.php';` y `include __DIR__ . '/footer.php';` para asegurar compatibilidad en cualquier contexto.

### Ejemplo de Migración: complaints_reports_view

- **Vista:** `app/Views/complaints_reports_view.php`
- **Controlador:** `app/Controllers/ComplaintsReportsController.php`
- **Ruta:** Definida en `routes/web.php` como:
  ```php
  $router->get('/complaints-reports', 'ComplaintsReportsController@index');
  ```
- **Consulta de departamentos:** El controlador consulta los departamentos activos y los pasa como `$departamentos` a la vista.
- **Formulario:** El formulario envía los datos a `/Alcaldia/public/process-complaints`.
- **Assets:**
  - CSS: `/Alcaldia/public/CSS/header.css`, `/Alcaldia/public/CSS/complaints_reports.css`
  - JS: `/Alcaldia/public/JS/complaints_map.js`, `/Alcaldia/public/JS/clon_input_photo.js`

### Estructura Recomendada para Nuevos Módulos

1. **Controlador:** Realiza la consulta de datos y los pasa a la vista.
2. **Vista:** Recibe los datos y muestra el contenido, usando rutas absolutas para assets.
3. **Rutas:** Se definen en `routes/web.php` usando el nombre del controlador y método.
4. **Assets:** Todos los archivos estáticos deben estar en la carpeta `public/`.

### Ejemplo de Controlador

```php
namespace App\Controllers;
class ComplaintsReportsController {
    public function index() {
        require_once __DIR__ . '/../../app/Models/Conexion.php';
        $conexion = (new \App\Models\Conexion())->getConexion();
        $departamentos_query = $conexion->query("SELECT id, nombre FROM departament WHERE estado = 'activo'");
        $departamentos = $departamentos_query ? $departamentos_query->fetchAll(\PDO::FETCH_ASSOC) : [];
        require __DIR__ . '/../Views/complaints_reports_view.php';
    }
}
```

### Ejemplo de Vista

```php
<select id="department" name="department_id" required>
  <option value="">Seleccione una opción</option>
  <?php foreach ($departamentos as $row): ?>
    <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
  <?php endforeach; ?>
</select>
```

### Recomendaciones para Desarrolladores

- Siempre usa rutas absolutas para assets en las vistas: `/Alcaldia/public/CSS/archivo.css`, `/Alcaldia/public/JS/archivo.js`, `/Alcaldia/public/IMG/imagen.png`.
- Los datos deben ser consultados en el controlador y pasados a la vista como variables.
- Los includes de header y footer deben usar `__DIR__` para evitar problemas de rutas.
- Las rutas deben estar definidas en `routes/web.php` y apuntar al controlador correspondiente.
- Para formularios, apunta la acción al endpoint correcto en la carpeta `public/`.

### Estructura de Carpetas

- `app/Controllers/`: Controladores PHP
- `app/Views/`: Vistas PHP
- `app/Models/`: Modelos y lógica de acceso a datos
- `public/CSS/`, `public/JS/`, `public/IMG/`: Assets estáticos
- `routes/web.php`: Definición de rutas
- `config/database.php`: Configuración de la base de datos

## Estructura del Proyecto

```
Alcaldia/
│
├── app/
│   ├── Controllers/      # Controladores (lógica de negocio y rutas)
│   ├── Models/           # Modelos (acceso a datos y entidades)
│   ├── Views/            # Vistas (archivos .php para la interfaz)
│   ├── Core/             # Clases base y utilidades (Router, etc.)
│   └── Helpers/          # Funciones auxiliares
│
├── config/               # Configuración de la aplicación (base de datos, bootstrap)
├── routes/               # Definición de rutas (web.php)
├── public/               # Punto de entrada (index.php) y archivos públicos
│   ├── CSS/              # Hojas de estilo
│   ├── JS/               # Scripts JavaScript
│   ├── IMG/              # Imágenes
│   └── ...
├── DATA/                 # Archivos de datos (JSON, etc.)
├── UPLOADS/              # Archivos subidos por usuarios
├── MEDIA/                # Otros archivos multimedia
├── phpmailer/            # Librería para envío de correos
├── README.md             # Documentación del proyecto
└── composer.json         # Dependencias PHP
```

## Instrucciones de Uso

1. Clona el repositorio y configura tu entorno local (XAMPP recomendado).
2. Instala dependencias con `composer install`.
3. Configura la base de datos en `config/database.php`.
4. Asegúrate de que el directorio raíz del servidor apunte a `public/`.
5. Revisa el archivo `.htaccess` para el correcto enrutamiento de URLs.
6. Accede al portal desde tu navegador y verifica el funcionamiento de las vistas y formularios.

## Créditos y Licencia

Desarrollado por el equipo de la Alcaldía. Uso libre para fines educativos y municipales.

## Refactorización de Paneles Administrativos y Barra Lateral

### Cambios realizados
- Migración de `PHP/header_admin.php` a `app/Views/header_admin.php` (solo barra lateral, sin `<html>` ni `<body>`).
- Actualización de rutas en todos los archivos de vistas y paneles admin para que incluyan `<?php include __DIR__ . '/header_admin.php'; ?>`.
- Adaptación de rutas de assets (`CSS`, `JS`) y enlaces internos a la nueva estructura.
- Refactorización de variables en vistas para evitar warnings y asegurar funcionalidad.
- Migración de todos los paneles admin (`admin_panel.php`, `suggestions_admin.php`, `complaints_admin.php`, `reports_admin.php`, `notification_admin.php`, `faq_admin.php`, `admin_slider.php`, `admin_postulations.php`, `logout.php`) a `app/Views/`, adaptando includes y rutas.

### Ejemplo de integración en una vista
```php
<?php include __DIR__ . '/header_admin.php'; ?>
```
Esto debe ir justo después de `<body>` en cada vista admin.

### Pendientes para finalizar la migración
- Migrar controladores admin a `app/Controllers` y adaptarlos para pasar datos a las vistas.
- Validar rutas y assets en producción.
- Migrar y adaptar cualquier JS específico de paneles admin.
- Pruebas de integración y validación de roles/seguridad.
- Revisar y adaptar formularios y acciones POST para el nuevo flujo MVC.

### Notas
- Todas las vistas admin ahora usan la barra lateral desde `app/Views/header_admin.php`.
- Las rutas internas y assets deben ser absolutas para evitar problemas de carga.
- Si alguna variable no está definida en la vista, se recomienda usar un valor por defecto para evitar warnings.