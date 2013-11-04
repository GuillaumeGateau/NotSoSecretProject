# deploy.rb
#ssh_options[:forward_agent] = true
default_run_options[:pty] = true

set :application, "app-kpi-v2"
set :repository, "git@github.com:smartdate/app-kpi-v2.git"
set :deploy_to, "/var/www/app-kpi"
set :current_path, "#{deploy_to}/current"
set :releases_path, "#{deploy_to}/releases"
set :shared_path, "#{deploy_to}/shared"

set :user, "deploy"
set :branch, "production"
set :git_enable_submodules, 1
set :use_sudo, false
ssh_options[:paranoid] = false

set :scm, :git

role :web, "kpi.smartdate.com:22"
role :app, "kpi.smartdate.com:22"

set :ssh_options, {:forward_agent => true}
on :start do    
  `ssh-add`
end

#namespace :deploy do
#  task :restart do
#  end
#end

namespace:deploy do
    task:start do
    end
    task:stop do
    end
    task:finalize_update do
        run "chmod -R g+w #{release_path}"
    end
    task:restart do
    end
   after "deploy:restart" do
         #add any tasks in here that you want to run after the project is deployed
         run "rm -rf #{release_path}.git"
   end
end