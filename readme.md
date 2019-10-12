# Grap

## How to install
- Clone this repo
- Import database .sql
- Set application/config/database.php
- Run Xampp Apache & Mysql
- Run `npm install` in project folder
- Run `npm run watch-poll` to auto-update javacsript (Dont close the terminal)
- Open browser and enter `localhost/grap`
- Every browser reload will also update compiled javascript

## Development
## App
- PHP framework - Codeigniter
- CSS framework - Bootstrap
- Admin template - SBAdmin2
- Javascript framework - VueJS

### Tools
- Xampp with PHP7
- Visual Studio Code

### Formatting
- Install 'Format HTML in PHP' & 'PHP fmt' plugin from vs code marketplace
- Add config below to setting.json in VS code

```json
    // HTML formatting (Format HTML in PHP)
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

    // PHP formatting (phpfmt)
    "intelephense.format.enable": false,
    "phpfmt.passes": [
        "PSR2KeywordsLowerCase",
        "PSR2LnAfterNamespace",
        "PSR2CurlyOpenNextLine",
        "PSR2ModifierVisibilityStaticOrder",
        "PSR2SingleEmptyLineAndStripClosingTag",
        "ReindentSwitchBlocks"
    ],
    "phpfmt.exclude": [
        "ReindentComments",
        "StripNewlineWithinClassBody"
    ],
    "phpfmt.enable_auto_align": true,

    // Javascript-vue formatting (Vetur)
    "editor.formatOnSave": true,
    "eslint.autoFixOnSave": true,
    "eslint.validate": [
        {
            "language": "vue",
            "autoFix": true
        },
        {
            "language": "html",
            "autoFix": true
        },
        {
            "language": "javascript",
            "autoFix": true
        }
    ],
    "javascript.preferences.quoteStyle": "single",
    "typescript.preferences.quoteStyle": "single",
    "editor.autoClosingQuotes": "always",
    "vetur.experimental.templateInterpolationService": false,
    "vetur.format.defaultFormatter.js": "vscode-typescript",
    "vetur.validation.template": false,
    "vetur.format.defaultFormatter.html": "js-beautify-html",
```
