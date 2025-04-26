# DnD_GM_WebManager
<h3>Dungeons and Dragons Game Master Manager for Web</h3>
<hr>

<p>Diseño de una web con gestor de usuarios, personajes y raids para partidas de DnD.</p>

<h1>Despliegue de la aplicación</h1>
<ul>
  <li>
    <h2>Docker</h2>
    <ol>
      <li>
        <p>Descargar la imagen de DockerHub con el siguiente comando:</p>
        <code>docker pull logecraft/logeverse</code>
      </li>
      <li>
        <p>Actualizar la web dentro del contenedor:</p>
        <code>docker start -ai LogeVerse sh /webupdate.sh</code>
      </li>
      <li>
        <p><a styles="color:green;">UNA VEZ INSTALADO</a> Arrancar el contenedor:</p>
        <code>docker create --name LogeVerse -p 8080:80 logecraft/logeverse</code>
      </li>
    </ol>
  </li>
  <h2>Descargar y ejecutar la web [XAMPP]</h2>
  <p>&lt;web_dir> || Directorio del servidor web donde guardaremos los archivos</p>
  <ol>
    <li>
      <p>Debemos tener previamente instalados los servicios pertinentes</p>
      <ul>
        <li>
          <p>Servidor Web | Recomendado: <a styles="color:green;">Apache</a></p>
        </li>
        <li>
          <p>Servidor <a styles="color:green;">MySQL</a></p>
        </li>
      </ul>
    </li>
    <li>
      <p>Clonar el repositorio con el siguiete comando:</p>
      <code>git clone https://github.com/IvanFreireDacoba/LogeVerse.git /opt/lampp/htdocs</code>
    </li>
    <li>
      <p>Mover los archivos al directorio deseado [Desde raíz de servidor Web]</p>
      <p styles="color:red;">NOTA: Los directorios raíz pueden depender de los programas elegidos y sus configuraciones.</p>
    </li>
    <li>
      <p>Arrancar los servidores pertinentes [Web + Base de Datos]</p>
    </li>
    <li>
      <p>Viajar a localhost/&lt;web_dir>/DB_Schema/scripts/perform_database.php</p>
      <p>Esta dirección creará, estructurará y rellenará la base de datos.</p>
      <p styles="color:red;">NOTA: Esto solo generará la base de datos LA PRIMERA VEZ que se accede.</p>
    </li>
    <li>
      <p>Acceder a nuestro programa mediante la url localhost/&lt;web_dir></p>
    </li>
  </ol>
</ul>

<h1>Utilidades</h1>
<p>Pendiente : <a styles="color:red; height: 200px;
                width: 200px;
                border-radius: 50%;
                text-align: center;"></a>
    || Realizado: <a styles="color:green;"></a></p>
<ul>
  <li>Gestor de Personajes <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
  </li>
  <li>Gestor de experiencia y economía <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Gestor de raids <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Razas <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Clases <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Efectos <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Objetos <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Idiomas <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Habilidades <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Atributos <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Pasivas <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
  <li>Propuesta de Eventos <a styles="color:red; height: 200px;
                                      width: 200px;
                                      border-radius: 50%;
                                      text-align: center;"></a>
 </li>
</ul>

<p>
  Actualmente -> Desplegar con Xampp, no necesita dependencias.
  Descargar el repositorio y copiar en htdocs su contenido.
  Es posible copiar el contenido en un directorio interno y acceder con
  localhost/$lt;nombre_directorio>
</p>
