<?php
#
$addon = rex_addon::get('auto_update');

$form = rex_config_form::factory($addon->name);


$field = $form->addCheckboxField('core_patch');
$field->setLabel("Core");
$field->addOption('Patch-Updates', '1');

$field = $form->addCheckboxField('core_minor');
$field->setLabel("Core");
$field->addOption('Minor-Updates', '1');

$field = $form->addCheckboxField('addon_patch');
$field->setLabel("Addon");
$field->addOption('Patch-Updates', '1');

$field = $form->addCheckboxField('addon_minor');
$field->setLabel("Addon");
$field->addOption('Minor-Updates', '1');

$field = $form->addCheckboxField('addon_major');
$field->setLabel("Addon");
$field->addOption('Major-Updates', '1');

$field = $form->addInputField('text', 'ignore', null, ["class" => "form-control"]);
$field->setLabel("Packages ignorieren");

$field = $form->addCheckboxField('only_trusted_authors');
$field->setLabel("Vertrauenswürdige Quellen");
$field->addOption('nur aus vertrauenswürdigen Quellen (nachfolgende Liste) installieren', '1');

$field = $form->addInputField('text', 'trusted_authors', null, ["class" => "form-control"]);
$field->setLabel("Vertrauenswürdige Autoren");

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('auto_update_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
