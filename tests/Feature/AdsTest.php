<?php


namespace Tests\Feature;

use App\Models\Ad;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AdsTest extends TestCase
{
    protected string $filePath;
    protected Container $app;
    protected HttpKernelInterface $kernel;

    protected function setUp(): void
    {
        $assetsPath = __DIR__ . '/../assets/';
        $testFile = 'single_test.jpg';
        copy($assetsPath . 'test_file.jpg', $assetsPath . $testFile);
        $this->filePath = $assetsPath . $testFile;

        $this->app = new \Core\App(__DIR__ . '/../../public');
        $this->kernel = $this->app->get('kernel');
    }

    public function testCreateAd()
    {
        $data = [
            'text' => 'testtest',
            'price' => '132',
            'limit' => '49',
        ];

        $files = [
            'banner' => new UploadedFile($this->filePath, 'test_file.png', 'image/jpeg', 0, true)
        ];

        $request = Request::create('/create', 'POST', $data, [], $files);
        $request->setMethod('POST');

        $response = $this->kernel->handle($request);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($content['success'], true);

        $item = Ad::query()->where('id', $content['id'])->first();
        $this->assertNotNull($item);
        $this->assertEquals($item->amount, 0);

    }

}