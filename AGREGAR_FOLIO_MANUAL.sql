-- EJECUTAR ESTE SCRIPT EN phpMyAdmin
-- 
-- INSTRUCCIONES:
-- 1. Abre http://localhost/phpmyadmin
-- 2. Selecciona tu base de datos (verifica el nombre en .env -> DB_DATABASE)
-- 3. Haz clic en la pestaña "SQL"
-- 4. Copia y pega el siguiente comando:

ALTER TABLE solicitudes ADD COLUMN folio VARCHAR(20) NULL AFTER id;
ALTER TABLE solicitudes ADD UNIQUE INDEX solicitudes_folio_unique (folio);

-- 5. Verifica que se creó correctamente:
DESCRIBE solicitudes;

-- Deberías ver la columna 'folio' en la lista

