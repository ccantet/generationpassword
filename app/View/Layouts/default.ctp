<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            Générateur de mot de passe
        </title>
        <?php
        echo $this->Html->meta(
                'favicon.ico', 'img/favicon.ico', array('type' => 'icon')
        );
        echo $this->Html->script('http://code.jquery.com/jquery-1.11.0.min.js');
        echo $this->Html->script('http://code.jquery.com/ui/1.10.4/jquery-ui.js');
        echo $this->Html->css('http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
        echo $this->Html->css('cake.generic');
        echo $this->Html->script('front');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <div id="header">
            <h1><?php echo $this->Html->image('key.icon.png') ?>Générateur de mot de passe</h1>
        </div>
        <div id="container">

            <div id="content">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
                Créé par Cassandre CANTET
            </div>
        </div>
    </body>
</html>
