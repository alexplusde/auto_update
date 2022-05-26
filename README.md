# Automatische Updates für REDAXO 5

Lädt täglich automatisch neue Updates aus dem REDAXO-Installer herunter
## Features

* Prüft und installiert täglich Updates aus dem REDAXO-Installer
* Einstellungsmöglichkeiten, ob nur bestimmte Versionssprünge und/oder Addons bestimmter Autoren geupdatet werden.
* Berücksichtigt YDeploy - auto_update wird nicht in Live-Umgebung mit YDeploy ausgeführt.
* E-Mail-Benachrichtigungen

> Hinweis: Dieses Addon wird nicht für Live-Seiten empfohlen, kann jedoch dort eingesetzt werden.
## Einstellungs-Seite

### Core Patches

Erlaubt Updates von REDAXO, z.B. `5.14.2` auf `5.14.3`.
### Core Minor-Updates

Erlaubt Updates von REDAXO, z.B. `5.14.2` auf `5.15.0`.
### Addon Patches

Erlaubt Updates von Addons, z.B. `1.1.2` auf `1.1.3`.
### Addon Minor-Updates

Erlaubt Updates von Addons, z.B. `1.1.2` auf `1.2.0`.
### Addon Major-Updates

Erlaubt Updates von Addons, z.B. `1.1.2` auf `2.0.0`.

### Packages ignorieren

Eine Liste von kommagetrennten Package-Namen, bspw. `yform`, die niemals geupdatet werden sollen.

### Vertrauenswürdige Quellen

Eine Liste von kommagetrennten Autoren-Namen (z.B. dein eigener Accountname auf redaxo.org/myredaxo, bspw. `friendsofredaxo`) die geupdatet werden dürfen.

### Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/auto_update/blob/master/LICENSE.md)  

## Autoren

**Alexander Walther**  
http://www.alexplus.de  
https://github.com/alexplusde  

**Projekt-Lead**  
[Alexander Walther](https://github.com/alexplusde)

## Credits
