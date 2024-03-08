<?php

namespace App\Models;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pessoa extends Model
{
    use HasFactory;
    protected $table = 'pessoa';
    protected $fillable = ['id', 'id_cidade', 'nome', 'idade', 'dataNascimento'];
    const UPDATED_AT = null;
    const CREATED_AT = null;
    public function cidade(){
        return $this->hasOne(Cidade::class, 'id');
    }
}
