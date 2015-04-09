#Is This Game Fun? - Documentación
[TOC]

##Cookies
###Objetivos
EL uso de cookies es imprescindible para interactuar con la aplicación mas allá de la mera visualización debido al uso de sesiones que se hace para poder gestionar perfiles y votaciones propias.
###Tipos
[[[*De analisis, estetica, etc (del trabajo de cookies que hicimos)*]]]
###Denominación
A continuación se presentan los nombre de las cookies a usar, los datos que contienen y su formato.

**cookie_compliance** (boolean)
Es necesario que esta cookie exista para que las demas puedan almacenarse. Representa que el usuario ha aceptado la politica de cookies del sitio y acepta su creacion y uso.
Ejemplo:
>true

**PHPSESSID** (string)
Contiene el ID de sesion de la cookie que identifica al usuario en el sevidor.
Ejemplo:
>el4ukv0kqbvoirg7nkp4dncpk3

###Política de cookies
[[[*Describir por encima el uso que le damos a las cookies y un enlace al documento completo*]]]
[[[*Buenos documentos de política de cookies:*
http://politicadecookies.com/
http://www.tudespensa.com/politica-cookies/
]]]

##Friendly URLs
El servidor utiliza el modulo Rewrite Engine de Apache para establecer sus URLs como amigables en pos de mejorar el posicionamiento buscadores y hacer las direcciones mas faciles de recordar.

De esta manera, URLs que son menos estéticas y dificiles de recordar como
>http://isthisgamefun.com/index.php?section=games&game=34

se transforman en 
>http://isthisgamefun.com/games/34

###Funcionamiento
La forma de utilizarlo es sencilla. El usuario solicita una URL y el motor analiza la dirección, toma de ella pedazos que el administrador ha especificado y los convierte en variables y valores. A continuación vemos un ejemplo:

- **Entrada**:  `GAME/ID/`
- **Salida**: `index.php?section=GAME&game=ID`

El motor selecciona pedazos de la dirección haciendo uso de expresiones regulares y los vuelca como variables (`$1`, `$2`, `$3`, ...).

Adicionalmente se pueden usar *flags* al final de cada regla para indicarle al motor que realice ciertas acciones. 
Debido a que el motor no se detiene al encontrar una coincidencia, nosotros haremos uso de un flag [L] que le indica al motor que deje de buscar coincidencias en las reglas si la regla actual ha dado positivo. 
Por este motivo, debemos declarar las reglas en orden de mas especifica a mas general.

###A tener en cuenta

Para que el motor funcione debes tener habilitado el modulo **mod_rewrite** en el servidor Apache.

Es necesario declarar que vas a usar el modulo. Además, para evitar problemas si el modulo se desactiva, utilizaremos el tag `<IfModule>` a la hora de definir las reglas del motor.

```
<IfModule mod_rewrite.c>
#Activar el modulo
RewriteEngine on
#Tus reglas aqui
</IfModule>
```

Estas reglas se definen dentro de la configuración del directorio o en el fichero `.htaccess`, por ello el servidor debe permitir que se definan configuraciones locales. La segunda es la mejor opción así que asegurate de que en el archivo de configuracion del servidor se encuentra la regla `AllowOverwrite on` en el directorio donde este alojada la aplicación.

###Usos
A continuación se especifican las diferentes reglas usadas en el motor:

**/user/nick**
Ejemplos: `/user/admin`, `/user/diego`.
>RewriteRule ^user/(\w+)/?\$ index.php?section=user&user=\$1 [L]

**/game/id**
Ejemplo: `/game/66`.
>RewriteRule ^games/([0-9]+)/?\$ index.php?section=games&game=\$1 [L]

**/section**
Ejemplos: `/games`, `/login`, `/about`.
>RewriteRule ^(\w+)/?\$ index.php?section=\$1 [L]

**/section/order**
Ejemplos: `/games/best`, `/games/last`.
>RewriteRule ^(\w+)/(\w+)/?\$ index.php?section=\$1&order=\$2 [L]

**/seccion/orden/page**
Ejemplo: `/games/best/2`.
>RewriteRule ^(\w+)/(\w+)/([0-9]+)/?\$ index.php?section=\$1&order=\$2&page=\$3 [L]

##Velocidad de carga

###Cache
To-Do

###Compresion GZip
To-Do

##API
El servidor dispone de una **API** de acceso a datos cuyo objetivo inicial es el de proveer acceso a los datos asíncronamente desde JavaScript a través JQuery.

> **Nota**
> La API hace uso de la cookie de sesión para devolver datos personalizados como por ejemplo si ha votado un juego del que solicitas información.

Todas las acciones realizan contra la siguiente dirección:
>http://isthisgamefun.com/api

----------


###GET
A continuación se describen los diferentes métodos para obtener información sobre uno o varios juegos.

 - [GET/GAME](#game)
 - [GET/GAMES/BY](#games-by)
 - [GET/GAMES/VOTED](#games-voted)

####GAME
Utilizando este método podemos recibir toda la información útil de un juego, además de alguna información variable dependiendo de si hay o no una sesión iniciada.
Para realizar la peticion GET tan solo hay que especificar el `game_id` mediante una variable:

>http://isthisgamefun.com/api?game_id=66
El servidor contestará con un JSON con el siguiente formato:
```
{
	"game_id":66,
	"name":"Game",
	"cover":"http://isthisgamefun.com/covers/66.png",
	"platforms":[
		{
		"id":11,
		"name":"Nintendo 64",
		"short_name":"N64",
		"icon":"http://isthisgamefun.com/tags/11.png"
		},
		...
	],
	"totalVotes":55,
	"totalPositiveVotes":50,
	"userVote":0|1|null
}
```
> **NOTA** 
> El atributo `userVote` solo aparece si la petición la realiza un usuario con sesión activa.

####GAMES BY
Con este método puedes solicitar una lista finita de juegos ordenados según diversos criterios.
La lista devuelta contiene un array de objetos JSON similares al objeto devuelto al pedir información sobre un juego ([ver ejemplo](#game)).

Para enviar esta petición son necesarios los siguientes parametros:

- `order_by`: Uno de los posibles valores descritos mas adelante.
- `limit`: Cantidad de objetos a devolver. Por defecto es 20.
- `offset`: Si es especificado, devuelte tantos elementos como indique empezando desde el `limit` establecido. Si este no está especificado toma la posición 0 como inicio.

Los `order_by` disponibles son:

- **latest**: ultimos juegos añadidos a la web.
- **best**: juegos cuyo porcentaje de votos positivos es mas alto.
- **most**: juegos que tienen el mayor numero de votos independientemente del balance.
- **alphabetical**: juegos ordenados alfabeticamente.
- **platform**: juegos ordenados por plataforma.
- **age**: juegos ordenados por su antiguedad descendente en la web.

A continuacion se muestran varios ejemplos de petición de un alista de juegos:

**Usando *order***
Esta peticion devuelve los 20 juegos con el mayor balance de votos positivos.
```
{
	"order":"best"
}
```
**Usando *order***
Esta peticion devuelve los 5 juegos con el mayor balance de votos positivos.
```
{
	"order":"best",
	"limit":5
}
```
**Usando *offset***
Esta peticion devuelve los juegos entre la posición 5 y la 15 con el mayor balance de votos positivos.
```
{
	"order":"best",
	"limit":5,
	"offset":10
}
```
**Usando *offset* sin *limit***
Esta peticion devuelve los juegos entre la posición 0 y la 10 con el mayor balance de votos positivos.
```
{
	"order":"best",
	"offset":10
}
```

####GAMES VOTED
Con este método puedes solicitar una lista finita de juegos votados por un usuario.
La lista devuelta contiene un array de objetos JSON similares al objeto devuelto al pedir información sobre un juego ([ver ejemplo](#game)).

Para enviar esta petición son necesarios los siguientes parametros:

- `user`: Nick del usuario.
- `limit`: Cantidad de objetos a devolver. Por defecto es 20.
- `offset`: Si es especificado, devuelte tantos elementos como indique empezando desde el `limit` establecido. Si este no está especificado toma la posición 0 como inicio.

> **Nota**
> La dinamica de uso de `limit` y `offset` es identica a la descrita en el apartado anterior ([ver explicación](#games-by)).

Los datos devueltos por esta petición contienen un atributo nuevo que no ha sido descrito en el metodo GET/GAME, el atributo `vote`. Este atributo contiene el voto del usuario **sobre** el que se solicita la información y **sustituye** al atributo `userVote` que manifiesta el voto del usuario que hace la petición.


----------


###POST
A continuación se describen los diferentes métodos para enviar información al servidor.

 - [POST/VOTE](#vote)
 - [POST/CHECK](#check)

####VOTE
Para votar un juego a través de la API es necesario enviar por POST una cadena en formato JSON mediante una variable `json`.
Los datos imprescindibles para votar son:

 - `action:"vote"` 
 - `game_id:#id`
 - `vote:true|false`

Ademas la peticion debe enviar la cookie de sesion para que el servidor identifique al usuario que vota. Esto significa que solo el propio usuario puede realizar el voto.

A continuación un ejemplo de una petición de voto:
```
{
	"action":"vote",
	"game_id":66,
	"vote":true
}
```
La respuesta del servidor a esta petición será un JSON con un error y un mensaje.

Ejemplo de un voto aceptado:
```
{
	"error":false,
	"msg":"OK"
}
```
Ejemplo de un voto rechazado:
```
{
	"error":true,
	"msg":"invalid game_id"
}
```
####CHECK
Mediante este método se pueden hacer diferentes comprobaciones tales como **disponibilidad** de un *nick* o un *email* durante la creación de la cuenta.

Es necesario enviar una cadena en formato JSON mediante una variable `json`. 

Este objeto contiene las siguientes posibles variables:

- `action:'check'`
- `type:'name|nick|email'`
- `text:'text_to_check'`

A continuación un ejemplo donde se comprueba si un *nick* esta ya en uso:
```
{
	"action":"check",
	"type":"nick",
	"text":"Kitty96"
}
```
Ante esta petición podemos obtener varias respuestas.

En el caso de que esté disponible:
```
{
	"error":false,
	"exist":false,
	"msg":"OK"
}
```
En el caso de que ya estuviera en uso:
```
{
	"error":false,
	"exist":true,
	"msg":"OK"
}
```
En el caso de que ocurra un error:
```
{
	"error":true,
	"msg":"Some needed parameters were missed"
}
```



> Written with [StackEdit](https://stackedit.io/).