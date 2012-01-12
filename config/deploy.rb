set :stages, %w(production staging)
set :default_stage, "staging"
require 'capistrano/ext/multistage'


set :application, "aluma"
set :repository,  "https://subversion.lib.virginia.edu/repos/alumna/trunk"

set :deploy_to, "/usr/local/projects/#{application}/"
set :deploy_via, :remote_cache
set :user, 'sds-deployer'
set :runner, user
set :run_method, :run

default_run_options[:pty] = true
ssh_options[:username] = user
ssh_options[:host_key] = 'ssh-dss'
ssh_options[:paranoid] = false


set :scm, :subversion
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `git`, `mercurial`, `perforce`, `subversion` or `none`


# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
