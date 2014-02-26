<?php

App::import('Model', 'Toolbox');

/**
 * Classe qui propose un ensemble de méthode sur une chaîne de caractère
 */
class ManipulableString {

    /**
     * Valeur de la chaîne
     * @var string 
     */
    private $value = '';

    /**
     * Constructeur
     * @param string $string
     */
    public function __construct($string = '') {
        $this->value = $string;
    }

    /**
     * Retourne la taille de la chaîne
     * @return int
     */
    public function getSize() {
        return mb_strlen($this->value);
    }

    /**
     * Get value
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set value
     * @param string $string
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Met aléatoirement en majuscules les premières lettres de chaque mot qui compose la chaine
     */
    public function buildUppercase() {
        $sentence = explode(' ', $this->value);
        $nbToUpper = rand(0, count($sentence));
        if ($nbToUpper != 0) {
            $toUpper = array_rand($sentence, $nbToUpper);
            if (is_numeric($toUpper)) {
                $sentence[$toUpper] = ucfirst($sentence[$toUpper]);
            } else {
                foreach ($toUpper as $value) {
                    $sentence[$value] = ucfirst($sentence[$value]);
                }
            }
        }

        $this->value = implode(" ", $sentence);
    }

    /**
     * Remplace les espaces dans la chaîne de manière alétaoire mais logique
     */
    public function replaceSpaces() {
        $replacePossible = array(
            '_',
            '-',
            '.',
            '<',
            '=',
            '!',
            '+',
            '/',
            '&',
            ':'
        );
        $sentence = explode(' ', $this->value);

        $sequenceNumbers = Toolbox::logicalSequence(count($sentence) - 1);
        $separator = Toolbox::arrayRand($replacePossible);

        $whereInsert = $this->whereInsert();
        foreach ($sentence as $key => $word) {
            if (isset($sentence[$key + 1])) {
                switch ($whereInsert) {
                    case 'before':
                        $sentence[$key] = $word . $separator . $sequenceNumbers[$key];
                        break;
                    default:
                        $sentence[$key] = $word . $sequenceNumbers[$key] . $separator;
                        break;
                }
            }
        }

        $this->value = implode($sentence);
    }

    /**
     * Applique trim() sur la chaîne
     */
    public function trim() {
        $this->value = trim($this->value);
    }

    /**
     * Applique trstrtolower() sur la chaîne
     */
    public function strtolower() {
        $this->value = strtolower($this->value);
    }

    /**
     * Transforme la chaîne en remplacement aléatoirement des caractère par leur équivalent (si ils en ont un), et en mettant aléatoirement des lettres en majuscule
     */
    public function transform() {
        $transform = '';
        for ($i = 0; $i < $this->getSize(); $i++) {
            $letter = $this->value[$i];
            $transform .= $this->replaceLetter($letter);
        }
        $this->value = $transform;
    }

    /**
     * Complète la chaîne avec des caractères aléatoires jusqu'à la taille passée en paramètre
     * 
     * @param int $minLength
     */
    public function complete($minLength) {
        while ($this->getSize() < $minLength) {
            $this->addChar();
        }
    }

    /**
     * Ajoute un caractère au début ou à la fin de la chaine 
     * @param string $type Type du caractère ajouté ('numeric', 'lower', 'upper' ou 'special'). Par défaut ou pour toute autre valeur, un caractère aléatoire sera retourné  
     */
    public function addChar($type = null) {
        $numbers = '0123456789';
        $letters = 'azertyuiopqsdfghjklmwxcvbn';
        $special = PasswordEntity::$specialChar;

        switch ($type) {
            case 'numeric':
                $charsPossible = str_split($numbers);
                break;
            case 'lower':
                $charsPossible = str_split($letters);
                break;
            case 'upper':
                $charsPossible = str_split(strtoupper($letters));
                break;
            case 'alpha':
                $charsPossible = array_merge(str_split($letters), str_split(strtoupper($letters)));
                break;
            case 'special':
                $charsPossible = $special;
                break;
            default:
                switch (mt_rand(1, 4)) {
                    case 1:
                        return $this->addChar('numeric');
                    case 2:
                        return $this->addChar('lower');
                    case 3:
                        return $this->addChar('upper');
                    case 4:
                        return $this->addChar('special');
                }
                break;
        }

        switch ($this->whereInsert()) {
            case 'before':
                $this->value = Toolbox::arrayRand($charsPossible) . $this->value;
                break;
            case 'after':
                $this->value = $this->value . Toolbox::arrayRand($charsPossible);
                break;
        }
    }

    /**
     * Indique si au moins un caractère minuscule est présent dans la chaîne
     * @return boolean True si c'est le cas, sinon false
     */
    public function atLeastOneCharLowerCase() {
        return preg_match('/a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z/', $this->value);
    }

    /**
     * Indique si au moins un caractère majuscule est présent dans la chaîne
     * @return boolean True si c'est le cas, sinon false
     */
    public function atLeastOneCharUpperCase() {
        return preg_match('/A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z/', $this->value);
    }

    /**
     * Indique si au moins un chiffre est présent dans la chaîne
     * @return boolean True si c'est le cas, sinon false
     */
    public function atLeastOneCharNumeric() {
        return preg_match('/1|2|3|4|5|6|7|8|9|0/', $this->value);
    }

    /**
     * Indique si une chaîne est alphanumérique (comprenant les accents de la langue française) ou non
     * @return boolean True si c'est le cas, sinon false
     */
    public function isAlphaNumeric() {
        return preg_match('/^[a-zA-Z0-9àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ]*$/', $this->value);
    }

    /**
     * Remplace (ou non) aléatoirement une lettre par son "équivalent" (ex : e => €) si cette lettre en possède un, et met aléatoirement ou non en majuscule 
     * @param char $letter La lettre qui sera testée
     * @return string Lettre modifiée (ou non)
     */
    private function replaceLetter($letter) {
        $concordance = array(
            'a' => '@',
            'e' => '€',
            's' => '$',
            'o' => '0',
            'i' => '1',
            'é' => '&',
            'l' => '!',
            'u' => 'µ'
        );
        //Equivalent
        if (array_key_exists($letter, $concordance) AND Toolbox::test()) {
            return $concordance[$letter];
        }

        // Mettre en majuscule
        if (Toolbox::test()) {
            return strtoupper($letter);
        }

        return $letter;
    }

    /**
     * Indique où insérer dans la chaîne
     * @return string 'before' ou 'after'
     */
    private function whereInsert() {
        $possibilities = array(
            'before',
            'after'
        );
        return Toolbox::arrayRand($possibilities);
    }

}
