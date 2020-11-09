# Mautic-Custom-Navlinks
 
Mautic Plugin that allows you to create custom navigation items 

<h2>V1.0 Feature Walkthrough</h2>
https://www.youtube.com/embed/Ya48-VxUvD8

<h2>Features</h2>
- Database driven navigation assembly
- Admin and Primary navigation placement with order
- Display within Mautic iframe or open in a new browser tab

<h2>Limitations</h2>
- Plugin activation is automatic, havent figured out how the plugin enable works
- individual nav items currently do not respect the ability to enable/disable
- No ability currently to control visibility by role, so turns on for all

<h2>Instructions</h2>
- Copy folder to the plugin directory
- Rename the plugin directory folder to "CustomNavigationLinksBundle"
- Make sure you have full read/write access to the folder (the plugin will be rebuilding config files in the directory)
- Install Plugin
- Clear cache
- Navigate to plugin via admin menu /s/navlinks/1
- Create new
 - Select Location (Admin/Primary)
 - Label of the link
 - URL of the link
 - Nav type (_blank, iframe)
 - Save and Close
 - Clear Cache (This will rebuild the config files and update everything)

<h2>Torn Marketing</h2>
Shared with love from the Torn Marketing Team
https://tornmarketing.com.au

<h2>Useful Links</h2>
https://mauteam.org/mautic/mautic-admins/mautic-cron-jobs-for-dummies-marketers-too/

https://github.com/virgilwashere/mautic-cron-commands
