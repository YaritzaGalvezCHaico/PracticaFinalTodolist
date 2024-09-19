UNIVERSIDAD TECNOLÓGICA DE LOS ANDES

FACULTAD DE INGENIERÍA

ESCUELA PROFESIONAL DE INGENIERÍA DE SISTEMAS E INFORMÁTICA

**Aplicación ToDoList**

Autores:

Yaritza Lucita Galvez Chaico

IS16074: Ingeniería de Software II

Semestre Académico: 2024 – I

Semestre: VII

Docente: Ing. Eduardo Chavez Vasquez

10 de agosto de 2024

**Marco teórico**

[**Introducción	3**](\#introducción)

[1.1. Objetivos de la documentación	3](\#1.1.-objetivos-de-la-documentación)

[1.2. Resumen del proyecto	3](\#1.2.-resumen-del-proyecto)

[**Evolución del Proyecto	5**](\#evolución-del-proyecto)

[2.1. Cambios y mejoras desde la última versión	5](\#2.1.-cambios-y-mejoras-desde-la-última-versión)

[**Nuevas Clases y Métodos Implementados	8**](\#nuevas-clases-y-métodos-implementados)

[3.1. Funciones y Técnicas Implementadas	8](\#3.1.-funciones-y-técnicas-implementadas)

[**Plantillas Añadidas	9**](\#plantillas-añadidas)

[4.1. Formulario de Búsqueda	10](\#4.1.-formulario-de-búsqueda)

[4.2. Formulario de Ordenación	10](\#4.2.-formulario-de-ordenación)

[**Explicación de Código Fuente	12**](\#explicación-de-código-fuente)

[5.1. Gestión de Sesiones	12](\#5.1.-gestión-de-sesiones)

[5.2. Recuperación del Nombre de Usuario	12](\#5.2.-recuperación-del-nombre-de-usuario)

[5.3. Procesamiento de Formularios POST	13](\#5.3.-procesamiento-de-formularios-post)

[5.4. Consulta y Visualización de Tareas	15](\#5.4.-consulta-y-visualización-de-tareas)

[5.5. Consulta y Visualización de Tareas	16](\#5.5.-consulta-y-visualización-de-tareas)

[**Conclusiones	17**](\#conclusiones)

# **Introducción** {#introducción}

## 

## **1.1. Objetivos de la documentación** {#1.1.-objetivos-de-la-documentación}

El objetivo general de la documentación del proyecto "ToDo List" es proporcionar una descripción técnica clara y concisa que permita a los miembros del equipo de desarrollo entender la evolución e implementación de la aplicación. Dado que solo una persona desarrolló el trabajo, la documentación deberá reflejar los aspectos técnicos del proyecto, destacando las decisiones de diseño, las clases y métodos implementados, así como las plantillas añadidas.

La documentación servirá como una guía para que los miembros del equipo puedan familiarizarse con el código y las funcionalidades implementadas, asegurando que todos tengan una comprensión común sobre el estado actual del proyecto y cómo se ha llegado a este, facilitando futuras contribuciones o modificaciones. A través de la documentación, se busca también transmitir el proceso de desarrollo utilizado, incluyendo el uso de Git y GitHub para el control de versiones, lo que garantiza que el equipo pueda seguir el progreso y colaborar efectivamente en el desarrollo del software.

## **1.2. Resumen del proyecto** {#1.2.-resumen-del-proyecto}

El proyecto "ToDo List" es una aplicación web diseñada para gestionar tareas y mejorar la organización personal. La aplicación permite a los usuarios crear, visualizar, editar y eliminar tareas, facilitando un seguimiento eficiente de sus actividades diarias. Desarrollada en PHP, la aplicación utiliza MySQL como sistema de gestión de bases de datos, lo que permite un almacenamiento persistente y efectivo de la información.

***1.2.1. Principales características del proyecto***

***1.2.1.1. Interfaz de Usuario Intuitiva***: Se diseñó una interfaz sencilla y amigable que permite a los usuarios interactuar fácilmente con las funcionalidades de la aplicación. Se emplean plantillas HTML y CSS para una presentación clara y atractiva.

**1.2.1.2. Gestión de Tareas:**

***a. Crear Tareas:*** Los usuarios pueden añadir nuevas tareas proporcionando un título y una descripción opcional.

***b. Listar Tareas**:* La aplicación muestra un listado de todas las tareas existentes, permitiendo la visualización rápida del estado de cada una.

***c. Editar Tareas***: Se implementa la opción de editar tareas para actualizar su contenido según lo requiera el usuario.

***d. Eliminar Tareas***: Los usuarios pueden eliminar tareas completadas o que ya no necesiten.

**1.2.1.3. Control de versiones:** Se utilizó Git para gestionar el control de versiones del proyecto, permitiendo registrar los değişiklikler realizados a lo largo del desarrollo. Las ramas de características (features) se emplearon para implementar nuevas funcionalidades de manera controlada y ordenada.

**1.2.1.4. Gestión de Base de Datos:** La aplicación utiliza MySQL para almacenar la información de las tareas. Las operaciones de creación, lectura, actualización y eliminación (CRUD) se gestionan a través de consultas SQL.

**1.2.1.5. Despliegue del Proyecto:** El proyecto se encuentra alojado en GitHub, donde se aplican estrategias de integración continua mediante pull requests (PRs), y se utilizan issues y milestone para mejorar la planificación y seguimiento del desarrollo.

# **Evolución del Proyecto** {#evolución-del-proyecto}

## **2.1. Cambios y mejoras desde la última versión** {#2.1.-cambios-y-mejoras-desde-la-última-versión}

Se han implementado varias mejoras y nuevas funcionalidades en el archivo tareas.php para mejorar la gestión de tareas y la experiencia del usuario. A continuación, se detallan los principales cambios:

***2.1.1. Gestión de Sesión y Usuario***

* **Nombre del Usuario:** Se añadió la lógica para consultar y almacenar el nombre del usuario en la sesión ($\_SESSION\['usuario\_nombre'\]). Esto se usa para personalizar la interfaz, mostrando el nombre del usuario en el título de la página y en los mensajes.

***121.2. Manejo de Mensajes***

* **Tipos de Mensaje**: Implementado un sistema para manejar diferentes tipos de mensajes (success, confirm, error). Utiliza la variable $mensaje\_tipo para adaptar el estilo y el comportamiento del mensaje según su tipo.

***2.1.3. Verificación de Tareas Duplicadas***

* **Nueva Funcionalidad:** Antes de añadir una nueva tarea, se verifica si ya existe una tarea con la misma descripción para el usuario actual. Si la tarea es duplicada, se muestra un mensaje de confirmación para que el usuario decida si desea duplicarla.

***2.1.4. Confirmación de Creación de Tareas***

* **Confirmación de Duplicados:** Se ha añadido un formulario de confirmación que se activa cuando se detecta una tarea duplicada. Permite al usuario confirmar o cancelar la creación de la tarea duplicada.

***2.1.5. Cambio de Estado de Tareas***

* **Botones de Estado:** Se han añadido botones en la tabla para cambiar el estado de las tareas entre pendiente y completada. Al hacer clic en el botón, el estado se actualiza en la base de datos y se refleja en la interfaz.

***2.1.6. Eliminación de Tareas***

* **Funcionalidad de Eliminación:** Se ha implementado un botón para eliminar tareas directamente desde la tabla. Este botón está integrado en un formulario que envía una solicitud POST para eliminar la tarea seleccionada.

***2.1.7. Ordenación de Tareas***

* Menú de Ordenación: Se ha añadido un menú desplegable que permite ordenar las tareas por id, descripcion, o estado. Esto facilita la organización de la lista de tareas según las preferencias del usuario.

***2.1.8. Búsqueda de Tareas***

* **Formulario de Búsqueda:** Se ha incluido un formulario para buscar tareas por su descripción, permitiendo a los usuarios localizar tareas específicas más fácilmente.

***2.1.9. Estilo y Presentación***

* **Estilo de Mensajes de Alerta:** Mejorado el estilo de los mensajes de alerta con clases CSS específicas para success, confirm, y error. Esto mejora la visibilidad y claridad de los mensajes para el usuario.  
* **Botones de Acción**: Los botones para añadir, editar, eliminar tareas y cerrar sesión han sido estilizados para mejorar la apariencia y usabilidad.

***2.1.10. Redirección y Manejo de Errores***

* **Redirección Automática:** Después de actualizar el estado de una tarea, se redirige automáticamente a la página de tareas para actualizar la vista.  
* **Manejo de Errores:** Se ha mejorado el manejo de errores en la conexión a la base de datos y las operaciones SQL, proporcionando mensajes claros para facilitar la depuración.

  # **Nuevas Clases y Métodos Implementados** {#nuevas-clases-y-métodos-implementados}

  ## **3.1. Funciones y Técnicas Implementadas** {#3.1.-funciones-y-técnicas-implementadas}

1. **session\_start()**  
* **Descripción:** Inicia la sesión PHP o la reanuda si ya está iniciada. Se utiliza para gestionar el estado del usuario y almacenar información entre páginas.  
* **Uso:** Se usa al principio del archivo para asegurar que las variables de sesión estén disponibles.  
2. **mysqli (MySQLi)**  
* **Descripción:** La extensión MySQLi proporciona una interfaz mejorada para trabajar con bases de datos MySQL. En este archivo, se usa para conectar a la base de datos y ejecutar consultas.  
* **Métodos Usados:**  
  * **new mysqli():** Crea una nueva instancia de la conexión a la base de datos.  
  * **connect\_error:** Verifica si hay errores en la conexión a la base de datos.  
  * **query():** Ejecuta una consulta SQL en la base de datos.  
  * **real\_escape\_string():** Escapa las cadenas para evitar inyecciones SQL.  
3. **htmlspecialchars()**  
* Descripción: Convierte caracteres especiales en entidades HTML para prevenir la inyección de código y asegurar que el contenido se muestre correctamente en el navegador.  
* Uso: Se utiliza para mostrar datos del usuario y mensajes en la interfaz de usuario de manera segura.  
4. **Método POST**  
* Descripción: Se utiliza para enviar datos al servidor en formularios HTML. En este archivo, se usa para manejar la creación, actualización y eliminación de tareas.  
* Uso: El archivo procesa diferentes solicitudes POST para añadir, actualizar y eliminar tareas.  
5. **header("Location: ...")**  
* **Descripción:** Redirige al navegador a una nueva URL.  
* **Uso**: Se utiliza para redirigir al usuario a la página de inicio de sesión si no está autenticado o para actualizar la página después de cambiar el estado o eliminar una tarea.

  # 

  # 

  # 

  # 

# 

# **Plantillas Añadidas** {#plantillas-añadidas}

## **4.1. Formulario de Búsqueda** {#4.1.-formulario-de-búsqueda}

* **Ubicación:** Parte superior de la página de tareas (tareas.php).  
* **Propósito:** Permite al usuario buscar tareas por descripción.  
* **Código:**

| \<form class="search-form" action="tareas.php" method="GET"\>     \<input type="text" name="busqueda" placeholder="Buscar tarea..."\>     \<button type="submit"\>Buscar\</button\> \</form\> |
| :---- |

* **Descripción:** Un campo de entrada para la descripción de la tarea que se busca y un botón de búsqueda. La búsqueda se realiza a través de una solicitud GET que actualiza la página con los resultados de la búsqueda.

  ## **4.2. Formulario de Ordenación** {#4.2.-formulario-de-ordenación}

* **Ubicación:** Parte superior de la página de tareas (tareas.php).  
* **Propósito:** Permite al usuario ordenar las tareas por diferentes criterios, como ID, descripción o estado.  
* **Código:**

| \<form class="sort-form" action="tareas.php" method="GET"\>     \<label for="orden"\>Ordenar por:\</label\>     \<select name="orden" id="orden" onchange="this.form.submit()"\>         \<option value="id" \<?php if ($orden \== 'id') echo 'selected'; ?\>\>ID\</option\>         \<option value="descripcion" \<?php if ($orden \== 'descripcion') echo 'selected'; ?\>\>Descripción\</option\>         \<option value="estado" \<?php if ($orden \== 'estado') echo 'selected'; ?\>\>Estado\</option\>     \</select\> \</form\> |
| :---- |

* **Descripción:** Un menú desplegable que permite al usuario seleccionar el criterio de ordenación. La selección se envía mediante una solicitud GET que actualiza la página para mostrar las tareas ordenadas según el criterio elegido.

  ## 

  # **Explicación de Código Fuente** {#explicación-de-código-fuente}


  ## **5.1. Gestión de Sesiones** {#5.1.-gestión-de-sesiones}

* Código:

| session\_start(); if (\!isset($\_SESSION\['usuario\_id'\])) {     header("Location: login.php");     exit(); } |
| :---- |

* Descripción:  
  * session\_start();: Inicia una sesión o reanuda una sesión existente. Es esencial para manejar el estado del usuario entre solicitudes.  
  * Chequeo de $\_SESSION\['usuario\_id'\]: Verifica si el identificador del usuario está presente en la sesión. Si no está presente, redirige al usuario a la página de inicio de sesión (login.php) y termina la ejecución del script.

  ## **5.2. Recuperación del Nombre de Usuario** {#5.2.-recuperación-del-nombre-de-usuario}

* Código:

| if (\!isset($\_SESSION\['usuario\_nombre'\])) {     $sql\_usuario \= "SELECT nombre\_usuario FROM usuarios WHERE id \= $usuario\_id";     $result\_usuario \= $conn-\>query($sql\_usuario);     if ($result\_usuario-\>num\_rows \> 0\) {         $usuario \= $result\_usuario-\>fetch\_assoc();         $\_SESSION\['usuario\_nombre'\] \= $usuario\['nombre\_usuario'\];     } else {         die("Error: El usuario no existe.");     } } |
| :---- |

* **Descripción:**  
  * **Chequeo de $\_SESSION\['usuario\_nombre'\]:** Verifica si el nombre del usuario ya está almacenado en la sesión. Si no está, realiza una consulta a la base de datos para obtenerlo.  
  * **Consulta SQL:** Obtiene el nombre del usuario basado en su ID.  
  * **Almacenamiento en la sesión:** Si el usuario existe, guarda el nombre en $\_SESSION\['usuario\_nombre'\] para su uso posterior.

  ## **5.3. Procesamiento de Formularios POST** {#5.3.-procesamiento-de-formularios-post}

* Código:

| if ($\_SERVER\["REQUEST\_METHOD"\] \== "POST") {     if (isset($\_POST\['nueva\_tarea'\]) && \!empty($\_POST\['descripcion'\])) {         $descripcion \= $conn-\>real\_escape\_string($\_POST\['descripcion'\]);                  $sql\_verificar\_tarea \= "SELECT id FROM tareas WHERE descripcion \= '$descripcion' AND usuario\_id \= $usuario\_id";         $result\_verificar\_tarea \= $conn-\>query($sql\_verificar\_tarea);         if ($result\_verificar\_tarea-\>num\_rows \> 0\) {             $mensaje \= 'La tarea con esta descripción ya existe. ¿Desea volver a crearla?';             $mensaje\_tipo \= 'confirm';             $\_SESSION\['nueva\_tarea\_descripcion'\] \= $descripcion;         } else {             $sql\_max\_id \= "SELECT IFNULL(MAX(id), 0\) AS max\_id FROM tareas WHERE usuario\_id \= $usuario\_id";             $result\_max\_id \= $conn-\>query($sql\_max\_id);             $max\_id \= $result\_max\_id-\>fetch\_assoc()\['max\_id'\];             $next\_id \= $max\_id \+ 1;             $sql\_tarea \= "INSERT INTO tareas (id, descripcion, estado, usuario\_id) VALUES ($next\_id, '$descripcion', 'pendiente', $usuario\_id)";                          if ($conn-\>query($sql\_tarea) \=== TRUE) {                 $mensaje \= 'Tarea creada correctamente.';                 $mensaje\_tipo \= 'success';             } else {                 $mensaje \= "Error: " . $sql\_tarea . "\<br\>" . $conn-\>error;                 $mensaje\_tipo \= 'error';             }         }     } elseif (isset($\_POST\['confirmar\_creacion'\])) {         $descripcion \= $\_SESSION\['nueva\_tarea\_descripcion'\];         $sql\_max\_id \= "SELECT IFNULL(MAX(id), 0\) AS max\_id FROM tareas WHERE usuario\_id \= $usuario\_id";         $result\_max\_id \= $conn-\>query($sql\_max\_id);         $max\_id \= $result\_max\_id-\>fetch\_assoc()\['max\_id'\];         $next\_id \= $max\_id \+ 1;         $sql\_tarea \= "INSERT INTO tareas (id, descripcion, estado, usuario\_id) VALUES ($next\_id, '$descripcion', 'pendiente', $usuario\_id)";                  if ($conn-\>query($sql\_tarea) \=== TRUE) {             $mensaje \= 'Tarea creada correctamente.';             $mensaje\_tipo \= 'success';             unset($\_SESSION\['nueva\_tarea\_descripcion'\]);         } else {             $mensaje \= "Error: " . $sql\_tarea . "\<br\>" . $conn-\>error;             $mensaje\_tipo \= 'error';         }     } elseif (isset($\_POST\['cambiar\_estado'\])) {         $tarea\_id \= intval($\_POST\['tarea\_id'\]);         $estado\_actual \= $\_POST\['estado\_actual'\];         $nuevo\_estado \= ($estado\_actual \== 'pendiente') ? 'completada' : 'pendiente';         $sql\_cambiar\_estado \= "UPDATE tareas SET estado \= '$nuevo\_estado' WHERE id \= $tarea\_id AND usuario\_id \= $usuario\_id";                  if ($conn-\>query($sql\_cambiar\_estado) \=== TRUE) {             header("Location: tareas.php");             exit();         } else {             $mensaje \= "Error: " . $sql\_cambiar\_estado . "\<br\>" . $conn-\>error;             $mensaje\_tipo \= 'error';         }     } elseif (isset($\_POST\['eliminar\_tarea'\])) {         $tarea\_id \= intval($\_POST\['tarea\_id'\]);         $sql\_eliminar\_tarea \= "DELETE FROM tareas WHERE id \= $tarea\_id AND usuario\_id \= $usuario\_id";                  if ($conn-\>query($sql\_eliminar\_tarea) \=== TRUE) {             header("Location: tareas.php");             exit();         } else {             $mensaje \= "Error: " . $sql\_eliminar\_tarea . "\<br\>" . $conn-\>error;             $mensaje\_tipo \= 'error';         }     } } |
| :---- |

* D**escripción:**  
  * **Añadir Nueva Tarea:**  
    * **Validación:** Verifica si la tarea ya existe y muestra un mensaje de confirmación si es necesario.  
    * **Inserción:** Si la tarea no existe, se genera un nuevo ID y se inserta en la base de datos.  
  * **Confirmación de Tarea Duplicada:**  
    * **Creación de Tarea Duplicada:** Si el usuario confirma, se crea la tarea con la descripción guardada en la sesión.  
  * C**ambio de Estado:**  
    * **Actualización del Estado:** Cambia el estado de una tarea entre 'pendiente' y 'completada'.  
    * **Eliminación de Tarea:**  
  * **Eliminación:** Elimina una tarea específica basada en su ID.

  ## **5.4. Consulta y Visualización de Tareas** {#5.4.-consulta-y-visualización-de-tareas}

* Código:

| $sql\_tareas \= "SELECT id, descripcion, estado FROM tareas WHERE usuario\_id \= $usuario\_id ORDER BY $orden"; $result\_tareas \= $conn-\>query($sql\_tareas); |
| :---- |

* Descripción:  
  * Consulta SQL: Selecciona tareas asociadas con el usuario\_id y las ordena según el criterio especificado en $orden.

  ## **5.5. Consulta y Visualización de Tareas** {#5.5.-consulta-y-visualización-de-tareas}

* Código:

| \<\!DOCTYPE html\> \<html lang="es"\> \<head\>     \<meta charset="UTF-8"\>     \<meta name="viewport" content="width=device-width, initial-scale=1.0"\>     \<title\>Listado de Tareas \<?php echo htmlspecialchars($\_SESSION\['usuario\_nombre'\]); ?\>\</title\>     \<link rel="stylesheet" href="tareas.css"\> \</head\> \<body id="tareas-page"\>     \<div class="tareas-container"\>         \<h2\>Listado de Tareas de \<span id="nombre-usuario"\>\<?php echo htmlspecialchars($\_SESSION\['usuario\_nombre'\]); ?\>\</span\>\</h2\>         \<\!-- Formulario de búsqueda \--\>         \<\!-- Formulario de ordenación \--\>         \<\!-- Tabla de tareas \--\>         \<\!-- Formulario de añadir tarea \--\>         \<\!-- Mensajes de alerta \--\>         \<\!-- Cierre de sesión \--\>     \</div\> \</body\> \</html\>  |
| :---- |

* **Descripción:**  
  * **Estructura:** La página HTML contiene formularios para la búsqueda, ordenación y adición de tareas, así como una tabla para mostrar las tareas existentes y mensajes de alerta para informar al usuario sobre el estado de sus acciones.  
  * **Inyección de Datos:** Se usa htmlspecialchars() para escapar datos del usuario y evitar problemas de seguridad como XSS (Cross-Site Scripting).

  # **Conclusiones** {#conclusiones}

La implementación y evolución del archivo tareas.php refleja un avance significativo en la funcionalidad y usabilidad de la aplicación de gestión de tareas. A lo largo del desarrollo, se han integrado y optimizado varias características clave, mejorando tanto la experiencia del usuario como la eficiencia del sistema. Las conclusiones generales son las siguientes:

* Optimización de Funcionalidades:  
  * Gestión Completa de Tareas: La adición de funciones para añadir, editar, eliminar y cambiar el estado de las tareas ha transformado el archivo tareas.php en un componente robusto para la administración de tareas. La verificación de duplicados y las opciones de confirmación para la creación de tareas garantizan la integridad de los datos y evitan errores.  
* Mejora en la Experiencia del Usuario:  
  * Interfaz de Usuario Intuitiva: La integración de formularios para búsqueda, ordenación y adición de tareas proporciona una interfaz más dinámica y accesible. Los usuarios pueden ahora encontrar, organizar y gestionar sus tareas de manera más eficiente.  
  * Manejo de Mensajes: La implementación de mensajes de alerta claros y específicos, tanto para el éxito como para los errores y confirmaciones, ayuda a mantener a los usuarios informados y facilita la resolución de problemas.

## 

