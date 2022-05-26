<?php
class auto_update extends \rex_yform_manager_dataset
{
    public static function isYDeployed()
    {
        if (method_exists("rex_ydeploy", "isDeployed")) {
            return rex_ydeploy::isDeployed();
        }
    }

    public static function version_formatter($version) :array
    {
        // Vereinheitlicht Versionsnummern, um gegen Major-Minor-Patch zu checken.
        $version = explode("-", $version)[0];
        $array = explode(".", $version);
        $return = [];
        if (is_numeric($array[0])) {
            $return[] = $array[0];
        }
        if (is_numeric($array[1])) {
            $return[] = $array[1];
        } else {
            $return[] = 0;
        }
        if (is_numeric($array[2])) {
            $return[] = $array[2];
        } else {
            $return[] = 0;
        }
        if (isset($version[1])) {
            // Flag wie -beta, -alpha oder -dev hinzufügen
            $return[] = "-". $version[1];
        }
        return $return;
    }
    public static function check_version($current_version, $latest_version, $package_key)
    {
        $current_version = self::version_formatter($current_version);
        $latest_version = self::version_formatter($latest_version);

        switch ($package_key) {
            case 'core':
                if ($latest_version[3]) {
                    // Wenn Entwickler-, Alpha- oder Beta-Version
                    return false;
                    break;
                }
                if ($latest_version[0] > $current_version[0]) {
                    return false;
                    break;
                }
                if ($latest_version[1] > $current_version[1] && rex_config::get('auto_update', 'core_minor') == 0) {
                    // Minor-Versionssprung, aber dieser ist nicht eingestellt.
                    return false;
                    break;
                }
                if ($latest_version[2] > $current_version[2] && rex_config::get('auto_update', 'core_patch') == 0) {
                    // Patch-Versionssprung, aber dieser ist nicht eingestellt.
                    return false;
                    break;
                }
                return true;
                break;
            
            default:
                if ($latest_version[3]) {
                    // Wenn Entwickler-, Alpha- oder Beta-Version
                    return false;
                    break;
                }
                if ($latest_version[0] > $current_version[0] && rex_config::get('auto_update', 'addon_major') == 0) {
                    return false;
                    break;
                }
                if ($latest_version[1] > $current_version[1] && rex_config::get('auto_update', 'addon_minor') == 0) {
                    // Minor-Versionssprung, aber dieser ist nicht eingestellt.
                    return false;
                    break;
                }
                if ($latest_version[2] > $current_version[2] && rex_config::get('auto_update', 'addon_patch') == 0) {
                    // Patch-Versionssprung, aber dieser ist nicht eingestellt.
                    return false;
                    break;
                }
                return true;
                break;
        }
    }

    public static function list()
    {
        // Siehe https://github.com/redaxo/redaxo/blob/main/redaxo/src/addons/install/lib/command/list.php        $io = $this->getStyle($input, $output);
        // ['key', 'name', 'author', 'last updated', 'latest version', 'installed version'];
        $available_packages = rex_install_packages::getUpdatePackages();

        $packages = [];

        $trusted_authors = explode(",", rex_config::get('auto_update', "trusted_authors"));

        foreach ($available_packages as $key => $package) {
            dump($package);

            $current_version = rex_addon::get($key)->getVersion();
            $latest_version = reset($package['files'])['version'];

            if (rex_config::get('auto_update', "only_trusted_authors")) {
                if (!array_search($package['author'], $trusted_authors)) {
                    continue;
                }
            }

            if (!self::check_version($current_version, $latest_version, $package_key)) {
                continue;
            }

            $packages[$key] = [
                'key' => $key,
                'fid' => $package['fid'],
                'files' => $package['files'],
                'name' => strlen($package['name']) > 40 ? substr($package['name'], 0, 40).'...' : $package['name'],
                'author' => $package['author'],
                'last_updated' => rex_formatter::intlDate($package['updated']),
                'latest_version' => $latest_version,
                'installed_version' => $current_version,
            ];
        }

        dump($packages);

        return $packages;
    }

    public static function run()
    {
        $packages = self::list();
        foreach ($packages as $key => $package) {
            self::update($package);
        }
        // 2nd run for dependencies
        $packages = self::list();
        foreach ($packages as $key => $package) {
            self::update($package);
        }
        // 3rd run for dependencies
        $packages = self::list();
        foreach ($packages as $key => $package) {
            self::update($package);
        }
    }

    public static function update($package_key)
    {
        // Siehe https://github.com/redaxo/redaxo/blob/main/redaxo/src/addons/install/lib/command/update.php
        if (!rex_addon::exists($package_key)) {
            rex_view::message('AddOn "%s" does not exist!', $package_key);
            return 1;
        }

        $packages = self::list();

        if (!isset($packages[$package_key])) {
            rex_view::message('No Updates available for AddOn "%s"!', $package_key);
            return 1;
        }
        $package = $packages[$package_key];

        $version = reset($package['files'])['version'];
        $file_id = reset($package['files'])['fid'];

        $install = new rex_install_package_update();

        try {
            $message = $install->run($package_key, $file_id);
        } catch (rex_exception $exception) {
            rex_view::message($exception->getMessage());
            return 1;
        }

        if ('' !== $message) {
            rex_view::message($message);
            return 1;
        }

        rex_view::message('AddOn "%s" successfully updated to version "%s".', $package_key, $version);
        return 0;
    }

    public static function logCrash()
    {
        // To Do: Log-Eintrag anlegen, Addon ignorieren, per Mail benachrichtigen
    }

    public static function notifyByMail($addon = '', $type = 'success')
    {
        if (rex_addon::get('phpmailer')->isAvailable()) {
            // To Do: Mail versenden
        }
    }
}
