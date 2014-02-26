<?php

App::uses('AppModel', 'Model');

/**
 * Modèle permettant d'accéder à la table qui stocke l'ensemble des passwords les plus communs au monde
 * 
 * @package       app.Model.AppModel
 */
class CommonPassword extends AppModel {
    
    /**
     * Regarde si un mot fait partie des mots de passe les plus commun
     * @param string $word Mot à chercher
     * @return mixed Retourne le rang du mot de passe (1 = le mot de passe le plus utilisé au monde, 2 = le deuxième, etc...), ou false s'il n'est pas dans la table 
     */
    public function passwordExist($word) {
        $optionsSearch = array(
            'fields' => 'frequency',
            'conditions' => array('password' => $word)
        );

        $commonPassword = $this->find('first', $optionsSearch);
        if (!empty($commonPassword)) {
            $frequency = $commonPassword['CommonPassword']['frequency'];
            $optionsCount = array(
                'conditions' => array('frequency >' => $frequency)
            );
            $rang = $this->find('count', $optionsCount);
            return ++$rang;
        }

        return false;
    }

}
