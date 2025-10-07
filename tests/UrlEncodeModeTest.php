<?php

namespace Sandstorm\LaravelImagor\Tests;

use Sandstorm\LaravelImagor\UrlEncodeMode;

beforeEach(function () {
    $this->simpleImageUrl = 'storage/app/__imagor-configtest_test1.jpg';
    $this->tempUploadUrl = 'storage/app/__imagor-configtest_WHukFiPIgKf8LE2UX2Mm5rLXYEJ9cv-metaU0NSLTIwMjUwOTI1LXJvZ2YuanBlZw==-.jpeg';
    $this->specialCharUrl = 'storage/app/__imagor-configtest_(name with special - characters?).jpeg';
});

it('UrlEncodeMode::NONE', function () {
    $mode = UrlEncodeMode::NONE;
    expect($mode->encodeSourcePath($this->simpleImageUrl))->toBe($this->simpleImageUrl);
    expect($mode->encodeSourcePath($this->tempUploadUrl))->toBe($this->tempUploadUrl);
    expect($mode->encodeSourcePath($this->specialCharUrl))->toBe($this->specialCharUrl);
});

it('UrlEncodeMode::URLENCODE', function () {
    $mode = UrlEncodeMode::URLENCODE;
    expect($mode->encodeSourcePath($this->simpleImageUrl))->toBe('storage%2Fapp%2F__imagor-configtest_test1.jpg');
    expect($mode->encodeSourcePath($this->tempUploadUrl))->toBe('storage%2Fapp%2F__imagor-configtest_WHukFiPIgKf8LE2UX2Mm5rLXYEJ9cv-metaU0NSLTIwMjUwOTI1LXJvZ2YuanBlZw%3D%3D-.jpeg');
    expect($mode->encodeSourcePath($this->specialCharUrl))->toBe('storage%2Fapp%2F__imagor-configtest_(name%20with%20special%20-%20characters%3F).jpeg');
});

it('UrlEncodeMode::BASE64_IF_UNSAFE_CHARS', function () {
    $mode = UrlEncodeMode::BASE64_IF_UNSAFE_CHARS;
    expect($mode->encodeSourcePath($this->simpleImageUrl))->toBe('storage%2Fapp%2F__imagor-configtest_test1.jpg');
    expect($mode->encodeSourcePath($this->tempUploadUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF9XSHVrRmlQSWdLZjhMRTJVWDJNbTVyTFhZRUo5Y3YtbWV0YVUwTlNMVEl3TWpVd09USTFMWEp2WjJZdWFuQmxadz09LS5qcGVn');
    expect($mode->encodeSourcePath($this->specialCharUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF8obmFtZSB3aXRoIHNwZWNpYWwgLSBjaGFyYWN0ZXJzPykuanBlZw');
});

it('UrlEncodeMode::BASE64_IF_UNSAFE_CHARS_CONSERVATIVE', function () {
    $mode = UrlEncodeMode::BASE64_IF_UNSAFE_CHARS_CONSERVATIVE;
    expect($mode->encodeSourcePath($this->simpleImageUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF90ZXN0MS5qcGc');
    expect($mode->encodeSourcePath($this->tempUploadUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF9XSHVrRmlQSWdLZjhMRTJVWDJNbTVyTFhZRUo5Y3YtbWV0YVUwTlNMVEl3TWpVd09USTFMWEp2WjJZdWFuQmxadz09LS5qcGVn');
    expect($mode->encodeSourcePath($this->specialCharUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF8obmFtZSB3aXRoIHNwZWNpYWwgLSBjaGFyYWN0ZXJzPykuanBlZw');
});

it('UrlEncodeMode::BASE64', function () {
    $mode = UrlEncodeMode::BASE64;
    expect($mode->encodeSourcePath($this->simpleImageUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF90ZXN0MS5qcGc');
    expect($mode->encodeSourcePath($this->tempUploadUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF9XSHVrRmlQSWdLZjhMRTJVWDJNbTVyTFhZRUo5Y3YtbWV0YVUwTlNMVEl3TWpVd09USTFMWEp2WjJZdWFuQmxadz09LS5qcGVn');
    expect($mode->encodeSourcePath($this->specialCharUrl))->toBe('b64:c3RvcmFnZS9hcHAvX19pbWFnb3ItY29uZmlndGVzdF8obmFtZSB3aXRoIHNwZWNpYWwgLSBjaGFyYWN0ZXJzPykuanBlZw');
});
