#!/bin/bash
#
# Renames `plugin-name/` folder and corresponding scripts to something new.
##

# A better class of script...
set -o errexit    # Exit on most errors (see the manual "man bash:/set \[")
set -o nounset    # Disallow expansion of unset variables
set -o pipefail   # Use last non-zero exit code in a pipeline

# DESC: Parameter parser
# ARGS: $1 (required): Folder path to process
#       $@ (optional): Arguments provided to the script
# OUTS: Variables indicating command-line parameters and options
function script::parse_params() {
  var_folder_path=${1}
  shift

  local param
  while [[ "$#" -gt 0 ]]; do
    param="${1}"
    case "${param}" in
      -vv | --debug)
        var_debug=1
        var_trace=1
        shift 1 ;;
      -v | --verbose)
        var_debug=1
        shift 1 ;;
      --name | -n)
        var_string_replace="${2}"
        shift 2
        ;;
      --find | -f)
        var_string_find="${2}"
        shift 2
        ;;
      --help | -h)
        var_trigger_help=1
        shift 1
        ;;
      *)
        script::log_debug "Unrecognized input: \"${1}\""
        shift ;;
    esac
  done
}

# DESC: Usage help
# ARGS: None
# OUTS: Writes help message to stdout
function script::help(){
  local verbose=$( if script::is_debug;then echo 'True';else echo 'False';fi)
  local stack_trace=$( if script::is_stack_trace;then echo 'True';else echo 'False';fi)
  local relative_script_path=$( script::get_relative_path "${BASH_SOURCE[0]}" )
  cat <<HELPMSG
Usage: ${relative_script_path} <FOLDER_PATH> [OPTIONS]...

Options:
    -v,  --verbose   Turn on additional log information (Value: ${verbose})
    -vv, --debug     Turn on stack trace information (Value: ${stack_trace})
    -n,  --name      New name of folder and classes (Value: "${var_string_replace}")
    -f,  --find      Target name to replace (Value: "${var_string_find}")
    -h,  --help      Display this message

Arguments:
    FOLDER_PATH: Folder location to find/replace
HELPMSG
}

# DESC: Generic script initialisation
# ARGS: $@ (optional): Arguments provided to the script
# OUTS: Many variables needed for the script
function script::init() {
  ## Wrappers for used applications ##
  PROGRAM_BASH="/usr/bin/env bash"
  PROGRAM_MOVE="/usr/bin/env mv"
  PROGRAM_REPLACE="/usr/bin/env sed"
  PROGRAM_TRANSLATE="/usr/bin/env tr"
  PROGRAM_PROCESS="/usr/bin/env awk"
  PROGRAM_FIND="/usr/bin/env find"

  ## Constants that will not change during runtime ##
  readonly dir_project_root=$(script::get_parent_directory 2)
  readonly dir_build_root="${dir_project_root}/build"

  ## App specific variables ##
  var_debug=0
  var_trace=0
  var_trigger_help=0
  var_folder_path=""
  var_string_find="plugin-name"
  var_string_replace="stream-agg"
}

function script::environment_check() {
  # Check if programs are available
  for program_name in ${!PROGRAM_*}; do
    script::application_check "${!program_name}"
  done

  # Stop if target directory not found.
  if [[ ! -d "${var_folder_path}" ]]; then
    script::log_error "${var_folder_path} not a directory"
    exit 1
  fi

  ## Wrapper Commands to be used by internal functions ##
  readonly cmd_bash="${PROGRAM_BASH}"
  readonly cmd_replace="${PROGRAM_REPLACE}"
  readonly cmd_translate="${PROGRAM_TRANSLATE}"
  readonly cmd_process="${PROGRAM_PROCESS}"
  readonly cmd_find="${PROGRAM_FIND} -P"

  if script::is_debug; then
    readonly cmd_move="${PROGRAM_MOVE} -n -v"
  else
    readonly cmd_move="${PROGRAM_MOVE} -n"
  fi

  # Enable xtrace if the DEBUG environment variable is set
  if script::is_stack_trace; then
      set -o xtrace # Trace the execution of the script (debug)
  fi
}

# DESC: Check if debug is active
# ARGS: None
# OUTS: true, if `${var_debug}` is true
function script::is_debug() {
  [[ ${var_debug} =~ ^1|yes|true$ ]]
}

# DESC: Check if xtrace is active
# ARGS: None
# OUTS: true, if `${var_trace}` is true
function script::is_stack_trace() {
  [[ ${var_trace} =~ ^1|yes|true$ ]]
}

# DESC: Check if help text is needed
# ARGS: None
# OUTS: true, if `${var_trigger_help}` is true
function script::need_help() {
  [[ ${var_trigger_help} =~ ^1|yes|true$ ]]
}

# DESC: Gets relative path of directory to root
# ARGS: $1 (required): Absolute path
#       $2 (optional): Root path (default: $dir_project_root)
# OUTS: Prints relative path
function script::get_relative_path() {
  local absolute_path="${1}"
  local root_path="${2:-$dir_project_root}"
  printf '%s' "${absolute_path/#$root_path/\.}"
}

# DESC: Gets absolute path relative to the script's path.
# ARGS: $1 (optional): Integer represents how many directories to traverse up the parent directory (default: 0)
# OUTS: Prints full path
function script::get_parent_directory() {
  local levels=${1};
  local full_path=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
  if [ "${levels}" -gt 1 ]; then
    for i in $(seq 2 ${levels}); do
      full_path=$(dirname -- "${full_path}");
    done;
  fi;
  printf '%s' "${full_path}"
}

# DESC: Check if application is available. Exit if not
# ARGS: $1 (required): Command to check if available
# OUTS: None
function script::application_check() {
  local var_application="${1}"
  if ! command -v ${var_application} &> /dev/null
  then
    script::log_error "Command \"${var_application}\" could not be found. Aborting."
    exit 1
  fi
}

# DESC: prints all arguments on the standard output stream
# ARGS: $* (optional): Message string provided to the script
# OUTS: Writes message to stdout
function script::log() {
  printf 'ℹ️ \e[0;34m%s\e[0m\n' "${*}"
}

# DESC: Prints all arguments on the standard output stream, if debug output is enabled
# ARGS: $* (optional): Message string provided to the script
# OUTS: Writes message to stdout if `${var_debug}` is true
function script::log_debug() {
  if script::is_debug;then printf '🪲 \e[0;36m%s\e[0m\n' "${*}";fi
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $* (optional): Message string provided to the script
# OUTS: Writes message to stderr
function script::log_error() {
  printf '🔥 \e[0;31m%s\e[0m\n' "${*}" 1>&2
}

# DESC: Process string to all uppercase
# ARGS: $1 (required): string to convert
# OUTS: string
function script::string_to_upper() {
  printf '%s' "${1}" | ${cmd_translate} '[:lower:]' '[:upper:]'
}

# DESC: Process string to all lowercase
# ARGS: $1 (required): string to convert
# OUTS: string
function script::string_to_lower() {
  printf '%s' "${1}" | ${cmd_translate} '[:upper:]' '[:lower:]'
}

# DESC: Capitalize string
# ARGS: $1 (required): string to convert
# OUTS: string
function script::string_to_capitalize() {
  printf '%s' "${1}" | ${cmd_process} 'BEGIN{FS=OFS="_"} {for(i=1;i<=NF;i++){ $i=toupper(substr($i,1,1)) substr($i,2) }}1'
}

# DESC: Replace dash '-' character to underscore '_'
# ARGS: $1 (required): string to convert
# OUTS: string
function script::dash_to_underscore() {
  printf '%s' "${1//-/_}"
}

function script::setup_build_folder() {
  local new_folder="${dir_build_root}/${var_string_replace}"

  # Check build root
  if [[ ! -d "${dir_build_root}" ]]; then
    script::log_debug "Make build directory [\"${dir_build_root}\"]"
    mkdir "${dir_build_root}"
  else
    script::log_debug "Build directory [\"${dir_build_root}\"]"
  fi

  if [[ ! -d "${new_folder}" ]]; then
    script::log_debug "Make target folder [\"${new_folder}\"]"
    mkdir "${new_folder}"
  else
    script::log_debug "Working folder [\"${new_folder}\"]"
    rm -rf "${new_folder}"
    mkdir "${new_folder}"
  fi

  cp -R -P "${var_folder_path}" "${new_folder}/"

  var_folder_path="${new_folder}"
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::process_files() {
  script::log_debug "Rename files..."

  local find=${1};
  local replace=${2};
  local target_directory="${3:-${var_folder_path}}";

  script::log_debug "\$find: \"${find}\""
  script::log_debug "\$replace: \"${replace}\""
  script::log_debug "\$directory: \"${target_directory}\""

  #/usr/bin/find "$PLUGIN_DIRECTORY" -type f -name '*plugin-name*' -exec bash -c 'mv $0 ${0/plugin-name/stream-agg}' {} \;
  ${cmd_find} "${target_directory}" \
    -type f \
    -name "*${find}*" \
    -exec ${cmd_bash} -c '${0} ${1} ${1/${2}/${3}}' "${cmd_move}" "{}" "${find}" "${replace}" \;

  script::log "Rename files complete."
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::process_lowercase() {
  script::log_debug "Rename lowercase..."

  local find="$( script::string_to_lower ${1} )"
  local replace="$( script::string_to_lower ${2} )"
  local target_directory="${3:-${var_folder_path}}";

  script::find_replace "${find}" "${replace}" "${target_directory}"
  script::log "Rename lowercase complete."
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::process_underscore() {
  script::log_debug "Rename _underscore_..."

  local find="$( script::dash_to_underscore "${1}" )"
  local replace="$( script::dash_to_underscore "${2}" )"
  local target_directory="${3:-${var_folder_path}}";

  script::find_replace "activate_${find}" "activate_${replace}" "${target_directory}"
  script::find_replace "run_${find}" "run_${replace}" "${target_directory}"
  script::find_replace "'${find}'" "'${replace}'" "${target_directory}"
  script::log "Rename _underscore_ complete."
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::process_uppercase() {
  script::log_debug "Rename UPPERCASE..."

  local find_to_underscore="$( script::dash_to_underscore "${1}" )"
  local replace_to_underscore="$( script::dash_to_underscore "${2}" )"
  local target_directory="${3:-${var_folder_path}}";

  local find="$( script::string_to_upper "${find_to_underscore}" )"
  local replace="$( script::string_to_upper "${replace_to_underscore}" )"

  script::find_replace "${find}_" "${replace}_" "${target_directory}"
  script::log "Rename UPPERCASE complete."
}

# DESC: Prints all arguments on the standard error stream
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::process_capitalization() {
  script::log_debug "Rename Capitalize..."

  local find_to_underscore="$( script::dash_to_underscore "${1}" )"
  local replace_to_underscore="$( script::dash_to_underscore "${2}" )"
  local target_directory="${3:-${var_folder_path}}";

  local find_to_lower_underscore="$( script::string_to_lower "${find_to_underscore}" )"
  local replace_to_lower_underscore="$( script::string_to_lower "${replace_to_underscore}" )"

  local find="$( script::string_to_capitalize "${find_to_lower_underscore}" )"
  local replace="$( script::string_to_capitalize "${replace_to_lower_underscore}" )"

  script::find_replace "${find}" "${replace}" "${target_directory}"
  script::log "Rename Capitalize complete."
}

# DESC: Run find and replace strings inside directory
# ARGS: $1 (required): string to find
#       $2 (required): string to replace find with
#       $3 (optional): Target path (default: $var_folder_path)
# OUTS: None
function script::find_replace() {
  local find=${1};
  local replace=${2};
  local target_directory="${3:-${var_folder_path}}";

  script::log_debug "\$find <\"${find}\"> and \$replace <\"${replace}\"> in \$directory <\"${target_directory}\">"

  ${cmd_find} "${target_directory}" \
    -type f \
    -exec ${cmd_bash} -c '${0} -i "" -e "s/${2}/${3}/g" "${1}"' "${cmd_replace}" "{}" "${find}" "${replace}" \;
}


# DESC: Main control flow
# ARGS: $@ (optional): Arguments provided to the script
# OUTS: None
function main() {
  script::init "$@"
  script::parse_params "$@"
  script::environment_check

  # Print help message and exit if flagged
  if script::need_help; then
      script::help
      exit 0
  fi

  script::setup_build_folder

  script::log "Running process in directory: ${var_folder_path}"
  script::log_debug "find: \"${var_string_find}\" -> \"${var_string_replace}\""

  script::process_files "${var_string_find}" "${var_string_replace}"
  script::process_lowercase "${var_string_find}" "${var_string_replace}"
  script::process_underscore "${var_string_find}" "${var_string_replace}"
  script::process_uppercase "${var_string_find}" "${var_string_replace}"
  script::process_capitalization "${var_string_find}" "${var_string_replace}"
}

### Runtime ###
main "$@"
