<?php 

class Home {
    function index() {
        $muser = new model\User(); 
        $user = $muser->get();

        view('home', [
            'user' => $user,
            'in' => model\User::in(),
        ]);
    }
}
