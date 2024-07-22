#!/bin/sh

PHPCS_BIN="./vendor/bin/phpcs"
PHPSTAN="./vendor/bin/phpstan analyse --memory-limit=1G --no-progress --configuration phpstan.dist.neon"
PHPMD="./vendor/bin/phpmd"

ALL_FILES=$(git diff --name-only --diff-filter=AM HEAD | grep .php)

if [ "$ALL_FILES" != "" ]
then
    echo "[PRE-COMMIT] Checking PHPCS..."

    $PHPCS_BIN -p $ALL_FILES

    if [ $? != 0 ]
    then
        echo "[PRE-COMMIT] Coding standards errors have been detected."
        exit 1
    fi

    echo "[PRE-COMMIT] Checking modified files with phpstan..."

    $PHPSTAN $ALL_FILES

    if [ $? != 0 ]
    then
      echo "[PRE-COMMIT] phpstan failed"
      exit 1
    fi

        echo "[PRE-COMMIT] Checking code smells with phpmd..."

        printf -v command '%s %s' $ALL_FILES " text rulesets.xml"

        $PHPMD $command

        if [ $? != 0 ]
        then
          echo "[PRE-COMMIT] phpmd failed"
          exit 1
        fi
fi

exit $?
