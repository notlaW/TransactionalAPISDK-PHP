test:
	./tests/run-unit-via-docker.sh

test-integration:
	./tests/run-integration-via-docker.sh

package:
	php build/packager.php
