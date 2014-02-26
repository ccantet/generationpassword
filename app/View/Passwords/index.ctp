<?php echo $this->Form->create('Password', array('id' => 'GeneratePasswordForm')); ?>
<?php echo $this->Form->input('sentence', array('label' => 'Indiquer une phrase qu\'il vous sera facile de retenir :', 'placeholder' => "Ex : c'est moi le patron...")); ?>
<div>Une bonne bonne phrase c'est :
    <ul>
        <li>Une phrase dont vous n'aurez aucun mal à vous souvenir</li>
        <li>Composée d'au moins 3 mots (même court)</li>
        <!--<li></li>-->
    </ul>
</div>
<?php echo $this->Form->input('transformSentence', array('type' => 'checkbox', 'label' => 'Transformez la phrase ? (Recommandée si la phrase est inférieure à 3 mots)')); ?>
<?php echo $this->Form->submit('Générer'); ?>
<?php echo $this->Form->input('generatePassword', array('label' => 'Mot de passe généré :', 'readonly' => true)); ?>
<?php echo $this->Form->end(); ?>
<hr>
<?php echo $this->Form->create('Password', array('id' => 'TestPasswordForm')); ?>
<?php echo $this->Form->input('testPassword', array('label' => 'Tester un mot de passe :')); ?>
<?php echo $this->Form->submit('Tester'); ?>
<?php echo $this->Form->end(); ?>
<?php if (isset($password)): ?>
    <?php echo $this->Form->hidden('security', array('value' => $password->security, 'id' => 'security')); ?>
    <?php echo $this->Text->timeToHackage($password->timeToHack); ?>
    <div id="bigbar"><div id="progressbar"></div></div>
    <div class="clear"></div>
    <?php echo $this->Text->securityPassword($password->security); ?>
    <?php echo $this->Text->displayTips($password); ?>
<?php endif; ?>