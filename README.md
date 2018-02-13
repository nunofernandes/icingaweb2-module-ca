# CA Module for Icinga Web 2

![Icinga Logo](https://www.icinga.com/wp-content/uploads/2014/06/icinga_logo.png)

1. [About](#about)
2. [Requirements](#requirements)
3. [License](#license)
4. [Getting Started](#getting-started)
5. [Contributing](#contributing)

## About

The Certificate Authority Module for Icinga Web 2 allows for the user to manage the
local Certification Authority.

## Requirements

* Icinga Web 2 version 2.5.1+
* PHP version 5.6.x or 7.x

## License

The CA Module for Icinga Web 2 is licensed under the terms of the GNU
General Public License Version 2, you will find a copy of this license in the
[COPYING](COPYING) file included in the source package.

## Getting Started

Nothing special. As with every Icinga Web 2 module, drop this to one of your
`modules` folders and make sure that the folder name is `ca`. Because the web
server doesn't run as icinga, we need to allow for the sudo operations:

vi /etc/sudoers.d/apache
	Cmnd_Alias      CA_CMDS = /usr/sbin/icinga2 ca list, /usr/sbin/icinga2 ca sign *
	Cmnd_Alias      APACHE_COMMANDS = CA_CMDS
	User_Alias      APACHEUSERS = apache

	Defaults:APACHEUSERS        !requiretty
	APACHEUSERS   ALL = (icinga) NOPASSWD: APACHE_COMMANDS

vi /etc/icingaweb2/modules/ca/config.ini
	[config]
	icinga2 = "/usr/sbin/icinga2"
	sudo = "/usr/bin/sudo"
	runas = "icinga"

## Screenshots

Sign Screenshot
![CA - Sign Menu](doc/screenshot/sign.png)

Module Informatio Screenshot
![CA - Module Info](doc/screenshot/module.png)

Module Config Screenshot
![CA - Module Config](doc/screenshot/config.png)

## Contributing

There are many ways to contribute to Icinga -- whether it be sending patches,
testing, reporting bugs, or reviewing and updating the documentation. Every
contribution is appreciated!
