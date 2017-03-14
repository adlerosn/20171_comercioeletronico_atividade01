#!/bin/sh

## https://github.com/LEDS/ledszeppellin/blob/master/runSonarQubeAnalysis.sh

# Exit on failure
set -e

# This assumes that the 2 following variables are defined:
# - SONAR_HOST_URL => should point to the public URL of the SQ server (e.g. for Nemo: https://nemo.sonarqube.org)
# - SONAR_TOKEN    => token of a user who has the "Execute Analysis" permission on the SQ server

installSonarQubeScanner() {
	export SONAR_SCANNER_HOME=$HOME/.sonar/sonar-scanner-2.6
	rm -rf $SONAR_SCANNER_HOME
	mkdir -p $SONAR_SCANNER_HOME
	curl -sSLo $HOME/.sonar/sonar-scanner.zip http://repo1.maven.org/maven2/org/sonarsource/scanner/cli/sonar-scanner-cli/2.6/sonar-scanner-cli-2.6.zip
	unzip $HOME/.sonar/sonar-scanner.zip -d $HOME/.sonar/
	rm $HOME/.sonar/sonar-scanner.zip
	export PATH=$SONAR_SCANNER_HOME/bin:$PATH
}

# Install the SonarQube Scanner
# TODO: Would be nice to have it pre-installed by Travis somehow
installSonarQubeScanner
# And set the related JVM options - this is where the size of the JVM can be increased if required (e.g. "-Xmx1G -Xms128m").
export SONAR_SCANNER_OPTS="-server"

# # And run the analysis
# # It assumes that there's a sonar-project.properties file at the root of the repo
# if [ "$TRAVIS_BRANCH" = "master" ] && [ "$TRAVIS_PULL_REQUEST" = "false" ]; then
# 	# => This will run a full analysis of the project and push results to the SonarQube server.
# 	#
# 	# Analysis is done only on master so that build of branches don't push analyses to the same project and therefore "pollute" the results
# 	echo "Starting analysis by SonarQube..."
# 	sonar-scanner \
# 		-Dsonar.host.url=$SONAR_HOST_URL \
# 		-Dsonar.login=$SONAR_TOKEN
# fi
