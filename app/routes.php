<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Controllers\ApiController;
use App\Controllers\AccessController;

$site = new SiteController();
$auth = new AuthController();
$admin = new AdminController();
$api = new ApiController();
$access = new AccessController();

$router->get('/', fn () => $site->home());
$router->get('/pages/{slug}', fn ($p) => $site->page($p));
$router->get('/posts', fn () => $site->posts());
$router->get('/posts/{slug}', fn ($p) => $site->post($p));
$router->get('/search', fn () => $site->search());
$router->get('/media', fn () => $site->media());
$router->get('/forums', fn () => $site->forums());
$router->get('/forums/{id}', fn ($p) => $site->forum($p));
$router->get('/forums/threads/{id}', fn ($p) => $site->thread($p));
$router->post('/forums/{id}/threads', fn ($p) => $site->submitThread($p));
$router->post('/forums/threads/{id}/posts', fn ($p) => $site->submitPost($p));
$router->get('/forms', fn () => $site->forms());
$router->get('/forms/{id}', fn ($p) => $site->form($p));
$router->post('/forms/{id}', fn ($p) => $site->submitForm($p));
$router->post('/comments', fn () => $site->submitComment());
$router->get('/serverstatus', fn () => $site->serverStatus());
$router->get('/serverstatus/{id}', fn ($p) => $site->server($p));
$router->get('/game/stats', fn () => $site->gameStats());
$router->get('/game/stats/{id}', fn ($p) => $site->gameStat($p));
$router->get('/game/guilds', fn () => $site->guilds());
$router->get('/game/guilds/{id}', fn ($p) => $site->guild($p));
$router->get('/game/events', fn () => $site->events());
$router->get('/game/events/{id}', fn ($p) => $site->event($p));
$router->get('/game/market', fn () => $site->market());
$router->get('/game/market/{id}', fn ($p) => $site->marketItem($p));
$router->get('/game/matches', fn () => $site->matches());
$router->get('/game/matches/{id}', fn ($p) => $site->match($p));
$router->get('/profiles', fn () => $site->profiles());
$router->get('/profiles/{id}', fn ($p) => $site->profile($p));
$router->get('/profile', fn () => $site->profileSettings());
$router->post('/profile', fn () => $site->updateProfileSettings());
$router->get('/groups', fn () => $site->groups());
$router->get('/groups/{id}', fn ($p) => $site->group($p));
$router->get('/themes', fn () => $site->themes());
$router->get('/themes/activate/{slug}', fn ($p) => $site->activateTheme($p));
$router->get('/plugins', fn () => $site->plugins());
$router->get('/api-docs', fn () => $site->apiDocs());
$router->get('/sitemap.xml', fn () => $site->sitemap());

$router->get('/login', fn () => $auth->loginForm());
$router->post('/login', fn () => $auth->login());
$router->get('/admin/login', fn () => $auth->adminLoginForm());
$router->post('/admin/login', fn () => $auth->adminLogin());
$router->get('/register', fn () => $auth->registerForm());
$router->post('/register', fn () => $auth->register());
$router->post('/logout', fn () => $auth->logout());
$router->post('/admin/logout', fn () => $auth->adminLogout());

$router->get('/admin', fn () => $admin->dashboard());
$router->get('/admin/{module}', fn ($p) => $admin->index($p));
$router->get('/admin/{module}/create', fn ($p) => $admin->create($p));
$router->post('/admin/{module}/create', fn ($p) => $admin->store($p));
$router->get('/admin/{module}/{id}', fn ($p) => $admin->show($p));
$router->get('/admin/{module}/{id}/edit', fn ($p) => $admin->edit($p));
$router->post('/admin/{module}/{id}/edit', fn ($p) => $admin->update($p));
$router->post('/admin/{module}/{id}/delete', fn ($p) => $admin->destroy($p));
$router->get('/admin/users/{id}/roles', fn ($p) => $access->userRoles($p));
$router->post('/admin/users/{id}/roles', fn ($p) => $access->updateUserRoles($p));
$router->get('/admin/roles/{id}/permissions', fn ($p) => $access->rolePermissions($p));
$router->post('/admin/roles/{id}/permissions', fn ($p) => $access->updateRolePermissions($p));

$router->get('/api/pages', fn () => $api->pages());
$router->get('/api/pages/{slug}', fn ($p) => $api->page($p));
$router->get('/api/posts', fn () => $api->posts());
$router->get('/api/posts/{slug}', fn ($p) => $api->post($p));
