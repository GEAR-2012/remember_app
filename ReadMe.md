# Todo app with PHP & mySql database

## Application called 'Remember' can authenticate multiple users & handle multiple task list.

### back-end writen in PHP
### database mySQL
### front-end JavaScript

## On resiter page
Users can be insert into the database if their username is unique.
In the users table can not be two same username.
So multiple users can share the same email address.

## On login page
User can login with username or email address.

## On task list collection page
Task list can be deleted only if empty.
Task list can be opened by cliking on its name.

## On task list page
Task list name can be edited by clicking on its name.
Task list item can be edited by clicking on its name.
Task list item can be checked or unchecked by clicking on the icon in front of it.
Task list item can be deleted by clicking on the icon on the end of it.

Underneath the task list there are four buttons:
- undo
- redo
- list uncheck
- list delete
Those are self-explanatory.

Next to the tasklist name there is a menu button.
Underneath the menu button in the menu are more features:
- filtering
- sorting
- reverse sorting

By clicking on the 'save & back' button the edited list will be updated in the database

Cookies also used to remember the user for 10 days

