export function isLoggedIn() {
  return !!localStorage.getItem('token');
}

export function getRole() {
  return localStorage.getItem('role');
}