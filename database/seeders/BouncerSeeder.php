<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Erstelle Abilities...');
        
        // Global Abilities
        $abilities = [
            // Games Management
            'manage-games' => 'Spiele verwalten (erstellen, bearbeiten, löschen)',
            'view-games' => 'Spiele anzeigen',
            
            // Communities
            'manage-communities' => 'Alle Communities verwalten',
            'view-communities' => 'Communities anzeigen',
            'moderate-community' => 'Community moderieren (context-aware)',
            
            // Teams
            'manage-teams' => 'Teams verwalten',
            'lead-team' => 'Team leiten (context-aware)',
            
            // Forum
            'manage-forum' => 'Forum-Struktur verwalten',
            'moderate-forum' => 'Forum-Inhalte moderieren',
            'create-threads' => 'Threads erstellen',
            'create-posts' => 'Posts erstellen',
            'edit-own-posts' => 'Eigene Posts bearbeiten',
            'delete-own-posts' => 'Eigene Posts löschen',
            'edit-any-post' => 'Alle Posts bearbeiten',
            'delete-any-post' => 'Alle Posts löschen',
            'pin-threads' => 'Threads anpinnen',
            'lock-threads' => 'Threads sperren',
            
            // Users
            'manage-users' => 'User verwalten',
            'view-users' => 'User anzeigen',
            'ban-users' => 'User bannen',
            
            // System
            'manage-themes' => 'Themes verwalten',
            'manage-plugins' => 'Plugins verwalten',
            'view-admin-panel' => 'Admin-Panel zugreifen',
            'view-telescope' => 'Telescope anzeigen',
            
            // CMS
            'manage-pages' => 'Seiten verwalten',
            'manage-posts' => 'Beiträge verwalten',
            'manage-menus' => 'Menüs verwalten',
            
            // Reports
            'view-reports' => 'Reports anzeigen',
            'manage-reports' => 'Reports bearbeiten',
        ];

        foreach ($abilities as $name => $title) {
            Bouncer::ability()->firstOrCreate(
                ['name' => $name],
                ['title' => $title]
            );
        }

        $this->command->info('✓ ' . count($abilities) . ' Abilities erstellt');

        // Roles
        $this->command->info('Erstelle Roles...');

        // Super Admin Role
        $superAdmin = Bouncer::role()->firstOrCreate(
            ['name' => 'super-admin'],
            ['title' => 'Super Administrator']
        );
        Bouncer::allow($superAdmin)->everything();

        // Admin Role
        $admin = Bouncer::role()->firstOrCreate(
            ['name' => 'admin'],
            ['title' => 'Administrator']
        );
        Bouncer::allow($admin)->to([
            'manage-games',
            'manage-communities',
            'manage-teams',
            'manage-forum',
            'moderate-forum',
            'manage-users',
            'view-users',
            'manage-themes',
            'manage-pages',
            'manage-posts',
            'manage-menus',
            'view-admin-panel',
            'view-telescope',
            'view-reports',
            'manage-reports',
        ]);

        // Moderator Role
        $moderator = Bouncer::role()->firstOrCreate(
            ['name' => 'moderator'],
            ['title' => 'Moderator']
        );
        Bouncer::allow($moderator)->to([
            'view-games',
            'view-communities',
            'moderate-forum',
            'edit-any-post',
            'delete-any-post',
            'pin-threads',
            'lock-threads',
            'view-admin-panel',
            'view-reports',
            'manage-reports',
        ]);

        // Community Manager Role
        $communityManager = Bouncer::role()->firstOrCreate(
            ['name' => 'community-manager'],
            ['title' => 'Community Manager']
        );
        Bouncer::allow($communityManager)->to([
            'view-games',
            'view-communities',
            'moderate-community', // Context-aware
            'manage-teams',
            'moderate-forum',
            'view-admin-panel',
        ]);

        // Team Leader Role
        $teamLeader = Bouncer::role()->firstOrCreate(
            ['name' => 'team-leader'],
            ['title' => 'Team Leader']
        );
        Bouncer::allow($teamLeader)->to([
            'view-games',
            'view-communities',
            'lead-team', // Context-aware
        ]);

        // Member Role (Default)
        $member = Bouncer::role()->firstOrCreate(
            ['name' => 'member'],
            ['title' => 'Member']
        );
        Bouncer::allow($member)->to([
            'view-games',
            'view-communities',
            'create-threads',
            'create-posts',
            'edit-own-posts',
            'delete-own-posts',
        ]);

        // Editor Role (CMS Redaktion)
        $editor = Bouncer::role()->firstOrCreate(
            ['name' => 'editor'],
            ['title' => 'Editor']
        );
        Bouncer::allow($editor)->to([
            'manage-pages',
            'manage-posts',
            'view-admin-panel',
        ]);

        $this->command->info('✓ 7 Roles erstellt');

        // Weise Admin User die Super Admin Rolle zu
        $adminUser = User::where('email', 'admin@gaming-cms.local')->first();
        if ($adminUser) {
            Bouncer::assign('super-admin')->to($adminUser);
            $this->command->info('✓ Super Admin Rolle zu admin@gaming-cms.local zugewiesen');
        }

        // Alle User bekommen standardmäßig Member Role
        $this->command->info('Weise allen Usern ohne Role die Member Rolle zu...');
        User::whereDoesntHave('roles')->each(function ($user) use ($member) {
            Bouncer::assign('member')->to($user);
        });

        $this->command->info('');
        $this->command->info('=== Bouncer Setup abgeschlossen ===');
        $this->command->info('Roles: 6');
        $this->command->info('Abilities: ' . count($abilities));
    }
}
