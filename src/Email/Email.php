<?php
    namespace Sarcoma\Email;

    class Email
    {

        protected $color;
        protected $font_family;
        protected $base_font_size;
        protected $ratio;
        protected $line_height;
        protected $styles;

        protected $email_title;
        protected $message;

        protected function __construct(
            $font_family = 'Helvetica,Arial,sans-serif',
            $base_font_size = 12,
            $line_height = 1.3,
            $ratio = 1.618
        ) {
            $this->font_family    = $font_family;
            $this->base_font_size = $base_font_size;
            $this->ratio          = $ratio;
            $this->line_height    = $line_height;
            $this->styles = array(
                'font-family'    => $font_family,
                'line-height'    => $line_height,
                'padding-bottom' => 0
            );
        }

        public function setStyle($styles)
        {
            foreach ($styles as $property => $style) {
                $this->styles[$property] = $style;
            }
        }

        public function setTag($text, $tag, $styles = array())
        {
            if (!empty($text)) {
                $text            = stripslashes($this->clean($text));
                $this->message[] = $this->makeTag($text, $tag, $styles);
            } else {
                $this->message[] = $this->makeTag('Not Provided', $tag);
            }
        }

        public function setTextArea($text_area, $tag, $styles = array(), $text_wrap = null)
        {
            if (!empty($text_area)) {
                if ($text_area) {
                    $text_area   = wordwrap($text_area, $text_wrap);
                }
                $text_area       = nl2br($text_area);
                $text_area       = stripslashes($this->clean($text_area));
                $text_area       = preg_replace('#&lt;((?:br) /?)&gt;#', '<\1>', $text_area);
                $this->message[] = $this->makeTag($text_area, $styles);
            } else {
                $this->message[] = $this->makeTag('Not Provided', $tag, $styles);
            }
        }

        public function setLink($array, $tag, $styles = array())
        {
            foreach ($array as $link_text => $url) {
                ;
                $this->message[] = $this->makeTag('<a href="'.$this->clean($url).'">'.$this->clean($link_text).'</a>',
                    $tag, $styles);
            }
        }

        /**
         * @return string
         */
        public function getEmailTitle()
        {
            return $this->email_title;
        }

        public function getMessage()
        {
            $message = '';
            foreach ($this->message as $output) {
                $message .= $output;
            }

            return $message;
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
                    $errors[] = $key." is empty.";
                }
            }

            return $errors;
        }

        protected function clean($data)
        {
            return htmlentities(trim($data));
        }

        protected function makeTag($title, $tag = 'h1', $styles = array())
        {
            return "<{$tag} style=\"{$this->makeStyles($styles)}\">{$title}:</{$tag}>";
        }

        protected function makeStyles($styles = array())
        {
            $styles = array_merge($this->styles, $styles);
            $output = '';
            foreach ($styles as $property => $value) {
                $output .= $property.':'.$value.';';
            }

            return $output;
        }

        public function modularScale($scale)
        {
            $size = $this->base_font_size;
            $i    = 0;
            if ($size > 0) {
                while ($i <= $scale) {
                    $size = $size * $this->ratio;
                    $i++;
                }
            } elseif ($size < 0) {
                while ($i >= $scale) {
                    $size = $size / $this->ratio;
                    $i--;
                }
            }

            return $size."px";
        }
    }