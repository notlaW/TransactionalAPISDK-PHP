test:
	vendor/bin/phpunit --testsuite=unit $(TEST)

package:
	php build/packager.php
