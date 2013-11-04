set :application, "app-kpi-v2"
set :repository,  "git@github.com:smartdate/app-kpi-v2.git"

set :scm, "git"
set :user, "deploy"  # The server's user for deploys
#set :scm_passphrase, "p@ssw0rd"  # The deploy user's password

ssh_options[:forward_agent] = true
set :branch, "production"

set :deploy_via, :remote_cache

# Or: `accurev`, `bzr`, `cvs`, `darcs`, `git`, `mercurial`, `perforce`, `subversion` or `none`

role :web, "kpi.smartdate.com"                          # Your HTTP server, Apache/etc
role :app, "kpi.smartdate.com"                          # This may be the same as your `Web` server
#role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
#role :db,  "your slave db-server here"

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