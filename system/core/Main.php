<?php 

    namespace Purge;

    class Main {
     
        private static $file = null;

        public static function get_env() : array {
            $env = file_get_contents(base_path . 'env');
            $env = \Symfony\Component\Yaml\Yaml::parse($env);
            return $env;
        }

        public static function init() : void {
            $env = self::get_env();

            $loop = \React\EventLoop\Factory::create();

            $server = new \React\Http\Server($loop, function(\Psr\Http\Message\ServerRequestInterface $request) {
                $path = $request->getUri()->getPath();
                $method = $request->getMethod();

                foreach(json_decode(file_get_contents(base_path . 'routes.json')) as $route) {
                    $pattern = $route->pattern;
                    $module = $route->module;

                    if($path === $pattern && $method === 'GET') {
                        return new \React\Http\Message\Response(
                            200,
                            ['Content-type' => 'text/html'],
                            Main::get_module($module)
                        );
                    }
                }

                print "\nRequest from client : ({$method}) {$path}";
            });

            $socket = new \React\Socket\Server($env['port'], $loop);
            $server->listen($socket);

            echo "Your web application running at http://localhost:{$env['port']}";

            $loop->run();
        } 

        protected static function get_module(string $target) : string {
            $lists = explode('.', $target);
            $module = $lists[0];
            $file = end($lists);

            array_shift($lists);
            array_pop($lists);

            $path = base_path . 'modules/' . $module . '/' . join('/', $lists) . 'public/' . $file . '.html';

            if(is_dir(base_path . 'modules/' . $module)) {
                if(\File::exists($path)) {
                    $raw = file_get_contents($path);
                    return Parser\Parser::compile($raw, base_path . 'modules/' . $module);
                } else {
                    echo "\nError: Not found your module file";
                }
            } else {
                echo "\nError : Not found your module directory";
            }
        }

    }

?>