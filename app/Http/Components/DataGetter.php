<?php


namespace App\Http\Components;


use App\Http\Components\Builders\DefaultMenuBuilder;

class DataGetter
{
    public function getDefaultSidemenuItems()
    {
        return app(DefaultMenuBuilder::class)->getElements();
    }
}
