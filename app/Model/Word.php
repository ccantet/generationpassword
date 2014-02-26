<?php

App::uses('AppModel', 'Model');

/**
 * Modèle permettant d'accéder à la table qui stocke l'ensemble des mots des dictionnaires fançais et anglais 
 * 
 * 
 * Comment est c
 * @package       app.Model.AppModel
 */
class Word extends AppModel {
    
    /**
     * Indique si un mot est présent dans le dictionnaire français et/ou anglais
     * @param string $word
     * @return boolean True s'il existe, false autrement
     */
    public function wordExist($word) {
        $optionsSearch = array(
            'conditions' => array('word' => $word)
        );

        if ($this->find('count', $optionsSearch) > 0) {
            return true;
        }
        return false;
    }

}
