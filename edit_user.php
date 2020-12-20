<?php
$db = require_once 'dbstart.php';
$id = $_POST['id'];
$current_email = $db->getById('users', $id)['email'];

$check_params = [
    'username' => [
        'require' => true,
        'min' => 2,
        'max' => 30
    ]
];

if (Input::exists()) {
    $validation = new Validator($db);
    /* Валидация email проводится только если он поменялся */
    if ($current_email != Input::get('email')) {
        $check_params += ['email' => [
            'require' => true,
            'email' => true,
            'unique' => 'users'
        ]];
    }

    $validation->check($_POST, $check_params);

    if ($validation->passed()) {
        $is_update_successful = $db->update('users', $id, [
            'username' => Input::get('username'),
            'email' => Input::get('email')
        ]);

        if ($is_update_successful) {
            Flash::set('success', 'User successfully updated.');
        } else {
            Flash::set('danger', 'Error during update. Contact with tech support.');
        }
    } else {
        Flash::set('danger', 'Error. Please check your data.' . $validation->errors_ul_html());
    }
    
    header("Location: /edit?id={$id}");
}