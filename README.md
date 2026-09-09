# negociosentuciudad.com

Plataforma multi-negocio: cada negocio elige una **plantilla** (`plantilla_id`) y
su página se sirve en `/{ciudad}/{slug}`. Incluye alta guiada por chat IA
(Gemini), directorio + buscador cruzado, personalización visual y una plantilla
de e-commerce ("Tienda", id 11) con Mercado Pago Connect.

- **Framework:** Laravel 8 · PHP 7.3/8.0 · MySQL
- **Hosting:** Hostinger (compartido)
- **Repo:** github.com/mmarfeo/negocio_ciudad
- **Roadmap / historial:** `../../documentos/` (fases 4–8, checklist general,
  plan maestro v2)

---

## Estructura de deploy (IMPORTANTE)

En Hostinger el dominio se sirve desde **dos carpetas hermanas** dentro de
`negociosentuciudad.com/`:

```
negociosentuciudad.com/
├── public_html/          <- lo que sirve Apache (webroot del dominio)
│   ├── index.php         <- shim: require '../negocio_ciudad/vendor/autoload.php'
│   │                        y '../negocio_ciudad/bootstrap/app.php'
│   ├── .htaccess
│   ├── css/ js/ fonts/ img/ ...   <- assets, se suben A MANO por FTP
│   └── storage/          <- destino de PUBLIC_STORAGE_PATH (ver abajo)
└── negocio_ciudad/       <- la app Laravel (este repo)
    ├── app/ config/ routes/ resources/ database/ ...
    ├── public/           <- NO es el webroot en producción (sí en local)
    └── vendor/
```

- **En producción** el webroot es `public_html/`, no `negocio_ciudad/public/`.
  El `public_html/index.php` es un `index.php` de Laravel modificado que apunta
  "de costado" a `../negocio_ciudad`.
- **En local** el webroot sí es `negocio_ciudad/public/` (Laravel estándar).
- La carpeta `negociosentuciudad.com - copia/` (un nivel más arriba) es un
  **snapshot viejo con `.env` de credenciales reales** — está para archivar, no
  se usa. Ver Fase 8, Bloque A.

### Deploy de código

`negocio_ciudad/` es el repo. Subir los cambios de `app/`, `config/`, `routes/`,
`resources/`, `database/` a `negocio_ciudad/` en el hosting. Correr migraciones
(o los `.sql` de `database/sql/` en phpMyAdmin — las tablas `negocios` y
`plantillas_propiedades` se crearon a mano, ver Fase 8 Bloque C).

### Deploy de assets (CSS/JS/imágenes de plantillas)

Los archivos de `negocio_ciudad/public/css|js|fonts|img|webfonts/` **se copian a
mano a `public_html/`** (mismas subcarpetas). El build de Mix
(`public/css/app.css`, `public/js/app.js`) también. Es la causa histórica de
"subí el cambio y no se ve": se editó en `negocio_ciudad/public/` pero no se
copió a `public_html/`.

### Archivos subidos por la app (logos, fotos de producto)

- Disco `public` de Laravel → configurado por **`PUBLIC_STORAGE_PATH`** en el
  `.env` de producción: ruta absoluta a `public_html/storage`
  (ej. `/home/u374453216/domains/negociosentuciudad.com/public_html/storage`).
  Sin esa variable cae a `public_path('storage')`, que en producción el dominio
  no sirve → 404. Ver `config/filesystems.php`.
- **Fase 8 Bloque B:** migrar este disco a S3-compatible (Cloudflare R2 /
  Backblaze B2) para eliminar de raíz este problema.
- Tras cambiar el `.env` en producción: `php artisan config:clear`.

---

## Setup local

```bash
composer install
cp .env.example .env
php artisan key:generate
# .env local: DB_DATABASE=negocio_ciudad_dev  (crearla)  ·  APP_URL=http://localhost:8000
php artisan migrate
php artisan storage:link
npm install && npm run dev

# servir (php artisan serve no sirve estáticos bien en Windows/Git Bash):
php -S localhost:8000 -t public server.php
```

Variables de entorno relevantes (ver `.env.example`): `GEMINI_API_KEY` (chat de
alta), `MERCADOPAGO_CLIENT_ID/SECRET/WEBHOOK_SECRET` + `MERCADOPAGO_COMISION_PORCENTAJE`
(marketplace plantilla Tienda), `PUBLIC_STORAGE_PATH` (solo producción).

---

## Mapa rápido del código

| Ruta | Qué es |
|---|---|
| `routes/web.php` | Todas las rutas. `/{ciudad}/{slug}` (catch-all) → `ProductController::InfoPlantilla` |
| `config/plantillas.php` | **Fuente única de plantillas** (id → label, vista, descripción, pasos). La consumen `ProductController`, `NegocioAdminController`, `ChatController` vía `App\Support\Plantillas` |
| `app/Http/Controllers/ProductController.php` | Index + directorio + buscador + render de página pública por plantilla |
| `app/Http/Controllers/NegocioAdminController.php` | Alta/edición manual de negocio + propiedades de plantilla |
| `app/Http/Controllers/ChatController.php` | Alta guiada por chat (Gemini) + vista previa en vivo |
| `app/Http/Controllers/TiendaController.php` | Catálogo/carrito/checkout de la plantilla Tienda (id 11) |
| `app/Http/Controllers/MercadoPago*Controller.php` | OAuth Connect por negocio + webhook de pago |
| `app/Services/` | `GeminiClient`, `NegocioWriter` (persistencia), `MercadoPagoService`, `GaleriaFotos` |
| `app/Models/Product.php` | El "negocio" (tabla `negocios`, nombre histórico) |
| `app/Models/propiedades_plantillas.php` | Contenido editable por negocio (tabla `plantillas_propiedades`) |
| `resources/views/0N-*.blade.php`, `1N-*.blade.php` | Una vista por plantilla |
| `database/sql/` | Equivalentes manuales de migraciones para phpMyAdmin de Hostinger |
