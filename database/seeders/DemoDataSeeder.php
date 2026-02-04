<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Community;
use App\Models\Team;
use App\Models\User;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gaming-cms.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_premium' => true,
            ]
        );

        // Demo Games
        $minecraft = Game::firstOrCreate(
            ['slug' => 'minecraft'],
            [
                'name' => ['de' => 'Minecraft', 'en' => 'Minecraft'],
                'description' => ['de' => 'Sandbox-Spiel', 'en' => 'Sandbox Game'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $valorant = Game::firstOrCreate(
            ['slug' => 'valorant'],
            [
                'name' => ['de' => 'Valorant', 'en' => 'Valorant'],
                'description' => ['de' => 'Taktischer Shooter', 'en' => 'Tactical Shooter'],
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Demo Community
        $community = Community::firstOrCreate(
            ['slug' => 'de-minecraft'],
            [
                'game_id' => $minecraft->id,
                'name' => ['de' => 'Deutsche Minecraft Community', 'en' => 'German Minecraft Community'],
                'description' => ['de' => 'Die größte deutsche Minecraft Community', 'en' => 'The largest German Minecraft Community'],
                'is_active' => true,
            ]
        );

        // Demo Team
        if (!Team::where('community_id', $community->id)->where('name', 'Admin Team')->exists()) {
            $team = Team::create([
                'community_id' => $community->id,
                'leader_id' => $admin->id,
                'name' => 'Admin Team',
                'max_members' => 10,
                'is_recruiting' => true,
            ]);
        }

        // Forum Categories
        $general = ForumCategory::firstOrCreate(
            ['slug' => 'general', 'game_id' => $minecraft->id],
            [
                'name' => ['de' => 'Allgemein', 'en' => 'General'],
                'description' => ['de' => 'Allgemeine Diskussionen', 'en' => 'General Discussions'],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // Demo Thread
        $thread = ForumThread::firstOrCreate(
            ['slug' => 'willkommen', 'category_id' => $general->id],
            [
                'game_id' => $minecraft->id,
                'community_id' => $community->id,
                'user_id' => $admin->id,
                'title' => 'Willkommen in der Community!',
            ]
        );

        // Demo Posts
        if (!ForumPost::where('thread_id', $thread->id)->exists()) {
            ForumPost::create([
                'thread_id' => $thread->id,
                'user_id' => $admin->id,
                'content_original' => 'Willkommen in unserer **Gaming Community**! Wir freuen uns, dass du hier bist.',
                'content_format' => 'markdown',
                'content_html' => '<p>Willkommen in unserer <strong>Gaming Community</strong>! Wir freuen uns, dass du hier bist.</p>',
            ]);
        }

        // CMS Demo Content
        $homePage = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Startseite',
                'status' => 'published',
                'published_at' => now(),
                'blocks' => [
                    ['type' => 'text', 'data' => ['text' => 'Willkommen im Gaming CMS.']],
                    ['type' => 'image', 'data' => ['url' => 'https://placehold.co/1200x400', 'alt' => 'Hero']],
                ],
            ]
        );

        $newsPost = Post::firstOrCreate(
            ['slug' => 'erste-news'],
            [
                'title' => 'Erste News',
                'excerpt' => 'Das ist der erste News-Beitrag.',
                'status' => 'published',
                'published_at' => now(),
                'blocks' => [
                    ['type' => 'text', 'data' => ['text' => 'Hier steht der Inhalt des Beitrags.']],
                ],
            ]
        );

        $menu = Menu::firstOrCreate(
            ['slug' => 'main'],
            ['name' => 'Hauptmenü']
        );

        if ($menu->items()->count() === 0) {
            MenuItem::create([
                'menu_id' => $menu->id,
                'label' => 'Startseite',
                'url' => '/cms',
                'sort_order' => 1,
            ]);
            MenuItem::create([
                'menu_id' => $menu->id,
                'label' => 'Beiträge',
                'url' => '/cms/posts',
                'sort_order' => 2,
            ]);
            MenuItem::create([
                'menu_id' => $menu->id,
                'label' => 'Forum',
                'url' => '/',
                'sort_order' => 3,
            ]);
        }

        $this->command->info('Demo-Daten erfolgreich erstellt!');
        $this->command->info('Login: admin@gaming-cms.local / password');
    }
}
