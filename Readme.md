# Sistema de Venta de Entradas - Laravel

## Descripción

Aplicación web desarrollada en Laravel para la gestión y compra de entradas de eventos con selección de asientos en tiempo real.

El sistema permite:
- Visualizar eventos
- Seleccionar asientos
- Bloquear asientos temporalmente
- Comprar entradas
- Recibir confirmación por correo
- Gestión de descuentos para nuevos usuarios

---

# Tecnologías utilizadas

- Laravel
- PHP
- MySQL
- JavaScript
- Blade
- Bootstrap
- Mailtrap / SMTP
- LocalStorage
- Fetch API

---

# Funcionalidades principales

## Eventos
- Listado de eventos
- Vista detallada
- Posters dinámicos

## Sistema de asientos
- Selección visual
- Estados:
  - Libre
  - Bloqueado
  - Vendido
- Actualización en tiempo real

## Carrito
- Persistencia en LocalStorage
- Eliminación dinámica
- Sincronización con backend

## Checkout
- Generación de entradas
- Bloqueo seguro
- Prevención de compra duplicada

## Emails
- Confirmación automática
- Render Blade para correo HTML

## Descuento temporal
- 50% para nuevos usuarios
- Disponible durante 5 minutos tras el registro

---

# Estructura principal del proyecto

## Controllers

### CheckoutController
Gestiona:
- Compra
- Locks
- Creación de entradas
- Envío de email

### EventoWebController
Renderizado Blade de eventos.

### EventoController
API REST de eventos.

---

# Modelos principales

## Evento
Información del evento.

## Entrada
Entradas compradas por usuarios.

## SeatLock
Bloqueo temporal de asientos.

## CarritoItem
Persistencia del carrito.

---

# Frontend JS

## ui.js
Gestiona:
- Carrito
- Render dinámico
- Tooltips
- Checkout
- Temporizador descuento
- Loading overlay

## api.js
Comunicación Fetch API con backend.

## store.js
Persistencia LocalStorage.

---

# Sistema de correo

Se utiliza:
- Laravel Mail
- SMTP configurado desde `.env`

Variables necesarias:

```env
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
MAIL_TEST_TO=
```

# Instalación

## Clonar proyecto

```bash
git clone https://github.com/usuario/proyecto.git
cd proyecto
```

## Instalar dependencias backend
```bash
composer install
```

## Instalar dependencias frontend
```bash
npm install
```

## Configurar base de datos
Editamos el archivo .envv con los datos de MySQL:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=root
DB_PASSWORD=
```

## Configurar sistema de correo
```bash
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
MAIL_TEST_TO=
```

## Ejecutar migraciones + carga seeders

```bash
php artisan migrate
php artisan db:seed
```

## Crear enlace de storage
Para poder tener en un lugar imagenes y archivos públicos
```bash
php artisan storage:link
```

# Ejecutar proyecto

## Iniciamos todo
```bash
vendor/bin/sail up -d
npm run dev
```

## Funcionalidades implementadas

· Registro y autenticación de usuarios
· Sistema de eventos
· Selección de asientos interactiva
· Bloqueo temporal de asientos
· Carrito persistente
· Checkout de entradas
· Envío de correo de confirmación
· Renderizado dinámico con Blade y JavaScript
· Sistema de descuentos temporales para nuevos usuarios

# Notas

· El sistema de descuentos funciona correctamente en backend y parcialmente a nivel visual frontend.
· El envío de correo SMTP está operativo.
· El sistema utiliza LocalStorage para persistencia temporal del carrito.
· Los asientos se sincronizan dinámicamente mediante peticiones periódicas al backend.


# Trabajo Realizado Por
[Walter Ponce Garcia - DAW 2º]

### Tecnologías principales utilizadas
- Laravel
- PHP
- MySQL
- JavaScript
- Blade
- Bootstrap
- Vite
- SMTP Mail

