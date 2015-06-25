<?php
    namespace Sarcoma\Email;

    use Twig_Loader_Filesystem;
    use Twig_Environment;

    class Email
    {

        protected $body_color = "#f1f1f1";
        protected $table_color = "#ffffff";
        protected $color;
        protected $base_font_size;
        protected $ratio;
        protected $styles;

        protected $email_title = "HTML Email";
        protected $message;

        /**
         * @param string $font_family
         * @param int $base_font_size
         * @param float $line_height
         * @param string $color
         * @param float $ratio
         */
        public function __construct(
            $font_family = 'Helvetica,Arial,sans-serif',
            $base_font_size = 12,
            $line_height = 1.3,
            $color = "#000000",
            $ratio = 1.618
        ) {
            $this->base_font_size = $base_font_size;
            $this->ratio          = $ratio;
            $this->line_height    = $line_height;
            $this->styles         = array(
                'font-family'    => $font_family,
                'font-size'      => $base_font_size . "px",
                'line-height'    => $line_height,
                'color'          => $color,
                'margin-bottom'  => ($line_height * $base_font_size) . "px",
                'padding-bottom' => 0,
                'margin-top'     => 0,
                'padding-top'    => 0
            );
        }

        /**
         * @param string $email_title
         */
        public function setEmailTitle($email_title)
        {
            $this->email_title = $email_title;
        }

        /**
         * @param string $body_color
         */
        public function setBodyColor($body_color)
        {
            $this->body_color = $body_color;
        }

        /**
         * @param$table_color
         */
        public function setTableColor($table_color)
        {
            $this->table_color = $table_color;
        }

        /**
         * @param string  $color
         */
        public function setColor($color)
        {
            $this->styles['color'] = $color;
        }

        /**
         * @param string  $font_family
         */
        public function setFontFamily($font_family)
        {
            $this->styles['font-family'] = $font_family;
        }

        /**
         * @param string  $font_size
         */
        public function setFontSize($font_size)
        {
            $this->styles['font-size'] = $font_size;
        }

        /**
         * @param string  $line_height
         */
        public function setLineHeight($line_height)
        {
            $this->styles['line-height'] = $line_height;
        }

        /**
         * @param array $styles
         */
        public function setStyle($styles)
        {
            foreach ($styles as $property => $style) {
                $this->styles[$property] = $style;
            }
        }

        /**
         * @param string $text
         * @param string $tag
         * @param array $styles
         */
        public function setTag($text, $tag, $styles = array())
        {
            if (!empty($text)) {
                $text            = stripslashes($this->clean($text));
                $this->message[] = $this->makeTag($text, $tag, $styles);
            } else {
                $this->message[] = $this->makeTag('Not Provided', $tag, $styles);
            }
        }

        /**
         * @param string $text_area
         * @param string $tag
         * @param array $styles
         * @param mixed $text_wrap
         */
        public function setTextArea($text_area, $tag, $styles = array(), $text_wrap = false)
        {
            if (!empty($text_area)) {
                if ($text_wrap) {
                    $text_area = wordwrap($text_area, $text_wrap);
                }
                $text_area       = nl2br($text_area);
                $text_area       = stripslashes($this->clean($text_area));
                $text_area       = preg_replace('#&lt;((?:br) /?)&gt;#', '<\1>', $text_area);
                $this->message[] = $this->makeTag($text_area, $tag, $styles);
            } else {
                $this->message[] = $this->makeTag('Not Provided', $tag, $styles);
            }
        }

        /**
         * @param string $link_text
         * @param string $url
         * @param string $tag
         * @param array $styles
         */
        public function setLink($link_text, $url, $tag, $styles = array())
        {
            $this->message[] = $this->makeTag('<a href="' . $this->clean($url) . '">' . $this->clean($link_text) . '</a>',
                $tag, $styles);
        }

        /**
         * @param string $template
         * @param $twig
         *
         * @return string
         */
        public function getMessage($template = 'email.twig', $twig = false)
        {
            if (!$twig) {
                $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../views');
                $twig   = new Twig_Environment($loader);
            }
            $message = '';
            foreach ($this->message as $output) {
                $message .= $output;
            }

            return $twig->render($template, array(
                'email_title'       => $this->email_title,
                'body_color'  => $this->body_color,
                'table_color' => $this->table_color,
                'content'     => $message
            ));
            //return $message;
        }

        /**
         * @param array $array
         *
         * @return array $errors
         */
        public function check_required_fields($array)
        {
            $errors = array();
            foreach ($array as $key => $field_name) {
                // check that required fields are set
                if (!isset($field_name) || (empty($field_name) && $field_name != '0')) {
                    $errors[] = $key . " is empty.";
                }
            }

            return $errors;
        }

        /**
         * @param $data
         *
         * @return string
         */
        protected function clean($data)
        {
            return htmlentities(trim($data));
        }

        /**
         * @param $title
         * @param string $tag
         * @param array $styles
         *
         * @return string
         */
        protected function makeTag($title, $tag = 'p', $styles = array())
        {
            return "<{$tag} style=\"{$this->makeStyles($styles)}\">{$title}</{$tag}>";
        }

        /**
         * @param array $styles
         *
         * @return string
         */
        protected function makeStyles($styles = array())
        {
            $styles = array_merge($this->styles, $styles);
            $output = '';
            foreach ($styles as $property => $value) {
                $output .= $property . ':' . $value . ';';
            }

            return $output;
        }

        /**
         * @param int $scale
         *
         * @return string
         */
        public function modularScale($scale = 0)
        {
            $size = $this->base_font_size;
            $i    = 0;
            if ($scale > 0) {
                while ($i < $scale) {
                    $size = round($size * $this->ratio, 2);
                    $i ++;
                }
            } elseif ($scale < 0) {
                while ($i > $scale) {
                    $size = round($size / $this->ratio, 2);
                    $i --;
                }
            }

            return $size . "px";
        }
    }