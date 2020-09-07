<?php
namespace Core;

use Illuminate\Container\Container;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;

class App extends Container
{
    protected ?string $basePath = null;
    protected RouteCollection $routes;
    protected array $paths;

    public function __construct(string $publicPath = null)
    {
        if ($publicPath)
            $this->basePath = dirname($publicPath);

        $this->bootstrap();
    }

    public function bootstrap(): void
    {
        static::setInstance($this);
        $this->instance('kernel', new HttpKernel());
        $this->routeBoot();
        $this->configure();
    }

    protected function routeBoot(): void
    {
        if ($this->basePath == null) return;

        $fileLocator = new FileLocator( $this->basePath);
        $loader = new PhpFileLoader($fileLocator);
        $this->instance('routes', $loader->load('router.php'));
    }

    /**
     * Лучше выносить такие вещи
     */
    protected function configure(): void
    {
        if ($this->basePath == null) return;

        $config = include $this->basePath . '/config/app.php';
        $this->instance('paths', $config['paths']);
        $this->instance('url', $config['url']);

        $this->setUpDatabase($config['database']);
    }

    /**
     * Место для изменений.
     * Лучше делать типа DatabaseProvider
     * Но оставлено так для простоты
     *
     * @param array $config
     */
    protected function setUpDatabase(array $config): void
    {
        $default = $config['default'];

        $factory = new ConnectionFactory($this);
        $connection = $factory->make($config['connections'][$default]);
        $this->instance('db.connection', $connection);

        $connectionResolver = new ConnectionResolver(['mysql' => $connection]);
        $connectionResolver->setDefaultConnection($default);
        Model::setConnectionResolver($connectionResolver);

        $this->instance('db.resolver', $connectionResolver);
    }
}