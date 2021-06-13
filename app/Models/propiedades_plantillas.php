<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class propiedades_plantillas extends Model
{
    public $table = "plantillas_propiedades";
    public $primaryKey = "id";

    protected $fillable = [

        'plantilla_id',	'negocio_id',	'nav_logo',	'favicon_logo',	'header_img_1',	'header_img_2',	'header_img_3',	'header_titulo_1',	'header_titulo_2',	'header_titulo_3',	'header_subtitulo_1',	'header_subtitulo_2',	'header_subtitulo_3',	'body_titulo',	'body_titulo_2',	'body_titulo_3',	'body_titulo_4',	'body_parrafo_1',	'body_parrafo_2',	'body_parrafo_3',	'body_parrafo_4',	'body_subtitulo',	'body_tarjeta_img_1',	'body_tarjeta_img_2',	'body_tarjeta_img_3',	'body_tarjeta_img_4',	'body_tarjeta_img_5',	'body_tarjeta_img_6',	'body_tarjeta_img_7',	'body_tarjeta_img_8',	'body_tarjeta_img_9',	'body_tarjeta_img_10',	'body_tarjeta_titulo_1',	'body_tarjeta_titulo_2',	'body_tarjeta_titulo_3',	'body_tarjeta_titulo_4',	'body_tarjeta_titulo_5',	'body_tarjeta_titulo_6',	'body_tarjeta_titulo_7',	'body_tarjeta_titulo_8',	'body_tarjeta_parrafo_1',	'body_tarjeta_parrafo_2',	'body_tarjeta_parrafo_3',	'body_tarjeta_parrafo_4',	'body_tarjeta_parrafo_5',	'body_tarjeta_parrafo_6',	'body_tarjeta_parrafo_7',	'body_tarjeta_parrafo_8',	'body_tarjeta_precio_1',	'body_tarjeta_precio_2',	'body_tarjeta_precio_3',	'body_tarjeta_precio_4',	'body_tarjeta_precio_5',	'body_tarjeta_precio_6',	'body_tarjeta_precio_7',	'body_tarjeta_precio_8',	'body_titulo_nuestros_trabajos',	'body_tipos_medios_pago',	'body_maps',	'footer_redes_facebook',	'footer_redes_instagram',	'footer_redes_youtube',	'footer_redes_twitter',	'footer_redes_linkedin'
       
    ];
}
