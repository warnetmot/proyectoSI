<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'comprobante_id',
        'numero_comprobante',
        'impuesto',
        'total',
        'fecha_hora',
        'user_id',
        'estado'
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class)
                    ->withPivot('cantidad', 'precio_venta', 'descuento');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}