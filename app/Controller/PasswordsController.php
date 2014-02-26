<?php

App::uses('AppController', 'Controller');
App::import('Model', 'PasswordEntity');

/**
 * Passwords Controller
 * 
 * @property Password $Password
 * @package       app.Controller
 */
class PasswordsController extends AppController {

    public function index() {
        $this->set('security', null);
        if ($this->request->is('post')) {
            if (!isset($this->request->data['Password']['testPassword'])) {
                $password = new PasswordEntity();
                $password->generatePassword($this->request->data['Password']['sentence'], $this->request->data['Password']['transformSentence']);
                $this->request->data['Password']['generatePassword'] = $password->value->getValue();
            } else {
                $password = new PasswordEntity($this->request->data['Password']['testPassword']);
            }

            $this->request->data['Password']['testPassword'] = $password->value->getValue();
            $this->set('password', $password);
        }
    }

}
