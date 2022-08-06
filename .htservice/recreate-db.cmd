REM Recreate the database.

echo "Recreating the database..."

echo "Drop database..." && mysql -h localhost -u test_work_db -p20IgodJ00eTY83D7 < database-drop.sql
echo "Create database..." && mysql -h localhost -u test_work_db -p20IgodJ00eTY83D7 < database-create.sql
echo "Import data..." && mysql -h localhost -u test_work_db -p20IgodJ00eTY83D7 < database-insert.sql

echo "Done."
