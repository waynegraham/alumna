
# Loading Data

There are two ways to load the data: bootstrapping and using the data dump.

## Bootstrapping

To bootstrap the data from the raw files, just run `db_init.sql`. It needs to
be run from the `internal_documentation` directory.

If something happens and you need to restart from a fresh state, run
`del_db.sql`. This deletes the database and the users that where created.

## Data Dump

The data dump is in `alumna-dump.sql`. It's just the result of bootstrapping,
dumped to one file.

## Differences

The only real difference is that the data dump doesn't include the users.
`CREATE USER` and `GRANT` statements can be found near the top of
`db_init.sql`.


