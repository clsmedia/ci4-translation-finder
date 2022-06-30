# clsmedia/ci4-translation-finder
Translation strings finder for CodeIgniter 4

# Quick start
1. Install with composer 
`composer require clsmedia/ci4-translation-finder`
2. Use `spark` to generate list
`php spark translations:find`

# Usage
As soon as the package is added to the project, you can start using it to find strings for translation. To find all `lang()` arguments in application files use CLI `php spark translations:find`

Example result of command execution:

    Auth.activationNoUser
    Auth.activationSuccess
    Auth.alreadyRegistered
    Auth.badAttempt
    Auth.email
    Auth.emailAddress
    Auth.emailOrUsername
    Auth.enterCodeEmailPassword
    Auth.enterEmailForInstructions
    Auth.forgotDisabled
    Auth.forgotEmailSent
    Auth.forgotNoUser
    Auth.forgotPassword

Now you can use this list to prepare the corresponding translation strings in `App/Language/`
