## Structure

Every Pull Script should use the same base file 'pull-data.sh'. To pull from another source just add a new bash-file that uses a new environment and forwards it tasks to the base pull script.

Normally it is enough to just have to 2 pull scripts. The first one pulls from live to dev01 and the second one from dev01 to local.