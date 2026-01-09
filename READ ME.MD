# API Rest de Gestión de Clientes - Prueba Técnica GDA

Este proyecto es una API Rest desarrollada con **Lumen (Laravel)** para la gestión de clientes, incluyendo autenticación personalizada mediante tokens SHA1 y auditoría de transacciones.

## 🚀 Funcionalidades
- **Autenticación**: Login con generación de tokens SHA1 almacenados en BD.
- **Seguridad**: Middlewares para validación de tokens y verificación de expiración.
- **CRUD de Clientes**: Registro, búsqueda filtrada y eliminación lógica (`status = 'trash'`).
- **Validaciones Geográficas**: Verificación de que la comuna pertenezca a la región seleccionada.
- **Auditoría**: Registro automático de logs de cada petición en la base de datos.

## 🛠️ Tecnologías utilizadas
- PHP (Framework Lumen)
- MySQL / MariaDB
- Composer
- Git / GitHub

## 📋 Requisitos e Instalación
1. Clonar el repositorio: `git clone https://github.com/intocaster/prueba-gda.git`
2. Instalar dependencias: `composer install`
3. Configurar el archivo `.env` con las credenciales de tu base de datos local.
4. Importar el archivo `database.sql` incluido en la raíz del proyecto para recrear la estructura y los datos base (regiones/comunas).

## 🔑 Autenticación
Para utilizar las rutas protegidas, primero debe obtener un token:
- **POST** `/login` -> Retorna un token SHA1.
- Usar este token en los headers de las demás peticiones:  
  `Authorization: Bearer [tu_token]`

## 🧪 Pruebas con Postman
Se incluye lógica para responder con códigos de estado HTTP adecuados (200, 201, 401, etc.) y mensajes descriptivos en caso de error.