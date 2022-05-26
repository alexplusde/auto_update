<?php


if (time() > rex_config::get('auto_update', 'next')) {
    auto_update::update();
    rex_config::set('auto_update', 'next', time() + 60 * 60 * 24);
}
