<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Productos') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md">
                {{ __('Crear nuevo producto') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($products->count())
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">{{ __('ID') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Nombre') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Precio') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Categoría') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Fecha de creación') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="px-4 py-2">{{ $product->id }}</td>
                                        <td class="px-4 py-2">{{ $product->name }}</td>
                                        <td class="px-4 py-2">{{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-2">{{ $product->category->name }}</td>
                                        <td class="px-4 py-2">{{ $product->created_at->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="text-blue-600 hover:underline">{{ __('Editar') }}</a>
                                            <form action="{{ route('products.destroy', $product) }}"
                                                  method="POST"
                                                  class="inline-block ml-2"
                                                  onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    {{ __('Eliminar') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('No hay productos registrados.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
