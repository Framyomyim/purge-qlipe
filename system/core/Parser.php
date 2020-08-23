<?php 

    namespace Purge\Parser;

    class Parser {
        public static function compile(string $raw, string $path) : string {
            $results = self::render_config($raw, $path . '/config/');
            $results = self::render_component($results, $path);
            
            return $results;
        }

        protected static function render_config(string $raw, string $path) : string {
            $results = $raw;
            $getConfig = \getParser('config{', $raw, '@', '}');
            foreach($getConfig as $config) {
                $attr = $config['attributes'];
                $toReplace = $config['raw'];

                $deserve = null;

                $type = strtolower($attr['type']);
                $config = json_decode(file_get_contents($path . $attr['src'] . '.json'));

                $html = '';
                $htmlCSS = '';
                foreach($config as $dir) {
                    foreach($dir->js as $js) {
                        $js = str_replace('{url}', \Purge\Main::get_env()['project']['url'], $js);
                        $html .= '<script type="text/javascript" src="'. $js .'"></script>';
                    }

                    foreach($dir->css as $css) {
                        $css = str_replace('{url}', \Purge\Main::get_env()['project']['url'], $css);
                        $htmlCSS .= '<link rel="stylesheet" href="' . $css . '">';
                    }
                }

                if($type === 'js') {
                    $results = str_replace($toReplace, $html, $results);
                }

                if($type === 'css') {
                    $results = str_replace($toReplace, $htmlCSS, $results);
                }

            }
            return $results;
        }

        protected static function render_component(string $raw, string $path) : string {
            $result = $raw;
            $parserComponent = \getParser('component{', $raw, '@', '}');

            foreach($parserComponent as $component) {
                $toReplace = $component['raw'];
                $src = $component['attributes']['src'];
                unset($component['attributes']['src']);
                $vars = $component['attributes'];

                $lists = explode('.', $src);
                $module = $lists[0];
                $file = end($lists);
                array_shift($lists);
                array_pop($lists);
                $folder = join('/', $lists);

                $pathFile = base_path . 'modules/' . $module . '/public/' . $folder . $file . '.html'; 

                if(\File::exists($pathFile)) {
                    $data = file_get_contents($pathFile);
                    $complied = Parser::compile($data, base_path . 'modules/' . $module);

                    $variable = [];

                    foreach($vars as $name => $value) {
                        $name = "@{{$name}}";
                        $variable[$name] = $value;
                    }

                    $result = str_replace($toReplace, $complied, $result);
                    $result = self::render_variable($result, $variable);
                } else {
                    echo "\nError : Not found component module";
                }
            }

            return $result;
        }

        private static function render_variable(string $raw, array $vars) : string {
            $result = strtr($raw, $vars);
            return $result;
        }
    }


?>