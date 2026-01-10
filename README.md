(
  # API Rest de Gestión de Clientes - Prueba Técnica GDA
 .
  Este proyecto consiste en una API Rest profesional desarrollada con el framework **Lumen (Laravel)**. La solución ha sido diseñada siguiendo el modelo relacional solicitado y aplicando una metodología de **Aseguramiento de Calidad (QA)** para garantizar la integridad de los datos y la seguridad del sistema.
 .
  ## 🚀 Funcionalidades Principales
  - **Autenticación Personalizada**: Sistema de inicio de sesión que genera tokens únicos en formato SHA1.
  - **Seguridad (Middlewares)**: 
    - Validación de existencia y validez del token en cada petición protegida.
    - Verificación de expiración del token basada en tiempo real.
  - **Gestión de Clientes (CRUD)**:
    - **Registro**: Con validación de campos obligatorios (address, email, dni, etc.).
    - **Búsqueda**: Consultas filtradas por DNI o Apellido, con cruce de datos (Joins) para mostrar nombres de Regiones y Comunas.
    - **Borrado Lógico**: Los registros no se eliminan físicamente, se marcan como status = 'trash'.
  - **Auditoría**: Sistema de logs que registra automáticamente cada transacción en la base de datos para trazabilidad técnica.
 .
  ## 🛠️ Stack Tecnológico
  - **Backend**: PHP 8.x (Lumen Framework)
  - **Base de Datos**: MySQL / MariaDB
  - **Gestión de Dependencias**: Composer
  - **Pruebas y QA**: Postman (Suite de pruebas de integración)
 .
  ## 🧪 Estrategia de Aseguramiento de Calidad (QA)
  Como especialista en formación de QA Testing, he implementado las siguientes validaciones:
  1. **Integridad Referencial**: El sistema valida que la Comuna pertenezca efectivamente a la Región seleccionada antes de permitir el guardado.
  2. **Validación de Estados**: Solo se permiten transacciones con Regiones y Comunas que tengan el estado Activo (status = 'A').
  3. **Negative Testing**: Gestión controlada de respuestas HTTP (401 Unauthorized, 400 Bad Request, 404 Not Found) ante tokens expirados o datos inválidos.
  4. **Consistencia de Esquema**: Alineación total con el diagrama de base de datos, incluyendo campos de auditoría (date_reg) y descriptivos (address).
 .
  ## 📋 Instalación y Despliegue
  1. **Clonar el repositorio**: git clone https://github.com/intocaster/prueba-gda.git
  2. **Instalar dependencias**: composer install
  3. **Configurar el entorno**: Copiar .env.example a .env y configurar credenciales de BD.
  4. **Importar Base de Datos**: El archivo database.sql en la raíz contiene la estructura completa y datos maestros.
 .
  ## 🔑 Endpoints Principales
  ^| Método ^| Ruta ^| Descripción ^|
  ^| :--- ^| :--- ^| :--- ^|
  ^| POST ^| /login ^| Genera el token de acceso (SHA1). ^|
  ^| POST ^| /customers ^| Registra un nuevo cliente (Requiere Token). ^|
  ^| GET ^| /customers/search ^| Busca clientes por DNI o Apellido (Requiere Token). ^|
  ^| DELETE ^| /customers/{dni} ^| Realiza el borrado lógico del cliente (Requiere Token). ^|
 .
  ---
  **Desarrollado por JUAN SERRANO** - *Enfoque dual: Desarrollo Backend ^& QA Automation*
) > README.md