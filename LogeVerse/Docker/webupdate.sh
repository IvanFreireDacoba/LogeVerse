#!/bin/bash

# ============================ Actualizar web ===========================
echo "Borrando contenido anterior..."
rm -rf /opt/lampp/htdocs/*
rm -rf /opt/lampp/htdocs

echo "Clonando repositorio..."
git clone https://github.com/IvanFreireDacoba/LogeVerse.git /opt/lampp/htdocs
rm -rf /opt/lampp/htdocs/.git

echo "¡Web actualizada correctamente!"

# ====================== Instalar librerías composer ====================
echo "Instalando librerías..."
cd /opt/lampp/htdocs
composer install
echo "Librerías instaladas correctamente."

# ========================== Ejecutar script PHP ========================
echo "Ejecutando el script de base de datos..."
/opt/lampp/bin/php /opt/lampp/htdocs/LogeVerse/DB_Schema/perform/perform_database.php
echo "¡Base de datos configurada correctamente!"
