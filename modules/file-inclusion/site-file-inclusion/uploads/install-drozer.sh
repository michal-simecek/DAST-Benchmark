#!/bin/sh

# https://labs.mwrinfosecurity.com/tools/drozer/

# Script must be executed as root
if [ "$EUID" -ne 0 ]
	then echo "Please run as root"
	exit 1
fi

# download apk
wget -c "https://github.com/mwrlabs/drozer/releases/download/2.3.4/drozer-agent-2.3.4.apk"

# install docker container 
docker pull fsecurelabs/drozer

echo "---------------------------------------------------"
echo "Drozer should be installed. Run drozer with: "
echo "   docker run -it fsecurelabs/drozer" 
echo "   drozer console connect --server <phone IP address>" 
echo "---------------------------------------------------"
echo "Don't forget to install drozer-agent on your device: \"adb install drozer-agent-2.3.4.apk\" "
echo "---------------------------------------------------"



