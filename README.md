# contacts

1. Clone project from https://github.com/dtarxanyan/contacts.git

2. Create virtual host

examplle

<VirtualHost *:80>
	DocumentRoot "C:/xampp/htdocs/contacts"
	ServerName contacts.local
</VirtualHost>

127.0.0.1 contacts.local

3. Import demo database located at contacts/dt_contacts.sql

4. composer update

5. to change db configuration or base url go to contacts/App/config.php

