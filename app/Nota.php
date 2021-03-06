<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model {

    protected $fillable = ['contenido'];

    public function comentable() {
        return $this->morphTo();
    }

}
