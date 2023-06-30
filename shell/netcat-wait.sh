#!/bin/sh

: ${SLEEP_LENGTH:=2}
: ${TIMEOUT_LENGTH:=30}

wait_for() {
  START=$(date +%s)
  
  while ! nc -z $1 $2 &>/dev/null;
    do
    echo "Waiting for $1 to listen on $2..."
    if [ $(($(date +%s) - $START)) -gt $TIMEOUT_LENGTH ]; then
        echo "Service $1:$2 did not start within $TIMEOUT_LENGTH seconds. Aborting..."
        exit 1
    fi
    sleep $SLEEP_LENGTH
  done
}

for var in "$@"
do
  host=${var%:*}
  port=${var#*:}
  wait_for $host $port
done