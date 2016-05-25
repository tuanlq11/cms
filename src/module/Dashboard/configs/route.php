<?php 
return [
	'index' => ['as'=> 'dashboard.index', 'uses' => '\App\Http\Modules\Dashboard\DashboardActions@index', 'middleware' => null, 'url' => "/dashboard", 'method' => 'get'],
];