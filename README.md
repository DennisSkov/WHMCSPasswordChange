# Password Change for WHMCS
This module allows you to change the passwords of users in WHMCS. 
It also allows you to send the password to the client via email.

A lot of people complained about the removal of the "Change Password" feature by WHMCS - this simple module implements it.

## Limitations
Password change is only possible for the account owner. You are not able to reset passwords for users associated with
an account at the moment.

## Requirements
* WHMCS 8.x (only tested on 8.7)
  * Could possibly work with older versions of WHMCS

## Installation
1. Download the repository to your WHMCS installation 
```shell
$ cd /path/to/whmcs/modules/addons
$ git clone https://github.com/DennisSkov/WHMCSPasswordChange.git PasswordChange
```
2. Go to *WHMCS Admin > System Settings > Addon Modules* and enable the module.
3. Click "Configure" next to the module and allow your admin roles access to change the password

## Disclaimer
This module is provided as-is, with no warranty and no promise of support or updates.
