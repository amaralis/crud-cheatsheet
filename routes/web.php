<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandController;

Route::get('/', [BandController::class, 'index'])->name('home');

// Registo de routes para registo, login e logout tratadas por Fortify em app/Providers/FortifyServiceProvider.php - registadas no método boot() quando precisam de uma view definida por nós.
// Docs: https://laravel.com/docs/11.x/fortify#authentication e https://laravel.com/docs/11.x/fortify#registration (nomenclatura mantida)

// Nota: fortify é headless. Retorna a view, mas como apresentamos essa view fica ao nosso critério. Possível usar com app como API, embora Sanctum ofereça um serviço mais apropriado e completo
// (para uma API de consumo interno). Mantido Fortify para efeitos de avaliação. Apenas foi mantida a feature para automatizar o registo de utilizadores.

// Nota 2: as routes geridas via Fortify têm nome: php artisan route:list
// A documentação aqui é anormalmente fraca. Só se consegue ver o nome das rotas, o controlador e o verbo http esperado. Para mais info, ver vendor/laravel/fortify/[...], em particular routes e [...]http/controllers

// Nota 3: para personalizar colunas da tabela users é necessário adicionar a criação do campo em app/Actions/Fortify/CreateNewUser.php. Neste caso estamos a forçar a criação de um user standard com o código 1. Admin terá
// código 0 e será inserido directamente na DB. Se quiséssemos inserir inputs, seria imperativo validá-los primeiro, no array passado a Validator::make(). Foi ainda necessário adicionar o campo 'user_type' ao array de fillables
// em app/Models/User.php