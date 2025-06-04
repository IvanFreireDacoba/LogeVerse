#!/bin/bash
FROM tomsik68/xampp
#================================INFO IMAGEN====================================
RUN echo "Imagen de LogeVerse"
RUN echo "Autor: Iván Freire Dacoba"
 
#=============================CONTROL DE PUERTOS================================
RUN echo "Exponiendo puertos 80 y 3306..."
EXPOSE 80
EXPOSE 3306
RUN echo "Puertos expuestos correctamente."

# ===============================DEPENDENCIAS===================================
RUN apt-get update && \
    apt-get install -y git curl unzip bash && \
    ln -s /opt/lampp/bin/php-8.2.12 /usr/bin/php && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer --version

#==========================COPIAR SCRIPT WebUpdate==============================
RUN echo "Copiando webupdate.sh."
COPY ./webupdate.sh /webupdate.sh
RUN echo "webupdate.sh copiado correctamente."
RUN chmod 777 /webupdate.sh
RUN echo "Permisos para webupdate.sh garantizados."

#=======================Ejecutar webupdate.sh en build==========================
RUN echo "Lanzando script para descargar el repositorio y generar la base de datos."
RUN bash /webupdate.sh
RUN echo "Web y base de datos creadas correctamente."