# Guida Sviluppo Plugin

## 📚 Introduzione
Questa guida fornisce tutte le informazioni necessarie per sviluppare plugin per il sistema.

## 🏗️ Anatomia di un Plugin

### Struttura Minima (Plugin Semplice)
```
mio-plugin/
├── plugin.json         # Manifest del plugin (OBBLIGATORIO)
└── src/
    └── MioPlugin.php   # Classe principale
```

### Struttura Completa (Plugin Complesso)
```
mio-plugin/
├── plugin.json         # Manifest del plugin
├── composer.json       # Dipendenze PHP (opzionale)
├── README.md          # Documentazione
├── LICENSE            # Licenza
├── src/
│   ├── MioPlugin.php  # Classe principale
│   ├── Controllers/   # HTTP Controllers
│   ├── Models/        # Eloquent Models
│   ├── Services/      # Business Logic
│   └── Providers/     # Service Providers
├── config/
│   └── mio-plugin.php # Configurazioni
├── database/
│   ├── migrations/    # Migration files
│   └── seeders/       # Database seeders
├── resources/
│   ├── views/         # Blade templates
│   ├── js/           # JavaScript
│   └── css/          # Stylesheets
├── routes/
│   ├── web.php       # Web routes
│   └── api.php       # API routes
└── tests/
    ├── Unit/         # Unit tests
    └── Feature/      # Feature tests
```

## 📄 Plugin Manifest (plugin.json)

### Esempio Base
```json
{
    "name": "mio-plugin",
    "version": "1.0.0",
    "description": "Descrizione del mio plugin",
    "author": {
        "name": "Nome Autore",
        "email": "email@example.com"
    },
    "type": "simple",
    "main": "src/MioPlugin.php",
    "minimum-core": "1.0.0"
}
```

### Esempio Avanzato
```json
{
    "name": "ecommerce-plugin",
    "version": "2.1.0",
    "description": "Plugin completo e-commerce",
    "author": {
        "name": "Team Dev",
        "email": "dev@company.com",
        "url": "https://company.com"
    },
    "type": "module",
    "main": "src/EcommercePlugin.php",
    "minimum-core": "1.0.0",
    "php": "^8.1",
    "autoload": {
        "psr-4": {
            "Ecommerce\\": "src/"
        }
    },
    "providers": [
        "Ecommerce\\Providers\\EcommerceServiceProvider"
    ],
    "aliases": {
        "Cart": "Ecommerce\\Facades\\Cart"
    },
    "routes": {
        "web": "routes/web.php",
        "api": "routes/api.php"
    },
    "migrations": "database/migrations",
    "config": "config/ecommerce.php",
    "views": "resources/views",
    "assets": {
        "js": "resources/js",
        "css": "resources/css"
    },
    "hooks": [
        {
            "name": "dashboard.widgets",
            "callback": "renderDashboardWidget",
            "priority": 10
        }
    ],
    "permissions": [
        "manage-products",
        "manage-orders",
        "view-reports"
    ],
    "dependencies": {
        "stripe-payments": "^1.0",
        "pdf-generator": "^2.0"
    },
    "settings": {
        "currency": {
            "type": "select",
            "label": "Default Currency",
            "default": "EUR",
            "options": ["EUR", "USD", "GBP"]
        },
        "tax_rate": {
            "type": "number",
            "label": "Tax Rate (%)",
            "default": 22,
            "validation": "required|numeric|min:0|max:100"
        }
    }
}
```

## 🔧 Classe Plugin Base

### Plugin Semplice
```php
<?php
namespace MioPlugin;

use App\Plugins\Core\Interfaces\Plugin;

class MioPlugin implements Plugin
{
    public function boot(): void
    {
        // Inizializzazione plugin
        app('plugins')->addHook('sidebar.menu', [$this, 'addMenuItem']);
    }
    
    public function addMenuItem($menu)
    {
        $menu->add([
            'title' => 'Mio Plugin',
            'route' => 'mio-plugin.index',
            'icon' => 'ti ti-puzzle'
        ]);
    }
}
```

### Plugin Complesso
```php
<?php
namespace Ecommerce;

use App\Plugins\Core\Interfaces\{Plugin, HasRoutes, HasViews, HasMigrations};
use App\Plugins\Core\Traits\{Configurable, HasHooks};

class EcommercePlugin implements Plugin, HasRoutes, HasViews, HasMigrations
{
    use Configurable, HasHooks;
    
    public function boot(): void
    {
        $this->loadConfig();
        $this->registerHooks();
        $this->registerComponents();
        $this->publishAssets();
    }
    
    protected function registerHooks(): void
    {
        // Hook nel dashboard
        $this->addHook('dashboard.widgets', [$this, 'renderSalesWidget']);
        
        // Hook nel menu
        $this->addHook('sidebar.menu', [$this, 'registerMenu'], 20);
        
        // Hook nelle notifiche
        $this->addFilter('notification.channels', [$this, 'addOrderChannel']);
    }
    
    protected function registerComponents(): void
    {
        // Registra componenti Blade
        Blade::component('ecommerce::components.product-card', 'product-card');
        Blade::component('ecommerce::components.cart-icon', 'cart-icon');
    }
    
    public function install(): void
    {
        // Logica di installazione
        Artisan::call('migrate', [
            '--path' => $this->getMigrationPath()
        ]);
        
        $this->seedInitialData();
        $this->createStorageDirectories();
    }
    
    public function uninstall(): void
    {
        // Pulizia durante disinstallazione
        $this->removeStorageDirectories();
        $this->cleanupDatabase();
    }
}
```

## 🎨 Lavorare con le View

### Registrare View Namespace
```php
public function boot(): void
{
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'mio-plugin');
}
```

### Usare le View del Plugin
```php
// Nel controller
return view('mio-plugin::pages.settings');

// Estendere layout dell'app
@extends('layouts.app')

@section('content')
    <div class="plugin-content">
        <!-- Contenuto plugin -->
    </div>
@endsection
```

### Inserire Hook nelle View
```blade
{{-- Vista del plugin che si aggancia al dashboard --}}
@pushonce('dashboard.widgets')
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>{{ __('mio-plugin::messages.widget_title') }}</h4>
                <!-- Widget content -->
            </div>
        </div>
    </div>
@endpushonce
```

## 🛣️ Gestione Route

### Route Web
```php
// routes/web.php
use MioPlugin\Controllers\PluginController;

Route::prefix('mio-plugin')
    ->name('mio-plugin.')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', [PluginController::class, 'index'])->name('index');
        Route::get('/settings', [PluginController::class, 'settings'])->name('settings');
        Route::post('/settings', [PluginController::class, 'updateSettings'])->name('settings.update');
    });
```

### Route API
```php
// routes/api.php
Route::prefix('api/mio-plugin')
    ->name('api.mio-plugin.')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function () {
        Route::get('/data', [ApiController::class, 'getData']);
        Route::post('/webhook', [ApiController::class, 'handleWebhook'])
            ->middleware('webhook.verify');
    });
```

## 💾 Database e Migrazioni

### Creare Migrazioni
```php
// database/migrations/2024_01_01_000000_create_plugin_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePluginTables extends Migration
{
    public function up()
    {
        Schema::create('mio_plugin_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('key');
            $table->json('value');
            $table->timestamps();
            
            $table->index(['user_id', 'key']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mio_plugin_data');
    }
}
```

## 🪝 Sistema Hook

### Tipi di Hook

#### 1. Action Hook
```php
// Registrare
$this->addAction('user.registered', [$this, 'onUserRegistered']);

// Implementare
public function onUserRegistered($user)
{
    // Invia email di benvenuto custom
    Mail::to($user)->send(new WelcomeEmail($user));
}
```

#### 2. Filter Hook
```php
// Registrare
$this->addFilter('user.permissions', [$this, 'addCustomPermissions']);

// Implementare
public function addCustomPermissions($permissions)
{
    $permissions[] = 'access-plugin-feature';
    return $permissions;
}
```

#### 3. View Hook
```php
// Nel plugin
$this->addViewHook('dashboard.stats', 'mio-plugin::widgets.stats');

// Nella view principale
@pluginHook('dashboard.stats')
```

### Hook Disponibili

| Hook Name | Type | Description |
|-----------|------|-------------|
| `app.boot` | Action | App avviata |
| `user.login` | Action | Utente loggato |
| `user.logout` | Action | Utente disconnesso |
| `sidebar.menu` | Filter | Modifica menu |
| `dashboard.widgets` | View | Widget dashboard |
| `user.profile.tabs` | View | Tab profilo |
| `notification.send` | Action | Prima invio notifica |
| `notification.channels` | Filter | Canali disponibili |

## ⚙️ Configurazione

### File di Config
```php
// config/mio-plugin.php
return [
    'enabled' => env('MIO_PLUGIN_ENABLED', true),
    'api_key' => env('MIO_PLUGIN_API_KEY'),
    'cache_ttl' => env('MIO_PLUGIN_CACHE_TTL', 3600),
    
    'features' => [
        'advanced_mode' => false,
        'debug_mode' => env('APP_DEBUG', false),
    ],
    
    'limits' => [
        'max_requests' => 100,
        'timeout' => 30,
    ],
];
```

### Settings UI
```php
public function getSettingsFields(): array
{
    return [
        'api_key' => [
            'type' => 'text',
            'label' => 'API Key',
            'help' => 'Enter your API key',
            'rules' => 'required|string|min:32',
            'encrypted' => true,
        ],
        'webhook_url' => [
            'type' => 'url',
            'label' => 'Webhook URL',
            'placeholder' => 'https://example.com/webhook',
            'rules' => 'nullable|url',
        ],
        'features' => [
            'type' => 'checkboxes',
            'label' => 'Enable Features',
            'options' => [
                'email_notifications' => 'Email Notifications',
                'sms_notifications' => 'SMS Notifications',
                'api_access' => 'API Access',
            ],
        ],
    ];
}
```

## 🧪 Testing

### Unit Test
```php
namespace MioPlugin\Tests\Unit;

use Tests\TestCase;
use MioPlugin\Services\PluginService;

class PluginServiceTest extends TestCase
{
    public function test_service_processes_data_correctly()
    {
        $service = new PluginService();
        $result = $service->process(['test' => 'data']);
        
        $this->assertArrayHasKey('processed', $result);
        $this->assertTrue($result['success']);
    }
}
```

### Feature Test
```php
namespace MioPlugin\Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class PluginRoutesTest extends TestCase
{
    public function test_settings_page_requires_authentication()
    {
        $response = $this->get('/mio-plugin/settings');
        $response->assertRedirect('/login');
    }
    
    public function test_authenticated_user_can_access_settings()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/mio-plugin/settings');
            
        $response->assertOk();
        $response->assertViewIs('mio-plugin::settings');
    }
}
```

## 🚀 Best Practices

### 1. Namespace e Naming
- Usa namespace univoci: `VendorName\PluginName`
- Prefissa tabelle DB: `mio_plugin_*`
- Prefissa config keys: `mio-plugin.*`

### 2. Performance
```php
// Cache pesante computazione
$data = Cache::remember('mio-plugin.expensive-data', 3600, function () {
    return $this->calculateExpensiveData();
});

// Lazy loading
public function boot()
{
    if ($this->isEnabled()) {
        $this->loadFunctionality();
    }
}
```

### 3. Sicurezza
```php
// Validazione input
$validated = $request->validate([
    'user_input' => 'required|string|max:255',
    'number' => 'required|integer|min:0|max:100',
]);

// Permessi
if (!$user->can('use-mio-plugin')) {
    abort(403);
}

// Sanitizzazione
$clean = e($request->input('html_content'));
```

### 4. Internazionalizzazione
```php
// resources/lang/en/messages.php
return [
    'welcome' => 'Welcome to :name plugin',
    'settings_saved' => 'Settings saved successfully',
];

// Uso
__('mio-plugin::messages.welcome', ['name' => 'My Plugin'])
```

## 📦 Distribuzione

### 1. Preparazione
- [ ] Versione bumping
- [ ] Update CHANGELOG
- [ ] Test completi
- [ ] Documentazione aggiornata

### 2. Package
```bash
# Crea ZIP escludendo file non necessari
zip -r mio-plugin-v1.0.0.zip mio-plugin/ \
    -x "*.git*" \
    -x "*node_modules*" \
    -x "*tests*" \
    -x "*.env"
```

### 3. Pubblicazione
- GitHub releases
- Plugin marketplace (futuro)
- Composer package (opzionale)

## 🆘 Troubleshooting

### Plugin non carica
1. Verifica `plugin.json` sia valido
2. Check permessi directory
3. Verifica logs: `storage/logs/plugin-*.log`

### Route non funzionanti
1. Clear route cache: `php artisan route:clear`
2. Verifica namespace corretto
3. Check middleware applicati

### View non trovate
1. Verifica namespace registrato
2. Path delle view corretto
3. Clear view cache: `php artisan view:clear`