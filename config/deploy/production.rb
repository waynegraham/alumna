set :app, "sds3.itc.virginia.edu"
set :app, "sds4.itc.virginia.edu", :no_release => true
set :web, "sds3.itc.virginia.edu", "sds4.itc.virginia.edu", :no_release => true
set :db,  "sds4.itc.virginia.edu", :primary => true, :no_release => true
