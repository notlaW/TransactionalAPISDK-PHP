#!/bin/bash

#------------------------------------------------------------------------------
set -o pipefail
set -e
#------------------------------------------------------------------------------

#------------------------------------------------------------------------------
__here="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
#------------------------------------------------------------------------------

function bold {
    tput setaf 6
    tput bold
    echo -e "$1"
    tput sgr0
}

bold "----> Testing against PHP 5.6 <----"
docker run \
    --volume $__here/../:/tests \
    --workdir="/tests" \
    php:5.6-cli-alpine3.4 \
    ./vendor/bin/phpunit --verbose --debug --filter=Integration --testsuite=integration


