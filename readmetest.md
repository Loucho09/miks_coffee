php artisan tinker



1. Check Current Registered Users

App\Models\User::count();


2. want to see a list of their names and emails:

App\Models\User::all(['name', 'email']);


3. Delete All Users

App\Models\User::query()->delete();