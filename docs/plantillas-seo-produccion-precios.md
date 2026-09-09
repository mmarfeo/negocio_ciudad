# Plantillas nuevas, SEO, salida a producción y precios

**Proyecto:** negociosentuciudad.com
**Estado:** Fases 1–3.5 en producción (plantillas dinámicas, catálogo de productos, chat con Gemini, login)
**Fecha:** agosto 2026

Un mismo documento para las cuatro preguntas: qué diseños ofrecerle a los usuarios, cómo aparecer mejor en Google, qué falta para llamar a esto "en producción" de verdad, y cómo pensar los precios ahora que crear una página es instantáneo.

> También publicado como documento visual: https://claude.ai/code/artifact/633f803d-a342-4bd3-b850-20e5c06eb34d

---

## 01 · Plantillas nuevas para que el usuario elija

Hoy existen cuatro plantillas activas (Independiente, Productos, Servicios, Tarjeta) más varias viejas fuera de uso. Todas ya se completan solas con lo que el usuario cuenta en el chat — el trabajo que falta es de **diseño**, no de arquitectura.

### Punto de partida: lo que ya funciona

La base técnica ya está resuelta y conviene no tocarla: el chat le hace preguntas al dueño del negocio, un asistente de IA interpreta las respuestas, y esos datos rellenan campos fijos de una plantilla (título, fotos, productos con precio, redes sociales, colores). Elegir una plantilla nueva es, en el fondo, diseñar una nueva combinación visual de esos mismos campos — no un sistema nuevo.

| Plantilla actual | Pensada para | Estado del diseño |
|---|---|---|
| Independiente | Portfolio creativo (fotografía, diseño, oficios de imagen) | ⚠️ CSS propio, sin actualizar |
| Productos | Venta de productos físicos (comida, indumentaria, kioscos) | ⚠️ Carga una hoja de estilos de ~76 KB heredada |
| Servicios | Oficios y profesionales (gasista, contador, salud) | ⚠️ CSS propio, sin actualizar |
| Tarjeta | Contacto mínimo, tipo "tarjeta digital" | ⚠️ CSS propio, sin actualizar |

### Propuesta: cinco diseños, un solo sistema visual

En vez de mantener cuatro hojas de estilo independientes (la causa de casi todos los bugs visuales de este último tiempo), conviene que las plantillas nuevas compartan la misma base de colores, tipografía y componentes que ya se construyó para el index y el chat. Cada plantilla pasa a ser una *disposición distinta* de los mismos bloques, no un sitio aparte.

1. **Vidriera** (reemplaza a "Productos") — Grilla de productos con foto y precio en primer plano, filtro por categoría, botón fijo de WhatsApp. Pensada para comida, kioscos, indumentaria — el rubro más elegido en el chat hoy.
2. **Estudio** (reemplaza a "Independiente") — Imagen grande, texto mínimo, tipografía como protagonista. Para fotógrafos, diseñadores, artesanos: negocios que se venden por lo visual, no por la lista de precios.
3. **Consultorio** (reemplaza a "Servicios") — Página larga con secciones: quién soy, qué ofrezco, horarios, mapa, contacto. Para oficios y profesionales donde la confianza se construye leyendo, no mirando fotos.
4. **Tarjeta** (reemplaza a "Tarjeta") — Una sola pantalla: logo, nombre, botones grandes de llamar / WhatsApp / cómo llegar. Pensada para imprimir un QR y pegarlo en el mostrador.
5. **Menú — nueva** — Productos agrupados por categoría (entradas, principales, bebidas) con una barra fija de "Pedir por WhatsApp". El campo `categoria` del catálogo de productos ya existe en la base — hoy no se usa para nada visual, y acá es donde tiene sentido.

> **Dato a favor:** el chat ya sabe elegir la plantilla según el rubro que cuenta el usuario (heladería → Productos, gasista → Servicios, etc.). Agregar estas cinco opciones es ajustar esa regla, no construir un selector nuevo.

### Lo que falta para que "subir productos" quede completo

El catálogo de productos ya guarda nombre y precio, y el chat ya sube el logo del negocio como imagen. Lo que todavía no existe es **una foto por producto** — hoy los productos cargados por chat son solo texto. Para plantillas como Vidriera o Menú, donde el producto se vende con la foto, esto es lo primero a resolver: reutilizar el mismo mecanismo que ya sube el logo, pero uno por cada producto que el usuario carga.

---

## 02 · Qué hace falta para un buen SEO

El sitio parte de una buena base — las páginas se generan del lado del servidor, no dependen de JavaScript para mostrar contenido — pero le faltan casi todas las señales que Google y WhatsApp usan para entender y mostrar cada página.

### Diagnóstico

| Elemento | Estado actual | Por qué importa |
|---|---|---|
| Título por página | ⚠️ Genérico o ausente | Es lo primero que se ve en el resultado de búsqueda |
| Meta descripción | ⚠️ No existe | El texto debajo del título en Google; sin esto, Google inventa uno |
| Vista previa en WhatsApp (Open Graph) | ⚠️ No existe | Los negocios comparten su link por WhatsApp constantemente — hoy no se ve ninguna imagen ni texto al pegarlo |
| Datos estructurados (negocio local) | ⚠️ No existe | Permite que Google muestre horario, teléfono y dirección directo en el resultado |
| Mapa del sitio / robots.txt | ⚠️ A confirmar | Le dice a Google qué páginas existen y cuáles indexar |
| Contenido generado en el servidor | ✅ Resuelto | Google ve el texto real, no depende de ejecutar JavaScript |
| Peso de la plantilla "Productos" | ⚠️ ~76 KB de CSS heredado | Afecta velocidad de carga, otra señal de posicionamiento |

### Plan de acción, de mayor a menor impacto por esfuerzo

1. **Título y descripción por negocio** — Ya existen los datos (el título y la descripción que cada dueño cargó en el chat) — falta usarlos en el `<title>` y en una etiqueta `meta description` de cada página. Es el cambio de menor esfuerzo y mayor impacto de toda la lista.
2. **Vista previa para WhatsApp y redes (Open Graph)** — Agregar las etiquetas `og:title`, `og:description`, `og:image` con el logo del negocio. Altísimo impacto en este rubro puntual, porque el link se comparte por WhatsApp mucho más de lo que se busca en Google.
3. **Datos estructurados de negocio local** — Un bloque invisible en cada página que le dice a Google "esto es un negocio, se llama así, atiende en este horario, este es el teléfono". Habilita resultados enriquecidos (estrellas, horario, botón de llamar directo en el buscador).
4. **Mapa del sitio y robots.txt** — Un archivo que lista todos los negocios activos para que Google los encuentre más rápido, más un archivo de reglas básico. Se envía una sola vez a Google Search Console.
5. **Aligerar la plantilla "Vidriera"/Productos** — Reemplazar la hoja de estilos heredada de 76 KB por el sistema de diseño liviano que ya se construyó para el resto del sitio (ver sección 01).
6. **Páginas por rubro y por zona** — Página tipo "heladerías en Ezeiza" que lista negocios por categoría — ya existen esos datos (rubro, zona, ciudad), solo falta una vista que los agrupe. Es la jugada de mediano plazo: más páginas indexables, más términos de búsqueda cubiertos.

> **Fuera del sitio:** ninguna mejora dentro del sitio reemplaza tener el **Perfil de Negocio de Google** (antes Google My Business) cargado y con los mismos datos que la página — mismo nombre, dirección y teléfono en los dos lugares. Vale la pena armar un instructivo de un párrafo para que cada dueño lo haga al publicar su página.

---

## 03 · Qué falta para llamarlo "en producción" de verdad

El sitio ya está en vivo y usuarios reales pueden crear su página hoy. "Producción" acá no significa lanzar — significa cerrar los huecos que hoy dependen de que alguien note el problema a mano.

> **El riesgo más grande, primero:** todo lo construido en las últimas semanas (plantillas, catálogo, chat con IA, login) vive únicamente en los archivos subidos al hosting — **nada quedó guardado en el control de versiones** del proyecto. Si un archivo se sube mal o se pierde, no hay forma de recuperarlo. Este es el primer punto a resolver, antes que cualquier otro.

### Hoy mismo

1. **Guardar todo el trabajo en el control de versiones** — Es el seguro más barato que existe: si algo se rompe, se puede volver atrás.
2. **Limitar los intentos de inicio de sesión** — El chat ya tiene un límite de mensajes por minuto para cuidar la cuota de la IA; el login y el registro todavía no tienen ningún límite, quedan expuestos a que alguien pruebe contraseñas al voleo.
3. **Confirmar que el hosting hace copias de seguridad automáticas** — Se revisa una vez en el panel de Hostinger; si no está activo, activarlo.
4. **Página de Términos y Privacidad** — El registro ahora pide DNI y datos personales — corresponde informar cómo se usan y guardan, aunque sea de forma simple.

### Esta semana

5. **Correo real para recuperar contraseña** — Hoy el sistema de correo apunta a una herramienta de prueba que no envía nada de verdad. Sin esto, un usuario que olvida su contraseña queda sin forma de recuperar el acceso a su negocio.
6. **Documentar el esquema completo de la base de datos** — Varias tablas se crearon a mano hace tiempo y guardan sorpresas (columnas con nombres o límites distintos a lo esperado) — conviene tener un respaldo completo de su estructura ahora, antes de seguir agregando funciones.
7. **Probar todo el circuito de punta a punta en un celular real** — Registrarse, crear la página por chat, verla publicada, editarla, buscarla desde el buscador del sitio.

### Primer mes

8. **Aviso automático si el sitio se cae** — Un chequeo gratuito que avisa por correo si el sitio deja de responder, en vez de enterarse por un usuario que se queja.
9. **Plantillas nuevas y SEO** — Las mejoras de las secciones 01 y 02 de este documento.
10. **Pruebas automáticas del camino crítico** — Un chequeo que corra solo y avise si el registro o la creación de una página se rompen — el tipo de error que esta última etapa fue encontrando a mano, uno por uno.

---

## 04 · Qué precios tienen sentido ahora

La página de planes actual (Gratis / $200 / $600 / $800 por mes) se pensó para un modelo donde alguien construye cada sitio a mano. Ese costo ya no existe: el chat arma la página sola, al instante. Vale la pena repensar los precios desde ese punto de partida, no solo actualizar los números viejos.

### Por qué cambia el cálculo

Antes, cada plan reflejaba principalmente horas de trabajo de un desarrollador armando un sitio a medida. Hoy, publicar un negocio nuevo no le cuesta a la plataforma prácticamente nada extra: el hosting ya está pago sin importar cuántos negocios haya, y la IA que arma la página usa un nivel gratuito con margen de sobra para el volumen actual. El costo real por negocio nuevo es, hoy, cercano a cero.

Eso cambia qué vale la pena cobrar. En vez de precios que reflejan "cuánto trabajo me llevó hacer tu sitio", tiene más sentido un esquema donde **lo gratis es realmente gratis** (porque no cuesta nada servirlo, y cuantos más negocios haya, más útil es el sitio para quien busca) y **lo pago se cobra por valor** — visibilidad, personalización, no tener que pensar en eso.

### Una estructura posible

| Plan | Precio | Incluye |
|---|---|---|
| **Gratis** | $0, para siempre (no es prueba) | Una página con plantilla estándar · Hasta 5 productos · Aparece en el buscador del sitio · Con "creado en Negocios en tu Ciudad" |
| **Negocio** *(punto de partida sugerido)* | ~ valor de una salida al cine por mes | Productos ilimitados con foto · Colores personalizados · Sin la marca del sitio · Elegir entre todas las plantillas |
| **Negocio Plus** | ~ el doble del plan Negocio | Todo lo del plan Negocio · Estadísticas de visitas · Prioridad en los resultados de su rubro · Soporte directo |

> **Por qué no hay pesos concretos acá:** poner un número fijo en este documento hoy y que siga siendo razonable dentro de unos meses no es realista dado el contexto de precios en Argentina. Lo que sí se puede fijar es la *relación* entre planes (el ejemplo de arriba: gratis / una salida al cine / el doble de eso) y volver a mirar el valor en pesos cada pocos meses, en vez de dejarlo fijo como quedaron $200/$600/$800 en la página actual.

### Otras dos ideas, más chicas

- **Destacado puntual:** un pago único (no mensual) para aparecer primero en su rubro durante un período — pensado para quien no quiere un abono pero sí un empujón en una fecha puntual (Día de la Madre, fiestas).
- **Mantener el plan Gratis realmente gratis:** cuantos más negocios haya cargados, más útil es el sitio para quien busca — y eso, indirectamente, es lo que hace valioso pagar por destacarse dentro de esa misma lista. Cobrar por el plan gratuito hoy jugaría en contra del propio negocio.

---

*Documento de referencia interno — no reemplaza validar precios y prioridades con negocios reales antes de fijarlos.*
