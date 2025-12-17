<?php 

function user_group($userId) {
    $db = \Config\Database::connect();
    return $db->table('auth_groups_users')
             ->select('auth_groups.name')
             ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
             ->where('user_id', $userId)
             ->get()->getRow('name');
}
