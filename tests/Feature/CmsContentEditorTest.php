<?php

namespace Tests\Feature;

use App\Models\Cms\Page;
use App\Models\Cms\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CmsContentEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_editor_loads_structured_content_without_json_textareas(): void
    {
        $user = User::factory()->create();
        $page = Page::query()->create([
            'slug' => 'about',
            'title' => 'About',
            'content' => [
                'intro' => 'Welcome',
                'approach' => [
                    ['title' => 'Hands-on', 'body' => 'Projects'],
                ],
                'hero' => [
                    'heading' => 'Hello',
                    'image' => 'uploads/cms/general/building.jpg',
                ],
            ],
            'is_published' => true,
        ]);

        $this->actingAs($user);

        $this->get(route('cms.pages.edit', $page))
            ->assertOk()
            ->assertSee('Page content')
            ->assertSee('Add item')
            ->assertSee('Hands-on')
            ->assertDontSee('json_encode');
    }

    public function test_page_editor_can_add_list_items_and_save(): void
    {
        $user = User::factory()->create();
        $page = Page::query()->create([
            'slug' => 'about',
            'title' => 'About',
            'content' => [
                'approach' => [
                    ['title' => 'One', 'body' => 'First'],
                ],
            ],
            'is_published' => true,
        ]);

        $this->actingAs($user);

        Volt::test('pages.cms.pages.edit', ['page' => $page])
            ->call('addListItem', 'approach')
            ->set('content.approach.1.title', 'Two')
            ->set('content.approach.1.body', 'Second')
            ->call('save')
            ->assertHasNoErrors();

        $page->refresh();

        $this->assertCount(2, $page->content['approach']);
        $this->assertSame('Two', $page->content['approach'][1]['title']);
    }

    public function test_program_form_uses_bullet_list_ui(): void
    {
        $user = User::factory()->create();
        $program = Program::query()->create([
            'title' => 'Primary',
            'slug' => 'primary',
            'subtitle' => 'Grades 1-6',
            'grades_label' => 'Grades 1–6',
            'summary' => 'Summary',
            'bullets' => ['Reading', 'Writing'],
            'meta_tags' => ['Primary'],
            'is_published' => true,
        ]);

        $this->actingAs($user);

        Volt::test('pages.cms.programs.form', ['program' => $program])
            ->assertSet('bullets', ['Reading', 'Writing'])
            ->call('addBullet')
            ->set('bullets.2', 'Math')
            ->call('save')
            ->assertHasNoErrors();

        $program->refresh();

        $this->assertSame(['Reading', 'Writing', 'Math'], $program->bullets);
    }
}
