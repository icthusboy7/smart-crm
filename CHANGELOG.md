#Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Task types forms ha a new preview button for formBuilder preview.

### Changed
- Task types has logic delete.

### Fixed
- Disable button in task types create and edit forms to prevent multiplicity.
- Removed id column in task types list table.
- Disable submit button in task form submit to prevent multiplicity.


## [0.0.6] - 2019-07-10
###Added
- CRM and Sonata admin:
    - Added "Alertas" frontend management:
        - Show alert information.
        - CRUD Alert.
        - Create custom user to find Alert.
    - Create a Service Alert.
- Maestros:
    - Added "MasterFamily" entity.
    - Added "MasterFamilySub" entity.
    - Added "MasterFamilyRel" entity.
    - Added "MasterQuotation" entity.
    - Added "MasterRequest" entity.
- Tasks:
    - Create Tasks.
    - CRUD Types for Tasks in backend.
    - CRUD Status for Tasks in backend.
- Expedientes:
    - Create expedientes.
    - Create cotizaciones.
    - Create disposiciones.
    - Edit expediente_padre.

### Changed
- Maestros:
  - Change "Office" to "MasterOffice" entity.
  - Change "Employee" to "MasterEmployee" entity.
  - Change "Customer" to "MasterCustomer" entity.
  - Change "Provider" to "MasterProvider" entity.
- DataReader:
  - Change file import function for each entity.

## [0.0.5.1] - 2019-04-24
### Added
- Added a function to change visits status to "Realizado"
- Added filters to calendar to show visits by status

### Changed
- Changed the list of contacts to add Customers and Providers to the list
- Changed the find form to be only 1 input
- Changed the import delimitator to text input

### Fixed
- Fixed the contact NIF/CIF validation to validate CIFs too.

### Security
- Updated jQuery version to 3.4.0 due to a minor security error ([more information](https://blog.jquery.com/2019/04/10/jquery-3-4-0-released/)).

## [0.0.5] - 2019-04-15
### Added
- Added "office" frontend management
  - Show office information
  - Create custom user to find office
  - Create visit with office information
- Added "contacts" frontend management
  - Create contact
  - Edit contact
  - Create visit with contact information
  - Delete contact
- Added "visits" frontend management using
  - Show visits using FullCalendarJS
  - Create visit
  - Show visit information
- Added documents upload screen

### Changed
- Changed the output information before executing de import command to be more understandable.

### Removed
- Delete the checkbox "client/provider" from users entity.

## [0.0.4] - 2019-04-01
### Added
- Notifications:
    - Added RabbitMQ server connection to manage the notification queues.
    - Added a flagNotify check to keep the notification unseen.
    - Added a function to set all notifications to seen when you open the notifications bar.
- Added CHANGELOG file.
- Maestros:
    - Added "office" entity.
    - Added "employee" entity.
    - Added "customer" entity.
    - Added "provider" entity.
    - Added "gestor horizontal" entity.
    - Added "gestor responsable" entity.
    - Added "commercial office" entity.
    - Added "commercial responsable" entity.
    - Added file import function for each entity.

### Changed
- Notification navigation bar now show all unseen notifications with a scroll.

### Fixed
- Fixed translations for login and register.
- Fixed username on left navigation bar.

## [0.0.3] - 2019-03-18
### Added
- Notifications:
    - Added a basic notification system to send a notification to user or group.
    - Added a frontend style for notifications.
- Translations:
    - Added translations files for Spanish, Catalan, English and Portuguese.
    - Added dropdown into navbar for language selection.
    - Added upload translations files.
    - Added backend for editing translations one by one.
- Maestros:
    - Added upload maestros files.
    - Added command to insert maestros into database.
    - Added a backend to manage maestros files and execute command.
- Added widget "attributes" column.
- Added input descriptions for CRUD.

### Changed
- Set mail as a environment parameter.
- Change sonata admin tables to collapse and optimize the view.
- Changed login and register style.
- Changed frontend style.

### Fixed
- Fix widgets entity mismatch.
- Check user role changes for every page loading.
- UpdatedAt function for Admin entity.

### Removed
- Widget "style" column.

## [0.0.2] - 2019-03-05
### Added
- Widgets:
  - Added widgets CRUD.
  - Added widgets custom order by user.
  - Added widgets style.
- Feedback after registration:
  - Added a message after registration.
- Admin entity:
  - Added Admin entity. 
  - Added SUPER_ADMIN role.
- Added user mail after activation.

### Changed
- User registration changed to NULL password by default.

### Fixed
- Fix Registration form max characters by input.
- Fix Registration unique username.

## [0.0.1] - 2019-02-15
### Added
- Login and register system:
  - Added login style.
  - Added register style.
  - Added Users CRUD.
  - Added LDAP Authentication.
- Mailing system:
  - Added mail after registration.
- Sonata admin backend.    
- README file.
- Deploy script.

[Unreleased]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.6...HEAD
[0.0.6]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.5.1...v0.0.6
[0.0.5.1]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.5...v0.0.5.1
[0.0.5]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.4...v.0.0.5
[0.0.4]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.3...v.0.0.4
[0.0.3]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.2...v.0.0.3
[0.0.2]: http://git.itteria.cat/PHP/CABK/smart-comerciales/compare/v0.0.1...v.0.0.2
[0.0.1]: http://git.itteria.cat/PHP/CABK/smart-comerciales/releases/v0.0.1
