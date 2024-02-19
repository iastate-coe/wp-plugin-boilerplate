#!/bin/bash

PLUGIN_DIRECTORY=$1
shift

APP_BASH=bash
APP_MV=mv
APP_SED=sed
APP_TR=tr
APP_AWK=awk
APP_FIND=find

SEARCH_STRING=plugin-name
NEW_STRING=stream-agg

### Process User Input ###
while [ ! $# -eq 0 ]; do
  case "$1" in
  --name | -n)
    NEW_STRING="$2"
    shift 2
    ;;
  --find | -f)
    SEARCH_STRING="$2"
    shift 2
    ;;
  esac
done

if [[ ! -d "$PLUGIN_DIRECTORY" ]]; then
    echo "$PLUGIN_DIRECTORY not a directory"
    exit 1
fi

#/usr/bin/find "$PLUGIN_DIRECTORY" -type f -name '*plugin-name*' -exec bash -c 'mv $0 ${0/plugin-name/stream-agg}' {} \;

"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -name "*$SEARCH_STRING*" -exec "$APP_BASH" -c '$0 -n -v $1 ${1/${2}/${3}}' "$APP_MV" '{}' "${SEARCH_STRING}" "${NEW_STRING}" \;

echo 'Lowercase:'

SEARCH_STRING_LOWER_CASE=$( echo "${SEARCH_STRING}" | "${APP_TR}" '[:upper:]' '[:lower:]' )
NEW_STRING_LOWER_CASE=$( echo "${NEW_STRING}" | "${APP_TR}" '[:upper:]' '[:lower:]' )

echo "$SEARCH_STRING_LOWER_CASE -> $NEW_STRING_LOWER_CASE"
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "${SEARCH_STRING_LOWER_CASE}" "${NEW_STRING_LOWER_CASE}" \;

echo 'Underscore:'

SEARCH_STRING_UNDERSCORE=${SEARCH_STRING_LOWER_CASE//-/_}
NEW_STRING_UNDERSCORE=${NEW_STRING_LOWER_CASE//-/_}

echo "$SEARCH_STRING_UNDERSCORE -> $NEW_STRING_UNDERSCORE"
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "activate_${SEARCH_STRING_UNDERSCORE}" "activate_${NEW_STRING_UNDERSCORE}" \;
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "run_${SEARCH_STRING_UNDERSCORE}" "run_${NEW_STRING_UNDERSCORE}" \;
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "'${SEARCH_STRING_UNDERSCORE}'" "'${NEW_STRING_UNDERSCORE}'" \;

echo 'Uppercase:'

SEARCH_STRING_UNDERSCORE_UPPER_CASE=$( echo "${SEARCH_STRING_UNDERSCORE}" | "${APP_TR}" '[:lower:]' '[:upper:]' )
NEW_STRING_UNDERSCORE_UPPER_CASE=$( echo "${NEW_STRING_UNDERSCORE}" | "${APP_TR}" '[:lower:]' '[:upper:]' )

echo "${SEARCH_STRING_UNDERSCORE_UPPER_CASE}_ -> ${NEW_STRING_UNDERSCORE_UPPER_CASE}_"
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "${SEARCH_STRING_UNDERSCORE_UPPER_CASE}_" "${NEW_STRING_UNDERSCORE_UPPER_CASE}_" \;

echo "Capitalize: "

SEARCH_STRING_UNDERSCORE_CAPITALIZED=$( echo "${SEARCH_STRING_UNDERSCORE=${SEARCH_STRING_LOWER_CASE//-/_}
}" | "$APP_AWK" 'BEGIN{FS=OFS="_"} {for(i=1;i<=NF;i++){ $i=toupper(substr($i,1,1)) substr($i,2) }}1' )
NEW_STRING_UNDERSCORE_CAPITALIZED=$( echo "${NEW_STRING_UNDERSCORE}" | "$APP_AWK" 'BEGIN{FS=OFS="_"} {for(i=1;i<=NF;i++){ $i=toupper(substr($i,1,1)) substr($i,2) }}1' )

echo "$SEARCH_STRING_UNDERSCORE_CAPITALIZED -> $NEW_STRING_UNDERSCORE_CAPITALIZED"
"$APP_FIND" "$PLUGIN_DIRECTORY" -type f -exec "$APP_BASH" -c '"$0" -i "" -e "s/${2}/${3}/g" "$1"' "$APP_SED" '{}' "${SEARCH_STRING_UNDERSCORE_CAPITALIZED}" "${NEW_STRING_UNDERSCORE_CAPITALIZED}" \;
