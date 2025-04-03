<?php

namespace App\Http\Controllers;
use App\Models\DetalleVenta;
use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-venta', ['only' => ['show']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
    }

    public function index()
    {
        $ventas = Venta::with(['comprobante', 'cliente.persona', 'user', 'productos.marca'])
            ->where('estado', 1)
            ->latest()
            ->get();

        return view('venta.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::select([
                'productos.id',
                'productos.codigo',
                'productos.nombre',
                'productos.stock',
                DB::raw('(SELECT precio_venta FROM compra_producto WHERE producto_id = productos.id ORDER BY created_at DESC LIMIT 1) as precio_venta')
            ])
            ->with('marca')
            ->where('productos.estado', 1)
            ->where('productos.stock', '>', 0)
            ->get();

        $clientes = Cliente::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $comprobantes = Comprobante::all();

        return view('venta.create', compact('productos', 'clientes', 'comprobantes'));
    }

    public function store(Request $request)
{
    DB::beginTransaction();
    
    try {
        // Validación de datos
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'comprobante_id' => 'required|exists:comprobantes,id',
            'numero_comprobante' => 'required|string|max:255',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_venta' => 'required|numeric|min:0',
            'detalles.*.descuento' => 'required|numeric|min:0',
        ]);

        // Crear la venta
        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'user_id' => auth()->id(), // Mejor usar auth()->id() directamente
            'comprobante_id' => $request->comprobante_id,
            'numero_comprobante' => $request->numero_comprobante,
            'impuesto' => $request->impuesto,
            'total' => $request->total,
            'fecha_hora' => now(), // Mejor usar now() directamente
        ]);
        
        // Procesar cada detalle
        foreach ($request->detalles as $detalle) {
            // Verificar stock
            $producto = Producto::findOrFail($detalle['producto_id']);
            if ($producto->stock < $detalle['cantidad']) {
                throw new \Exception("No hay suficiente stock para el producto: {$producto->nombre}");
            }

            // Crear detalle
            $venta->productos()->attach($detalle['producto_id'], [
                'cantidad' => $detalle['cantidad'],
                'precio_venta' => $detalle['precio_venta'],
                'descuento' => $detalle['descuento']
            ]);
            
            // Actualizar stock
            $producto->decrement('stock', $detalle['cantidad']);
        }
        
        DB::commit();
        
        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada correctamente');
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error al registrar venta: ' . $e->getMessage());
        return back()
            ->withInput()
            ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
    }
}

    public function show(Venta $venta)
    {
        $venta->load(['productos.marca', 'comprobante', 'cliente.persona', 'user']);
        return view('venta.show', compact('venta'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::with('productos')->findOrFail($id);
            
            // Revertir stock
            foreach ($venta->productos as $producto) {
                $producto->increment('stock', $producto->pivot->cantidad);
            }

            // Anular venta
            $venta->update(['estado' => 0]);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada exitosamente');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al anular venta: ' . $e->getMessage());
            
            return back()->with('error', 'Ocurrió un error al anular la venta');
        }
    }

    protected function validarStock(Request $request): bool
    {
        $arrayProducto_id = $request->get('arrayidproducto');
        $arrayCantidad = $request->get('arraycantidad');

        foreach ($arrayProducto_id as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = intval($arrayCantidad[$index]);

            if ($producto->stock < $cantidad) {
                return false;
            }
        }

        return true;
    }

    protected function procesarProductos(Venta $venta, Request $request): void
    {
        $arrayProducto_id = $request->get('arrayidproducto');
        $arrayCantidad = $request->get('arraycantidad');
        $arrayPrecioVenta = $request->get('arrayprecioventa');
        $arrayDescuento = $request->get('arraydescuento');

        foreach ($arrayProducto_id as $index => $productoId) {
            $venta->productos()->attach($productoId, [
                'cantidad' => $arrayCantidad[$index],
                'precio_venta' => $arrayPrecioVenta[$index],
                'descuento' => $arrayDescuento[$index]
            ]);

            // Actualizar stock
            Producto::where('id', $productoId)
                ->decrement('stock', $arrayCantidad[$index]);
        }
    }
}