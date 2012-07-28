<?php

#=================================================
# MySQL variables
#=================================================
global $gDb;
global $gDbControl;
global $gDbEEdge;
global $gNumRows;
global $gResult;
global $gMysqlHost;
global $gMysqlUser;
global $gMysqlPass;
global $gMysqlDbname;
global $gMysqlSuffix;

#=================================================
# Control DB variables
#=================================================
global $gLevel;   # level of current user
global $gLevelEnabled;
global $gLevelIdToLevel;
global $gLevelIdToName;
global $gLevelNameToVal;
global $gLevelToName;
global $gLevels;  # array of records
global $gPrivileges;  # array of user_privileges
global $gUsers;      # array of all users
global $gUser;    # current user record
global $gUserEnabled;
global $gUserId;
global $gUserVerified;

#=================================================
# Common Library variables
#=================================================
global $gLogoImage;
global $gLogoURL;
global $gMailSetup;
global $gMailSite;  # location designator - see local-mail-setup.php
global $gMessage1;
global $gMessage2;
global $gSiteName;  # parenthetical on Browser tab
global $gSupport;
global $gTitle;

#=================================================
# General variables
#=================================================
global $gAction;
global $gActionLeft;
global $gActionRight;
global $gDebug;
global $gFrom;
global $gFunction;
global $gManager;
global $gSourceCode;
global $gTrace;

?>