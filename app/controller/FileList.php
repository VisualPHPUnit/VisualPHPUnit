<?php

namespace app\controller;

class FileList extends \app\core\Controller {

    // GET
    public function index($request) {
        if ( !$request->is('ajax') ) {
            return $this->redirect('/');
        }

        return array();
    }

}

?>
