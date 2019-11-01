#!/bin/bash

echo "Ingrese el nombre de la app en modo CamelCase, seguido de [ENTER]:"

read NAME_CAMEL_CASE

if [ -z "$NAME_CAMEL_CASE" ]; then
	echo "Se necesita un nombre de App"
	exit 0
fi

NAME_UPPER=${NAME_CAMEL_CASE^^}
NAME_LOWER=${NAME_CAMEL_CASE,,}

echo "Nombres: $NAME_CAMEL_CASE, $NAME_UPPER, $NAME_LOWER"

git init

sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" www/index.php
sed -i "s/APP_LOWER/${NAME_LOWER}/g" www/index.php
sed -i "s/APP_UPPER/${NAME_UPPER}/g" www/index.php

# Inicializar Gatuf como submodulo git
git submodule add https://github.com/gatuno/Gatuf.git src/Gatuf

echo "src/${NAME_CAMEL_CASE}/conf/${NAME_LOWER}.php" > .gitignore
cd src

ln -s Gatuf/extracttemplates.php extracttemplates.php
ln -s Gatuf/Gatuf.php Gatuf.php
ln -s Gatuf/migrate.php migrate.php

echo "#!/bin/sh" > update_locale.sh
echo "APP=$NAME_CAMEL_CASE" >> update_locale.sh
echo "CONFFILE=$NAME_LOWER.php" >> update_locale.sh
echo "POTFILE=$NAME_LOWER.pot" >> update_locale.sh
echo "POFILE=$NAME_LOWER.po" >> update_locale.sh

echo "GATUF_PATH=\`php -r \"require_once('./\$APP/conf/path.php'); echo ${NAME_UPPER}_PATH;\"\`" >> update_locale.sh

cat >> update_locale.sh << 'EOF'
echo "php $GATUF_PATH/extracttemplates.php ./$APP/conf/$CONFFILE ./$APP/gettexttemplates"
echo "xgettext -o $POTFILE -p ./$APP/locale --force-po --from-code=UTF-8 --keyword --keyword=__ --keyword=_n:1,2 -L PHP ./$APP/*.php"
echo "find ./$APP/ -iname \"*.php\" -exec xgettext -o $POTFILE -p ./$APP/locale/ --from-code=UTF-8 -j --keyword --keyword=__ --keyword=_n:1,2 -L PHP {} \;"
echo "for pofile in \`ls ./$APP/locale/*/$POFILE\`; do msgmerge -U \$pofile ./$APP/locale/$POTFILE; done"
echo "rm -R ./$APP/gettexttemplates"

EOF

cd AppName

cd conf

sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" appname.php
sed -i "s/APP_LOWER/${NAME_LOWER}/g" appname.php
sed -i "s/APP_UPPER/${NAME_UPPER}/g" appname.php

mv appname.php ${NAME_LOWER}.php.dist

sed -i "s/APP_UPPER/${NAME_UPPER}/g" path.php

sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" urls.php
sed -i "s/APP_LOWER/${NAME_LOWER}/g" urls.php

cd ..

unalias ls || true
cd Views
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ..

sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" Template/Tag/Messages.php

# Corregir las plantillas
cd templates

sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" appname/base.html
sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" appname/index.html
sed -i "s/APP_LOWER/${NAME_LOWER}/g" appname/index.html

cd appname/login
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ../..

cd appname/users
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ../..

cd appname/register
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ../..

mv appname ${NAME_LOWER}

cd ..

# Corregir las migrations
sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" Migrations/Install.php

# Corregir los formularios
cd Form

cd Login
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ..

cd Register
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ..

cd User
for g in `ls`; do
	sed -i "s/APP_UPPER/${NAME_UPPER}/g" $g
	sed -i "s/APP_CAMEL_CASE/${NAME_CAMEL_CASE}/g" $g
	sed -i "s/APP_LOWER/${NAME_LOWER}/g" $g
done
cd ..

cd ..

cd ..
mv AppName $NAME_CAMEL_CASE

cd ..

echo "Listo"

rm template.sh

