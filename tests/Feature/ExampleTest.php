<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * There is no public web UI: routes/web.php sends the root straight to the
     * Filament admin login. The customer UI is the separate Nuxt application.
     */
    public function test_the_root_redirects_to_the_admin_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/admin/login');
    }
}
