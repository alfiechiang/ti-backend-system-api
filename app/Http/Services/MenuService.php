<?php

namespace App\Http\Services;

use App\Http\Repositorys\MenuRepository;
use App\Http\Repositorys\UserRepository;

class MenuService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new MenuRepository();
    }

    public function list()
    {
        $menus = $this->repository->list();
        $list = [];
        foreach ($menus as $menu) {
            $m = [];
            $m['name'] = $menu->name;
            $m['url'] = $menu->url;
            $list[] = $m;
        }

        return $list;
    }

  
}
