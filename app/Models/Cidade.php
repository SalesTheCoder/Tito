<?php

namespace App\Models;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cidade extends Model
{
    use HasFactory;
    protected $table = 'cidade';
    protected $fillable = ['id', 'nome'];
    const UPDATED_AT = null;
    const CREATED_AT = null;
    public function pessoa(){
        return $this->belongsTo(Pessoa::class, 'id_cidade', 'id');
    }
}
