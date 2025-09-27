<?php

namespace Sandstorm\LaravelImagor\Tests;

use Sandstorm\LaravelImagor\Imagor;
use Sandstorm\LaravelImagor\ImagorFactory;

beforeEach(function () {
    $this->sampleImageUrl = 'https://picsum.photos/800/600';
    $this->app['config']->set('imagor.base_url', 'http://localhost:8001');
    $this->app['config']->set('imagor.secret', 'UNSAFE_DEV_SECRET');
});

it('can make HTTP request to workbench basic test endpoint', function () {
    $response = $this->get('/imgproxy-test/basic');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'original',
            'processed',
            'test',
        ])
        ->assertJson([
            'test' => 'basic_url_generation',
        ]);

    $data = $response->json();
    expect($data['processed'])->toContain('400x300');
});

it('can make HTTP request to workbench effects test endpoint', function () {
    $response = $this->get('/imgproxy-test/effects');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'original',
            'processed',
            'effects_applied',
            'test',
        ])
        ->assertJson([
            'test' => 'effects_and_quality',
        ]);

    $data = $response->json();
    expect($data['processed'])->toContain('quality(85)')
        ->and($data['processed'])->toContain('blur(1)')
        ->and($data['processed'])->toContain('brightness(10)');
});

it('can make HTTP request to workbench formats test endpoint', function () {
    $response = $this->get('/imgproxy-test/formats');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'original',
            'formats' => [
                'jpeg',
                'png',
                'webp',
                'avif',
            ],
            'test',
        ])
        ->assertJson([
            'test' => 'format_conversion',
        ]);

    $data = $response->json();
    expect($data['formats']['jpeg'])->toContain('jpeg')
        ->and($data['formats']['png'])->toContain('png')
        ->and($data['formats']['webp'])->toContain('webp')
        ->and($data['formats']['avif'])->toContain('avif');
});

it('can make HTTP request to workbench resize test endpoint', function () {
    $response = $this->get('/imgproxy-test/resize');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'original',
            'resize_types' => [
                'fit',
                'fill',
                'force',
                'auto',
            ],
            'test',
        ]);

    $data = $response->json();
    expect($data['resize_types']['fit'])->toContain('fit-in')
        ->and($data['resize_types']['force'])->toContain('/stretch/')
        ->and($data['resize_types']['auto'])->toContain('/smart/');
});


it('can access workbench test index endpoint', function () {
    $response = $this->get('/imgproxy-test/');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'available_tests',
            'usage',
        ]);

    $data = $response->json();
    expect($data['available_tests'])->toHaveCount(9)
        ->and($data['message'])->toContain('ImgProxy Laravel Package Test Suite');
});

// Test that the package is properly integrated in workbench environment
it('has imagor helper function available in workbench', function () {
    expect(function_exists('imagor'))->toBeTrue();
});


it('imagorFactory is singleton', function () {
    $imagorFactory1 = $this->app->get(ImagorFactory::class);
    $imagorFactory2 = $this->app->get(ImagorFactory::class);

    expect($imagorFactory1)->toBe($imagorFactory2);
});

it('imagor object is prototype, and is resolvable through DI', function () {
    $imagor1 = $this->app->get(Imagor::class);
    $imagor2 = $this->app->get(Imagor::class);

    expect($imagor1)->not->toBe($imagor2);
});
