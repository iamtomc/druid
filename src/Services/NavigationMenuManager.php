<?php

namespace Webid\Druid\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webid\Druid\Dto\Menu;
use Webid\Druid\Repositories\MenuRepository;

class NavigationMenuManager
{
    public function __construct(private readonly MenuRepository $menuRepository) {}

    /**
     * @throws ModelNotFoundException
     */
    public function getById(int $menuId): Menu
    {
        $menu = $this->menuRepository->findOrFailById($menuId);

        return Menu::fromMenu($menu);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getBySlugAndLang(string $menuSlug, string $lang): Menu
    {
        $menu = $this->menuRepository->findOrFailBySlugAndLang($menuSlug, $lang);

        return Menu::fromMenu($menu);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getBySlug(string $menuSlug): Menu
    {
        $menu = $this->menuRepository->findOrFailBySlug($menuSlug);

        return Menu::fromMenu($menu);
    }
}
