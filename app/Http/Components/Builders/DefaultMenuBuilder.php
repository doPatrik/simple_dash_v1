<?php


namespace App\Http\Components\Builders;


use App\Models\DefaultMenu;

class DefaultMenuBuilder implements BuilderInterface
{
    protected function getDefaultSidemenuItems()
    {
        return $defaultMenuItems = DefaultMenu::all();

    }

    public function getElements()
    {
        return $this->getDefaultSidemenuItems();
    }
}
