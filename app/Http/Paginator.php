<?php

namespace App\Http;


class Paginator
{

    protected $per_page;
    protected $page;
    protected $items;
    protected $current_page;
    protected $last_page;



    public function __construct($per_page, $page, $items, $current_page, $last_page)
    {
        $this->per_page = $per_page;
        $this->page = $page;
        $this->items = $items;
        $this->current_page = $current_page;
        $this->last_page = $last_page;
    }

    public static  function format($per_page, $page, $items, $current_page, $last_page, $total)
    {

        $data = [];
        $data['per_page'] = intval($per_page);
        $data['page'] = intval($page);
        $data['data'] = $items;
        $data['current_page'] = $current_page;
        $data['last_page'] = $last_page;

        $data['total'] = $total;

        return $data;
    }
}
