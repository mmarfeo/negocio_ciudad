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
        /* $products = Product::all (); */

/*         $products = Product::query()
        
        
        ->paginate(20); */
        $products = Product::all();
        /* $products = Product::all()->random(); */
        /* $products = Product::all()->random(9); */
        /* $products = DB::table("negocios")->Random(); */
          /* $products = Product::query()->inRandomOrder('id')->first(); */

/*         dd($products[0]->name);

foreach($products as $product) {
    dump($products->name);
} */

      /*  dd($products);
       exit; */

      
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
        $products = Product::where("nombre", "LIKE", "%".$search."%")
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
                              -> orderBy("id")
                              ->paginate(20);
/*            dd($products);
  exit; */
    }else{
      $products = Product::orderBy("id")->paginate(20);
    }

    return view("index", [
      "products" => $products
    ]);

    /* return view("/inicio",compact ("products")); */
  }



  /* Funciones creadas por mi a partir del 09/06/2021 */

function InfoPlantilla(Request $slug){

  $id=$slug["slug"];
  
  /* $products = Product::find($id); */

  $products = Product::where('slug',$id)->first();

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

/* dd($plantilla_id);
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
}

 
  
/*   return view("planes.html", compact ("products")); */
}

}


