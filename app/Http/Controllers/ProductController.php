<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\propiedades_plantillas;
use Illuminate\Http\Request;

class ProductController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

/*     public function listado () {
        $products = Product::all ();

        return view("adminProducts", compact ("products"));
    } */

/*     public function prueba (){
        dd("lalal");
        exit;     
    } */

    public function listado () {
        $query = Product::orderBy("id");

        // Fase 4, Paso 2: el filtro en vivo del index pide esta misma ruta
        // (sin término, para "limpiar" la búsqueda) por fetch() con
        // Accept: application/json -- mismo formato que Buscar().
        if (request()->wantsJson()) {
            return response()->json(
                $query->limit(24)->get()->map(fn (Product $p) => [
                    "nombre" => $p->nombre,
                    "ciudad" => $p->ciudad,
                    "url" => $p->urlPublica(),
                    "avatar" => $p->navLogoUrl(),
                ])
            );
        }

        $products = $query->get();

        return view("index", compact ("products"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


         /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store (Request $request){

        $reglas=[
          'name'=>"string|required|min:3",
          'description'=>"string|required|min:3",
          'price'=>"numeric|required|min:0",
          'img'=>"image|required"
        ];

        $mensajes=[
          'string'=> "Sólo puede ingresar texto",
          'required'=> "Este campo es obligatorio",
          'min'=> "Este campo requiere al menos 3 carateres",
          'unique'=> "Este nombre ya fue utilizado",
          'numeric'=>"Sólo puede ingresar números",
          'image'=>"Sólo puede subir archivos .jpg o .png",
        ];


        $this->validate($request,$reglas,$mensajes);


        $rutaImgProduct = $request -> file("img") -> store("public/IMGproduct");


        $nombreimgProduct=basename($rutaImgProduct);

        $NewProduct = new Product();
        $NewProduct->img = $nombreimgProduct;
        $NewProduct->name = $request->name;
        $NewProduct->description = $request->description;
        $NewProduct->price = $request->price;
        $NewProduct->zona = $request->zona;
        $NewProduct->localidad = $request->localidad;
        $NewProduct->prodserv = $request->prodserv;
        $NewProduct->articulo = $request->articulo;
        $NewProduct->provincia = $request->provincia;
        $NewProduct->vista = $request->vista;
        $NewProduct->palabraclave = $request->palabraclave;


        $NewProduct->save();

        return redirect("adminProducts");
      }

       //function modificarDatos(Request $request){
    public function editarProd(Request $request){



      $reglas=[
        'name'=>"string|required|min:3",
        'description'=>"string|required|min:3",
        'price'=>"numeric|required|min:0",
        'img'=>"image",
      ];

      $mensajes=[
        'string'=> "Sólo puede ingresar texto",
        'required'=> "Este campo es obligatorio",
        'min'=> "Este campo requiere al menos 3 carateres",
        'unique'=> "Este nombre ya fue utilizado",
        'numeric'=>"Sólo puede ingresar números",
        'image'=>"Sólo puede subir archivos .jpg o .png",
      ];

      $this->validate($request,$reglas,$mensajes);
      // dd($request);
      // exit;
      // $EditProduct = Product::find($id);

      // $nombreimgProduct = Product()->img;

      // $rutaImgProduct = $request -> file("img") -> store("public/IMGproduct");


      // $nombreimgProduct=basename($rutaImgProduct);

      $id=$request["id"];
      $EditProduct = Product::find($id);

      if($request->hasfile('img')){

        $request->validate([
        'img' => 'file',
        ]);
        $rutaImgProduct = $request -> file("img") -> store("public/IMGproduct");
        $nombreimgProduct=basename($rutaImgProduct);
        $EditProduct->img = $nombreimgProduct;
        }
      // $EditProduct->img = $nombreimgProduct;
      $EditProduct->name = $request["name"];
      $EditProduct->description = $request["description"];
      $EditProduct->price = $request["price"];


      $EditProduct->save();

      return redirect("adminProducts");
    }


  public function verProduct($id){
      $product=Product::find($id);
      $vac=compact('product');
      return view('editarProducts',$vac);
  }

  public function ProductDestroy(Request $req)
  {
      $id=$req["id"];
      $product = Product::find($id);
      $product->delete();
      return redirect("adminProducts");
  }



  public function Buscar($search = null){
    if (!empty ($search)){
   /*      dd($search);
  exit; */
        $query = Product::where("nombre", "LIKE", "%".$search."%")
                              ->orWhere("url", "LIKE", "%".$search."%" )
                              ->orWhere("profesion", "LIKE", "%".$search."%" )
                              ->orWhere("horario", "LIKE", "%".$search."%" )
                              ->orWhere("direccion", "LIKE", "%".$search."%" )
                              ->orWhere("ciudad", "LIKE", "%".$search."%" )
                              ->orWhere("provincia", "LIKE", "%".$search."%" )
                              ->orWhere("producto_servicio", "LIKE", "%".$search."%" )
                              ->orWhere("rubro", "LIKE", "%".$search."%" )
                              ->orWhere("zona", "LIKE", "%".$search."%" )
                              ->orWhere("palabras_clave", "LIKE", "%".$search."%" )
                              // Fase 2: negocios vinculados a un producto del catálogo
                              // que matchea la búsqueda (nombre o categoría).
                              ->orWhereHas("productos", function ($q) use ($search) {
                                  $q->where("nombre", "LIKE", "%".$search."%")
                                    ->orWhere("categoria", "LIKE", "%".$search."%");
                              })
                              -> orderBy("id");
     }else{
      $query = Product::orderBy("id");
    }

    // Fase 4, Paso 2: filtro en vivo del index -- el buscador pide esta
    // misma URL por fetch() con Accept: application/json mientras el
    // usuario escribe, para no duplicar las condiciones de búsqueda de
    // arriba en dos lugares distintos. Sin paginar (una tanda corta
    // alcanza para ir tipeando); la página completa sigue paginada.
    if (request()->wantsJson()) {
        return response()->json(
            $query->limit(24)->get()->map(fn (Product $p) => [
                "nombre" => $p->nombre,
                "ciudad" => $p->ciudad,
                "url" => $p->urlPublica(),
                "avatar" => $p->navLogoUrl(),
            ])
        );
    }

    $products = $query->paginate(20);

    return view("index", [
      "products" => $products
    ]);

    /* return view("/inicio",compact ("products")); return redirect*/
  }



  /* Funciones creadas por mi a partir del 09/06/2021 */

// Fase 4: la URL ahora lleva la ciudad (/{ciudad}/{slug}), el negocio se
// busca por los dos segmentos juntos -- el slug ya no es único global (ver
// Product::normalizarCiudadSlug / migración change_slug_unique_to_composite).
function InfoPlantilla($ciudad, $slug){

  $products = Product::where('ciudad_slug', $ciudad)->where('slug', $slug)->first();

  if (! $products) {
      abort(404);
  }

  /* dd($products);
  exit; */
  /* $products = Product::all(); */

  $plantilla_id = $products->plantilla_id;
  
  /* dd($products->plantilla_id);
  exit; */

/*   foreach($products as $product){
   if ($product[0]->slug = $slug){
    $negocio = $product[0];
  } */

  /*  dd($id);
  exit;  */

/*   if ($plantilla_id = "remeras"){
    return view("03-productos",$products);
  }
 */
$id_products=$products->id;
 /*  dd($id_products);
  exit; */

$propiedades = propiedades_plantillas::where('negocio_id',$id_products)->first();

/* dd($propiedades);
  exit;  */

  switch ($plantilla_id) {
    case 1:
      return view("01-misia-paula");
        break;
    case 2;
      return view("02-independiente", compact ("products", "propiedades"));
        break;
    case 3:
      return view("03-productos",compact ("products", "propiedades"));
        break;
    case 4:
      return view("04-servicios",compact ("products", "propiedades"));
        break;
    case 5:
      return view("05-tarjeta",compact ("products", "propiedades"));
      break;
    case 6:
      return view("06-sugary");
      break;
    case 7:
      return view("07-mncontabilidad");
      break;
    case 8:
      return view("08-pizza-nico");
      break;
    case 9:
      return redirect('https://negociosentuciudad.com/cookie-boss-tienda');
      break;
    case 10:
      return view("10-vidriera",compact ("products", "propiedades"));
      break;
    case 11:
      return view("11-tienda",compact ("products", "propiedades"));
      break;
}

 
  
/*   return view("planes.html", compact ("products")); */
}

  /**
   * Fase 2: autocompletado del buscador contra el catálogo de productos.
   */
  public function autocompletarProductos(Request $request)
  {
    $q = trim((string) $request->query('q', ''));

    if (mb_strlen($q) < 2) {
        return response()->json([]);
    }

    $productos = \App\Models\ProductoCatalogo::where('nombre', 'LIKE', '%'.$q.'%')
        ->orderBy('nombre')
        ->limit(10)
        ->pluck('nombre');

    return response()->json($productos);
  }

}


