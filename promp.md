<Request>
<Context>
    <Models>
        <Category>
            <Table>
                Schema::create('category', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('description');
                    $table->timestamps();
                });
            </Table>
            <Php>
                class Category extends Model
                {
                    protected $fillable = [
                        'name',
                        'description'
                    ];
                
                    public function products(): HasMany
                    {
                        return $this->hasMany(Product::class);
                    }
                }
            </Php>
            <Controller>
                <?php
                
                namespace App\Http\Controllers;
                
                use App\Models\Category;
                use Illuminate\Http\Request;
                
                class CategoryController extends Controller
                {
                    /**
                     * Display a listing of categories.
                     */
                    public function index()
                    {
                        $categories = Category::orderBy('created_at', 'desc')->paginate(15);
                        return view('categories.index', compact('categories'));
                    }
                
                    /**
                     * Show the form for creating a new category.
                     */
                    public function create()
                    {
                        return view('categories.create');
                    }
                
                    /**
                     * Store a newly created category in storage.
                     */
                    public function store(Request $request)
                    {
                        $validated = $request->validate([
                            'name'        => 'required|string|max:255|unique:category,name',
                            'description' => 'nullable|string',
                        ]);
                
                        Category::create($validated);
                
                        return redirect()
                            ->route('categories.index')
                            ->with('success', 'Category created successfully.');
                    }
                
                    /**
                     * Display the specified category.
                     */
                    public function show(Category $category)
                    {
                        return view('categories.show', compact('category'));
                    }
                
                    /**
                     * Show the form for editing the specified category.
                     */
                    public function edit(Category $category)
                    {
                        return view('categories.edit', compact('category'));
                    }
                
                    /**
                     * Update the specified category in storage.
                     */
                    public function update(Request $request, Category $category)
                    {
                        $validated = $request->validate([
                            'name'        => 'required|string|max:255|unique:category,name,' . $category->id,
                            'description' => 'nullable|string',
                        ]);
                
                        $category->update($validated);
                
                        return redirect()
                            ->route('categories.index')
                            ->with('success', 'Category updated successfully.');
                    }
                
                    /**
                     * Remove the specified category from storage.
                     */
                    public function destroy(Category $category)
                    {
                        // Optionally, you could check for related products before deleting.
                        $category->delete();
                
                        return redirect()
                            ->route('categories.index')
                            ->with('success', 'Category deleted successfully.');
                    }
                }
            </Controller>
        </Category>
        <Product>
            <Table>
            Schema::create('product', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->float('price');
                $table->foreign('category_id')->references('id')->on('category');
                $table->string('description');
                $table->timestamps();
            });
            </Table>
            <Php>
                class Product extends Model
                {
                
                    protected $fillable = [
                        'name',
                        'description',
                        'price',
                        'category_id',
                    ];
                
                    public function category(): HasOne
                    {
                        return $this->hasOne(Category::class);
                    }
                }
            </Php>
            <Controller>
                <?php
                
                namespace App\Http\Controllers;
                
                use App\Models\Product;
                use App\Models\Category;
                use Illuminate\Http\Request;
                
                class ProductController extends Controller
                {
                    /**
                     * Display a listing of products.
                     */
                    public function index()
                    {
                        $products = Product::with('category')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
                
                        return view('products.index', compact('products'));
                    }
                
                    /**
                     * Show the form for creating a new product.
                     */
                    public function create()
                    {
                        $categories = Category::pluck('name', 'id');
                        return view('products.create', compact('categories'));
                    }
                
                    /**
                     * Store a newly created product in storage.
                     */
                    public function store(Request $request)
                    {
                        $validated = $request->validate([
                            'name'        => 'required|string|max:255',
                            'description' => 'nullable|string',
                            'price'       => 'required|numeric|min:0',
                            'category_id' => 'required|exists:category,id',
                        ]);
                
                        Product::create($validated);
                
                        return redirect()
                            ->route('products.index')
                            ->with('success', 'Product created successfully.');
                    }
                
                    /**
                     * Display the specified product.
                     */
                    public function show(Product $product)
                    {
                        $product->load('category');
                        return view('products.show', compact('product'));
                    }
                
                    /**
                     * Show the form for editing the specified product.
                     */
                    public function edit(Product $product)
                    {
                        $categories = Category::pluck('name', 'id');
                        return view('products.edit', compact('product', 'categories'));
                    }
                
                    /**
                     * Update the specified product in storage.
                     */
                    public function update(Request $request, Product $product)
                    {
                        $validated = $request->validate([
                            'name'        => 'required|string|max:255',
                            'description' => 'nullable|string',
                            'price'       => 'required|numeric|min:0',
                            'category_id' => 'required|exists:category,id',
                        ]);
                
                        $product->update($validated);
                
                        return redirect()
                            ->route('products.index')
                            ->with('success', 'Product updated successfully.');
                    }
                
                    /**
                     * Remove the specified product from storage.
                     */
                    public function destroy(Product $product)
                    {
                        $product->delete();
                
                        return redirect()
                            ->route('products.index')
                            ->with('success', 'Product deleted successfully.');
                    }
                }
            </Controller>
        </Product>
    </Models>
</Context>
<Instruction>
Make create, index and edit views for the provided models taking into account that this is my layout
it should also have a create button in the header

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
IMPORTANT TAKE INTO ACCUNT THE FOLLOWING EN.JSON FILE, THE DEFAULT LOCALE IS SPANISH
<EN.JSON>

{
    "Categorías": "Categories",
    "Crear nueva categoría": "Create new category",
    "Correo Electrónico" : "Email",
    "Cerrar Sesión": "Log Out",
    "Contraseña" : "Password",
    "Recuerdame": "Remember Me",
    "Olvidaste tu contraseña?": "Forgot your password?",
    "Inicia Sesión": "Log in",
    "Confirmar Contraseña": "Confirm Password",
    "Registrarse": "Register",
    "Ya estas registrado?": "Already registered?",
    "ID": "ID",
    "Perfil":  "Profile",
    "Nombre": "Name",
    "Descripción": "Description",
    "Fecha de creación": "Creation date",
    "Acciones": "Actions",
    "Editar": "Edit",
    "¿Seguro que deseas eliminar?": "Are you sure you want to delete?",
    "Eliminar": "Delete",
    "No hay categorías registradas.": "No categories found.",

    "Crear categoría": "Create category",
    "Ingresa el nombre": "Enter the name",
    "Ingresa la descripción (opcional)": "Enter the description (optional)",
    "Guardar": "Save",
    "Cancelar": "Cancel",

    "Editar categoría": "Edit category",
    "Actualiza el nombre": "Update the name",
    "Actualiza la descripción (opcional)": "Update the description (optional)",

    "Productos": "Products",
    "Crear nuevo producto": "Create new product",
    "Precio": "Price",
    "Categoría": "Category",
    "No hay productos registrados.": "No products found.",

    "Crear producto": "Create product",
    "0.00": "0.00",
    "Selecciona una categoría": "Select a category",

    "Editar producto": "Edit product",
    "Actualizar": "Update",
    "Estas registrado!": "You're logged in"
}
</EN.JSON>
</Instruction>
</Request>
