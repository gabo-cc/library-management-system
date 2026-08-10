 📚 Sistema de Gestión de Biblioteca

Mini-aplicación web desarrollada con **PHP y MySQL** para gestionar libros, usuarios y préstamos de una biblioteca.

El sistema permite administrar:

- 📚 Libros
- 👤 Usuarios
- 📖 Préstamos
- 🔄 Devoluciones

## 🛠️ Tecnologías utilizadas

- **PHP**
- **MySQL**
- **PDO**
- **HTML5**
- **CSS3**

## 📁 Estructura del proyecto

```text
library-management-system/
│
├── classes/
│   ├── Biblioteca.php
│   ├── Database.php
│   ├── Libro.php
│   ├── Prestamo.php
│   └── Usuario.php
│
├── css/
│   └── style.css
│
├── biblioteca.sql
├── index.php
└── README.md
```

## 🚀 Instalación

### 1. Clonar el repositorio

Se debe clonar el repositorio utilizando Git:

```bash
git clone https://github.com/gabo-cc/library-management-system.git
```

Luego, ingresar al directorio del proyecto:

```bash
cd library-management-system
```

### 2. Configurar la base de datos

Se debe importar el archivo `biblioteca.sql` utilizando **phpMyAdmin** o **MySQL Workbench**.

Este archivo se encarga de crear la base de datos `biblioteca` y las tablas necesarias para el funcionamiento del sistema.

### 3. Configurar la conexión a MySQL

Se deben configurar las credenciales de conexión a MySQL en:

```text
classes/Database.php
```

Ejemplo:

```php
private $host = 'localhost';
private $db_name = 'biblioteca';
private $username = 'root';
private $password = '';
```

> **Nota:** La contraseña debe coincidir con la configuración de MySQL en el entorno local.

### 4. Ejecutar el proyecto con XAMPP

Si se utiliza **XAMPP**, se deben iniciar los servicios:

- **Apache**
- **MySQL**

Luego, colocar el proyecto dentro de la carpeta:

```text
htdocs/
```

Por ejemplo:

```text
C:\xampp\htdocs\library-management-system\
```

Después, abrir el navegador y acceder a:

```text
http://localhost/library-management-system/
```

### 5. Ejecutar con el servidor integrado de PHP

También es posible ejecutar el proyecto utilizando el servidor integrado de PHP.

Desde la carpeta raíz del proyecto, ejecutar:

```bash
php -S localhost:8000
```

Luego, abrir el navegador y acceder a:

```text
http://localhost:8000
```

## 🗄️ Base de datos

La aplicación utiliza una base de datos MySQL llamada:

```text
biblioteca
```

El archivo `biblioteca.sql` contiene la estructura necesaria para crear la base de datos y sus tablas.

## 📌 Requisitos

Antes de ejecutar el proyecto, se recomienda contar con:

- **PHP 7.4 o superior**
- **MySQL 5.7 o superior**
- **Apache** (si se utiliza XAMPP)
- **Git** para clonar el repositorio
