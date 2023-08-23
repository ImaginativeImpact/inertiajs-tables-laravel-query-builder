<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GlobalSearchTest extends DuskTestCase
{
    /** @test */
    public function it_can_globally_search()
    {
        $this->browse(function (Browser $browser) {
            User::first()->forceFill([
                'name'  => 'Imaginative Impact',
                'email' => 'hi@imaginativeimpact.com',
            ])->save();

            $users = User::query()
                ->select(['id', 'name', 'email'])
                ->orderBy('name')
                ->get();

            $browser->visit('/users/eloquent')
                ->waitFor('table')
                // First user
                ->assertSeeIn('tr:first-child td:nth-child(1)', $users->get(0)->name)
                ->assertDontSee('Imaginative Impact')
                ->type('global', 'Imaginative Impact')
                ->waitForText('hi@imaginativeimpact.com')
                ->type('global', ' ')
                ->waitUntilMissingText('hi@imaginativeimpact.com')
                ->type('global', 'hi@imaginativeimpact.com')
                ->waitForText('Imaginative Impact');
        });
    }
}
