-- Script SQL para agregar la columna folio a la tabla solicitudes
-- Ejecutar este script directamente en phpMyAdmin o MySQL

-- IMPORTANTE: Cambia 'reportes_ciudadanos' por el nombre real de tu base de datos
-- Puedes verificar el nombre de tu base de datos en el archivo .env (DB_DATABASE)

-- Paso 1: Seleccionar la base de datos (cambia el nombre si es diferente)
USE reportes_ciudadanos;

-- Paso 2: Verificar si la columna ya existe
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'reportes_ciudadanos' 
  AND TABLE_NAME = 'solicitudes' 
  AND COLUMN_NAME = 'folio';

-- Paso 3: Si la consulta anterior no devuelve resultados, ejecuta este comando:
ALTER TABLE solicitudes 
ADD COLUMN folio VARCHAR(20) NULL UNIQUE AFTER id;

-- Paso 4: Verificar que la columna se creó correctamente
DESCRIBE solicitudes;

-- Si hay errores de "Duplicate entry" al crear el índice único, puedes hacer esto:
-- Primero agregar la columna sin el índice único:
-- ALTER TABLE solicitudes ADD COLUMN folio VARCHAR(20) NULL AFTER id;
-- Luego agregar el índice único:
-- ALTER TABLE solicitudes ADD UNIQUE INDEX solicitudes_folio_unique (folio);

