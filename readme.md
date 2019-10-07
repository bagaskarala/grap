# Grap

## How to install
- Clone this repo
- Import database .sql
- Set application/config/database.php
- Run Xampp Apache & Mysql
- Open browser and enter localhost/grap

## Development
## App
- PHP framework - Codeigniter
- CSS framework - Bootstrap
- Admin template - SBAdmin2
- Javascript framework - VueJS / jQuery

### Tools
- Xampp with PHP7
- Visual Studio Code

### Formatting
- Install 'Format HTML in PHP' & 'PHP intelephense' plugin from vs code marketplace
- Add config below to setting.json in VS code

```json
    // Format HTML in PHP
    "editor.insertSpaces": true,
    "editor.tabSize": 3,
    "html.format.contentUnformatted": "pre,code,textarea",
    "html.format.endWithNewline": false,
    "html.format.extraLiners": "head, body, /html",
    "html.format.indentHandlebars": false,
    "html.format.indentInnerHtml": false,
    "html.format.maxPreserveNewLines": null,
    "html.format.preserveNewLines": true,
    "html.format.wrapLineLength": 120,
    "html.format.wrapAttributes": "force-expand-multiline",
    // intelephense
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
    }
```

#
