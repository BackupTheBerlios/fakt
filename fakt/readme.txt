FAKT - Free Authoring Knowledge and Thinking

Preliminary documentation

General
=======
The FAKT system is a Forums and guides system written in PHP and uses MySQL
as a database to store messages and guides.
The main features of the forums systems are:
1. Messages are opened in the same page
2. Messages are hierarchical
3. A user can edit messages that he/she has written.

License
======
The FAKT system is distributed under the GNU General Public License (GPL).
This means that any user may copy the system for his/her own use, 
and make changes to adapt it for his/her purposes.
Any user that has enhanced the software may redistribute it,
however it must remain under the same license.
Any user enhancing the software is encouraged to send it back to me so I will
add the enhancements to future releases of the software.
Any copy of the software must carry the original copyright notice along with
the copyright notice of the enhancement.

Installation
==========
In order to install the FAKT system follow the following procedure:

1. Copy all files to one directory in your web site
2. Edit the config.inc file with the necessary parameters,
   see comments inside the file
3. In mysql create database in the same name as the $database variable in the
   configuration file.
4. Grant all privileges to the username as set in $user parameter of the
   configuration file. 
   This can be done using the command "GRANT ALL PRIVILEGES ON database.* TO user;";
    where database is the database name as set in $database variable of the configuration file
    user is the user name as set in $user variable in the configuration file.
5. 
   a. To generate tables needed for the forums system, 
      Run the forumadmin.php script, this will check the database existance
      and create the basic tables for the forums system.
   b. To generate tables needed for the guides system,
      Run the guidescreate.php script.
6. add a user named 'admin' this user is the administrator of all forums
   and has administration privileges on all forums even if not listed as forum manager


*************** End of documentation *******************
The following is non updated information on the structures of the
tables I would be happy if someone continues it.

Database structure
===============
The program uses one database (the name is stored in the $database variable in the config.inc file)
This database contains 3 types of tables:
		1. Login names table (login)
		2. Main forums list (mainlist)
		3. Forums tables

login table structure:
	
	Field	  	 Type			Comments
	=====       	====			=========
	name		varchar(30)		Unique user login name
	fullname		varchar(50)
	email      	varchar(40)
	password 	varchar(15)
	lastonline 	timestamp		last login time, used to validate user login against value stored in
	 							user computer (cookie).
	pubemail	varchar(40)		Published email that will be shown in user details
	web			varchar(60)		user web site
	messangernum varchar(15)		Instant messanger software user number
	messangersoft varchar(15)		Instant messanger software used
	birthdate	date
	sex			enum('female', 'male')
	occupation	varchar(30)
	interest		varchar(60)
	signature	varchar(255)
	comments	text
	picture		varchar(50)		Not used in this version, should be picture file name

mainlist table structure:

	Field		Type			Comments
	====		====			=========
	forum		varchar(30)		Forum table name no spaces allowed since this is a file name
	category	varchar(30)		Forum category
	forum_title	varchar(80)		title of the forum
	manager		varchar(80)		comma seperated list of forum managers (login names)
	tag_line		varchar(255)		text that will appear below forum title on forum  page
								(see note 1)
	description	text				forum description to be shown when the user clicks the forum title

note 1: The tag_line was intended to be a scrolling text like the MSIE marquee tag, however
since I want the system to be compatible with all browsers, I can't use this tag.
I would appriciate if someone implement this mechanism in JavaScript and send me the modified
files.

forum table structure:

	Field		Type					Comments
	====		====					========
	num			integer auto_inrement	Message number
	ancestor		integer					If this message is an answer to a message, what message
										number does it answer.
	date		date					message date
	time		time					message time
	name		varchar(30)				login name of message author
	contents		text						Text of the message.
	urldesc1		varchar(40)
	url1			varchar(80)
	urldesc2		varchar(40)
	url2			varchar(80)
	urldesc3		varchar(40)
	url3			varchar(80)
