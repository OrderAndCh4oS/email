<?php
    namespace Sarcoma\Email;

    class Email
    {

        private $h2_style = "color:#000;font-size:14px;font-family:Helvetica,Arial,sans-serif;font-weight:bold;padding-bottom:0;margin-bottom:0;";
        private $p_style = "color:#000;font-size:12px;font-family:Helvetica,Arial,sans-serif;font-weight:normal;";

        private $email_title;
        private $message;

        public function __construct()
        {
        }

        /**
         * @param string $email_title
         */
        public function setEmailTitle( $email_title )
        {
            $this->email_title = $email_title;
        }

        /**
         * @param string $color
         * @param string $size
         * @param string $font
         */
        public function setH2Style( $color = "#000", $size = "14px", $font = "Helvetica,Arial,sans-serif" )
        {
            $this->h2_style = "color:$color;font-size:$size;font-family:$font;font-weight:bold;padding-bottom:0;margin-bottom:0;";
        }

        /**
         * @param string $color
         * @param string $size
         * @param string $font
         */
        public function setPStyle( $color = "#000", $size = "12px", $font = "Helvetica,Arial,sans-serif" )
        {
            $this->p_style = "color:$color;font-size:$size;font-family:$font;font-weight:normal;";
        }

        /**
         * @param string $subject
         */
        public function setSubject( $subject )
        {
            $this->subject = $subject;
        }

        /**
         * @param array $array
         *
         * @return array $errors
         */
        public function check_required_fields( $array )
        {
            $errors = array();
            foreach ($array as $field_name) {
                // check that required fields are set
                if ( ! isset( $_POST[$field_name] ) || ( empty( $_POST[$field_name] ) && $_POST[$field_name] != '0' )) {
                    $errors[] = $field_name;
                }
            }

            return $errors;
        }

        private function clean( $data )
        {
            return htmlentities( trim( $data ) );
        }

        /**
         * @return string
         */
        public function getEmailTitle()
        {
            return $this->email_title;
        }

        /**
         * @return mixed
         */
        public function getMessage()
        {
            return $this->message;
        }

        public function setText( $array )
        {
            foreach ($array as $key => $form_field) {
                if ( ! empty( $_POST[$form_field] )) {
                    $this->message[$key] = $this->clean( $_POST[$form_field] );
                } else {
                    $this->message[$key] = "Not Provided";
                }
            }
        }

        public function setTextArea( $array )
        {
            foreach ($array as $key => $form_field) {
                if ( ! empty( $_POST[$form_field] )) {
                    $text_area           = nl2br( wordwrap( $_POST[$form_field], 60 ) );
                    $text_area           = $this->clean( $text_area );
                    $text_area           = preg_replace( '#&lt;((?:br) /?)&gt;#', '<\1>', $text_area );
                    $this->message[$key] = $text_area;
                } else {
                    $this->message[$key] = "Not Provided";
                }
            }
        }

        public function buildMessage()
        {
            $body = "<html><head><title>{$this->email_title}</title></head>";
            $body .= "<body><table><tbody><tr><td>";
            $body .= "<h1 style=\"{$this->h2_style}\">$this->email_title</h1>";
            foreach ($this->message as $title => $text) {
                $text = stripslashes( $text );
                $body .= "<h2 style=\"{$this->h2_style}\">{$title}:</h2>";
                $body .= "<p style=\"{$this->p_style}\"><strong>{$text}</strong></p>";
            }
            $body .= "</td></tr></tbody></table></body>";

            return $body;
        }
    }