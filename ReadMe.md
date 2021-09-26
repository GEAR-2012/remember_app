#Todo app with PHP

##Application called Remember can authenticate multiple users & handle multiple task list.

###back-end writen in PHP
###database mySQL
###front-end JavaScript

##on resiter page
users can be insert into the database if their username is unique
in the users table can not be two same username
so multiple users can share the same email address

##on login page
user can login with username or email address

##on task list collection page
task list can be deleted only if empty
task list can be opened by cliking on its name

##on task list page
task list name can be edited by clicking on its name
task list item can be edited by clicking on its name
task list item can be checked or unchecked by clicking on the icon in front of it
task list item can be deleted by clicking on the icon on the end of it
underneath the task list there are two buttons:
the left button reset the full task list so all of them will be unchecked
the right button delete the full task list
by clicking on the 'save & back' button the edited list will updated in the database
if you clicked that button there is no way back
so if the list accidentally deleted just refresh the page to retrive back everything

