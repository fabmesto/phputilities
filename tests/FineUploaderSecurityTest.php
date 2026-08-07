<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name($fileName)
    {
        return (string) $fileName;
    }
}

final class FineUploaderSecurityTest extends TestCase
{
    public function testUploadHandlerRequiresPathSafeUuid(): void
    {
        $handler = new \fab\fineuploader\UploadHandler();
        $method = new ReflectionMethod($handler, 'validatedUuid');
        $method->setAccessible(true);

        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', $method->invoke($handler, '123e4567-e89b-12d3-a456-426614174000'));
        $this->assertSame('upload_123', $method->invoke($handler, 'upload_123'));
        $this->assertSame('', $method->invoke($handler, ''));
        $this->assertSame('', $method->invoke($handler, '../uploads'));
        $this->assertSame('', $method->invoke($handler, 'folder/name'));
        $this->assertSame('', $method->invoke($handler, array('invalid')));
    }

    public function testUploadHandlerRequiresAPathSafeFileName(): void
    {
        $handler = new \fab\fineuploader\UploadHandler();
        $method = new ReflectionMethod($handler, 'validatedFileName');
        $method->setAccessible(true);

        $this->assertSame('Documento finale 01.pdf', $method->invoke($handler, 'Documento finale 01.pdf'));
        $this->assertSame('dati_è.csv', $method->invoke($handler, 'dati_è.csv'));
        $this->assertSame('', $method->invoke($handler, '../config.php'));
        $this->assertSame('', $method->invoke($handler, '..\\config.php'));
        $this->assertSame('', $method->invoke($handler, array('config.php')));
    }

    public function testRelativeUploadPathsKeepNestedDirectoriesButRejectTraversal(): void
    {
        $method = new ReflectionMethod(\fab\upload::class, '_normalize_relative_upload_path');
        $method->setAccessible(true);

        $this->assertSame('files' . DIRECTORY_SEPARATOR . 'documenti' . DIRECTORY_SEPARATOR . '123', $method->invoke(null, 'files/documenti/123'));
        $this->assertSame('files' . DIRECTORY_SEPARATOR . 'documenti', $method->invoke(null, 'files//documenti'));
        $this->assertFalse($method->invoke(null, '../documenti'));
        $this->assertFalse($method->invoke(null, 'files/../documenti'));
        $this->assertFalse($method->invoke(null, '/var/www/files'));
        $this->assertFalse($method->invoke(null, 'C:\\files'));
    }

    public function testDeleteRequestValidatesUuidFromTheUrl(): void
    {
        $root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('phputilities-delete-', true);
        mkdir($root);
        file_put_contents($root . DIRECTORY_SEPARATOR . 'keep.txt', 'keep');

        $oldServer = $_SERVER;
        try {
            $_SERVER['REQUEST_METHOD'] = 'DELETE';
            $_SERVER['REQUEST_URI'] = '/fineuploader/%2e%2e';

            $result = (new \fab\fineuploader\UploadHandler())->handleDelete($root);

            $this->assertFalse($result['success']);
            $this->assertSame('Invalid or missing upload UUID.', $result['error']);
            $this->assertFileExists($root . DIRECTORY_SEPARATOR . 'keep.txt');
        } finally {
            $_SERVER = $oldServer;
            unlink($root . DIRECTORY_SEPARATOR . 'keep.txt');
            rmdir($root);
        }
    }

    public function testRecursiveDeleteIsConfinedToItsAllowedRoot(): void
    {
        $container = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('phputilities-root-', true);
        $root = $container . DIRECTORY_SEPARATOR . 'uploads';
        $target = $root . DIRECTORY_SEPARATOR . 'valid_uuid';
        $nested = $target . DIRECTORY_SEPARATOR . 'nested';
        $outside = $container . DIRECTORY_SEPARATOR . 'outside';
        mkdir($nested, 0777, true);
        mkdir($outside);
        file_put_contents($nested . DIRECTORY_SEPARATOR . 'file.txt', 'content');
        file_put_contents($outside . DIRECTORY_SEPARATOR . 'keep.txt', 'keep');

        $this->assertFalse(\fab\upload::delete_directory_not_empty($outside, $root));
        $this->assertFileExists($outside . DIRECTORY_SEPARATOR . 'keep.txt');
        $this->assertTrue(\fab\upload::delete_directory_not_empty($target, $root));
        $this->assertDirectoryDoesNotExist($target);

        unlink($outside . DIRECTORY_SEPARATOR . 'keep.txt');
        rmdir($outside);
        rmdir($root);
        rmdir($container);
    }

    public function testRenameReportsFailureAndKeepsTheExistingDestination(): void
    {
        $container = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('phputilities-rename-', true);
        $source = $container . DIRECTORY_SEPARATOR . 'source';
        $destination = $container . DIRECTORY_SEPARATOR . 'destination';
        mkdir($source, 0777, true);
        mkdir($destination);
        file_put_contents($destination . DIRECTORY_SEPARATOR . 'existing.pdf', 'existing');

        $result = \fab\upload::rename_file('missing.pdf', 'existing', $source, $destination, true);

        $this->assertFalse($result);
        $this->assertSame('existing', file_get_contents($destination . DIRECTORY_SEPARATOR . 'existing.pdf'));

        unlink($destination . DIRECTORY_SEPARATOR . 'existing.pdf');
        rmdir($source);
        rmdir($destination);
        rmdir($container);
    }

    public function testRenameKeepsTheExistingSuccessfulReturnValue(): void
    {
        $container = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('phputilities-rename-ok-', true);
        $source = $container . DIRECTORY_SEPARATOR . 'source';
        $destination = $container . DIRECTORY_SEPARATOR . 'destination';
        mkdir($source, 0777, true);
        mkdir($destination);
        file_put_contents($source . DIRECTORY_SEPARATOR . 'original.pdf', 'content');

        $result = \fab\upload::rename_file('original.pdf', 'renamed', $source, $destination, true);

        $this->assertSame('renamed.pdf', $result);
        $this->assertFileDoesNotExist($source . DIRECTORY_SEPARATOR . 'original.pdf');
        $this->assertSame('content', file_get_contents($destination . DIRECTORY_SEPARATOR . 'renamed.pdf'));

        unlink($destination . DIRECTORY_SEPARATOR . 'renamed.pdf');
        rmdir($source);
        rmdir($destination);
        rmdir($container);
    }

    public function testUploadWrappersValidateUuidBeforeCleanup(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/upload.php');

        $this->assertIsString($source);
        $this->assertGreaterThanOrEqual(2, substr_count($source, 'Invalid or missing upload UUID.'));
        $this->assertStringContainsString("preg_match('/^[A-Za-z0-9_-]{1,128}$/', (string) \$result['uuid'])", $source);
    }
}
