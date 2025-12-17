<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAuthFilter implements FilterInterface {
  public function before(RequestInterface $request, $arguments = null) {
    $token = $request->getHeaderLine('Authorization');
    // parse JWT or Myth-Auth token
    // set user context: service('current_user') or helper set_current_user($user)
    // set current_shop_id (e.g. in config or session)
  }
  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
