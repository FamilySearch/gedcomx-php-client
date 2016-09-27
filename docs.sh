# Get ApiGen.phar
wget http://www.apigen.org/apigen.phar

# Get the boostrap theme
git clone https://github.com/jimmyz/ThemeBootstrap.git ../ThemeBootstrap

# Generate docs
php apigen.phar generate -s src -s vendor/gedcomx/gedcomx-php/src -d ../docs --access-levels="public" --title="gedcomx-php-client" --template-config="../ThemeBootstrap/src/config.neon"
