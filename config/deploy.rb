set :stages, %w(production staging)
set :default_stage, "staging"
require 'capistrano/ext/multistage'


set :application, "alumna"
#set :repository,  "https://subversion.lib.virginia.edu/repos/alumna/trunk"
set :repository, 'git://github.com/waynegraham/alumna.git'
set :branch, "master"


set :deploy_to, "/usr/local/projects/#{application}/"
set :deploy_via, :remote_cache
set :user, 'sds-deployer'
set :runner, user
set :run_method, :run

default_run_options[:pty] = true
ssh_options[:username] = user
ssh_options[:host_key] = 'ssh-dss'
ssh_options[:paranoid] = false



#set :scm, :subversion
set :scm, :git


after 'deploy', 'deploy:db_symlink', 'deploy:cleanup'

namespace :deploy do
  task :start do ; end
  task :stop do ; end
  task :restart do ; end

  desc 'Symlink the database.ini file in the correct place'
  task :db_symlink, :except => {:no_release => true } do
    run "cd #{current_path}/config && ln -s #{shared_path}/database.ini"
  end

end


