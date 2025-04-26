# LogeVerse
<h3>Dungeons and Dragons Game Master Manager like for Web</h3>
<hr>

<p>Diseño de una web con gestor de usuarios, personajes y raids para partidas de "DnD".</p>

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
        <p><a>UNA VEZ INSTALADO</a> Arrancar el contenedor:</p>
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
          <p>Servidor Web | Recomendado: <a>Apache</a></p>
        </li>
        <li>
          <p>Servidor <a>MySQL</a></p>
        </li>
      </ul>
    </li>
    <li>
      <p>Clonar el repositorio con el siguiete comando:</p>
      <code>git clone https://github.com/IvanFreireDacoba/LogeVerse.git /opt/lampp/htdocs</code>
    </li>
    <li>
      <p>Mover los archivos al directorio deseado [Desde raíz de servidor Web]</p>
      <p>NOTA: Los directorios raíz pueden depender de los programas elegidos y sus configuraciones.</p>
    </li>
    <li>
      <p>Arrancar los servidores pertinentes [Web + Base de Datos]</p>
    </li>
    <li>
      <p>Viajar a localhost/&lt;web_dir>/DB_Schema/scripts/perform_database.php</p>
      <p>Esta dirección creará, estructurará y rellenará la base de datos.</p>
      <p>NOTA: Esto solo generará la base de datos LA PRIMERA VEZ que se accede.</p>
    </li>
    <li>
      <p>Acceder a nuestro programa mediante la url localhost/&lt;web_dir></p>
    </li>
  </ol>
</ul>

<h1>Utilidades</h1>
<p>Pendiente : <a>🔴</a>
    || Realizado: 🟢</p>
<ul>
  <li>
    <p>Gestor de Personajes <a>🔴</a></p>
  </li>
  <li>
    <p>Gestor de experiencia y economía <a>🔴</a></p>
 </li>
  <li>
    <p>Gestor de raids <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Razas <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Clases <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Efectos <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Objetos <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Idiomas <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Habilidades <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Atributos <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Pasivas <a>🔴</a></p>
 </li>
  <li>
    <p>Propuesta de Eventos <a>🔴</a></p>
 </li>
</ul>
