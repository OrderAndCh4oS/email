<?php
    namespace Sarcoma\Email;

    class Email
    {

        protected $h1_style = "color:#000;font-size:20px;font-family:Helvetica,Arial,sans-serif;font-weight:bold;
        padding-bottom:0;margin-bottom:0;";
        protected $h2_style = "color:#000;font-size:14px;font-family:Helvetica,Arial,sans-serif;font-weight:bold;
        padding-bottom:0;margin-bottom:0;";
        protected $p_style = "color:#000;font-size:12px;font-family:Helvetica,Arial,sans-serif;font-weight:normal;";

        protected $email_title;
        protected $message;

        /**
         * @param string $email_title
         */
        public function setEmailTitle($email_title)
        {
            $this->email_title = $email_title;
        }

        /**
         * @param string $color
         * @param string $size
         * @param string $font
         */
        public function setH1Style($color = "#000", $size = "14px", $font = "Helvetica,Arial,sans-serif")
        {
            $this->h1_style = "color:$color;font-size:$size;font-family:$font;font-weight:bold;padding-bottom:0;
            margin-bottom:0;";
        }

        /**
         * @param string $color
         * @param string $size
         * @param string $font
         */
        public function setH2Style($color = "#000", $size = "14px", $font = "Helvetica,Arial,sans-serif")
        {
            $this->h2_style = "color:$color;font-size:$size;font-family:$font;font-weight:bold;padding-bottom:0;margin-bottom:0;";
        }

        /**
         * @param string $color
         * @param string $size
         * @param string $font
         */
        public function setPStyle($color = "#000", $size = "11px", $font = "Helvetica,Arial,sans-serif")
        {
            $this->p_style = "color:$color;font-size:$size;font-family:$font;font-weight:normal;";
        }

        public function setText($array)
        {
            foreach ($array as $title => $text) {
                if (!empty($text)) {
                    $text            = stripslashes($this->clean($text));
                    $this->message[] = $this->title($title).$this->paragraph($text);
                } else {
                    $this->message[] = $this->title($title).$this->paragraph('Not Provided');
                }
            }
        }

        public function setTextArea($array)
        {
            foreach ($array as $title => $text_area) {
                if (!empty($text_area)) {
                    $output          = nl2br(wordwrap($text_area, 60));
                    $output          = stripslashes($this->clean($output));
                    $output          = preg_replace('#&lt;((?:br) /?)&gt;#', '<\1>', $output);
                    $this->message[] = $this->title($title).$this->paragraph($output);
                } else {
                    $this->message[] = $this->title($title).$this->paragraph('Not Provided');
                }
            }
        }

        public function setLink($array)
        {
            foreach ($array as $link_text => $url) {;
                $this->message[] = $this->paragraph('<a href="'.$this->clean($url).'">'.$this->clean($link_text).'</a>');
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
            $message = "<html><head><title>{$this->email_title}</title></head>";
            $message .= "<body><table><tbody><tr><td>";
            $message .= "<h1 style=\"{$this->h1_style}\">$this->email_title</h1>";
            foreach ($this->message as $output) {
                $message .= $output;
            }
            $message .= "</td></tr></tbody></table></body></html>";

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

        protected function title($title)
        {
            return "<h2 style=\"{$this->h2_style}\">{$title}:</h2>";
        }

        protected function paragraph($content)
        {
            return "<p style=\"{$this->p_style}\">{$content}</p>";
        }
    }