# ![moodle-mod_groupmembers](pix/icon.png) Resource Module: Group Members 
[![Moodle Plugin CI](https://github.com/learnweb/moodle-mod_groupmembers/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/learnweb/moodle-mod_groupmembers/actions/workflows/moodle-ci.yml)

This plugin adds a resource module that shows all groups, all groups of a predefined grouping, or all groups that someone is enrolled in.
Optionally, it displays email addresses of other members of one's group.
In that, it respects a user's decision not to reveal his/her email address.

This plugin is written by [Dennis Riehle](https://github.com/driehle) and [Jan Dageförde](https://github.com/Dagefoerde).

## Screenshots
This module can be added as a regular course module. That way, it fits neatly into course contents and can be displayed at any
point in the course area that a teacher deems appropriate. The teacher chooses
 * which grouping to use (or none, in which case all groups of a course could be displayed)
 * which groups to show (only ones in which a participant is also enrolled, i.e. their "own" group, or all groups)
 * whether email addresses should be displayed in order to facilitate communication (can be limited to "own" group only)

![Add module](https://cloud.githubusercontent.com/assets/432117/25745551/b0701b78-319f-11e7-8c73-5359e4180ff2.png)

Afterwards, participants can see a list of groups and their respective members, according to the configuration made by the teacher.
Furthermore, if the messaging system is enabled on the site and the user is allowed to use it, a :envelope: messaging button is displayed right next to the user.
This is expected to facilitate communication within the group.

The screenshot (Theme Boost) shows a configuration that displays members of all groups, but shows emails only for the own group. The viewing user is enrolled in Group B.
You can also see that one user chose not to reveal his/her email address, which is made explicit to avoid confusion.

![Module view on Boost](https://cloud.githubusercontent.com/assets/432117/25745549/b06e813c-319f-11e7-8a6d-6e53b305f952.png)

It also plays nice with other themes (Theme Clean):
 
![Module view on Clean](https://cloud.githubusercontent.com/assets/432117/25745550/b06f3726-319f-11e7-9194-5080effe2ce3.png)

## Feedback and pull requests welcome

Thanks for trying out this plugin, we hope you enjoy it. Please feel free to contact us by reporting issues or feature requests on GitHub.
We are also looking forward to reviewing incoming pull requests!

:heart:




