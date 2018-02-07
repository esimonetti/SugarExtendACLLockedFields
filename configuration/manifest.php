<?php
$manifest['id'] = 'extend_acllockedfields';
$manifest['built_in_version'] = '7.10.0.0';
$manifest['name'] = 'Extend SugarACLLockedFields';
$manifest['description'] = 'Extend SugarACLLockedFields to only execute for modules with Advance Workflows enabled';
$manifest['author'] = 'Enrico Simonetti';
$manifest['acceptable_sugar_flavors'] = array('ENT', 'ULT');
$manifest['acceptable_sugar_versions']['regex_matches'] = array('^7.9.[\d]+.[\d]+$', '^7.10.[\d]+.[\d]+$');
