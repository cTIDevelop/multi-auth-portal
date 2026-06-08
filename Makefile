# Multi-Auth Portal — Convenience Commands
# Usage: make <target>

.PHONY: install fresh seed tinker admin-shell provider-shell

install:
	composer install
	cp .env.example .env
	php artisan key:generate
	npm install && npm run build
	php artisan storage:link
	php artisan migrate --seed
	@echo ""
	@echo "✅ Installation complete!"
	@echo "   php artisan serve  →  http://localhost:8000"

fresh:
	php artisan migrate:fresh --seed

seed:
	php artisan db:seed

tinker:
	php artisan tinker

# Create a new super admin interactively
make-admin:
	php artisan tinker --execute="App\Models\Admin::create(['name'=>'$(NAME)','email'=>'$(EMAIL)','password'=>bcrypt('$(PASS)'),'is_super_admin'=>true])"

# Assign a role to an admin
assign-role:
	php artisan tinker --execute="App\Models\Admin::where('email','$(EMAIL)')->first()->assignRole('$(ROLE)')"

# Clear all caches
clear:
	php artisan optimize:clear
	php artisan permission:cache-reset

# Run tests
test:
	php artisan test
