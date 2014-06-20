<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'main';
$route['404_override'] = '';

/**
 * Редиректы проекта rynda.org:
 */
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['forgot'] = 'auth/forgotPassword';
$route['auth/reset(.*)'] = 'auth/resetPassword$1'; 
$route['user/(:num)'] = 'user/personal/$1';
$route['user'] = 'user/all';
$route['users'] = 'user/all';
$route['info/u/(:num)'] = 'user/personal/$1';
$route['vse'] = 'messages';
$route['pomogite'] = 'messages/type/request';
$route['pomogu'] = 'messages/type/offer';
$route['info'] = 'messages/type/info';
$route['messages/detail'] = 'messages';
$route['pomogite/s'] = 'messages/type/request';
$route['pomogu/s'] = 'messages/type/offer';
$route['info/s'] = 'messages/type/info';
$route['pomogite/dobavit'] = 'messages/addRequest';
$route['pomogu/dobavit'] = 'messages/addOffer';
$route['pomogite/s/(:num)'] = 'messages/detail/$1';
$route['pomogu/s/(:num)'] = 'messages/detail/$1';
$route['info/s/(:num)'] = 'messages/detail/$1';
$route['info/m/(:num)'] = 'messages/detail/$1';
$route['pomogite/c/(:any)'] = 'messages/category/$1';
$route['pomogu/c/(:any)'] = 'messages/category/$1';
$route['info/c/(:any)'] = 'messages/category/$1';
$route['pomogite/r/(:any)'] = 'messages/region/$1';
$route['pomogu/r/(:any)'] = 'messages/region/$1';
$route['info/r/(:any)'] = 'messages/region/$1';
$route['pomogite/pomogli'] = 'messages/helped';
$route['org'] = 'organizations';
$route['org/(:num)'] = 'organizations/index/$1';
$route['org/dobavit'] = 'organizations/add';
$route['org/add'] = 'organizations/add';
$route['org/c/(:any)'] = 'organizations/category/$1';
$route['org/r/(:any)'] = 'organizations/region/$1';
$route['org/d/(:any)'] = 'organizations/detail/$1';
$route['org/t/(:any)'] = 'organizations/type/$1';
$route['info/o/(:any)'] = 'organizations/detail/$1';
$route['info/(:any)'] = 'main/info/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */