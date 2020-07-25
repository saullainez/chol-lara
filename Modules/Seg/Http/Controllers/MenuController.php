<?php

namespace Modules\Seg\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Seg\Entities\Menu;
use Modules\Seg\Entities\SubMenu;

class MenuController extends Controller
{
   public function getMenuPermission($module, $role){
      $menus = Menu::where('mod_prefix', $module)->get();
      foreach($menus as $menu){
         $sub_menu = SubMenu::select('sub_menu.description as sub_desc', 'sub_menu.icon as sub_icon', 'sub_menu.uri as uri')
         ->join('permission_menu as perm', 'perm.sub_menu_id', 'sub_menu.id')
         ->where('sub_menu.id_menu', $menu->id)
         ->where('perm.estatus', 'A')
         ->where('perm.rol_prefix', $role)->get();
         $menu->sub_menu = $sub_menu;
         $menu->count = $sub_menu->count();
      }  
      
      return response()->json([
         'menu' => $menus
      ]);

   }
}
