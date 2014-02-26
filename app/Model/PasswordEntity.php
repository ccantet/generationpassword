<?php

App::import('Model', 'ManipulableString');

/**
 * Classe password. Permet de générer, tester un password et contient un ensemble d'informations notamment concernant sa sécurité.
 * 
 */
class PasswordEntity {

    /**
     * Valeur du mot de passe
     * @var ManipulableString 
     */
    public $value = null;

    /**
     * Phrase à partir de laquelle le mot de passe a été générée (
     * @var ManipulableString 
     */
    public $sentence = null;

    /**
     * Securité du mot de passe. 
     * @var string 
     */
    public $security = null;

    /**
     * Contient les informations concernants le temps nécessaire à un pc de bureau à 4 cores pour hacker le mot de passe par force brute
     * @var array 
     */
    public $timeToHack = array(
        'inSec' => null,
        'lowerBound' => null,
        'upperBound' => null,
        'unit' => null
    );

    /**
     * Conseils pour améliorer le mot de passe
     * @var type 
     */
    public $tipsToImprove = array();

    /**
     * Taille minimum du mot de passe à sa création. Un delta à +2 caractères sera appliqué pour éviter de connaître à l'avance la taille du mot de passe
     * @var type 
     */
    public $minLength = 12;

    /**
     * Taille mimimum pour qu'un mot de passe ne soit considéré comme trop petit
     */
    public static $minLengthOptimum = 12;

    /**
     * Caractères spéciaux qui peuvent être intégrés à un mot de passe
     * @var array
     */
    public static $specialChar = array(
        '<', '>', '&', '_', '-', '@', '=', '+', '*', '/', '$', '%', '!', ':', ';', '?', '.', '[', ']', '(', ')', '{', '}', '|', '#'
    );

    /**
     * Constructeur. Si on passe une chaîne en paramètre, le mot de passe sera directement testé.
     * @param string
     */
    public function __construct($value = null) {
        if (isset($value)) {
            $this->value = new ManipulableString($value);
            $this->testPassword();
        } else {
            $this->value = new ManipulableString();
        }
    }

    /**
     * Set timeToHack
     * @param int 
     * @param int 
     * @param int 
     * @param string 
     */
    private function setTimeToHack($inSec = null, $lowerBound = null, $upperBound = null, $unit = null) {
        $this->timeToHack = array(
            'inSec' => $inSec,
            'lowerBound' => $lowerBound,
            'upperBound' => $upperBound,
            'unit' => $unit
        );
    }

    /**
     * Génère un mot de passe en fonction de la phrase passé en paramètre. Ce mot de passe sera normalement assez facilement mémorisable.
     * @param string  
     * @param boolean Indique si l'on doit transformer ou non la phrase (remplacer certaines lettres par un caractère approchant ex : 'a' => '@', 'u'  => 'ù' etc...)
     */
    public function generatePassword($sentence = '', $transformSentence = true) {
        if (empty($sentence)) {
            $sentence = '';
        }

        $this->sentence = new ManipulableString($sentence);
        $this->sentence->trim();
        $this->sentence->strtolower();

        if ($this->sentence->getSize() < $this->minLength) {
            $this->minLength = mt_rand($this->minLength, $this->minLength + 2);
        }

        $this->value = new ManipulableString();
        $this->value->setValue($this->sentence->getValue());
        $this->value->buildUppercase();
        $this->value->replaceSpaces();

        if ($transformSentence) {
            $this->value->transform();
        }

        // Si le mot de passe est trop court, on le complète pour qu'il atteigne au moins la taille minimum requise
        if ($this->value->getSize() < $this->minLength) {
            $this->value->complete($this->minLength);
        }

        // Vérification qu'il y a au moins un caractère numérique, si ce n'est pas le cas, en ajoute un
        if (!$this->value->atLeastOneCharNumeric()) {
            $this->value->addChar('numeric');
        }
        // Vérification qu'il y a au moins un caractère spécial, si ce n'est pas le cas, en ajoute un
        if ($this->value->isAlphaNumeric()) {
            $this->value->addChar('special');
        }
        // Vérification qu'il y a au moins un caractère numérique, si ce n'est pas le cas, en ajoute un
        if (!$this->value->atLeastOneCharUpperCase()) {
            $this->value->addChar('upper');
        }
        // Vérification qu'il y a au moins un caractère numérique, si ce n'est pas le cas, en ajoute un
        if (!$this->value->atLeastOneCharLowerCase()) {
            $this->value->addChar('lower');
        }

        $this->testPassword();
    }

    /**
     * Test la sureté d'un mot de passe. Celle-ci est déterminée en fonction du nombre de possibilités qu'il faudrait tester en tentative d'attaque par force brute. 
     * Renseigne les attributs security, timeToHack, et tipsToImprove de l'objet
     */
    public function testPassword() {
        $rang = $this->isCommonPassword();
        if ($rang !== false) {
            $this->setLowerSecurity();
            $this->addTips(array(
                'message' => "Mot de passe beaucoup trop commun : Ce mot de passe est au rang $rang des mots de passe les plus utilisés au monde, autrement dit c'est comme s'il n'y en avait pas !",
                'tip' => 'Une seule solution, choisissez-en un autre'
            ));
            return;
        }
        if ($this->isExistingWord() !== false) {
            $this->setLowerSecurity();
            $this->addTips(array(
                'message' => "Votre mot de passe est dans le dictionnaire",
                'tip' => 'Une seule solution, choisissez-en un autre'
            ));
            return;
        }

        $nbPossibilitiesChar = 0;
        if ($this->value->atLeastOneCharLowerCase()) {
            $nbPossibilitiesChar += 26;
        } else {
            $this->addTips(array(
                'message' => "Votre mot de passe ne contient pas de minuscule",
                'tip' => 'Ajoutez en au moins une'
            ));
        }
        if ($this->value->atLeastOneCharUpperCase()) {
            $nbPossibilitiesChar += 26;
        } else {
            $this->addTips(array(
                'message' => "Votre mot de passe ne contient pas de majuscule",
                'tip' => 'Ajoutez en au moins une'
            ));
        }
        if ($this->value->atLeastOneCharNumeric()) {
            $nbPossibilitiesChar += 10;
        } else {
            $this->addTips(array(
                'message' => "Votre mot de passe ne contient pas de chiffre",
                'tip' => 'Ajoutez en au moins un'
            ));
        }
        if (!$this->value->isAlphaNumeric()) {
            $nbPossibilitiesChar += 26;
        } else {
            $this->addTips(array(
                'message' => "Votre mot de passe ne contient pas de caractère spécial",
                'tip' => 'Ajoutez en au moins un'
            ));
        }
        if ($this->value->getSize() < self::$minLengthOptimum) {
            $this->addTips(array(
                'message' => "Votre mot de passe ne contient que " . $this->value->getSize() . " caractère(s). 8 caractères est un strict minimum et pour une sécurité optimum, " . self::$minLengthOptimum . " caractères minimums sont recommandés",
                'tip' => "Ajoutez au moins " . ( self::$minLengthOptimum - $this->value->getSize() ) . " caractère(s) à votre mot de passe"
            ));
        }

        $nbPossibilities = bcpow($nbPossibilitiesChar, $this->value->getSize());

        $this->setTimeToHackInSec(bcdiv($nbPossibilities, 200000000000, 0));
    }

    /**
     * Renseigne les informations timeToHack
     * @param int 
     */
    private function setTimeToHackInSec($timeToHackInSec) {
        $time = array(
            'sec' => Toolbox::convertTimeToSeconds('sec'),
            'min' => Toolbox::convertTimeToSeconds('min'),
            'hour' => Toolbox::convertTimeToSeconds('hour'),
            'day' => Toolbox::convertTimeToSeconds('day'),
            'week' => Toolbox::convertTimeToSeconds('week'),
            'month' => Toolbox::convertTimeToSeconds('month'),
            'year' => Toolbox::convertTimeToSeconds('year')
        );

        if ($timeToHackInSec <= 1) {
            $this->setLowerSecurity();
            return;
        } else if ($timeToHackInSec >= $time['year'] * 1000) {
            $lowerBound = 1000;
            $upperBound = null;
            $unit = 'year';
        } else if ($timeToHackInSec >= $time['year']) {
            $lowerBound = floor(bcdiv($timeToHackInSec, $time['year'], 13));
            $upperBound = $lowerBound + 1;
            $unit = 'year';
        } else {
            foreach ($time as $unit => $value) {
                if ($timeToHackInSec < $value) {
                    $lowerBound = floor(bcdiv($timeToHackInSec, $valuePrev, 13));
                    $upperBound = $lowerBound + 1;
                    $unit = $unitPrev;
                    break;
                }
                $unitPrev = $unit;
                $valuePrev = $value;
            }
        }
        $this->setTimeToHack($timeToHackInSec, $lowerBound, $upperBound, $unit);
        $this->calcSecurity();
    }

    /**
     * Indique que la sécurité de l'objet est la plus basse  qui soit
     */
    private function setLowerSecurity() {
        $this->security = 'veryBad';
        $this->setTimeToHack(1, null, 1, 'sec');
    }

    /**
     * Calcul la sécurité de l'objet en fonction du temps nécessaire pour le hacker
     */
    private function calcSecurity() {
        $timeToHack = $this->timeToHack['inSec'];

        if ($timeToHack <= Toolbox::convertTimeToSeconds('min', 30)) {
            $this->security = 'veryBad';
        } else if ($timeToHack <= Toolbox::convertTimeToSeconds('year', 1)) {
            $this->security = 'bad';
        } else if ($timeToHack <= Toolbox::convertTimeToSeconds('year', 1000)) {
            $this->security = 'good';
        } else {
            $this->security = 'veryGood';
        }
    }

    /**
     * Ajoute un tips 
     * @param array  
     */
    private function addTips($tab) {
        $this->tipsToImprove[] = array(
            'message' => $tab['message'],
            'tip' => $tab['tip']
        );
    }

    /**
     * Indique si le mot de passe est "commun"
     * @return mixed False s'il ne l'est pas, autrement son range des mots de passe les plus utilisés (1 = le mot de passe le plus utilisé, 2 = le deuxième etc...)
     */
    private function isCommonPassword() {
        App::uses('CommonPassword', 'Model');
        $commonPassword = new CommonPassword();

        return $commonPassword->passwordExist($this->value->getValue());
    }

    /**
     * Indique si le mot de passe est dans le dictionnaire français ou anglais
     * @return type
     */
    private function isExistingWord() {
        App::uses('Word', 'Model');
        $word = new Word();

        return $word->wordExist($this->value->getValue());
    }

}
