# Script to package both extensions
# By: Zach Curtis - Wayin, Inc

TMPFOLDER=PATH TO MY LOCAL JOOMLA tmp FOLDER

rm com_allplayers-v0.0.1.zip 
zip -r --exclude=*.git* --exclude=*.DS_Store* com_allplayers-v0.0.1.zip ./com_allplayers

#rm com_allplayers_auth-v1.0.zip 
#zip -r --exclude=*.git* --exclude=*.DS_Store* com_allplayers_auth-v1.0.zip ./com_allplayers_auth

#rm plugin_allplayers_auth-v1.0.zip
#zip -r --exclude=*.git* --exclude=*.DS_Store* plugin_allplayers_auth-v1.0.zip ./plugin_allplayers_auth

#rm plugin_allplayers_profile-v1.0.zip
#zip -r --exclude=*.git* --exclude=*.DS_Store* plugin_allplayers_profile-v1.0.zip ./plugin_allplayers_profile
#mv ./*.zip $TMPFOLDER