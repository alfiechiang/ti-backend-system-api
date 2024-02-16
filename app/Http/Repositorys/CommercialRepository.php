<?php

namespace App\Http\Repositorys;

use App\Models\Commercial;

class CommercialRepository
{

    public function list()
    {
        $data =  Commercial::all();
        return $data;
    }
}
