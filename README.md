# Ckeditor Allowed Content Rules

## INTRODUCTION
CKEditor Allowed Content Rules allows you to override 
Drupal default "Limit allowed HTML tags and correct faulty HTML" editor 
configuration with CKEditor Allowed Content Rules.

For more details visit:-
    https://ckeditor.com/docs/ckeditor4/latest/guide/dev_allowed_content_rules.html

## INSTALLATION

1) Install module as usual.
https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules

## CONFIGURATION

Adding configuration to an editor
1) Navigate to an editor configuration page 
(/admin/config/content/formats/manage/[editor]).
2) Please ensure you have enabled <i>"Limit allowed HTML tags and correct faulty 
HTML"</i> filter for this setting to take effect.
2) On the configuration page, navigate to 
"CKEditor Allowed Content Rules Override" under "CKEditor plugin settings".
Override rules with each item on its own line.

Example :- 

    // A rule accepting <a> only if it contains the "href" attribute.
    a[!href]
    
    // A rule accepting <img> with a required "src" attribute and an optional
    // "alt" attribute plus optional "width" and "height" styles and an optional 
    // class name.
    img[alt,!src]{width,height}(className)
