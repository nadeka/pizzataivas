cd ~/pizzataivas/

rsync -z -r app assets config lib sql vendor index.php composer.json ~/public_html/pizzataivas/

cd ~/public_html/pizzataivas/
php composer.phar dump-autoload
exit