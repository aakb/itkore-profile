#Setup guidelines

##About
ITKore represents a drupal 8 platform from which new drupal 8 sites may be built. This way some of the trivial tasks are bypassed to start the project a little further ahead.
ITKore consists of three parts:
* ITKore profile (https://github.com/aakb/itkore-profile)
* ITKore theme (https://github.com/aakb/itkore-theme)
* Drupal 8 core

ITKore profile is a drupal install profile which holds custom modules and depends on some contrib modules. The profile also alters some default core configuration.

ITKore theme is a Drupal theme which holds the bare minimum for a theme to work.

## How to use?

###Buliding a new drupal core site with composer
If you are building a new drupal site locally in an ITK vagrant it is recommended to run the script file located at vagrant/base/scripts/setup-drupal8.sh.
The setup script will use composer to fetch the itkore-theme repo, itkore-profile repo and drupal core.
The script will use composer to fetch the required contrib modules and place them in the htdocs/modules folder

A local setup flow would be:

* Go to vagrant repo

* Create new local site:
```
./run.sh
```

* Run Drupal 8 setup script:
```
./base/scripts/setup-drupal8.sh
```

* Start up vagrant
* Setup site with drush or from browser


### ITKore profile as a seperate installation
It's possible to grab the install profile and attach it to any drupal 8 site. The profile depends on a group of contrib modules see itkore.info.yml

##Modules

###ITKore
ITKore contains the following modules

* ITKore admin
   * Provides an admin interface for site specific config settings
* ITKore content types
   * Provides four content types
      1. News
      2. Page
      3. Overview page
* ITKore fields
   * Holds field definitions
* ITKore text filters
   * Sets up text filters
* ITKore user roles
   * Sets up user roles and default permissions
* ITKore user theme
   * Sets up login form to use admin theme
* ITKore default content
   * Sets up cookie page and 404.
* ITKore fields
   * Sets up nodes with default fields

###Other
To support the build som basic drupal contrib and custom ITK modules are added.

* ITK cookie message
   * Provides cookie warning
* ITK paragraph
   * Provides default paragraph types commonly used by ITK
* ITK admin links
   * Provides admin links as a block
* Ctools
* Entity reference revisions
* Field group
* Imce
* Metatag
* Paragraphs
* Pathauto
* Paragraphs
* Redirect
* Role delegation
* Token
* Toolbar visibility
* Youtube field
