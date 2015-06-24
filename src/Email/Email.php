<?php
    namespace Sarcoma\Email;

    class Email
    {

        protected $color;
        protected $font_family;
        protected $base_font_size;
        protected $ratio;
        protected $line_height;
        protected $base_style;
        protected $style;

        protected $email_title;
        protected $message;

        protected function __construct(
            $base_font_size = 12,
            $font_family = 'Helvetica,Arial,sans-serif',
            $line_height = 1.3,
            $ratio = 1.618
        ) {
            $this->font_family    = $font_family;
            $this->base_font_size = $base_font_size;
            $this->ratio          = $ratio;
            $this->line_height    = $line_height;
            $this->base_style     = array(
                'font-family'    => $font_family,
                'line-height'    => $line_height,
                'padding-bottom' => 0
            );
        }

        /**
         * @param string $email_title
         */
        public function setEmailTitle($email_title)
        {
            $this->email_title = $email_title;
        }

        public function setText($array, $styles = array())
        {
            foreach ($array as $title => $text) {
                if (!empty($text)) {
                    $text            = stripslashes($this->clean($text));
                    $this->message[] = $this->paragraph($text, $styles);
                } else {
                    $this->message[] = $this->paragraph('Not Provided');
                }
            }
        }

        public function setTextArea($array, $styles = array())
        {
            foreach ($array as $title => $text_area) {
                if (!empty($text_area)) {
                    $output          = nl2br(wordwrap($text_area, 60));
                    $output          = stripslashes($this->clean($output));
                    $output          = preg_replace('#&lt;((?:br) /?)&gt;#', '<\1>', $output);
                    $this->message[] = $this->paragraph($output, $styles);
                } else {
                    $this->message[] = $this->paragraph('Not Provided', $styles);
                }
            }
        }

        public function setLink($array, $styles = array())
        {
            foreach ($array as $link_text => $url) {
                ;
                $this->message[] = $this->paragraph('<a href="' . $this->clean($url) . '">' . $this->clean($link_text) . '</a>', $styles);
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
                    $errors[] = $key . " is empty.";
                }
            }

            return $errors;
        }

        protected function clean($data)
        {
            return htmlentities(trim($data));
        }

        protected function title($title, $level = 'h1', $styles = array())
        {
            return "<{$level} style=\"{$this->makeStyles($styles)}\">{$title}:</h2>";
        }

        protected function paragraph($content, $styles = array())
        {
            return "<p style=\"{$this->makeStyles($styles)}\">{$content}</p>";
        }

        protected function makeStyles($styles = array())
        {
            $styles = array_merge($this->base_style, $styles);
            $output = '';
            foreach ($styles as $property => $value) {
                $output .= $property . ':' . $value . ';';
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
                    $i ++;
                }
            } elseif ($size < 0) {
                while ($i >= $scale) {
                    $size = $size / $this->ratio;
                    $i --;
                }
            }

            return $size . "px";
        }
    }